<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use App\Models\QuizAttempt;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;

class QuizAttemptRelationManager extends RelationManager
{
    protected static string $relationship = 'attempts';
    protected static ?string $title = 'Resultados';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Empleado'),
                TextColumn::make('score')->label('Puntaje'),
                TextColumn::make('total_questions')->label('Preguntas'),
                TextColumn::make('time_spent')
                    ->label('Tiempo')
                    ->formatStateUsing(fn($state) => gmdate('i:s', $state) . ' min'),
                TextColumn::make('completed_at')->label('Fecha')->dateTime(),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Ver Respuestas')
                    ->url(fn(QuizAttempt $record) => route('filament.resources.quiz-attempts.view', $record)),
            ])
            ->headerActions([])
            ->defaultSort('completed_at', 'desc');
    }
}
