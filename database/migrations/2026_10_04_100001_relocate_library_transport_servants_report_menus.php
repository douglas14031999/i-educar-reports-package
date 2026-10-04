<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Disable transaction so that individual errors do not cancel the whole migration.
     */
    public $withinTransaction = false;

    public function up(): void
    {
        try {
            // 1. Resolve IDs dos menus pais
            $parentBibCadastrais = Menu::query()->where('old', 999905)->first();
            $parentBibMov = Menu::query()->where('old', 999906)->first();
            $parentBibDocs = Menu::query()->where('old', 999907)->first();
            $parentTransCadastrais = Menu::query()->where('old', 9998847)->first();
            $parentServCadastrais = Menu::query()->where('old', 999914)->orWhere('id', 286)->first();
            $parentServDocs = Menu::query()->where('old', 999916)->orWhere('id', 285)->first();

            // 2. Biblioteca - Relatórios Cadastrais
            if ($parentBibCadastrais) {
                Menu::query()->where('link', '/module/Reports/LibraryWorks')->update([
                    'parent_id' => $parentBibCadastrais->getKey(),
                    'parent_old' => 999905,
                    'order' => 1,
                ]);
                Menu::query()->where('link', '/module/Reports/LibraryAuthors')->update([
                    'parent_id' => $parentBibCadastrais->getKey(),
                    'parent_old' => 999905,
                    'order' => 2,
                ]);
                Menu::query()->where('link', '/module/Reports/LibraryPublishers')->update([
                    'parent_id' => $parentBibCadastrais->getKey(),
                    'parent_old' => 999905,
                    'order' => 3,
                ]);
                Menu::query()->where('link', '/module/Reports/LibraryClients')->update([
                    'parent_id' => $parentBibCadastrais->getKey(),
                    'parent_old' => 999905,
                    'order' => 4,
                ]);
            }

            // 3. Biblioteca - Relatórios Movimentações
            if ($parentBibMov) {
                Menu::query()->where('link', '/module/Reports/LibraryLoans')->update([
                    'parent_id' => $parentBibMov->getKey(),
                    'parent_old' => 999906,
                    'order' => 1,
                ]);
                Menu::query()->where('link', '/module/Reports/LibraryDevolutions')->update([
                    'parent_id' => $parentBibMov->getKey(),
                    'parent_old' => 999906,
                    'order' => 2,
                ]);
            }

            // 4. Biblioteca - Documentos Comprovantes
            if ($parentBibDocs) {
                Menu::query()->where('link', '/module/Reports/LibraryLoanReceipt')->update([
                    'parent_id' => $parentBibDocs->getKey(),
                    'parent_old' => 999907,
                    'order' => 1,
                ]);
                Menu::query()->where('link', '/module/Reports/LibraryDevolutionReceipt')->update([
                    'parent_id' => $parentBibDocs->getKey(),
                    'parent_old' => 999907,
                    'order' => 2,
                ]);
            }

            // 5. Transporte escolar - Relatórios Cadastrais
            if ($parentTransCadastrais) {
                Menu::query()->where('link', '/module/Reports/TransportationUsers')->update([
                    'parent_id' => $parentTransCadastrais->getKey(),
                    'parent_old' => 9998847,
                    'order' => 1,
                ]);
                Menu::query()->where('link', '/module/Reports/Drivers')->update([
                    'parent_id' => $parentTransCadastrais->getKey(),
                    'parent_old' => 9998847,
                    'order' => 2,
                ]);
                Menu::query()->where('link', '/module/Reports/TransportationCard')->update([
                    'parent_id' => $parentTransCadastrais->getKey(),
                    'parent_old' => 9998847,
                    'order' => 3,
                ]);

                // Garante Relatório de rotas do transporte
                $routeMenu = Menu::query()->where('link', '/module/Reports/TransportationRoutes')->first();
                if (!$routeMenu) {
                    $routeMenu = Menu::query()->create([
                        'title' => 'Relatório de rotas do transporte',
                        'description' => null,
                        'link' => '/module/Reports/TransportationRoutes',
                        'order' => 4,
                        'old' => 21242,
                        'process' => 21242,
                        'parent_id' => $parentTransCadastrais->getKey(),
                        'parent_old' => 9998847,
                        'type' => 4,
                        'active' => true,
                    ]);
                } else {
                    $routeMenu->update([
                        'parent_id' => $parentTransCadastrais->getKey(),
                        'parent_old' => 9998847,
                        'order' => 4,
                    ]);
                }

                // Permissões
                if (Schema::hasTable('menu_tipo_usuario')) {
                    DB::table('menu_tipo_usuario')->insertOrIgnore([
                        ['menu_id' => $routeMenu->getKey(), 'ref_cod_tipo_usuario' => 1],
                        ['menu_id' => $routeMenu->getKey(), 'ref_cod_tipo_usuario' => 13],
                    ]);
                }
            }

            // 6. Servidores - Relatórios Cadastrais e Documentos
            if ($parentServCadastrais) {
                Menu::query()->where('link', '/module/Reports/Servants')->update([
                    'parent_id' => $parentServCadastrais->getKey(),
                    'parent_old' => 999914,
                    'order' => 1,
                ]);
            }
            if ($parentServDocs) {
                Menu::query()->where('link', '/module/Reports/ServantSheet')->update([
                    'parent_id' => $parentServDocs->getKey(),
                    'parent_old' => 999916,
                    'order' => 1,
                ]);
            }

            // 7. Corrige Relatório geral de escolas e remove menu fantasma Turmas (cadastral completo)
            Menu::query()->where('title', 'like', '%geral de escolas%')->orWhere('link', '/module/Reports/GeneralSchools')->update([
                'link' => '/module/Reports/Schools',
                'process' => 999605,
            ]);

            $turmasMenus = Menu::query()->where('title', 'like', '%Turmas (cadastral completo)%')->orWhere('old', 464)->get();
            foreach ($turmasMenus as $tm) {
                if (Schema::hasTable('menu_tipo_usuario')) {
                    DB::table('menu_tipo_usuario')->where('menu_id', $tm->getKey())->delete();
                }
                $tm->delete();
            }
        } catch (\Throwable $e) {
            // Não aborta
        }
    }

    public function down(): void
    {
        // Reversão opcional mantida vazia para segurança
    }
};
