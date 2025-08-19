<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroResource\Pages;
use App\Filament\Resources\HeroResource\RelationManagers;
use App\Models\Hero;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HeroResource extends Resource
{
    protected static ?string $navigationGroup = 'Comics';

    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Banners';

    protected static ?string $navigationLabel = 'Banners';

    protected static ?string $model = Hero::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->hasPermissionTo('delete_hero');
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->hasPermissionTo('edit_hero');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo('create_hero');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermissionTo('view_hero');
    }

    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
                FileUpload::make('hero_path')
                    ->label('Imagem de Hero')
                    ->image()
                    ->directory('heros')
                    ->disk(env('FILESYSTEM_DISK'))
                    ->required()
                    ->maxSize(10000)
                    ->imageEditor()
                    ->acceptedFileTypes(['image/webp', 'image/jpeg', 'image/png'])
                    ->helperText(
                        fn (): string => 'Requisitos: '
                            . 'Máximo 10MB. '
                            . 'Formatos: WEBP (recomendado), JPEG ou PNG. '
                            . 'Dimensões ideais: 1200x1800px.'
                    )
                    ->hintIcon('heroicon-o-information-circle')
                    ->hintIconTooltip('A imagem de capa será exibida como destaque na página da comic')
                    ->openable()
                    ->downloadable()
                    ->visibility('public')
                    ->imagePreviewHeight('250px')
                    ->panelAspectRatio('2:1'),

                    TextInput::make('hero_link')
                        ->label('Link do Hero')
                        ->placeholder('https://exemplo.com/link-da-comic')
                        ->helperText('Link externo relacionado ao Hero (opcional). Use URLs válidos com http:// ou https://')
                        ->url()
                        ->maxLength(255)
                        ->hint('Será aberto em nova janela quando clicado')
                        ->hintIcon('heroicon-o-globe-alt')
                        ->hintColor('primary')
                        ->hintIconTooltip('Links externos devem seguir nossa política de conteúdo')
                        ->prefixIcon('heroicon-o-link')
                        ->prefixIconColor('primary')
                        ->suffixAction(
                            Action::make('openLink')
                                ->icon('heroicon-o-arrow-top-right-on-square')
                                ->color('gray')
                                ->action(fn (Component $component) => redirect($component->getState()))
                                ->hidden(fn (?string $state): bool => blank($state))
                        )
                        ->autofocus(false)
                        ->columnSpanFull()
                        ->regex('/^(http|https):\/\/[^ "]+$/')
                        ->default(null)
                        ->extraInputAttributes([
                            'autocomplete' => 'off',
                            'spellcheck' => 'false'
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                ImageColumn::make('hero_path')
                    ->label('Hero')
                    ->getStateUsing(fn ($record) => $record->hero_path ? Storage::disk(env('FILESYSTEM_DISK'))->url($record->hero_path) : null),

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
            'index' => Pages\ListHeroes::route('/'),
            'create' => Pages\CreateHero::route('/create'),
            'edit' => Pages\EditHero::route('/{record}/edit'),
        ];
    }
}
