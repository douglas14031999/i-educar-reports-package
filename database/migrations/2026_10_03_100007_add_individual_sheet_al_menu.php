<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999707], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999707,
            'title' => 'Ficha Individual - AL',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/IndividualSheetAl'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999707)->delete();
    }
};
