<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

class QuizUsuarios extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios', User::count())
                ->description('Número total de usuarios registrados')
                ->color('success'),
        ];
    }

    protected function getColumns(): int
    {
        return 2; // Ocupa una fila completa debajo
    }
}
