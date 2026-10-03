<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999713], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999713,
            'title' => 'Nota necessária para exame',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/ScoreRequiredForExam'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999713)->delete();
    }
};
