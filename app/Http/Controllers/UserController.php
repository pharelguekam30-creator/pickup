<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Appliquer le middleware auth et isAdmin


    // Afficher la liste des utilisateurs
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Afficher le formulaire de création d'un utilisateur
    public function create()
    {
        $roles = ['admin' => 'admin', 'menagere' => 'menagere', 'vidangeur' => 'vidangeur'];
        return view('users.create', compact('roles'));
    }

    // Enregistrer un nouvel utilisateur
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,menagere,vidangeur',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    // Afficher un utilisateur
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Afficher le formulaire de modification
    public function edit(User $user)
    {
        $roles = ['admin' => 'admin', 'menagere' => 'menagere', 'vidangeur' => 'vidangeur'];
        return view('users.edit', compact('user', 'roles'));
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,menagere,vidangeur',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    // Afficher le profil de l'utilisateur connecté
    public function profile()
    {
        $user = auth()->user()->load('reservations.service');
        return view('users.profile', compact('user'));
    }

    // Afficher le formulaire d'edition du profil
    public function profileEdit()
    {
        $user = auth()->user();
        return view('users.profile-edit', compact('user'));
    }

    // Mettre a jour le profil
    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'country' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'quarter' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        $data = $request->only('name', 'email', 'phone', 'country', 'region', 'city', 'quarter', 'address');

        if ($request->hasFile('photo')) {
            $photoPath = 'uploads/profiles/' . time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('uploads/profiles'), basename($photoPath));
            $data['photo'] = $photoPath;
        }

        $user->update($data);

        if ($user->role === 'vidangeur') {
            $request->validate(['tarif' => 'nullable|numeric']);
            $user->update($request->only('tarif', 'latitude', 'longitude'));
        }

        return redirect()->route('profile')->with('success', 'Profil mis à jour avec succès.');
    }

    // Supprimer un utilisateur
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
