<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Disable transactions to avoid PostgreSQL 25P02 transaction aborted errors.
     *
     * @var bool
     */
    public $withinTransaction = false;

    public function up()
    {
        $activeProcesses = [
            999701, 999702, 999703, 999704, 999705, 999706, 999707, 999708,
            999709, 999710, 999711, 999712, 999713, 999714, 999715, 999716, 999717
        ];

        $activeLinks = [
            '/module/Reports/MinorConsentDeclaration',
            '/module/Reports/AbsenceTerm',
            '/module/Reports/EarlyChildhoodCommitmentTerm',
            '/module/Reports/VacancyWaiverTerm',
            '/module/Reports/StudentImageUseAuthorization',
            '/module/Reports/EarlyChildhoodCertificate',
            '/module/Reports/IndividualSheetAl',
            '/module/Reports/IndividualSheet69Al',
            '/module/Reports/StudentHousingForm',
            '/module/Reports/StudentMedicalForm',
            '/module/Reports/TransportationCard',
            '/module/Reports/ClassRecordBackCover',
            '/module/Reports/ScoreRequiredForExam',
            '/module/Reports/TeacherReceiptStub',
            '/module/Reports/StudentTrackingSheet',
            '/module/Reports/IndividualSheetEja',
            '/module/Reports/SchoolHistoryConference',
        ];

        $baseOldCategories = [
            21126, 21127, 999301, 999922, 999300, 999923, 999303,
            999400, 999450, 999925, 999861, 999460, 999500,
            999913, 999916, 999914
        ];

        // 1. Identifica menus legados de relatórios para exclusão
        // Menus de relatório possuem link apontando para /Reports/ ou /module/Reports/
        $oldMenusQuery = Menu::query()
            ->where(function ($q) {
                $q->where('link', 'LIKE', '%/Reports/%')
                  ->orWhere('link', 'LIKE', '%/module/Reports/%');
            })
            ->whereNotIn('link', $activeLinks);

        if (Schema::hasColumn('menus', 'process')) {
            $oldMenusQuery->whereNotIn('process', $activeProcesses);
        }

        if (Schema::hasColumn('menus', 'old')) {
            $oldMenusQuery->whereNotIn('old', $baseOldCategories);
            $oldMenusQuery->whereNotIn('old', $activeProcesses);
        }

        $oldMenus = $oldMenusQuery->get();

        // Também seleciona processos legados 999000..999699 que possuem link preenchido (não são categorias)
        if (Schema::hasColumn('menus', 'process')) {
            $oldProcessMenus = Menu::query()
                ->where('process', '>=', 999000)
                ->where('process', '<', 999700)
                ->whereNotIn('process', $activeProcesses)
                ->where(function ($q) {
                    $q->whereNotNull('link')->where('link', '!=', '');
                });

            if (Schema::hasColumn('menus', 'old')) {
                $oldProcessMenus->whereNotIn('old', $baseOldCategories);
            }

            $oldMenus = $oldMenus->merge($oldProcessMenus->get())->unique('id');
        }

        $oldMenuIds = $oldMenus->pluck('id')->filter()->map(function ($id) {
            return (int) $id;
        })->all();

        if (!empty($oldMenuIds)) {
            // 2. Remove Foreign Keys que apontam para menus.id antes de excluir
            // Consulta dinâmica no catálogo do PostgreSQL para descobrir todas as FKs apontando para menus.id
            try {
                $foreignKeys = DB::select("
                    SELECT tc.table_schema, tc.table_name, kcu.column_name
                    FROM information_schema.table_constraints AS tc 
                    JOIN information_schema.key_column_usage AS kcu
                      ON tc.constraint_name = kcu.constraint_name
                      AND tc.table_schema = kcu.table_schema
                    JOIN information_schema.constraint_column_usage AS ccu
                      ON ccu.constraint_name = tc.constraint_name
                      AND ccu.table_schema = tc.table_schema
                    WHERE tc.constraint_type = 'FOREIGN KEY' 
                      AND ccu.table_name = 'menus' 
                      AND ccu.column_name = 'id'
                ");

                foreach ($foreignKeys as $fk) {
                    $table = $fk->table_name;
                    $column = $fk->column_name;
                    $schema = $fk->table_schema;
                    $targetTable = ($schema && $schema !== 'public') ? "{$schema}.{$table}" : $table;

                    if ($table === 'menus' && $column === 'parent_id') {
                        // Se algum menu tiver como pai um menu que será excluído, desvincula o parent_id
                        DB::table('menus')->whereIn('parent_id', $oldMenuIds)->update(['parent_id' => null]);
                    } else {
                        DB::table($targetTable)->whereIn($column, $oldMenuIds)->delete();
                    }
                }
            } catch (\Throwable $e) {
                // Fallback via Schema::hasTable
                foreach (['menu_tipo_usuario', 'pmieducar.menu_tipo_usuario'] as $permTable) {
                    if (Schema::hasTable($permTable) && Schema::hasColumn($permTable, 'menu_id')) {
                        DB::table($permTable)->whereIn('menu_id', $oldMenuIds)->delete();
                    }
                }
            }

            // 3. Remove os menus antigos da tabela menus
            DB::table('menus')->whereIn('id', $oldMenuIds)->delete();
        }

        // 4. Concede permissão para os 17 relatórios ativos
        try {
            $permTable = null;
            if (Schema::hasTable('menu_tipo_usuario')) {
                $permTable = 'menu_tipo_usuario';
            } elseif (Schema::hasTable('pmieducar.menu_tipo_usuario')) {
                $permTable = 'pmieducar.menu_tipo_usuario';
            }

            if ($permTable) {
                $columns = Schema::getColumnListing($permTable);

                // Busca tabela de tipos de usuário
                $tipoUsuarioTable = null;
                foreach (['tipo_usuario', 'pmieducar.tipo_usuario'] as $tut) {
                    if (Schema::hasTable($tut)) {
                        $tipoUsuarioTable = $tut;
                        break;
                    }
                }

                $tipos = [];
                if ($tipoUsuarioTable) {
                    $tutCols = Schema::getColumnListing($tipoUsuarioTable);
                    $userTypeCol = in_array('cod_tipo_usuario', $tutCols) ? 'cod_tipo_usuario' : (in_array('id', $tutCols) ? 'id' : null);
                    if ($userTypeCol) {
                        $tipos = DB::table($tipoUsuarioTable)->pluck($userTypeCol)->all();
                    }
                }

                $userTypeFkCol = in_array('ref_cod_tipo_usuario', $columns) ? 'ref_cod_tipo_usuario' : (in_array('tipo_usuario_id', $columns) ? 'tipo_usuario_id' : null);

                if (empty($tipos) && $userTypeFkCol) {
                    $tipos = DB::table($permTable)->distinct()->pluck($userTypeFkCol)->filter()->all();
                }

                $hasMenuId = in_array('menu_id', $columns);
                $hasProcess = in_array('ref_processo_ap', $columns);

                if ($userTypeFkCol && ($hasMenuId || $hasProcess) && !empty($tipos)) {
                    $activeMenus = Menu::query()->whereIn('process', $activeProcesses)->get();

                    foreach ($activeMenus as $activeMenu) {
                        $menuId = (int) $activeMenu->getKey();
                        $proc = (int) $activeMenu->process;

                        foreach ($tipos as $tipoId) {
                            $query = DB::table($permTable)->where($userTypeFkCol, $tipoId);
                            if ($hasMenuId) {
                                $query->where('menu_id', $menuId);
                            } elseif ($hasProcess) {
                                $query->where('ref_processo_ap', $proc);
                            }

                            if (!$query->exists()) {
                                $data = [
                                    $userTypeFkCol => $tipoId,
                                ];
                                if ($hasMenuId) {
                                    $data['menu_id'] = $menuId;
                                }
                                if ($hasProcess) {
                                    $data['ref_processo_ap'] = $proc;
                                }
                                if (in_array('visualiza', $columns)) {
                                    $data['visualiza'] = 1;
                                }
                                if (in_array('cadastra', $columns)) {
                                    $data['cadastra'] = 1;
                                }
                                if (in_array('exclui', $columns)) {
                                    $data['exclui'] = 1;
                                }

                                DB::table($permTable)->insert($data);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Permissões já existentes ou tratadas
        }
    }

    public function down()
    {
    }
};
