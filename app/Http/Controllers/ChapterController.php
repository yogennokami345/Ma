<?php

namespace App\Http\Controllers;

use App\Services\ChapterService;
use App\Services\CommentService;
use App\Utils\Settings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChapterController extends Controller
{
    protected $service;
    protected $commentsService;

    public function __construct(ChapterService $service, CommentService $commentsService)
    {
        $this->service = $service;
        $this->commentsService = $commentsService;
    }

    public function index(string $id)
    {
        $chapter = $this->service->getChapter($id);
        $comments = $this->commentsService->chapterIndex($id);
        // dd($comments);
        return Inertia::render('Chapter/Show', [
            'settings' => Settings::get(),
            'chapter' => $chapter,
            'comments' => $comments,
        ]);
        // return response()->json($chapter);
    }

    // Outros métodos do controller
}
