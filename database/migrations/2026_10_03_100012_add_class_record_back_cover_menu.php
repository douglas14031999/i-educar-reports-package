<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddClassRecordBackCoverReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999712], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999712,
            'title' => 'Diário de classe - contracapa',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/ClassRecordBackCover'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999712)->delete();
    }
}
