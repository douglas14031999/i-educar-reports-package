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
        // Garante permissões em menu_tipo_usuario para todos os relatórios e menus
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
                    $allMenus = Menu::query()->whereNotNull('process')->get();

                    foreach ($allMenus as $menu) {
                        $menuId = (int) $menu->getKey();
                        $proc = (int) $menu->process;

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

                                try {
                                    DB::table($permTable)->insert($data);
                                } catch (\Throwable $e) {
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
        }
    }

    public function down()
    {
    }
};
