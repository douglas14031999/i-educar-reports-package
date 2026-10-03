<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999715], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999715,
            'title' => 'Ficha de acompanhamento do aluno',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/StudentTrackingSheet'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999715)->delete();
    }
};
