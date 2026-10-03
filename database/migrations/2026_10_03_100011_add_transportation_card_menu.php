<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddTransportationCardReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999711], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999711,
            'title' => 'Carteira de Transporte',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/TransportationCard'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999711)->delete();
    }
}
