<?php

namespace iEducar\Community\Reports\Providers;

use iEducar\Community\Reports\Commands\CommunityReportsCompileCommand;
use iEducar\Community\Reports\Commands\CommunityReportsInstallCommand;
use iEducar\Community\Reports\Commands\CommunityReportsLinkCommand;
use Illuminate\Support\ServiceProvider;

class ReportsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->commands([
                CommunityReportsCompileCommand::class,
                CommunityReportsInstallCommand::class,
                CommunityReportsLinkCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../../ieducar/Assets' => public_path('vendor/legacy/Reports/Assets')
            ], ['reports-assets']);
        }

        // Garante a renderização do menu superior e destaque ativo do módulo na barra lateral
        \Illuminate\Support\Facades\View::composer(['layout.topmenu', 'layout.menu'], function ($view) {
            $data = $view->getData();
            if (empty($data['mainmenu']) || empty($data['root'])) {
                $path = '/' . ltrim(request()->path(), '/');
                if (!empty($path) && $path !== '/') {
                    $topmenu = \App\Menu::query()->where('link', $path)->first()
                        ?: \App\Menu::query()->where('link', 'like', "{$path}%")->first();

                    if ($topmenu) {
                        $ancestors = \App\Menu::getMenuAncestors($topmenu);
                        $rootId = $topmenu->root()->getKey();
                        $view->with([
                            'mainmenu' => $rootId,
                            'currentMenu' => $topmenu,
                            'menuPaths' => $ancestors,
                            'root' => $rootId,
                        ]);
                    }
                }
            }
        });
    }

    public function register()
    {
        if (interface_exists(\iEducar\Reports\Contracts\TeacherReportCard::class) && class_exists(\TeacherReportCardReport::class)) {
            $this->app->bind(\iEducar\Reports\Contracts\TeacherReportCard::class, \TeacherReportCardReport::class);
        }
    }
}
