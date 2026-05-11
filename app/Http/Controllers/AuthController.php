<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Formulaire d'inscription
    public function registerForm(Request $request)
    {
        $role = $request->query('role', old('role', null));
        return view('auth.register', compact('role'));
    }

    // Traiter l'inscription
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|string|confirmed|min:6',
            'role' => 'required|string|in:vidangeur,menagere',
        ]);

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'role'=> $request->role,
            'phone'=> $request->phone ?? null,
            'country'=> $request->country ?? null,
            'region'=> $request->region ?? null,
            'city'=> $request->city ?? null,
            'quarter'=> $request->quarter ?? null,
            'address'=> $request->address ?? null,
            'birthdate'=> $request->birthdate ?? null,
        ]);

        Auth::login($user);

        return $this->redirectByRole($user->role);
    }

    // Formulaire de connexion
    public function loginForm()
    {
        return view('auth.login');
    }

    // Traiter la connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=> 'required|email',
            'password'=> 'required|string',
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user()->role);
        }

        return back()->withErrors(['email' => 'Identifiants invalides']);
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // Redirection selon le rôle
    private function redirectByRole($role)
    {
        return match($role) {
            'menagere' => redirect()->route('menagere.dashboard'),
            'vidangeur' => redirect()->route('vidangeur.dashboard'),
            'admin' => redirect()->route('dashboard'),
            default => redirect()->route('home'),
        };
    }
}
