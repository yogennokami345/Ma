<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use App\Utils\Settings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    protected $service;

    public function __construct(HomeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $hero = $this->service->getHero();
        $newReleases = $this->service->getNewReleases();
        $newChapters = $this->service->getNewChapters();
        $perGenre = $this->service->PerGenre();

        return Inertia::render('Home', [
            'settings' => Settings::get(),
            'hero' => $hero,
            'new_releases' => $newReleases,
            'new_chapters' => $newChapters,
            'per_genres' => $perGenre,
        ]);

        // return response()->json([
        //     'hero' => $hero,
        //     'new_releases' => $newReleases,
        //     'new_chapters' => $newChapters,
        //     'per_genres' => $perGenre,
        // ]);
    }

    // Outros métodos do controller
}
