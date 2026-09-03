<?php

namespace App\Http\Controllers;

use App\Models\StockUnite;
use App\Models\TransactionUnite;
use App\Models\WifiForfait;
use App\Models\TransactionWifi;

use App\Models\Produit;
use App\Models\ProduitUnite;
use App\Models\Vente;
use App\Models\VenteLigne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaisseController extends Controller
{
    public function index(Request $request)
{
    $module = $request->get('module', 'librairie');

    if ($module === 'unites_wifi') {
        $stocksUnites = StockUnite::orderBy('operateur')->get();
        $wifiForfaits = WifiForfait::where('actif', true)->orderBy('nom_forfait')->get();

        return view('caisse.index', [
            'module' => $module,
            'produits' => collect(),
            'stocksUnites' => $stocksUnites,
            'wifiForfaits' => $wifiForfaits,
            'typeServices' => collect(),
        ]);
    }

    if ($module === 'services') {
        $typeServices = \App\Models\TypeService::where('actif', true)->orderBy('nom')->get();

        return view('caisse.index', [
            'module' => $module,
            'produits' => collect(),
            'stocksUnites' => collect(),
            'wifiForfaits' => collect(),
            'typeServices' => $typeServices,
        ]);
    }

    $produits = Produit::where('module', $module)
        ->where('actif', true)
        ->with('unites')
        ->orderBy('nom')
        ->get();

    return view('caisse.index', [
        'module' => $module,
        'produits' => $produits,
        'stocksUnites' => collect(),
        'wifiForfaits' => collect(),
        'typeServices' => collect(),
    ]);
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'module' => 'required|in:secretariat,librairie,boissons,services',
        'lignes' => 'required|array|min:1',
        'client_nom' => 'nullable|string|max:100',
        'client_prenom' => 'nullable|string|max:100',
        'client_telephone' => 'nullable|string|max:20',
    ]);

    $isServiceModule = $validated['module'] === 'services';

    if ($isServiceModule) {
        $validated = $request->validate([
            'module' => 'required|in:services',
            'lignes' => 'required|array|min:1',
            'lignes.*.type_service_id' => 'nullable|exists:type_services,id',
            'lignes.*.description_libre' => 'nullable|string|max:255',
            'lignes.*.prix' => 'required|numeric|min:1|max:1000000',
            'lignes.*.quantite' => 'required|integer|min:1|max:1000',
            'client_nom' => 'nullable|string|max:100',
            'client_prenom' => 'nullable|string|max:100',
            'client_telephone' => 'nullable|string|max:20',
        ]);
    } else {
        $validated = $request->validate([
            'module' => 'required|in:secretariat,librairie,boissons',
            'lignes' => 'required|array|min:1',
            'lignes.*.produit_id' => 'required|exists:produits,id',
            'lignes.*.produit_unite_id' => 'required|exists:produit_unites,id',
            'lignes.*.quantite' => 'required|integer|min:1',
            'client_nom' => 'nullable|string|max:100',
            'client_prenom' => 'nullable|string|max:100',
            'client_telephone' => 'nullable|string|max:20',
        ]);
    }

    DB::beginTransaction();

    try {
        $montantTotal = 0;
        $lignesAEnregistrer = [];

        foreach ($validated['lignes'] as $ligne) {

            if ($isServiceModule) {
                if (empty($ligne['type_service_id']) && empty($ligne['description_libre'])) {
                    DB::rollBack();
                    return back()->with('error', "Chaque ligne de service doit avoir un type ou une description.")->withInput();
                }

                $sousTotal = $ligne['prix'] * $ligne['quantite'];
                $montantTotal += $sousTotal;

                $lignesAEnregistrer[] = [
                    'produit_id' => null,
                    'produit_unite_id' => null,
                    'type_service_id' => $ligne['type_service_id'] ?? null,
                    'description_libre' => $ligne['description_libre'] ?? null,
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix'],
                    'sous_total' => $sousTotal,
                ];

                continue;
            }

            $produit = Produit::lockForUpdate()->findOrFail($ligne['produit_id']);
            $unite = ProduitUnite::findOrFail($ligne['produit_unite_id']);

            $quantiteDetailNecessaire = $ligne['quantite'] * $unite->quantite_equivalente_detail;

            if ($produit->type !== 'service' && $produit->quantite_stock < $quantiteDetailNecessaire) {
                DB::rollBack();
                return back()->with('error', "Stock insuffisant pour « {$produit->nom} ». Disponible : {$produit->quantite_stock}, demandé : {$quantiteDetailNecessaire}.")->withInput();
            }

            $sousTotal = $unite->prix_vente_unite * $ligne['quantite'];
            $montantTotal += $sousTotal;

            if ($produit->type !== 'service') {
                $produit->decrement('quantite_stock', $quantiteDetailNecessaire);
            }

            $lignesAEnregistrer[] = [
                'produit_id' => $produit->id,
                'produit_unite_id' => $unite->id,
                'type_service_id' => null,
                'description_libre' => null,
                'quantite' => $ligne['quantite'],
                'prix_unitaire' => $unite->prix_vente_unite,
                'sous_total' => $sousTotal,
            ];
        }

        $vente = Vente::create([
            'caissiere_id' => auth()->id(),
            'module' => $validated['module'],
            'montant_total' => $montantTotal,
            'client_nom' => $validated['client_nom'] ?? null,
            'client_prenom' => $validated['client_prenom'] ?? null,
            'client_telephone' => $validated['client_telephone'] ?? null,
            'statut' => 'validee',
            'date_vente' => now(),
        ]);

        foreach ($lignesAEnregistrer as $ligne) {
            $vente->lignes()->create($ligne);
        }

        DB::commit();

        // Nettoyer le panier du localStorage côté client
        return redirect()->route('caisse.facture', ['vente' => $vente->id])
            ->with('success', "Vente enregistrée avec succès. Total : " . number_format($montantTotal, 0, ',', ' ') . " F");

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', "Une erreur est survenue lors de l'enregistrement de la vente.")->withInput();
    }
}

    public function venteUnite(Request $request)
    {
        $validated = $request->validate([
            'stock_unite_id' => 'required|exists:stocks_unites,id',
            'montant_transige' => 'required|numeric|min:1',
            'benefice' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $stock = StockUnite::lockForUpdate()->findOrFail($validated['stock_unite_id']);

            if ($validated['montant_transige'] > $stock->solde_actuel) {
                DB::rollBack();
                return back()->with('error', "Solde insuffisant pour {$stock->operateur}. Solde actuel : " . number_format($stock->solde_actuel, 0, ',', ' ') . " F.")->withInput();
            }

            $stock->decrement('solde_actuel', $validated['montant_transige']);

            TransactionUnite::create([
                'stock_unite_id' => $stock->id,
                'caissiere_id' => auth()->id(),
                'montant_transige' => $validated['montant_transige'],
                'benefice' => $validated['benefice'],
                'note' => $validated['note'] ?? null,
                'date_transaction' => now(),
            ]);

            DB::commit();

            return redirect()->route('caisse.index', ['module' => 'unites_wifi'])
                ->with('success', "Vente d'unités enregistrée avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Une erreur est survenue lors de l'enregistrement de la vente d'unités.")->withInput();
        }
    }

    public function venteWifi(Request $request)
    {
        $validated = $request->validate([
            'forfait_id' => 'required|exists:wifi_forfaits,id',
        ]);

        DB::beginTransaction();

        try {
            $forfait = WifiForfait::lockForUpdate()->findOrFail($validated['forfait_id']);

            TransactionWifi::create([
                'forfait_id' => $forfait->id,
                'caissiere_id' => auth()->id(),
                'montant_vente' => $forfait->prix_vente,
                'benefice' => $forfait->prix_vente - $forfait->prix_cout,
                'date_transaction' => now(),
            ]);

            DB::commit();

            return redirect()->route('caisse.index', ['module' => 'unites_wifi'])
                ->with('success', 'Vente WiFi enregistrée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la vente WiFi.')->withInput();
        }
    }

    public function mesVentes(Request $request)
{
    $periode = $request->get('periode', 'jour');
    $caissiereId = auth()->id();

    // Déterminer les dates selon la période
    match ($periode) {
        'semaine' => [
            'debut' => now()->startOfWeek(),
            'fin' => now()->endOfWeek(),
            'label' => 'cette semaine'
        ],
        'mois' => [
            'debut' => now()->startOfMonth(),
            'fin' => now()->endOfMonth(),
            'label' => 'ce mois'
        ],
        default => [
            'debut' => now()->startOfDay(),
            'fin' => now()->endOfDay(),
            'label' => "aujourd'hui"
        ]
    };

    $debut = match ($periode) {
        'semaine' => now()->startOfWeek(),
        'mois' => now()->startOfMonth(),
        default => now()->startOfDay(),
    };

    $fin = match ($periode) {
        'semaine' => now()->endOfWeek(),
        'mois' => now()->endOfMonth(),
        default => now()->endOfDay(),
    };

    $ventesProduits = Vente::where('caissiere_id', $caissiereId)
        ->whereBetween('date_vente', [$debut, $fin])
        ->get()
        ->map(fn ($v) => [
            'type' => 'produit',
            'label' => 'Vente ' . ucfirst($v->module),
            'montant' => $v->montant_total,
            'date' => $v->date_vente,
        ]);

    $ventesUnites = TransactionUnite::with('stockUnite')
        ->where('caissiere_id', $caissiereId)
        ->whereBetween('date_transaction', [$debut, $fin])
        ->get()
        ->map(fn ($t) => [
            'type' => 'unite',
            'label' => 'Unités ' . ($t->stockUnite->operateur ?? '—'),
            'montant' => $t->montant_transige,
            'date' => $t->date_transaction,
        ]);

    $ventesWifi = TransactionWifi::with('forfait')
        ->where('caissiere_id', $caissiereId)
        ->whereBetween('date_transaction', [$debut, $fin])
        ->get()
        ->map(fn ($t) => [
            'type' => 'wifi',
            'label' => 'WiFi ' . ($t->forfait->nom_forfait ?? '—'),
            'montant' => $t->montant_vente,
            'date' => $t->date_transaction,
        ]);

    $toutesLesVentes = $ventesProduits
        ->concat($ventesUnites)
        ->concat($ventesWifi)
        ->sortByDesc('date')
        ->values();

    $total = $toutesLesVentes->sum('montant');

    $periodLabel = match ($periode) {
        'semaine' => 'Mes ventes de cette semaine',
        'mois' => 'Mes ventes de ce mois',
        default => 'Mes ventes du jour',
    };

    return view('caisse.mes-ventes', [
        'ventes' => $toutesLesVentes,
        'total' => $total,
        'periode' => $periode,
        'periodLabel' => $periodLabel,
    ]);
}

    public function facture(Vente $vente)
    {
        // Vérifier que la vente appartient à l'utilisateur connecté ou qu'il est admin
        if ($vente->caissiere_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $vente->load('lignes', 'caissiere');

        return view('caisse.facture', ['vente' => $vente]);
    }
}