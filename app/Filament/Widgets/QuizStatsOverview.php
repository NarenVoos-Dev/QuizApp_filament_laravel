<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Quiz;
use App\Models\User;

class QuizStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Quizzes', Quiz::count())
                ->description('Quizzes disponibles')
                ->color('success'),
                
            Stat::make('Usuarios registrados', User::count())
                ->description('Total de usuarios')
                ->color('primary'),
        ];
    }
    protected function getColumns(): int
    {
        return 2; // Muestra ambos stats en una fila
    }
}
