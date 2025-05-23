<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\QuizAttempt;

class QuizScorePieChart extends ChartWidget
{
    protected static ?string $heading = 'Resultado de Quizzes';
    protected static string $color = 'warning';
    protected static ?int $sort = 1;

    // ✅ CORRECTO (no estático)
    protected int | string | array $columnSpan = 3; // 4 columnas de ancho

    protected function getData(): array
    {
        $approved = QuizAttempt::whereRaw('score >= total_questions * 0.6')->count();
        $failed = QuizAttempt::count() - $approved;

        return [
            'datasets' => [[
                'data' => [$approved, $failed],
                'backgroundColor' => ['rgba(34,197,94,0.7)', 'rgba(239,68,68,0.7)'],
                'borderWidth' => 0,
            ]],
            'labels' => ['Aprobados', 'Reprobados'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getChartOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive' => true,
            'height' => 180, // Altura fija
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12, // Iconos de leyenda más pequeños
                    ],
                ],
            ],
        ];
    }
    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'p-2', // Padding mínimo
        ];
    }
}