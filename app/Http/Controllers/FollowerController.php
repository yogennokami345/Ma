<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FollowerService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FollowerController extends Controller
{
    protected $followerService;

    public function __construct(FollowerService $followerService)
    {
        $this->followerService = $followerService;
    }

    /**
     * Alternar entre seguir e deixar de seguir um usuário
     * 
     * @param Request $request
     * @param string $userPath - Agora recebendo string em vez de model binding
     * @return mixed
     */
    public function toggle(Request $request, $userPath)
    {
        // Buscar o usuário pelo user_path
        $user = User::where('user_path', $userPath)->firstOrFail();

        $follower = auth()->user();

        // Verificar se não está tentando seguir a si mesmo
        if ($follower->id === $user->id) {
            throw ValidationException::withMessages([
                'error' => 'Você não pode seguir a si mesmo'
            ]);
        }

        $this->followerService->toggleFollow($follower, $user);

        if ($request->wantsJson()) {
            return response()->json([
                'following' => $this->followerService->isFollowing($follower, $user),
                'followers_count' => $this->followerService->countFollowers($user)
            ]);
        }

        return back()->with('success', 'Ação realizada com sucesso');
    }

    /**
     * Verificar se o usuário autenticado segue outro usuário
     * Útil para APIs e componentes Vue
     */
    public function checkFollowing($userPath)
    {
        $user = User::where('user_path', $userPath)->firstOrFail();

        if (!auth()->check()) {
            return response()->json(['following' => false]);
        }

        $isFollowing = $this->followerService->isFollowing(auth()->user(), $user);

        return response()->json(['following' => $isFollowing]);
    }

    /**
     * Obter estatísticas de seguidores
     */
    public function stats($userPath)
    {
        $user = User::where('user_path', $userPath)->firstOrFail();

        return response()->json([
            'followers' => $this->followerService->countFollowers($user),
            'following' => $this->followerService->countFollowing($user)
        ]);
    }
}
