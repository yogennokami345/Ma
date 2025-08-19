<?php

namespace App\Livewire\Widgets;

use App\Models\Chapter;
use App\Models\Comic;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class StatusChart extends BaseWidget
{
    protected static ?int $navigationSort = -2;

    protected static ?string $pollingInterval = '10s';

    /**
     * @return array|Stat[]
     */
    protected function getStats(): array
    {
        // Total Comics created
        $totalComics = Comic::count();

        // Total Chapters created
        $totalChapters = Chapter::count();

        // Total Users created
        $totalUsers = User::count();

        // Total Users per month
        $totalUsersMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        // Total Users last month
        $totalUsersLastMonth = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();
        
        // Total Chapters per month
        $totalChaptersMonth = Chapter::whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        // Total Chapters last month
        $totalChaptersLastMonth = Chapter::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();
        
        // Total Comics per month
        $totalComicsMonth = Comic::whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        // Total Comics last month
        $totalComicsLastMonth = Comic::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();
        
        // Trend Count Chapters month
        $trendChapters = Trend::query(Chapter::where('created_at', '>=', Carbon::now()->subDays(30)))
            ->between(
                start: now()->subDays(30),
                end: now()
            )->perDay()->count();
        
        // Trend Count Comics month
        $trendComics = Trend::query(Comic::where('created_at', '>=', Carbon::now()->subDays(30)))
            ->between(
                start: now()->subDays(30),
                end: now()
            )->perDay()->count();
        
        // Trend Count Users month
        $trendUsers = Trend::query(User::where('created_at', '>=', Carbon::now()->subDays(30)))
            ->between(
                start: now()->subDays(30),
                end: now()
            )->perDay()->count();

        return [
            Stat::make('Obras mês', $totalComicsMonth)
            ->description('Obras criadas no mês')
            ->descriptionIcon($totalComicsLastMonth > $totalComicsMonth ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up')
            ->color($totalComicsLastMonth > $totalComicsMonth ? 'gray' : 'success')
            ->chart($trendComics->map(fn (TrendValue $value) => $value->aggregate)->toArray()),

            Stat::make('Capítulos mês', $totalChaptersMonth)
            ->description('Capítulos criados no mês')
            ->descriptionIcon($totalChaptersLastMonth > $totalChaptersMonth ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up')
            ->color($totalChaptersLastMonth > $totalChaptersMonth ? 'red' : 'success')
            ->chart($trendChapters->map(fn (TrendValue $value) => $value->aggregate)->toArray()),

            Stat::make('Usuários mês', $totalUsersMonth)
                ->description('Usuários cadastrados no mês')
                ->descriptionIcon($totalUsersLastMonth > $totalUsersMonth ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up')
                ->color($totalUsersLastMonth > $totalUsersMonth ? 'red' : 'success')
                ->chart($trendUsers->map(fn (TrendValue $value) => $value->aggregate)->toArray()),
            
            Stat::make('Total Obras', $totalComics),

            Stat::make('Total Capítulos', $totalChapters),

            Stat::make('Total Usuários', $totalUsers),
        ];
    }
}
