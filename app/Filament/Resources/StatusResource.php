<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusResource\Pages;
use App\Filament\Resources\StatusResource\RelationManagers;
use App\Models\Status;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class StatusResource extends Resource
{
    protected static ?string $navigationGroup = 'Comics';

    protected static ?int $navigationSort = 4;

    protected static ?string $label = 'Status';

    protected static ?string $navigationLabel = 'Status';

    protected static ?string $model = Status::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo('create_status');
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->hasPermissionTo('edit_status');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->hasPermissionTo('delete_status');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermissionTo('view_status');
    }

    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
                TextInput::make('name')
                    ->label('Nome do Status')
                    ->required()
                    ->unique(table: Status::class, column: 'name', ignoreRecord: true)
                    ->hint('Ex: "Em Progresso" ou "Concluído"')
                    ->helperText('Nome único que identifica o status')
                    ->maxLength(50)
                    ->placeholder('Digite um nome para o status...')
                    ->columnSpanFull(),

                TextInput::make('description')
                    ->label('Descrição')
                    ->hint('Opcional')
                    ->helperText('Breve explicação sobre quando usar este status')
                    ->placeholder('Ex: "Etapa de desenvolvimento ativo"')
                    ->maxLength(255)
                    ->columnSpanFull()
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

                TextColumn::make('created_at')
                    ->label('Criado á')
                    ->date('d/m/Y H:i:s'),

                TextColumn::make('updated_at')
                    ->label('Atualizado á')
                    ->date('d/m/Y H:i:s'),
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
            'index' => Pages\ListStatuses::route('/'),
            'create' => Pages\CreateStatus::route('/create'),
            'edit' => Pages\EditStatus::route('/{record}/edit'),
        ];
    }
}
