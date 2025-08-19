<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class ShowByGenreController extends Controller
{
    public function index($id)
    {
        $genreComics = Genre::where('id', $id)->with(['comics' => function($query) {
            $query->inRandomOrder()->get();
        }])->first();

        return $genreComics;
    }

    public function showGenres()
    {
        $genres = Genre::has('comics')->get();

        return response()->json($genres, 200);
    }
}
