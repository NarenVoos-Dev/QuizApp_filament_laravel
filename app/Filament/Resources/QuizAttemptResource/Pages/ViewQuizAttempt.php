<?php

namespace App\Filament\Resources\QuizAttemptResource\Pages;

use App\Filament\Resources\QuizAttemptResource;
use Filament\Resources\Pages\ViewRecord;

class ViewQuizAttempt extends ViewRecord
{
    protected static string $resource = QuizAttemptResource::class;
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('filament.resources.quiz-attempt-resource.pages.view-record', [
            'record' => $this->record,
        ]);
    }
}