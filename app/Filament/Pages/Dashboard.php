<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\TotalQuizzes;
use App\Filament\Widgets\QuizAttemptsChart;
use App\Filament\Widgets\QuizScorePieChart;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public static function getWidgets(): array
    {
        return [
            QuizAttemptsChart::class,
            TotalQuizzes::class,
            QuizScorePieChart::class,
        ];
    }
}
