<?php

namespace App\Filament\Settings\Forms;

use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Http;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class App
{
    /**
     * @return Tab
     */
    public static function getTab(): Tab
    {
        return Tab::make('app')
            ->label(__('app-settings::app-settings.form.app'))
            ->icon('heroicon-o-computer-desktop')
            ->schema(array_merge(self::getFields()))
            ->columns()
            ->statePath('app');
    }

    public static function getFields(): array
    {
        return [
            Section::make('Basico')
                ->schema([
                    TextInput::make('app_name')
                        ->label(__('app-settings::app-settings.form.field.app.name'))
                        ->maxLength(255)
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('app_url_cdn')
                        ->label('URL do CDN')
                        ->helperText('URL do CDN para os arquivos estáticos, como imagens e scripts.')
                        ->placeholder('https://cdn.exemplo.com')
                        ->maxLength(255)
                        ->required()
                        ->columnSpanFull(),
                    Grid::make()->schema([
                        FileUpload::make('app_logo')
                            ->label(fn() => __('app-settings::app-settings.form.field.app.logo'))
                            ->image()
                            ->directory('assets')
                            ->visibility('public')
                            ->moveFiles()
                            ->imageEditor()
                            ->getUploadedFileNameForStorageUsing(fn() => 'site_logo.png'),
                        FileUpload::make('app_favicon')
                            ->label(fn() => __('app-settings::app-settings.form.field.app.favicon'))
                            ->image()
                            ->directory('assets')
                            ->visibility('public')
                            ->moveFiles()
                            ->getUploadedFileNameForStorageUsing(fn() => 'site_favicon.ico')
                            ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon']),
                    ])->columns(2),
                ])
                ->columns(3)->collapsible(),

            Section::make('Login')
                ->schema([
                    Toggle::make('app_require_auth')
                        ->label('Login obrigatório')
                        ->inline(false)
                        ->columnSpanFull()
                        ->onColor('success')
                        ->offColor('danger')
                        ->onIcon('heroicon-o-star')
                        ->offIcon('heroicon-o-x-circle'),
                ])
                ->columns(3)->collapsible(),

            Section::make('Canal de Avisos')
                ->schema([
                    TextInput::make('app_warning_channel')
                        ->label('Link do canal de avisos')
                        ->placeholder('https://discord.gg/exemple')
                        ->helperText('Insira o link completo do canal para envio dos avisos.')
                        ->columnSpanFull()
                        ->prefixIcon('heroicon-o-link'),
                ])
                ->columns(3)
                ->collapsible(),

            Section::make('Vip')
                ->schema([
                    Toggle::make('app_vip_show_banner')
                        ->label('Mostrar banner de VIP')
                        ->inline(false)
                        ->columnSpanFull()
                        ->onColor('success')
                        ->offColor('danger')
                        ->onIcon('heroicon-o-star')
                        ->offIcon('heroicon-o-x-circle'),
                ])
                ->columns(3)->collapsible(),

            Section::make('Pagina da Comic')
                ->schema([
                    Toggle::make('app_chapter_show_views')
                        ->label('Mostrar visualizações')
                        ->inline(false)
                        ->columnSpanFull()
                        ->onColor('success')
                        ->offColor('danger')
                        ->onIcon('heroicon-o-star')
                        ->offIcon('heroicon-o-x-circle'),
                ])
                ->columns(3)->collapsible(),

            Section::make('Slugs')
                ->schema([
                    TextInput::make('app_comic_slug')
                        ->label('Slug da Comic')
                        ->helperText('Slug utilizado para identificar a comic na URL.')
                        ->placeholder('comic')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])
                ->columns(3)->collapsible(),

            Section::make('Footer Info')
                ->schema([
                    TextInput::make('app_contact_link')
                        ->label('Link de contato')
                        ->helperText('Link para a página de contato ou suporte.')
                        ->placeholder('https://discord.gg/exemplo')
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->prefixIcon('heroicon-o-link'),
                ])
                ->columns(3)->collapsible(),


            Section::make('Livepix')
                ->schema([
                    TextInput::make('app_livepix_client_id')
                        ->label('Client ID')
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->required(fn(callable $get) => !empty($get('app_livepix_client_secret')))
                        ->password(),
                    TextInput::make('app_livepix_client_secret')
                        ->label('Client Secret')
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->required(fn(callable $get) => !empty($get('app_livepix_client_id')))
                        ->password(),
                ])
                ->columns(3)->collapsible(),
        ];
    }

    public static function getSortOrder(): int
    {
        return 2;
    }
}
