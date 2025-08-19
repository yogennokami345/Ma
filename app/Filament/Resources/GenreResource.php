<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenreResource\Pages;
use App\Filament\Resources\GenreResource\RelationManagers;
use App\Models\Genre;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class GenreResource extends Resource
{
    protected static ?string $navigationGroup = 'Comics';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Generos';

    protected static ?string $navigationLabel = 'Generos';

    protected static ?string $model = Genre::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->hasPermissionTo('delete_genre');
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->hasPermissionTo('edit_genre');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo('create_genre');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermissionTo('view_genre');
    }

    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->minLength(3)
                    ->maxLength(50)
                    ->unique(table: Genre::class, column: 'name', ignoreRecord: true)
                    ->helperText('O nome deve ter entre 3 e 50 caracteres.')
                    ->unique()
                    ->required()
                    ->placeholder('Digite seu nome'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('updated_at'),

                TextColumn::make('created_at'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => !Auth::user()->hasRole('editor')),
                ]),
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
            'index' => Pages\ListGenres::route('/'),
            'create' => Pages\CreateGenre::route('/create'),
            'edit' => Pages\EditGenre::route('/{record}/edit'),
        ];
    }
}
