<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Booking;
use App\Models\Coach;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de usuarios', User::count())
                ->description('Todos los usuarios registrados')
                ->descriptionIcon('heroicon-m-user')
                ->color('primary'),

            Stat::make('Entrenadores', Coach::count())
                ->description('Entrenadores registrados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Entrenadores verificados', Coach::where('verified', true)->count())
                ->description('Entrenadores verificados')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
