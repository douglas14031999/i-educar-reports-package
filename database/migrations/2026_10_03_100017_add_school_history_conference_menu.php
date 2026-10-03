<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999717], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999717,
            'title' => 'Histórico escolar - conferência',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/SchoolHistoryConference'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999717)->delete();
    }
};
