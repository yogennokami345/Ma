<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\FollowerRepository;

class FollowerService
{
    protected $repository;

    public function __construct(FollowerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Verifica se um usuário está seguindo outro
     */
    public function isFollowing(User $follower, User $user)
    {
        return $this->repository->isFollowing($follower, $user);
    }
    
    /**
     * Seguir um usuário
     */
    public function follow(User $follower, User $userToFollow)
    {
        if ($follower->id !== $userToFollow->id) {
            return $this->repository->follow($follower, $userToFollow);
        }
        
        return false;
    }
    
    /**
     * Deixar de seguir um usuário
     */
    public function unfollow(User $follower, User $userToUnfollow)
    {
        return $this->repository->unfollow($follower, $userToUnfollow);
    }
    
    /**
     * Alternar entre seguir e deixar de seguir
     */
    public function toggleFollow(User $follower, User $user)
    {
        if ($this->isFollowing($follower, $user)) {
            return $this->unfollow($follower, $user);
        }
        
        return $this->follow($follower, $user);
    }
    
    /**
     * Obter todos os seguidores de um usuário
     */
    public function getFollowers(User $user, int $perPage = 15)
    {
        return $this->repository->getFollowers($user, $perPage);
    }
    
    /**
     * Obter todos que um usuário está seguindo
     */
    public function getFollowing(User $user, int $perPage = 15)
    {
        return $this->repository->getFollowing($user, $perPage);
    }
    
    /**
     * Contar seguidores de um usuário
     */
    public function countFollowers(User $user)
    {
        return $this->repository->countFollowers($user);
    }
    
    /**
     * Contar quantas pessoas um usuário está seguindo
     */
    public function countFollowing(User $user)
    {
        return $this->repository->countFollowing($user);
    }
}