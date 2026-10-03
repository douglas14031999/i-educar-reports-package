<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;

class AddEarlyChildhoodCertificateReportMenu extends Migration
{
    public function up()
    {
        Menu::query()->updateOrCreate(['old' => 999706], [
            'parent_id' => Menu::query()->where('old', 999922)->firstOrFail()->getKey(),
            'process' => 999706,
            'title' => 'Certificado de Conclusão da Educação Infantil',
            'order' => 0,
            'parent_old' => 999922,
            'link' => '/module/Reports/EarlyChildhoodCertificate'
        ]);
    }

    public function down()
    {
        Menu::query()->where('old', 999706)->delete();
    }
}
