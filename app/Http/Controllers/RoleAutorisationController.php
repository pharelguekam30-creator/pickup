<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Autorisation;

class RoleAutorisationController extends Controller
{
    public function index()
    {
        $roles = Role::with('autorisations')->get();
        return view('role_autorisations.index', compact('roles'));
    }

    public function create()
    {
        $roles = Role::all();
        $autorisations = Autorisation::all();
        return view('role_autorisations.create', compact('roles', 'autorisations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'autorisation_id' => 'required|exists:autorisations,id',
        ]);

        $role = Role::find($request->role_id);
        $role->autorisations()->syncWithoutDetaching($request->autorisation_id);

        return redirect()->route('role_autorisations.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
