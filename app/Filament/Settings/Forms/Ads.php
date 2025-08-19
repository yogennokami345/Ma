<?php

namespace App\Filament\Settings\Forms;

use Dotswan\FilamentCodeEditor\Fields\CodeEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;

class Ads
{
    /**
     * @return Tab
     */
    public static function getTab(): Tab
    {
        return Tab::make('ads')
                    ->label('Propaganda')
                    ->icon('heroicon-o-computer-desktop')
                    ->schema(self::getFields())
                    ->columns()
                    ->statePath('ads')
                    ->visible(true);
    }

    public static function getFields(): array
    {
        return [
            Section::make('Home')
                ->schema([
                    Section::make('Banner Superior')
                        ->schema([
                            CodeEditor::make('ads_home_top')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                    Section::make('Recem Publicadas')
                        ->schema([
                            CodeEditor::make('ads_home_recent')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                    Section::make('Novos Capítulos')
                        ->schema([
                            CodeEditor::make('ads_home_new_chapters')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                    Section::make('Por Gênero')
                        ->schema([
                            CodeEditor::make('ads_home_per_genre')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                    Section::make('Card')
                        ->schema([
                            CodeEditor::make('ads_home_card')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                    Section::make('Banner Inferior')
                        ->schema([
                            CodeEditor::make('ads_home_bottom')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                ])
                ->columns(3)
                ->collapsible()
                ->collapsed(),
            Section::make('Obra')
                ->schema([
                    Section::make('Banner Superior')
                        ->schema([
                            CodeEditor::make('ads_comic_top')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                    Section::make('Banner Inferior')
                        ->schema([
                            CodeEditor::make('ads_comic_bottom')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                ])
                ->columns(3)
                ->collapsible()
                ->collapsed(),
            Section::make('Leitor')
                ->schema([
                    Section::make('Banner Superior')
                        ->schema([
                            CodeEditor::make('ads_reader_top')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                    Section::make('Banner Inferior')
                        ->schema([
                            CodeEditor::make('ads_reader_bottom')
                                ->label('')
                                ->minHeight(768)
                                ->darkModeTheme('gruvbox-dark')
                                ->lightModeTheme('basic-light')
                                ->columnSpanFull(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),
                ])
                ->columns(3)
                ->collapsible()
                ->collapsed(),
        ];
    }

    public static function getSortOrder(): int
    {
       return 4;
    }
}
