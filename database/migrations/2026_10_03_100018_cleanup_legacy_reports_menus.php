<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $activeProcesses = [
            999701, 999702, 999703, 999704, 999705, 999706, 999707, 999708,
            999709, 999710, 999711, 999712, 999713, 999714, 999715, 999716, 999717
        ];

        // 1. Identifica os IDs e processos dos menus antigos que devem ser removidos
        $oldMenus = Menu::query()
            ->where(function ($query) use ($activeProcesses) {
                $query->where('link', 'LIKE', '/module/Reports/%')
                      ->whereNotIn('process', $activeProcesses);
            })
            ->orWhere(function ($query) {
                $query->where('process', '>=', 999000)
                      ->where('process', '<', 999700);
            })
            ->get();

        $oldMenuIds = $oldMenus->pluck('id')->filter()->all();
        $oldProcesses = $oldMenus->pluck('process')->filter()->all();

        // 2. Remove as referências em tabelas de permissões (menu_tipo_usuario) para não violar Foreign Key
        if (!empty($oldMenuIds)) {
            foreach (['menu_tipo_usuario', 'pmieducar.menu_tipo_usuario'] as $table) {
                try {
                    DB::table($table)->whereIn('menu_id', $oldMenuIds)->delete();
                } catch (\Throwable $e) {
                }
            }
        }

        if (!empty($oldProcesses)) {
            foreach (['menu_tipo_usuario', 'pmieducar.menu_tipo_usuario'] as $table) {
                try {
                    DB::table($table)->whereIn('ref_processo_ap', $oldProcesses)->delete();
                } catch (\Throwable $e) {
                }
            }
        }

        // 3. Remove os registros de menus antigos da tabela menus
        if (!empty($oldMenuIds)) {
            Menu::query()->whereIn('id', $oldMenuIds)->delete();
        }

        // 4. Concede permissão para os 17 relatórios ativos a todos os tipos de usuário
        try {
            $tiposUsuarios = [];
            foreach (['pmieducar.tipo_usuario', 'tipo_usuario'] as $userTypeTable) {
                try {
                    $tiposUsuarios = DB::table($userTypeTable)->pluck('cod_tipo_usuario')->all();
                    if (!empty($tiposUsuarios)) {
                        break;
                    }
                } catch (\Throwable $e) {
                }
            }

            $activeMenus = Menu::query()->whereIn('process', $activeProcesses)->get();

            foreach ($activeMenus as $activeMenu) {
                $processId = $activeMenu->process;
                $menuId = $activeMenu->getKey();

                foreach ($tiposUsuarios as $tipoId) {
                    foreach (['pmieducar.menu_tipo_usuario', 'menu_tipo_usuario'] as $table) {
                        try {
                            $query = DB::table($table)
                                ->where('ref_cod_tipo_usuario', $tipoId);

                            // Verifica por menu_id ou ref_processo_ap
                            $exists = (clone $query)->where(function ($q) use ($menuId, $processId) {
                                $q->where('menu_id', $menuId)
                                  ->orWhere('ref_processo_ap', $processId);
                            })->exists();

                            if (!$exists) {
                                $data = [
                                    'ref_cod_tipo_usuario' => $tipoId,
                                    'ref_processo_ap' => $processId,
                                    'visualiza' => 1,
                                    'cadastra' => 1,
                                    'exclui' => 1,
                                ];

                                // Se a coluna menu_id existir na tabela, adiciona
                                try {
                                    $data['menu_id'] = $menuId;
                                } catch (\Throwable $t) {
                                }

                                DB::table($table)->insert($data);
                            }
                            break; // Se inseriu com sucesso, não precisa tentar na tabela alternativa
                        } catch (\Throwable $e) {
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
