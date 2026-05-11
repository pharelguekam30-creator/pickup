<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autorisation;

class AutorisationController extends Controller
{
    public function index()
    {
        $autorisations = Autorisation::all();
        return view('autorisations.index', compact('autorisations'));
    }

    public function create()
    {
        return view('autorisations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Autorisation::create($request->all());
        return redirect()->route('autorisations.index');
    }

    public function show(Autorisation $autorisation)
    {
        return view('autorisations.show', compact('autorisation'));
    }

    public function edit(Autorisation $autorisation)
    {
        return view('autorisations.edit', compact('autorisation'));
    }

    public function update(Request $request, Autorisation $autorisation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $autorisation->update($request->all());
        return redirect()->route('autorisations.index');
    }

    public function destroy(Autorisation $autorisation)
    {
        $autorisation->delete();
        return redirect()->route('autorisations.index');
    }
}
