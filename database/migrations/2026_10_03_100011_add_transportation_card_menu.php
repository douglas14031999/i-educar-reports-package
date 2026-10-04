<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        $parent = Menu::query()->where('old', 9998847)->first();
        Menu::query()->updateOrCreate(['old' => 999711], [
            'parent_id' => $parent ? $parent->getKey() : null,
            'process' => 999711,
            'title' => 'Carteira de Transporte',
            'order' => 3,
            'parent_old' => 9998847,
            'link' => '/module/Reports/TransportationCard'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999711)->delete();
    }
};
