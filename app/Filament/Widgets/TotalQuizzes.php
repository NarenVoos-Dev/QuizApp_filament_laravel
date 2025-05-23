<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Quiz;


class TotalQuizzes extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Quizzes', Quiz::count())
                ->description('Número total de quizzes disponibles')
                ->color('success'),
        ];
    }
}
