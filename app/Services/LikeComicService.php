<?php

namespace App\Services;

use App\Repositories\LikeComicRepository;

class LikeComicService
{
    protected $repository;

    public function __construct(LikeComicRepository $repository)
    {
        $this->repository = $repository;
    }

    public function like($user, $comic)
    {
        return $this->repository->like($user, $comic);
    }

    public function isLiked($user, $comic)
    {
        if ($user === null) {
            return false;
        }
        return $this->repository->isLiked($user, $comic);
    }

    public function unlike($user, $comic)
    {
        return $this->repository->unlike($user, $comic);
    }

    public function countLikes($comic)
    {
        return $this->repository->countLikes($comic);
    }

    public function toggleLike($user, $comic)
    {
        if ($this->isLiked($user, $comic)) {
            return $this->unlike($user, $comic);
        }

        return $this->like($user, $comic);
    }
}
