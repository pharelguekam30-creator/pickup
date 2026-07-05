<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Applique le middleware pour restreindre l’accès aux admins.
     */
    // protected by route middleware already

    /**
     * Affiche la liste des rôles.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Affiche le formulaire de création d’un rôle.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Enregistre un nouveau rôle.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create($request->only('name'));

        return redirect()->route('roles.index')
            ->with('success', 'Rôle créé avec succès !');
    }

    /**
     * Affiche les détails d’un rôle spécifique.
     */
    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    /**
     * Affiche le formulaire pour éditer un rôle.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Met à jour un rôle existant.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update($request->only('name'));

        return redirect()->route('roles.index')
            ->with('success', 'Rôle mis à jour avec succès !');
    }

    /**
     * Supprime un rôle.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rôle supprimé avec succès !');
    }
}
