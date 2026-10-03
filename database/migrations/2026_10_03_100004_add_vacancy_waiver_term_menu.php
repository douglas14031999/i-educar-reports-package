<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddVacancyWaiverTermReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999704], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999704,
            'title' => 'Termos de Desistência de Vaga',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/VacancyWaiverTerm'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999704)->delete();
    }
}
