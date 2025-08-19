<?php

namespace App\Repositories;

class LikeComicRepository
{
    public function like($user, $comic)
    {
        return $user->likedComics()->attach($comic->id);
    }

    public function unlike($user, $comic)
    {
        return $user->likedComics()->detach($comic->id);
    }

    public function isLiked($user, $comic)
    {
        return $user->likedComics()->where('comic_id', $comic->id)->exists();
    }

    public function countLikes($comic)
    {
        return $comic->likes()->count();
    }
}