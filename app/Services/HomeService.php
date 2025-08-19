<?php

namespace App\Services;

use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Genre;
use App\Models\Hero;
use App\Repositories\HomeRepository;

class HomeService
{
    protected $repository;

    public function __construct(HomeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getHero()
    {
        $hero = Hero::get();

        return $hero;
    }

    public function getNewReleases()
    {
        $comic = Comic::orderByDesc('created_at')->take(20)->get();

        return $comic;
    }

    public function getNewChapters() {
        $newChapters = Comic::whereHas('chapters')
            ->with(['chapters' => function ($query) {
                $query->orderByDesc('chapter_number')->take(2);
            }])
            ->orderByDesc(
                Chapter::select('created_at')
                    ->whereColumn('comic_id', 'comics.id')
                    ->orderByDesc('created_at')
                    ->limit(1)
            )
            ->get()
            ->map(function ($comic) {
                return [
                    'comic'    => array_merge(
                        $comic->only('id', 'title', 'cover', 'slug'),
                        ['status' => $comic->status]
                    ),
                    'chapters' => $comic->chapters->map(fn($c) => $c->only('chapter_cover', 'chapter_number', 'chapter_title', 'chapter_path', 'created_at', 'locked'))
                ];
            });

        return $newChapters;
    }

    public function PerGenre()
    {
        $genres = Genre::all();
        $comic = $genres->map(function($genre) {
            $randomComics = $genre->comics()->inRandomOrder()->take(20)->get();
            
            return [
            'genre' => $genre->only('id', 'name'),
            'comics' => $randomComics
            ];
        });

        return $comic;
    }
}