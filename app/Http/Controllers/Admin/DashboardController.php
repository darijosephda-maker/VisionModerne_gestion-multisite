<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\StockUnite;
use App\Models\TransactionUnite;
use App\Models\TransactionWifi;
use App\Models\Vente;
use App\Models\VenteLigne;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $aujourdhui = now()->toDateString();

        // Chiffre d'affaires du jour par module
        $caParModule = Vente::where('statut', 'validee')
            ->whereDate('date_vente', $aujourdhui)
            ->groupBy('module')
            ->select('module', DB::raw('SUM(montant_total) as total'))
            ->pluck('total', 'module');

        $caSecretariat = $caParModule['secretariat'] ?? 0;
        $caLibrairie = $caParModule['librairie'] ?? 0;
        $caBoissons = $caParModule['boissons'] ?? 0;

        // Bénéfice du jour Unités + WiFi
        $beneficeUnitesJour = TransactionUnite::whereDate('date_transaction', $aujourdhui)->sum('benefice');
        $beneficeWifiJour = TransactionWifi::whereDate('date_transaction', $aujourdhui)->sum('benefice');
        $beneficeUnitesWifi = $beneficeUnitesJour + $beneficeWifiJour;

        // Chiffre d'affaires total du mois (tous modules confondus)
        $caMois = Vente::where('statut', 'validee')
            ->whereMonth('date_vente', now()->month)
            ->whereYear('date_vente', now()->year)
            ->sum('montant_total');

        // Produits les plus vendus (top 5, toutes périodes confondues)
        $produitsPlusVendus = VenteLigne::select('produit_id', DB::raw('SUM(quantite) as total_vendu'))
            ->with('produit:id,nom,module')
            ->groupBy('produit_id')
            ->orderByDesc('total_vendu')
            ->limit(5)
            ->get();

        // Produits en stock bas
        $produitsStockBas = Produit::whereColumn('quantite_stock', '<=', 'seuil_alerte_stock')
            ->where('actif', true)
            ->orderBy('quantite_stock')
            ->limit(5)
            ->get();

        // Stocks Unités télécom en alerte
        $stocksUnitesBas = StockUnite::whereColumn('solde_actuel', '<=', 'seuil_alerte')->get();

        // Évolution des ventes sur les 7 derniers jours (pour graphique)
        $ventes7jours = Vente::where('statut', 'validee')
            ->where('date_vente', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(date_vente)'))
            ->select(DB::raw('DATE(date_vente) as jour'), DB::raw('SUM(montant_total) as total'))
            ->orderBy('jour')
            ->get()
            ->keyBy('jour');

        $labelsJours = [];
        $dataVentes = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $cle = $date->toDateString();
            $labelsJours[] = $date->translatedFormat('D d/m');
            $dataVentes[] = (float) ($ventes7jours[$cle]->total ?? 0);
        }

        return view('admin.dashboard', compact(
            'caSecretariat',
            'caLibrairie',
            'caBoissons',
            'beneficeUnitesWifi',
            'caMois',
            'produitsPlusVendus',
            'produitsStockBas',
            'stocksUnitesBas',
            'labelsJours',
            'dataVentes'
        ));
    }
}