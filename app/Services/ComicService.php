<?php

namespace App\Services;

use App\Models\Comic;
use App\Repositories\ComicRepository;
use Illuminate\Support\Facades\Auth;

class ComicService
{
    protected $repository;

    public function __construct(ComicRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getComic(string $slug)
    {
        $comic = Comic::where('slug', $slug)->with((['chapters' => function ($query) {
            $query->orderByDesc('chapter_number');
        }]))->with(['genres', 'statuses'])->firstOrFail();

        return $comic;
    }
    public function checkLibrary($comic)
    {
        $user = Auth::user();
        if ($user === null)
        {
            return false;
        }

        $inLibrary = $user->comics()->where('comic_id', $comic->id)->exists();

        return $inLibrary;


    }
}
