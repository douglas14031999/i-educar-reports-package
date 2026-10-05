<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up()
    {
        $parentMenu = Menu::query()->where('old', 999400)->first() 
                   ?: Menu::query()->where('old', 999922)->first();
        $parentId = $parentMenu ? $parentMenu->getKey() : null;

        $menu = Menu::query()->updateOrCreate(['old' => 999718], [
            'parent_id' => $parentId,
            'process' => 999718,
            'title' => 'Emissão de Diplomas de Conclusão',
            'order' => 12,
            'parent_old' => $parentMenu ? $parentMenu->old : 999400,
            'link' => '/module/Reports/DiplomaCertificate'
        ]);

        try {
            $permTable = Schema::hasTable('menu_tipo_usuario') ? 'menu_tipo_usuario' : 'pmieducar.menu_tipo_usuario';
            $tutTable = Schema::hasTable('tipo_usuario') ? 'tipo_usuario' : 'pmieducar.tipo_usuario';
            $tipos = DB::table($tutTable)->pluck('cod_tipo_usuario')->all();

            foreach ($tipos as $tipoId) {
                DB::table($permTable)->updateOrInsert(
                    ['ref_cod_tipo_usuario' => $tipoId, 'menu_id' => $menu->getKey()],
                    ['visualiza' => 1, 'cadastra' => 1, 'exclui' => 1]
                );
            }
        } catch (\Throwable $e) {}
    }

    public function down()
    {
        Menu::query()->where('old', 999718)->delete();
    }
};
