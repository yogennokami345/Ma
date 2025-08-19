<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoginService;
use App\Utils\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class LoginController extends Controller
{
    protected $service;

    public function __construct(LoginService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Auth/Login', [
            'settings' => Settings::get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);
        // dd($user);
        $this->service->getUserLogin($user);

        return redirect()->route('home');
    }

    public function register()
    {
        return Inertia::render('Auth/Register', [
            'settings' => Settings::get(),
        ]);
    }

    public function storeRegister(Request $request)
    {
        $user = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $this->service->createUser($user);

        return redirect()->route('home');
    }

    public function loginApi(Request $request)
    {
        // dd($request);
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuário não encontrado com este email.',
            ], 404);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'As credenciais fornecidas estão incorretas.',
            ], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'user'    => $user,
            'token'   => $user->createToken('MyTokenAccess')->plainTextToken,
        ], 200);
        // Auth::login($user);
    }

    // Outros métodos do controller
}
