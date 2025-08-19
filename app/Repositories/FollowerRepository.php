<?php

namespace App\Repositories;

use App\Models\User;

class FollowerRepository
{
    /**
     * Verifica se um usuário está seguindo outro
     */
    public function isFollowing(User $follower, User $following)
    {
        return $follower->following()->where('following_id', $following->id)->exists();
    }
    
    /**
     * Seguir um usuário
     */
    public function follow(User $follower, User $following)
    {
        return $follower->following()->attach($following->id);
    }
    
    /**
     * Deixar de seguir um usuário
     */
    public function unfollow(User $follower, User $following)
    {
        return $follower->following()->detach($following->id);
    }
    
    /**
     * Obter seguidores de um usuário
     */
    public function getFollowers(User $user, int $perPage = 15)
    {
        return $user->followers()->paginate($perPage);
    }
    
    /**
     * Obter quem o usuário está seguindo
     */
    public function getFollowing(User $user, int $perPage = 15)
    {
        return $user->following()->paginate($perPage);
    }
    
    /**
     * Contar seguidores
     */
    public function countFollowers(User $user)
    {
        return $user->followers()->count();
    }
    
    /**
     * Contar seguindo
     */
    public function countFollowing(User $user)
    {
        return $user->following()->count();
    }
}