<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('payments.index', compact('user', 'transactions'));
    }

    public function depositForm()
    {
        return view('payments.deposit');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:100',
            'methode' => 'required|string|in:om,momo,carte',
        ]);

        $montant = $request->montant;
        $user = auth()->user();

        DB::beginTransaction();
        try {
            $solde_avant = $user->solde;
            $user->solde += $montant;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'depot',
                'montant' => $montant,
                'solde_avant' => $solde_avant,
                'solde_apres' => $user->solde,
                'methode' => $request->methode,
                'description' => 'Dépôt de ' . number_format($montant, 0, ',', ' ') . ' FCFA',
                'statut' => 'completed',
            ]);

            DB::commit();
            return redirect()->route('payments.index')
                ->with('success', 'Dépôt de ' . number_format($montant, 0, ',', ' ') . ' FCFA effectué avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du dépôt : ' . $e->getMessage());
        }
    }

    public function withdrawForm()
    {
        return view('payments.withdraw');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:100',
        ]);

        $montant = $request->montant;
        $user = auth()->user();

        if (($user->solde ?? 0) < $montant) {
            return back()->with('error', 'Solde insuffisant.');
        }

        DB::beginTransaction();
        try {
            $solde_avant = $user->solde;
            $user->solde -= $montant;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'retrait',
                'montant' => -$montant,
                'solde_avant' => $solde_avant,
                'solde_apres' => $user->solde,
                'description' => 'Retrait de ' . number_format($montant, 0, ',', ' ') . ' FCFA',
                'statut' => 'completed',
            ]);

            DB::commit();
            return redirect()->route('payments.index')
                ->with('success', 'Retrait de ' . number_format($montant, 0, ',', ' ') . ' FCFA effectué.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du retrait : ' . $e->getMessage());
        }
    }
}
