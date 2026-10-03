<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999710], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999710,
            'title' => 'Ficha Médica do Aluno',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/StudentMedicalForm'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999710)->delete();
    }
};
