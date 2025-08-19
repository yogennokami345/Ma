<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChapterResource\Pages;
use App\Filament\Resources\ChapterResource\RelationManagers;
use App\Models\Chapter;
use App\Models\Comic;
use App\Services\ChapterService;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChapterResource extends Resource
{
    protected static ?string $model = Chapter::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->hasPermissionTo('edit_chapter');
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->hasPermissionTo('delete_chapter');
    }

    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
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

                    TextInput::make('chapter_path')
                        ->label('ID do Capítulo')
                        ->default(Str::uuid()->toString())
                        ->required()
                        ->disabled()
                        ->helperText('Identificador único gerado automaticamente')
                        ->hintIcon('heroicon-m-document-duplicate')
                        ->columnSpan(1),

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
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),

                TextColumn::make('chapter_number')
                    ->label('Capítulo')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('views')
                    ->label('Visualizações')
                    ->sortable()
                    ->searchable()
                    ->default(0)
                    ->icon('heroicon-o-fire')
                    ->iconPosition('before')
                    ->alignCenter()
                    ->color('success')
                    ->formatStateUsing(fn (string $state): string =>
                        number_format($state, 0, ',', '.'))
                    ->description(fn ($record) =>
                        $record->views > 1000 ? '🔥 Popular!' :
                        ($record->views > 500 ? '📈 Crescendo!' : ''))
                    ->size('sm'),

                TextColumn::make('created_at')
                    ->label('Criado á')
                    ->date('d/m/Y H:i:s'),

                TextColumn::make('updated_at')
                    ->label('Atualizado á')
                    ->date('d/m/Y H:i:s'),
            ])
            ->filters([
                SelectFilter::make('chapter_comic_id')
                    ->label('Mangá')
                    ->relationship('comic', 'title')
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Action::make('upload-chapters')
                ->label('Capítulos Upload')
                ->icon('heroicon-o-plus')
                ->action(function (array $data){
                    ChapterService::createChapterPages($data, intval($data['comic_id']));
                    Notification::make()
                        ->title('Sucesso')
                        ->body('Capítulo criado com sucesso!')
                        ->success()
                        ->send();
                    })
                ->form([
                        Select::make('comic_id')
                            ->relationship('comic', 'title', function () {
                                $user = Auth::user();
                                if ($user->hasRole('admin')) {
                                    return Comic::query();
                                }
                                return Comic::query()->whereHas('groups', function (Builder $query) use ($user) {
                                    $query->whereIn('groups.id', $user->groups()->pluck('groups.id'));
                                });
                            })
                            ->preload()
                            ->searchable()
                            ->default(function () {
                                $referer = request()->header('referer');
                                $queryString = parse_url($referer, PHP_URL_QUERY);
                                parse_str($queryString, $queryParams);
                                return data_get($queryParams, 'tableFilters.chapter_comic_id.value');
                            })
                            ->required(),

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
                            ->disk(env('FILESYSTEM_DISK'))
                            ->multiple()
                            ->image()
                            ->required()
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

                Action::make('multi_files')
                    ->label('Multi Upload')
                    ->icon('heroicon-o-document-plus')
                    ->action(function (array $data){
                        $zip = $data['zip_file'];
                        $local = Storage::disk('local')->path($zip);
                        ChapterService::multUpZip($local, intval($data['comic_id']));
                        Storage::disk('local')->delete($local);
                        Notification::make()
                            ->title('Sucesso')
                            ->body('Capítulos criados com sucesso!')
                            ->success()
                            ->send();
                        })
                    ->form([
                        Select::make('comic_id')
                            ->relationship('comic', 'title', function () {
                                $user = Auth::user();
                                if ($user->hasRole('admin')) {
                                    return Comic::query();
                                }
                                return Comic::query()->whereHas('groups', function (Builder $query) use ($user) {
                                    $query->whereIn('groups.id', $user->groups()->pluck('groups.id'));
                                });
                            })
                            ->label('Quadrinho Relacionado')
                            ->preload()
                            ->searchable()
                            ->default(function () {
                                try {
                                    $referer = request()->header('referer');
                                    if (!$referer) return null;

                                    $queryString = parse_url($referer, PHP_URL_QUERY) ?? '';
                                    parse_str($queryString, $queryParams);

                                    return data_get($queryParams, 'tableFilters.chapter_comic_id.value');
                                } catch (\Exception $e) {
                                    return null;
                                }
                            })
                            ->required()
                            ->helperText('Selecione o quadrinho ao qual este capítulo pertence')
                            ->hintIcon('heroicon-m-question-mark-circle')
                            ->hintIconTooltip('Comece a digitar para buscar quadrinhos')
                            ->placeholder('Selecione ou busque um quadrinho')
                            ->columnSpanFull(),

                        FileUpload::make('zip_file')
                            ->label('Arquivo ZIP de Capítulos')
                            ->required()
                            ->maxSize(3145728) // 3 GB em KB
                            ->disk('local')
                            ->directory('temp_zips')
                            ->acceptedFileTypes([
                                'application/zip',
                                'application/x-zip-compressed',
                                'application/octet-stream',
                                'application/x-zip'
                            ])
                            ->hint('Tamanho máximo: 3GB | Formato: .zip') // Atualizado para 3GB
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintColor('primary')
                            ->preserveFilenames()
                            ->visibility('private')
                            ->downloadable()
                            ->previewable(false)
                            ->imagePreviewHeight('250px')
                            ->uploadingMessage('Enviando arquivo... (isso pode levar alguns minutos)')
                            ->maxFiles(1)
                            ->deleteUploadedFileUsing(function () {})
                            ->columnSpanFull()
                            ->validationMessages([
                                'maxSize' => 'O arquivo excede o limite de 3GB', // Atualizado para 3GB
                                'acceptedFileTypes' => 'Formato inválido. Apenas arquivos ZIP são aceitos'
                            ]),
                    ])
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
            'index' => Pages\ListChapters::route('/'),
            'create' => Pages\CreateChapter::route('/create'),
            'edit' => Pages\EditChapter::route('/{record}/edit'),
        ];
    }
}
