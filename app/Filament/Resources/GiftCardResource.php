<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftCardResource\Pages;
use App\Models\GiftCard;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class GiftCardResource extends Resource
{
    protected static ?string $navigationGroup = 'Administrativo';

    protected static ?string $label = 'Cartões de presente';

    protected static ?string $navigationLabel = 'Cartão de presente';

    protected static ?string $model = GiftCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermissionTo('gift_card_view');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo('create_gift_card');
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->hasPermissionTo('edit_gift_card');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->hasPermissionTo('delete_gift_card');
    }

    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
                TextInput::make('code')
                    ->label('Código do Convite')
                    ->unique(ignorable: fn ($record) => $record)
                    ->reactive()
                    ->disabled()
                    ->dehydrated(true)
                    ->suffixAction(
                        Action::make('gerarCodigo')
                            ->icon('heroicon-m-arrow-path')
                            ->tooltip('Gerar novo código')
                            ->action(function (callable $set) {
                                $novoCodigo = Str::random(30);
                                $set('code', $novoCodigo);
                                $set('invite_link', $novoCodigo);
                                $set('reedim', env('APP_URL') . '/giftcards/redeem/' . $novoCodigo);
                            })
                        )
                    ->helperText('Código único para o link. Pode ser gerado automaticamente.')
                    ->visible(fn ($context) => $context !== 'edit')
                    ->required(),


                TextInput::make('reedim')
                    ->label('Link de Resgate')
                    ->dehydrated(false)
                    ->disabled()
                    ->default('fdsf')
                    ->formatStateUsing(function ($state, $get, $record) {
                        if ($get('code')) {
                            return $get('code');
                        }
                        if ($record && $record->code) {
                            return $record->code;
                        }
                        return '';
                    })
                    ->suffixAction(CopyAction::make())
                    ->afterStateHydrated(function ($component, $get) {
                        $component->state(env('APP_URL') . '/giftcards/redeem/' . $get('code'));
                    })
                    ->helperText('Visualização do link final.'),

                Select::make('gift_create_by_user_id')
                    ->label('Criado por')
                    ->relationship('createByUser', 'email')
                    ->preload()
                    ->searchable()
                    ->nullable()
                    ->helperText('Usuário que criou este gift card'),

                Select::make('owner_user_id')
                    ->label('Convite exclusivo para o usuário?')
                    ->relationship('ownerUser', 'email')
                    ->preload()
                    ->searchable()
                    ->nullable()
                    ->helperText('Se definido, apenas esse usuário poderá usar.'),

                TextInput::make('usage_limit')
                    ->label('Limite de usos')
                    ->numeric()
                    ->default(1)
                    ->required(),

                TextInput::make('usage_count')
                    ->label('Usos efetuados')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->helperText('Quantas vezes já foi usado'),

                DateTimePicker::make('expires_at')
                    ->label('Expira em')
                    ->placeholder('Sem expiração se vazio')
                    ->timezone('America/Sao_Paulo')
                    ->helperText('Data/hora de expiração do convite'),

                // TextInput::make('subscription_days')
                //     ->label('Dias de assinatura a adicionar')
                //     ->numeric()
                //     ->default(7)
                //     ->required(),
                Select::make('plan_id')
                    ->label('Plano')
                    ->relationship('plan', 'name')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->helperText('Se definido, o convite ativa o plano.'),

                Toggle::make('active')
                    ->label('Convite está ativo?')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->toggleable(), // se quiser permitir esconder

                Tables\Columns\TextColumn::make('ownerUser.name')
                    ->label('Exclusivo para (Usuário)')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Usos')
                    ->sortable(),

                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('Limite'),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expira em')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('active')
                    ->label('Ativo'),
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
            'index' => Pages\ListGiftCards::route('/'),
            'create' => Pages\CreateGiftCard::route('/create'),
            'edit' => Pages\EditGiftCard::route('/{record}/edit'),
        ];
    }
}
