<?php

namespace App\Providers;

use App\Models\User;
use Filament\Support\Colors\Color;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Configurar colores de Filament
        \Filament\Support\Facades\FilamentColor::register([
            'primary' => Color::Amber,
            'danger' => Color::Rose,
            'success' => Color::Emerald,
            'gray' => Color::Gray,
        ]);
    }
}
