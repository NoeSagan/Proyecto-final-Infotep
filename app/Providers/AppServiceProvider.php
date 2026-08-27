<?php

namespace App\Providers;

use App\Themes\AutoAlquilerTheme;
use DistortedFusion\BladeComponents\ThemeManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::resourceVerbs([
            'create' => 'crear',
            'edit'   => 'editar',
        ]);

        ThemeManager::disableDefaultTheme();
        ThemeManager::registerTheme(AutoAlquilerTheme::class);
    }
}
