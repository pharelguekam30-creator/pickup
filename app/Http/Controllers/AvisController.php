<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avis;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    // Middleware pour restreindre l'accès aux ménagères connectées
    public function __construct()
    {
       // $this->middleware(['auth', 'role:menagere']);
    }

    /**
     * Affiche le formulaire pour créer un nouvel avis
     */
    public function create($service_id)
    {
        $service = Service::findOrFail($service_id);
        return view('avis.create', compact('service'));
    }

    /**
     * Stocke un nouvel avis dans la base de données
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'required|string|max:1000',
        ]);

        Avis::create([
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'note' => $request->note,
            'contenu' => $request->commentaire,
        ]);

        return redirect()->route('services.index')->with('success', 'Votre avis a été envoyé !');
    }

    /**
     * Optionnel : liste des avis de l'utilisateur connecté
     */
    public function index()
    {
        $avis = Avis::where('user_id', Auth::id())->with('service')->get();
        return view('avis.index', compact('avis'));
    }
    
}
