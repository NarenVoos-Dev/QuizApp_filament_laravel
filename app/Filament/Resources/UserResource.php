<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $pluralLabel = 'Usuarios';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $navigationGroup = 'Users';

    public static function form(Form $form): Form
    {
         return $form->schema([
        TextInput::make('name')->required(),
        TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
        TextInput::make('password')
            ->password()
            ->required(fn (string $context) => $context === 'create')
            ->dehydrateStateUsing(fn ($state) => \Hash::make($state))
            ->label('Contraseña'),

        Select::make('role')
            ->label('Rol')
            ->options([
                'administrador' => 'Administrador',
                'usuario' => 'Usuario',
            ])
            ->required(),
    ]);

    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name'),
            TextColumn::make('email'),
            TextColumn::make('role')->label('Rol'),
        ])
        ->filters([
            // Opcional: agregar filtros por rol
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
