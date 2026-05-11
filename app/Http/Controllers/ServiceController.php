<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Affiche tous les services (liste publique ou interface admin)
    public function index()
    {
        $services = Service::with(['avis.user'])->get(); // Récupère tous les services avec leurs avis
        return view('services.index', compact('services'));
    }

    // Affiche le formulaire pour créer un nouveau service
    public function create()
    {
        return view('services.create');
    }

    // Enregistre un nouveau service
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required', 
            'description' => 'required',
            'price' => 'required|numeric'
        ]);

        Service::create($request->all());

        return redirect()->route('services.index')->with('success', 'Service ajouté');
    }

    // Affiche le formulaire pour éditer un service existant
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('services.edit', compact('service'));
    }

    // Met à jour un service
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required', 
            'description' => 'required',
            'price' => 'required|numeric'
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->all());

        return redirect()->route('services.index')->with('success', 'Service modifié');
    }

    // Supprime un service
    public function destroy($id)
    {
        Service::destroy($id);
        return back()->with('success', 'Service supprimé');
    }

    // Affiche un service en détail (optionnel)
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('services.show', compact('service'));
    }
}
