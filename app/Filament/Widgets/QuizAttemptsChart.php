<?php
namespace App\Filament\Widgets;

use App\Models\QuizAttempt;
use Filament\Widgets\BarChartWidget;

class QuizAttemptsChart extends BarChartWidget
{
    protected static ?string $heading = 'Intentos por día';

    protected function getData(): array
    {
        $data = QuizAttempt::selectRaw('DATE(completed_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->take(7)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Intentos',
                    'data' => $data->pluck('total'),
                ],
            ],
            'labels' => $data->pluck('date')->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M')),
        ];
    }
}
