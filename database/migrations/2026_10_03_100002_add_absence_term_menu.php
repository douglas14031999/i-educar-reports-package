<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddAbsenceTermReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999702], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999702,
            'title' => 'Termo de Ausência',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/AbsenceTerm'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999702)->delete();
    }
}
