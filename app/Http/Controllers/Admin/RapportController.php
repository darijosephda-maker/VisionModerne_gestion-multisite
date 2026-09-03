<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionUnite;
use App\Models\TransactionWifi;
use App\Models\User;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    public function factures(Request $request)
    {
        $validated = $request->validate([
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'recherche' => ['nullable', 'string', 'max:100'],
            'module' => ['nullable', 'in:secretariat,librairie,boissons,services,unites_wifi'],
            'caissiere_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $dateDebut = $validated['date_debut'] ?? now()->startOfMonth()->toDateString();
        $dateFin = $validated['date_fin'] ?? now()->toDateString();
        $recherche = trim($validated['recherche'] ?? '');
        $module = $validated['module'] ?? '';
        $caissiereId = $validated['caissiere_id'] ?? '';

        $facturesQuery = Vente::query()
            ->where('statut', 'validee')
            ->whereDate('date_vente', '>=', $dateDebut)
            ->whereDate('date_vente', '<=', $dateFin);

        if ($recherche !== '') {
            $facturesQuery->where(function ($query) use ($recherche) {
                $query->where('id', $recherche)
                    ->orWhere('client_nom', 'like', "%{$recherche}%")
                    ->orWhere('client_prenom', 'like', "%{$recherche}%")
                    ->orWhere('client_telephone', 'like', "%{$recherche}%")
                    ->orWhereHas('caissiere', fn ($caissiere) => $caissiere->where('name', 'like', "%{$recherche}%"));
            });
        }

        if ($module !== '') {
            $facturesQuery->where('module', $module);
        }

        if ($caissiereId !== '') {
            $facturesQuery->where('caissiere_id', $caissiereId);
        }

        $factures = (clone $facturesQuery)
            ->with(['caissiere', 'lignes.produit'])
            ->orderByDesc('date_vente')
            ->paginate(15)
            ->withQueryString();

        $totalFactures = (clone $facturesQuery)->count();
        $montantTotal = (clone $facturesQuery)->sum('montant_total');
        $caissieres = User::where('role', 'caissiere')->orderBy('name')->get();

        return view('admin.factures.index', compact(
            'factures',
            'totalFactures',
            'montantTotal',
            'caissieres',
            'dateDebut',
            'dateFin',
            'recherche',
            'module',
            'caissiereId'
        ));
    }

    public function index(Request $request)
    {
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->toDateString());
        $dateFin = $request->get('date_fin', now()->toDateString());
        $module = $request->get('module', '');
        $caissiereId = $request->get('caissiere_id', '');

        $ventesQuery = Vente::where('statut', 'validee')
            ->whereDate('date_vente', '>=', $dateDebut)
            ->whereDate('date_vente', '<=', $dateFin);

        if ($module) {
            $ventesQuery->where('module', $module);
        }
        if ($caissiereId) {
            $ventesQuery->where('caissiere_id', $caissiereId);
        }

        $ventes = (clone $ventesQuery)->with(['caissiere', 'lignes.produit'])
            ->orderByDesc('date_vente')
            ->paginate(20)
            ->withQueryString();

        $totalVentes = (clone $ventesQuery)->sum('montant_total');
        $nombreVentes = (clone $ventesQuery)->count();

        $repartitionParModule = (clone $ventesQuery)
            ->groupBy('module')
            ->select('module', DB::raw('SUM(montant_total) as total'), DB::raw('COUNT(*) as nb'))
            ->get();

        $beneficeUnites = TransactionUnite::whereDate('date_transaction', '>=', $dateDebut)
            ->whereDate('date_transaction', '<=', $dateFin)
            ->sum('benefice');

        $beneficeWifi = TransactionWifi::whereDate('date_transaction', '>=', $dateDebut)
            ->whereDate('date_transaction', '<=', $dateFin)
            ->sum('benefice');

        $caissieres = User::where('role', 'caissiere')->orderBy('name')->get();

        return view('admin.rapports.index', compact(
            'ventes',
            'totalVentes',
            'nombreVentes',
            'repartitionParModule',
            'beneficeUnites',
            'beneficeWifi',
            'caissieres',
            'dateDebut',
            'dateFin',
            'module',
            'caissiereId'
        ));
    }
}