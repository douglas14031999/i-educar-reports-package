<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddEarlyChildhoodCommitmentTermReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999703], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999703,
            'title' => 'Termos de Compromisso da Educação Infantil',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/EarlyChildhoodCommitmentTerm'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999703)->delete();
    }
}
