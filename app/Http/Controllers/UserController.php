<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Services\FollowerService;
use App\Utils\Settings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $service;
    protected $followerService;

    public function __construct(UserService $service, FollowerService $followerService)
    {
        $this->service = $service;
        $this->followerService = $followerService;
    }

    public function index($id)
    {
        $user = User::where('user_path', $id)->firstOrFail();
        $selfUser = auth()->user();
        $comics = $user->comics()->select('id', 'cover', 'title', 'slug')->get();

        $followersCount = $this->followerService->countFollowers($user);
        $followingCount = $this->followerService->countFollowing($user);
        $isSelfe = auth()->check() && $selfUser->id === $user->id;
        if ($isSelfe) {
            $giftCards = $this->service->getUserGiftCards($selfUser->user_path);
            $getOrders = $this->service->getOrders($selfUser->user_path);
        }
        $isFollowing = auth()->check()
            ? $this->followerService->isFollowing($selfUser, $user)
            : false;

        return Inertia::render('User/Show', [
            'settings' => Settings::get(),
            'user' => $user,
            'comic' => $comics,
            'stats' => [
                'followers' => $followersCount,
                'following' => $followingCount
            ],
            'isFollowing' => $isFollowing,
            'giftCards' => $isSelfe ? $giftCards : null,
            'orders' => $isSelfe ? $getOrders : null,
            'isSelf' => $isSelfe
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string|max:150',
        ]);

        $user = auth()->user();

        $banner = $request->hasFile('banner') ? $request->file('banner') : null;
        $cover  = $request->hasFile('cover')  ? $request->file('cover')  : null;
        // dd($request->banner);
        $this->service->updateProfile($user, $banner, $cover, $request->description);


    }

    // Outros métodos do controller
}
