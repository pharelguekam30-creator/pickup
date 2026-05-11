<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class DashboardController extends Controller
{
    /**
     * Middleware admin uniquement pour dashboard admin
     */
    public function __construct()
    {
        //$this->middleware(['auth', 'isAdmin'])->only('index');
    }

    // Dashboard Admin
    public function index()
    {
        return view('admin.dashboard');
    }

    // Dashboard Ménagère
    public function menagere()
    {
        $reservations = Reservation::where('client_id', auth()->id())
            ->orderBy('reservation_date', 'desc')
            ->get();

        $stats = [
            'total' => $reservations->count(),
            'pending' => $reservations->where('status', 'pending')->count(),
            'accepted' => $reservations->where('status', 'accepted')->count(),
            'canceled' => $reservations->where('status', 'canceled')->count(),
            'completed' => $reservations->where('status', 'completed')->count(),
        ];

        return view('menagere.dashboard', compact('reservations', 'stats'));
    }

    // Dashboard Vidangeur
    public function vidangeur()
    {
        $interventions = Reservation::where('user_id', auth()->id())
            ->orderBy('reservation_date', 'desc')
            ->get();

        $stats = [
            'total' => $interventions->count(),
            'pending' => $interventions->where('status', 'pending')->count(),
            'accepted' => $interventions->where('status', 'accepted')->count(),
            'canceled' => $interventions->where('status', 'canceled')->count(),
            'completed' => $interventions->where('status', 'completed')->count(),
        ];

        return view('vidangeur.dashboard', compact('interventions', 'stats'));
    }
}
