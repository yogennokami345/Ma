<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\LoginRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginService
{
    protected $repository;

    public function __construct(LoginRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getUserLogin($userLogin)
    {

        $user = User::where('email', $userLogin['email'])->first();
        if(!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email não encontrado'
            ]);
        }

        if (!Hash::check($userLogin['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas'
            ]);
        }

        return Auth::login($user);

    }

    public function createUser($userData)
    {
        $user = $this->repository->create([
            'name'     => $userData['name'],
            'email'    => $userData['email'],
            'password' => $userData['password'],
        ]);

        $user->assignRole('user');

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Erro ao criar usuário'
            ]);
        }

        Auth::login($user);

        return $user;
    }
}
