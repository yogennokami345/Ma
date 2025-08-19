<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Services\LikeComicService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LikeComicController extends Controller
{
    protected $service;

    public function __construct(LikeComicService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, $slug)
    {
        $comic = Comic::where('slug', $slug)->firstOrFail();
        $user = auth()->user();
        
        $this->service->toggleLike($user, $comic);
        $isLike = $this->service->isLiked($user, $comic);
        return back()->with([
            'likeComic' => $isLike,
        ]);
    }

    // Outros métodos do controller
}