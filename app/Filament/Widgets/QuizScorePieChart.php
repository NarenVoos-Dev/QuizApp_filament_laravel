<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\QuizAttempt;

class QuizScorePieChart extends ChartWidget
{
    protected static ?string $heading = 'Resultado de Quizzes';
    protected static string $color = 'warning';
    protected static ?int $sort = 1; // Orden en el dashboard

    protected function getData(): array
    {
        $approved = QuizAttempt::whereColumn('score', '>=', 'total_questions' * 0.6)->count();
        $failed = QuizAttempt::count() - $approved;

        return [
            'datasets' => [
                [
                    'label' => 'Intentos',
                    'data' => [$approved, $failed],
                    'backgroundColor' => [
                        'rgba(34,197,94,0.7)',  // Verde aprobado
                        'rgba(239,68,68,0.7)',  // Rojo reprobado
                    ],
                ],
            ],
            'labels' => ['Aprobados', 'Reprobados'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
