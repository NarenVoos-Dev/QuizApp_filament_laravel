<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizAttemptResource\Pages;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;

class QuizAttemptResource extends Resource
{
    protected static ?string $model = QuizAttempt::class;


    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $pluralLabel = 'Intentos de Prueba';
    protected static ?string $navigationGroup = 'Pruebas';
    protected static ?string $navigationLabel = 'Intentos de Prueba';

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Sin formulario
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quiz.title')->label('Quiz')->sortable()->searchable(),
                TextColumn::make('user.name')->label('Empleado')->sortable()->searchable(),
                TextColumn::make('score')->label('Puntaje')->sortable(),
                TextColumn::make('total_questions')->label('Preguntas'),
                TextColumn::make('completed_at')->label('Fecha')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('quiz_id')
                    ->relationship('quiz', 'title')
                    ->label('Filtrar por Quiz'),
            ])
            ->actions([

                DeleteAction::make(),
            ])
            ->defaultSort('completed_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizAttempts::route('/'),
            //'edit' => Pages\EditQuizAttempt::route('/{record}/edit'),
            //'view' => Pages\ViewQuizAttempt::route('/{record}'),
        ];
    }
}
