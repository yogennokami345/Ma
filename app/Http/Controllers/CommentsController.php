<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }   

    public function chapterStore(Request $request, $id)
    {
        $this->commentService->chapterStore($request, $id);

        return back()->with('success');
    }

    public function reponseCommentStore(Request $request, $id)
    {
        $this->commentService->reponseChapterStore($request, $id);
        return back()->with('success');
    }

    public function deleteComment($id)
    {
        $this->commentService->deleteChapterComment($id);
        return back()->with('success');
    }
}
