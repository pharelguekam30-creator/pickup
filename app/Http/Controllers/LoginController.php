<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Affiche la page de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Assure-toi que tu as une vue resources/views/auth/login.blade.php
    }

    /**
     * Gère la tentative de connexion
     */
    public function login(Request $request)
    {
        // Validation des champs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Tentative de connexion
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Vérifie le rôle et redirige
            $user = Auth::user();

            if ($user->role === 'menagere') {
                return redirect()->route('menagere.dashboard');
            } elseif ($user->role === 'vidangeur') {
                return redirect()->route('vidangeur.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('home');
            }
        }

        // Si échec
        return back()->withErrors([
            'email' => 'Les informations fournies sont incorrectes.',
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
