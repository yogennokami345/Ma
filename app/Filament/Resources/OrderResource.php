<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Plan;
use App\Payments\Livepix\Livepix;
use App\Repositories\OrderRepository;
use App\Repositories\ShoppingRepository;
use App\Services\ShoppingService;
use Filament\tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Administrativo';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Pedidos';
    protected static ?string $navigationLabel = 'Pedidos';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermissionTo('view_order');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable(),
                TextColumn::make('plan.name')
                    ->label('Plano')
                    ->searchable(),
                TextColumn::make('plan.price')
                    ->label('Preço')
                    ->sortable(),
                TextColumn::make('paid')
                    ->badge()
                    ->label('Status')
                    ->formatStateUsing(fn(string $state): string => $state ? 'Pago' : 'Pendente')
                    ->color(fn(string $state): string => $state ? 'success' : 'danger'),
            ])
            ->filters([
                SelectFilter::make('paid')
                    ->searchable()
                    ->label('Status')
                    ->options([
                        0 => 'Pendente',
                        1 => 'Pago',
                    ]),
            ])
            ->actions([
                Action::make('valid')
                ->label('Verificar pagamento')
                ->action(function ($record) {
                    $order = new Order();
                    $plan = new Plan();
                    $shoppingService = new ShoppingService(
                        new ShoppingRepository($plan),
                        new OrderRepository($order),
                        new Livepix()
                    );
                    $shoppingService->verifyPayment($record->id);
                })->visible(fn ($record) => !$record->paid),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
