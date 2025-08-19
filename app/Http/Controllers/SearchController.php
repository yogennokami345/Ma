<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\User;
use App\Utils\CleanPaginate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');

        $comics = CleanPaginate::clean(Comic::search($query)
            ->query(fn (Builder $query) => $query
                ->with('statuses')
                ->withCount('chapters'))
            ->paginate(10));
        $users = CleanPaginate::clean(User::search($query)->paginate(10));

        return response()->json([
            'comics' => $comics,
            'users' => $users
        ]);
    }

}
