<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Tables\Actions\ViewAction;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\Action;
use Filament\Forms\Get;



use Illuminate\Support\Str;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;
    protected static ?string $navigationLabel = 'Cuestionarios';
    protected static ?string $pluralLabel = 'Cuestionarios';
    protected static ?string $modelLabel = 'Cuestionario';
    protected static ?string $navigationGroup = 'Pruebas';

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        
                        Textarea::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),
                        
                        DateTimePicker::make('start_date')
                            ->label('Fecha de Inicio')
                            ->required()
                            ->displayFormat('d/m/Y H:i'),
                            
                        
                        DateTimePicker::make('end_date')
                            ->label('Fecha de Fin')
                            ->required()
                            ->displayFormat('d/m/Y H:i')
                            ->minDate(fn (Get $get) => $get('start_date')),
                        
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'active' => 'Activo',
                                'inactive' => 'Inactivo'
                            ])
                            ->required(),
                    ])
                    ->columns(2),
                
                Card::make()
                    ->schema([
                        TextInput::make('question_count')
                            ->label('Cantidad de Preguntas')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('time_per_question', $state > 0 ? 1 : 0);
                            }),
                        
                        TextInput::make('time_per_question')
                            ->label('Tiempo por Pregunta')
                            ->numeric()
                            ->required()
                            ->suffix('minutos'),
                        
                        TextInput::make('attempts')
                            ->label('Intentos')
                            ->numeric()
                            ->minValue(1)
                            ->default(1),
                        
                        Toggle::make('show_results')
                            ->label('Mostrar Resultados')
                            ->inline(false),
                    ])
                    ->columns(3),
                
                Card::make()
                    ->schema([
                        Repeater::make('questions')
                            ->label('Preguntas')
                            ->relationship()
                            ->schema([
                                Textarea::make('question')
                                    ->label('Pregunta')
                                    ->required()
                                    ->columnSpanFull(),
                                
                                Select::make('type')
                                    ->label('Tipo de Pregunta')
                                    ->options([
                                        'multiple_choice' => 'Opción múltiple',
                                        'open' => 'Respuesta abierta'
                                    ])
                                    ->required()
                                    ->reactive(),
                                
                                Repeater::make('options')
                                        ->label('Opciones')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('option')
                                            ->required(),
                                        
                                        Checkbox::make('is_correct')
                                            ->label('Correcta?')
                                    ])
                                    ->columns(2)
                                    ->visible(fn ($get) => $get('type') === 'multiple_choice')
                                    ->createItemButtonLabel('Añadir opción')
                                    ->minItems(2)
                            ])
                            ->collapsible()
                            ->createItemButtonLabel('Añadir pregunta')
                            ->defaultItems(1)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Título')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Estado')
                    ->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('start_date')->label('Fecha de Inicio')->dateTime(),
                Tables\Columns\TextColumn::make('end_date')->label('Fecha de Fin')->dateTime(),
                Tables\Columns\IconColumn::make('show_results')->label('Mostrar Resultados')->boolean(),
                

            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
    public static function getFormMaxWidth(): string | int | null
    {
        return 'full';
    }
}
