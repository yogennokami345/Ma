<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static ?string $navigationGroup = 'Administrativo';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Planos';

    protected static ?string $navigationLabel = 'Planos';

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo('create_plan');
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->hasPermissionTo('edit_plan');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->hasPermissionTo('delete_plan');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermissionTo('view_plan');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),

                TextInput::make('price')
                    ->label('Preço')
                    ->prefix('R$')
                    ->minValue(1)
                    ->default(1)
                    ->numeric()
                    ->required()
                    ->step(0.50),

                TextInput::make('days')
                    ->label('Dias')
                    ->default(30)
                    ->numeric()
                    ->required(),

                Select::make('role_id')
                    ->relationship('role', 'name')
                    ->label('Role')
                    ->preload()
                    ->searchable()
                    ->required(),

                RichEditor::make('description')
                    ->label('Descrição')
                    ->minLength(2)
                    ->required()
                    ->helperText('Forneça uma descrição breve do plano - máximo 150 caracteres.')
                    ->hintIcon('heroicon-m-document-text')
                    ->hintColor('primary')
                    ->placeholder('Descreva o plano...')
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'undo',
                    ])
                    ->columnSpanFull()
                    ->maxLength(150),
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
                TextColumn::make('price')
                    ->sortable(),
                TextColumn::make('days')
                    ->sortable(),
                TextColumn::make('role.name')
                    ->badge()
                    ->label('Role')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
