<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddStudentImageUseAuthorizationReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999705], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999705,
            'title' => 'Utilização da Imagem do Aluno',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/StudentImageUseAuthorization'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999705)->delete();
    }
}
