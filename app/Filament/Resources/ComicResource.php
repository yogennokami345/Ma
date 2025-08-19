<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComicResource\Pages;
use App\Models\Comic;
use App\Services\ChapterService;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComicResource extends Resource
{
    protected static ?string $navigationGroup = 'Comics';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Obras';

    protected static ?string $navigationLabel = 'Obras';

    protected static ?string $model = Comic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->hasPermissionTo('edit_comic');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasPermissionTo('create_comic');
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->hasPermissionTo('delete_comic');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermissionTo('comic_view');
    }

    public static function form(Form $form): Form
    {
        return $form->columns(1)
        ->schema([
            FileUpload::make('banner')
                ->label('Banner')
                ->image()
                ->directory('banner')
                ->disk(env('FILESYSTEM_DISK'))
                ->acceptedFileTypes(['image/webp', 'image/jpeg', 'image/png'])
                ->helperText('Imagem do banner da Comic. Formatos suportados: WEBP, JPEG, PNG.'),

            FileUpload::make('cover')
                ->label('Capa')
                ->image()
                ->directory('covers')
                ->disk(env('FILESYSTEM_DISK'))
                ->required()
                ->acceptedFileTypes(['image/webp', 'image/jpeg', 'image/png'])
                ->helperText('Imagem da capa da Comic. Formatos suportados: WEBP, JPEG, PNG.'),

            TextInput::make('title')
                ->label('Título')
                ->required()
                ->minLength(2)
                ->maxLength(255)
                ->lazy()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('slug', Str::slug($state));
                })
                ->unique(table: Comic::class, column: 'title', ignoreRecord: true)
                ->helperText('Título da Comic. Será usado para gerar o slug automaticamente.'),

            TextInput::make('slug')
                ->label('Slug')
                ->prefix(env('APP_URL') . '/')
                ->minLength(2)
                ->maxLength(255)
                ->required()
                ->unique(table: Comic::class, column: 'slug', ignoreRecord: true)
                ->helperText('URL amigável da Comic. Gerado automaticamente a partir do título.'),

            TextInput::make('alternative_name')
                ->label('Nome Alternativo')
                ->minLength(2)
                ->maxLength(255)
                ->helperText('Nome alternativo ou tradução do título da Comic.'),

            Select::make('genres')
                ->label('Gêneros')
                ->preload()
                ->multiple()
                ->searchable()
                ->relationship('genres', 'name')
                ->required()
                ->helperText('Selecione os gêneros associados a Comic'),

            Select::make('status')
                ->label('Status')
                ->preload()
                ->searchable()
                ->relationship('statuses', 'name')
                ->required()
                ->helperText('Status de publicação da Comic.'),

            RichEditor::make('description')
                ->label('Descrição')
                ->minLength(2)
                ->required()
                ->helperText('Descrição detalhada da Comic. Use o editor para formatar o texto.'),

            Select::make('groups')
                ->label('Grupos')
                ->preload()
                ->multiple()
                ->searchable()
                ->relationship('groups', 'name')
                ->helperText('Selecione um ou mais grupos associados à Comic.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $user = Auth::user();
                if ($user->hasRole('admin')) {
                    return Comic::query();
                }
                return Comic::query()->whereHas('groups', function (Builder $query) use ($user) {
                    $query->whereIn('groups.id', $user->groups()->pluck('groups.id'));
                });
            })
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                ImageColumn::make('cover')
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->cover ? Storage::disk(env('FILESYSTEM_DISK'))->url($record->cover) : null),

                TextColumn::make('title')
                    ->searchable()
                    ->label('Título'),

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
                ActionGroup::make([
                    Action::make('upload-chapters')
                        ->label('Capítulos Upload')
                        ->icon('heroicon-m-arrow-up-tray')
                        ->action(function ($record, array $data) {
                            ChapterService::createChapterPages($data, $record->id);
                            Notification::make()
                                ->title('Sucesso')
                                ->body('Capítulo criado com sucesso!')
                                ->success()
                                ->send();
                            })
                        ->form([
                            FileUpload::make('chapter_cover')
                                ->label('Capa do Capítulo')
                                ->directory('chapterCover')
                                ->disk(env('FILESYSTEM_DISK'))
                                ->image()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->multiple(false)
                                ->maxFiles(1)
                                ->imagePreviewHeight('300px')
                                ->imageEditor()
                                ->helperText('Imagem de capa do capítulo (recomendado proporção 3:4)')
                                ->hintIcon('heroicon-m-information-circle')
                                ->hintColor('primary')
                                ->hintIconTooltip('Formatos suportados: JPEG, PNG, WebP'),

                            TextInput::make('chapter_number')
                                ->required()
                                ->numeric()
                                ->prefix('Capítulo')
                                ->label('Número do Capítulo')
                                ->minValue(1)
                                ->maxValue(1000)
                                ->helperText('Número sequencial do capítulo (ex: 1, 2, 3...)')
                                ->hint('Apenas números')
                                ->default(1)
                                ->columnSpan(1),

                            TextInput::make('id_chapter')
                                ->label('ID do Capítulo')
                                ->default(Str::uuid()->toString())
                                ->required()
                                ->disabled()
                                ->helperText('Identificador único gerado automaticamente')
                                ->hintIcon('heroicon-m-document-duplicate')
                                ->columnSpan(1),

                            TextInput::make('title')
                                ->label('Título do Capítulo')
                                ->maxLength(60)
                                ->helperText('Título exibido na lista de capítulos')
                                ->hint('Máximo 60 caracteres')
                                ->columnSpanFull(),

                            DateTimePicker::make('locked')
                                ->label('Bloqueio')
                                ->nullable()
                                ->helperText('Selecione uma data e hora para bloquear o capítulo (opcional)')
                                ->hint('Este campo é opcional')
                                ->displayFormat('d/m/Y H:i:s')
                                ->firstDayOfWeek(0)
                                ->timezone('America/Sao_Paulo')
                                ->dehydrateStateUsing(function ($state) {
                                    if ($state) {
                                        return \Carbon\Carbon::parse($state, 'America/Sao_Paulo')
                                            ->setTimezone('UTC')
                                            ->format('Y-m-d H:i:s');
                                    }
                                    return null;
                                })
                                ->columnSpan(1),

                            FileUpload::make('page_path')
                                ->label('Páginas do Capítulo')
                                ->directory('pages')
                                ->multiple()
                                ->image()
                                ->required()
                                ->disk(env('FILESYSTEM_DISK'))
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxParallelUploads(5)
                                ->imageResizeMode('cover')
                                ->imageEditor()
                                ->reorderable()
                                ->appendFiles()
                                ->helperText(
                                    'Formatos aceitos: JPEG, PNG, WebP | ' .
                                    'Arquivos serão ordenados numericamente pelo nome (ex: 1.jpg, 2.jpg)'
                                )
                                ->hintIcon('heroicon-m-information-circle')
                                ->hintIconTooltip('Ordene as páginas arrastando ou nomeie os arquivos em ordem numérica')
                                ->afterStateUpdated(function (callable $set, $state) {
                                    $orderedFiles = collect($state)
                                        ->sortBy(function ($file) {
                                            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                                            preg_match('/^\d+/', $filename, $matches);
                                            $number = isset($matches[0]) ? intval($matches[0]) : 0;

                                            return $number;
                                        })
                                        ->values()
                                        ->toArray();

                                    $set('page_path', $orderedFiles);
                                })
                                ->columnSpanFull()
                        ]),
                    Action::make('chapters')
                        ->label('Capítulos')
                        ->icon('heroicon-m-numbered-list')
                        ->openUrlInNewTab()
                        ->url(fn ($record) => route('filament.admin.resources.chapters.index', ['tableFilters[chapter_comic_id][value]' => $record->id])),

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn () => !Auth::user()->hasRole('editor')),
                ]),
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
            'index' => Pages\ListComics::route('/'),
            'create' => Pages\CreateComic::route('/create'),
            'edit' => Pages\EditComic::route('/{record}/edit'),
        ];
    }
}
