<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddIndividualSheet69AlReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999708], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999708,
            'title' => 'Ficha Individual (6º ao 9º ano) - AL',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/IndividualSheet69Al'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999708)->delete();
    }
}
