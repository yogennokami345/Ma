<?php

namespace App\Services;

use App\Models\Chapter;
use Illuminate\Http\Request;


class CommentService
{
    public function chapterIndex($id)
    {
        $chapter = Chapter::where('chapter_path', $id)->first();
        // $chapter->commentAsUser(auth()->user(), 'gay');
        $comments = $chapter->comments()->with('commentator')->get();
        return $comments;
    }

    public function chapterStore(Request $request, $id)
    {
        $chapter = Chapter::where('chapter_path', $id)->first();
        $comment = $chapter->commentAsUser(auth()->user(), $request->content);
        return response()->json($comment);
    }

    public function reponseChapterStore(Request $request, $id)
    {
        $comment = \BeyondCode\Comments\Comment::find($id);
        $response = $comment->commentAsUser(auth()->user(), $request->content);
        return response()->json($response);
    }

    public function deleteChapterComment($id)
    {
        $comment = \BeyondCode\Comments\Comment::find($id);
        if($comment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}