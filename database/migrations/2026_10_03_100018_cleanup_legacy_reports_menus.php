<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $activeProcesses = [
            999701, 999702, 999703, 999704, 999705, 999706, 999707, 999708,
            999709, 999710, 999711, 999712, 999713, 999714, 999715, 999716, 999717
        ];

        // 1. Remove menus de relatórios antigos pelo link /module/Reports/
        $oldMenus = Menu::query()
            ->where('link', 'LIKE', '/module/Reports/%')
            ->whereNotIn('process', $activeProcesses)
            ->get();

        foreach ($oldMenus as $menu) {
            $menu->delete();
        }

        // 2. Remove também pelos códigos de processos do pacote antigo que não fazem parte dos 17 atuais
        Menu::query()
            ->where('process', '>=', 999000)
            ->where('process', '<', 999700)
            ->delete();

        // 3. Garante que os 17 relatórios ativos tenham permissões concedidas em pmieducar.menu_tipo_usuario
        try {
            $tiposUsuarios = DB::table('pmieducar.tipo_usuario')->pluck('cod_tipo_usuario');
            foreach ($tiposUsuarios as $tipoId) {
                foreach ($activeProcesses as $processId) {
                    $exists = DB::table('pmieducar.menu_tipo_usuario')
                        ->where('ref_cod_tipo_usuario', $tipoId)
                        ->where('ref_processo_ap', $processId)
                        ->exists();

                    if (!$exists) {
                        DB::table('pmieducar.menu_tipo_usuario')->insert([
                            'ref_cod_tipo_usuario' => $tipoId,
                            'ref_processo_ap' => $processId,
                            'visualiza' => 1,
                            'cadastra' => 1,
                            'exclui' => 1,
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silencia caso a tabela ou estrutura esteja em outro schema
        }
    }

    public function down()
    {
    }
};
