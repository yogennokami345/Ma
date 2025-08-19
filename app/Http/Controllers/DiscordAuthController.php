<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DiscordAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DiscordAuthController extends Controller
{
    protected $service;

    public function __construct(DiscordAuthService $service)
    {
        $this->service = $service;
    }

    public function redirect()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function index()
    {
        $discordUser = Socialite::driver('discord')->user();
        $existingUserWithSameName = User::where('name', $discordUser->name)->first();
        $userName = $discordUser->name;

        if($existingUserWithSameName){
            $userName = $discordUser->name . Str::random(4);
        }

        $password = Str::random();

        $user = User::updateOrCreate(
            [
                'email' => $discordUser->email,
            ],
            [
                'name' => $userName,
                'password' => $password
            ]
        );

        $user->assignRole('user');

        Auth::login($user);

        return redirect('/');
    }

    // Outros métodos do controller
}