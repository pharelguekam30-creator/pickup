<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;
use App\Models\Message;

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

    public function stats()
    {
        $totalReservations = Reservation::count();
        $totalCompleted = Reservation::where('status', 'completed')->count();
        $totalRevenue = Transaction::where('type', 'commission')->where('montant', '>', 0)->sum('montant');
        $totalVidangeurs = User::where('role', 'vidangeur')->count();
        $totalMenageres = User::where('role', 'menagere')->count();
        $totalServices = Service::count();

        $monthlyReservations = Reservation::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();

        $monthlyRevenue = Transaction::where('type', 'commission')->where('montant', '>', 0)
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(montant) as total')
            ->groupBy('year', 'month')->orderBy('year')->orderBy('month')->get();

        $topVidangeurs = User::where('role', 'vidangeur')
            ->withCount(['reservations as completed_count' => function ($q) { $q->where('status', 'completed'); }])
            ->orderBy('completed_count', 'desc')->take(5)->get(['id', 'name']);

        $statusCounts = [
            'pending' => Reservation::where('status', 'pending')->count(),
            'accepted' => Reservation::where('status', 'accepted')->count(),
            'completed' => $totalCompleted,
            'canceled' => Reservation::where('status', 'canceled')->count(),
            'awaiting' => Reservation::where('status', 'completed_vidangeur')->count(),
        ];

        return view('admin.stats', compact(
            'totalReservations', 'totalCompleted', 'totalRevenue',
            'totalVidangeurs', 'totalMenageres', 'totalServices',
            'monthlyReservations', 'monthlyRevenue', 'topVidangeurs', 'statusCounts'
        ));
    }

    // Dashboard Ménagère
    public function menagere()
    {
        $reservations = Reservation::where('client_id', auth()->id())
            ->orderBy('reservation_date', 'desc')
            ->get();

        $unreadCounts = Message::whereIn('reservation_id', $reservations->pluck('id'))
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', 0)
            ->groupBy('reservation_id')
            ->selectRaw('reservation_id, COUNT(*) as count')
            ->pluck('count', 'reservation_id');

        $stats = [
            'total' => $reservations->count(),
            'pending' => $reservations->where('status', 'pending')->count(),
            'accepted' => $reservations->where('status', 'accepted')->count(),
            'canceled' => $reservations->where('status', 'canceled')->count(),
            'completed' => $reservations->where('status', 'completed')->count(),
            'awaiting_confirmation' => $reservations->where('status', 'completed_vidangeur')->count(),
        ];

        return view('menagere.dashboard', compact('reservations', 'stats', 'unreadCounts'));
    }

    // Dashboard Vidangeur
    public function vidangeur()
    {
        $interventions = Reservation::where('user_id', auth()->id())
            ->orderBy('reservation_date', 'desc')
            ->get();

        $unreadCounts = Message::whereIn('reservation_id', $interventions->pluck('id'))
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', 0)
            ->groupBy('reservation_id')
            ->selectRaw('reservation_id, COUNT(*) as count')
            ->pluck('count', 'reservation_id');

        $stats = [
            'total' => $interventions->count(),
            'pending' => $interventions->where('status', 'pending')->count(),
            'accepted' => $interventions->where('status', 'accepted')->count(),
            'canceled' => $interventions->where('status', 'canceled')->count(),
            'completed' => $interventions->where('status', 'completed')->count(),
            'awaiting_confirmation' => $interventions->where('status', 'completed_vidangeur')->count(),
        ];

        return view('vidangeur.dashboard', compact('interventions', 'stats', 'unreadCounts'));
    }
}
