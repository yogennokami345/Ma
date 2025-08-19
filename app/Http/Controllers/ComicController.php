<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Services\ComicService;
use App\Services\LikeComicService;
use App\Utils\Settings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComicController extends Controller
{
    protected $service;
    protected $serviceLike;

    public function __construct(ComicService $service, LikeComicService $serviceLike)
    {
        $this->service = $service;
        $this->serviceLike = $serviceLike;
    }

    public function index(string $slug)
    {
        $comic = $this->service->getComic($slug);
        $inLibrary = $this->service->checkLibrary($comic);
        $likeComic = $this->serviceLike->isLiked(auth()->user(), $comic);
        $countLikes = $this->serviceLike->countLikes($comic);
        return Inertia::render('Comic/Show', [
            'settings' => Settings::get(),
            'comic_infos' => $comic,
            'inLibrary' => $inLibrary,
            'likeComic' => $likeComic,
            'countLikes' => $countLikes
        ]);
    }

    public function library(Request $request, Comic $comic)
    {
        $user = auth()->user();

        $wasAttached = $user->comics()->where('comic_id', $comic->id)->exists();
        $user->comics()->toggle($comic->id);

        $nowAttached = !$wasAttached;

        return back()->with([
            'message' => $nowAttached
                ? 'Comic adicionado à biblioteca'
                : 'Comic removido da biblioteca',
            'inLibrary' => $nowAttached
        ]);
    }

    // Outros métodos do controller
}
