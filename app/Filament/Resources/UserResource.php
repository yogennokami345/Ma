<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $navigationGroup = 'Administrativo';

    protected static ?string $label = 'Usuários';

    protected static ?string $navigationLabel = 'Usuários';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo('create_user');
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->hasPermissionTo('edit_user');
    }

    public static function canDelete($record): bool
    {
        return Auth::user()->hasPermissionTo('delete_user');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermissionTo('view_user');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('banner')
                    ->label('Banner')
                    ->image()
                    ->disk(env('FILESYSTEM_DISK'))
                    ->directory('user/banners')
                    ->maxSize(10240)
                    ->acceptedFileTypes(['image/webp', 'image/jpeg', 'image/png'])
                    ->helperText('Upload de imagens: formatos permitidos WEBP, JPEG, PNG, máximo 10MB')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('right')
                    ->panelAspectRatio('2:1'),

                FileUpload::make('cover')
                    ->label('Cover')
                    ->image()
                    ->disk(env('FILESYSTEM_DISK'))
                    ->directory('user/covers')
                    ->maxSize(10240)
                    ->acceptedFileTypes(['image/webp', 'image/jpeg', 'image/png'])
                    ->helperText('Upload de imagens: formatos permitidos WEBP, JPEG, PNG, máximo 10MB')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('right')
                    ->panelAspectRatio('2:1'),

                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->unique(
                        table: User::class,
                        column: 'name',
                        ignoreRecord: true
                    )
                    ->maxLength(50)
                    ->helperText('O nome deve conter no máximo 50 caracteres'),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->formatStateUsing(fn (User $user) => $user->email ? $user->email : '')
                    ->unique(
                        table: User::class,
                        column: 'email',
                        ignoreRecord: true
                    )
                    ->helperText('O email deve ser único e válido'),

                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->required(fn (string $context) => $context === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->minLength(8)
                    ->maxLength(64)
                    ->helperText('A senha deve ter no mínimo 8 caracteres. Informe apenas se deseja alterá-la.'),

                // Toggle::make('force_password_change')
                //     ->label('Marcar como senha temporária')
                //     ->helperText('Se marcado, o usuário será obrigado a alterar a senha no próximo login.')
                //     ->default(false),
                TextInput::make('user_path')
                    ->label('ID do usuario')
                    ->default(Str::uuid()->toString())
                    ->required()
                    ->disabled()
                    ->helperText('Identificador único gerado automaticamente')
                    ->hintIcon('heroicon-m-document-duplicate')
                    ->columnSpan(1),

                Select::make('role')
                    ->label('Cargo')
                    ->relationship('roles', 'name')
                    ->default(fn () => Role::where('name', 'user')->first()?->id)
                    ->multiple(false)
                    ->preload()
                    ->searchable()
                    ->required()
                    ->helperText('Selecione o cargo para o usuário'),

                RichEditor::make('description')
                    ->label('Bio')
                    ->minLength(2)
                    ->helperText('Escreva uma bio curta e descontraída - máximo 150 caracteres para se apresentar de forma divertida')
                    ->hintIcon('heroicon-m-information-circle')
                    ->hintColor('primary')
                    ->placeholder('Conte um pouco sobre você...')
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

                DatePicker::make('subscription_start')
                    ->label('Início da Assinatura')
                    ->helperText('Selecione a data em que a assinatura começa.')
                    ->prefix('Começa')
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->timezone('America/Sao_Paulo'),

                DatePicker::make('subscription_end')
                    ->label('Fim da Assinatura')
                    ->helperText('Selecione a data em que a assinatura finaliza.')
                    ->prefix('Termina')
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->timezone('America/Sao_Paulo')
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

                TextColumn::make('first_role')
                    ->label('Cargo')
                    ->getStateUsing(fn(User $record) => optional($record->roles->first())->name)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'editor' => 'warning',
                        'user'   => 'success',
                        default  => 'secondary',
                    }),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
