<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddTeacherReceiptStubReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999714], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999714,
            'title' => 'Canhoto do professor',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/TeacherReceiptStub'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999714)->delete();
    }
}
