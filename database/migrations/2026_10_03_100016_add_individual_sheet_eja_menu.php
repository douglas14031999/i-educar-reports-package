<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddIndividualSheetEjaReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999716], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999716,
            'title' => 'Ficha individual - EJA',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/IndividualSheetEja'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999716)->delete();
    }
}
