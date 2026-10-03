<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999701], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999701,
            'title' => 'Declaração de Anuência para Menor',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/MinorConsentDeclaration'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999701)->delete();
    }
};
