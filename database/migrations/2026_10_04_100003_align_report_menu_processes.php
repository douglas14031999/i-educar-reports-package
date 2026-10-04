<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlignReportMenuProcesses extends Migration
{
    /**
     * Disable transaction so that individual errors do not cancel the whole migration.
     */
    public $withinTransaction = false;

    public function up()
    {
        // 1. Mapeamento de links canônicos de relatórios para seus respectivos processos de controle
        $mapping = [
            '/module/Reports/StudentsWithBenefits' => 999233,
            '/module/Reports/StudentsPerProjects' => 999234,
            '/module/Reports/StudentDisciplinaryOccurrence' => 999217,
            '/module/Reports/LibraryWorks' => 999617,
            '/module/Reports/LibraryAuthors' => 999615,
            '/module/Reports/LibraryPublishers' => 999616,
            '/module/Reports/LibraryClients' => 999845,
            '/module/Reports/LibraryLoans' => 999618,
            '/module/Reports/LibraryDevolutions' => 999619,
            '/module/Reports/LibraryLoanReceipt' => 999620,
            '/module/Reports/LibraryDevolutionReceipt' => 999621,
            '/module/Reports/TransportationUsers' => 999825,
            '/module/Reports/Drivers' => 21252,
            '/module/Reports/Servants' => 999820,
            '/module/Reports/ServantSheet' => 999822,
            '/module/Reports/StudentsTransferredAbandonment' => 999607,
            '/module/Reports/ConferenceEvaluationsFaults' => 999809,
            '/module/Reports/StudentsEntranceAndAllocation' => 999871,
            '/module/Reports/StudentsMovement' => 999201,
            '/module/Reports/RegistrationSchool' => 999105,
            '/module/Reports/AgeDistortionInSerie' => 999840,
            '/module/Reports/ClassAverageComparative' => 999872,
            '/module/Reports/StudentsAverage' => 999834,
            '/module/Reports/EducationalProgressAndProcedures' => 999830,
            '/module/Reports/RegistrationCertificate' => 999103,
            '/module/Reports/FrequencyCertificate' => 999102,
            '/module/Reports/ConclusionCertificate' => 999812,
            '/module/Reports/ReportCard' => 999202,
            '/module/Reports/SchoolHistory' => 999200,
            '/module/Reports/FinalResult' => 999608,
            '/module/Reports/ClassRecordBook' => 999816,
            '/module/Reports/Birthdays' => 999807,
        ];

        foreach ($mapping as $link => $proc) {
            try {
                DB::table('menus')->where('link', $link)->update(['process' => $proc]);
            } catch (\Throwable $e) {
                // Prossegue
            }
        }

        // 2. Remoção de itens de menu duplicados nos submenus
        try {
            $duplicateIds = [566, 619];
            if (Schema::hasTable('menu_tipo_usuario')) {
                DB::table('menu_tipo_usuario')->whereIn('menu_id', $duplicateIds)->delete();
            }
            if (Schema::hasTable('pmieducar.menu_tipo_usuario')) {
                DB::table('pmieducar.menu_tipo_usuario')->whereIn('menu_id', $duplicateIds)->delete();
            }
            DB::table('menus')->whereIn('id', $duplicateIds)->delete();
        } catch (\Throwable $e) {
            // Prossegue
        }

        // 3. Garante permissões em menu_tipo_usuario para todos os tipos de usuário existentes
        try {
            $permTable = null;
            if (Schema::hasTable('menu_tipo_usuario')) {
                $permTable = 'menu_tipo_usuario';
            } elseif (Schema::hasTable('pmieducar.menu_tipo_usuario')) {
                $permTable = 'pmieducar.menu_tipo_usuario';
            }

            if ($permTable) {
                $columns = Schema::getColumnListing($permTable);
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
                $hasProcessCol = in_array('ref_processo_ap', $columns);

                if ($userTypeFkCol && ($hasMenuId || $hasProcessCol) && !empty($tipos)) {
                    $reportMenus = Menu::query()->where('link', 'like', '/module/Reports/%')->get();
                    foreach ($reportMenus as $targetMenu) {
                        $mId = (int) $targetMenu->getKey();
                        $proc = $targetMenu->process ? (int) $targetMenu->process : null;

                        foreach ($tipos as $tipoId) {
                            try {
                                $q = DB::table($permTable)->where($userTypeFkCol, $tipoId);
                                if ($hasMenuId) {
                                    $q->where('menu_id', $mId);
                                } elseif ($hasProcessCol && $proc) {
                                    $q->where('ref_processo_ap', $proc);
                                } else {
                                    continue;
                                }

                                if (!$q->exists()) {
                                    $row = [
                                        $userTypeFkCol => $tipoId,
                                    ];
                                    if ($hasMenuId) {
                                        $row['menu_id'] = $mId;
                                    }
                                    if ($hasProcessCol && $proc) {
                                        $row['ref_processo_ap'] = $proc;
                                    }
                                    if (in_array('visualiza', $columns)) {
                                        $row['visualiza'] = 1;
                                    }
                                    if (in_array('cadastra', $columns)) {
                                        $row['cadastra'] = 1;
                                    }
                                    if (in_array('exclui', $columns)) {
                                        $row['exclui'] = 1;
                                    }
                                    DB::table($permTable)->insert($row);
                                } else {
                                    $updateData = [];
                                    if (in_array('visualiza', $columns)) {
                                        $updateData['visualiza'] = 1;
                                    }
                                    if (in_array('cadastra', $columns)) {
                                        $updateData['cadastra'] = 1;
                                    }
                                    if (in_array('exclui', $columns)) {
                                        $updateData['exclui'] = 1;
                                    }
                                    if (!empty($updateData)) {
                                        $q->update($updateData);
                                    }
                                }
                            } catch (\Throwable $e) {
                                // Prossegue
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Prossegue
        }
    }

    public function down()
    {
    }
}
