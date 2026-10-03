<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddStudentHousingFormReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999709], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999709,
            'title' => 'Ficha de Moradia do Aluno',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/StudentHousingForm'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999709)->delete();
    }
}
