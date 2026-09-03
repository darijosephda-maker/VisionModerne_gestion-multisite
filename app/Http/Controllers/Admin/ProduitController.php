<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\ProduitUnite;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $module = $request->get('module', 'librairie');
        $search = $request->get('search');

        $produits = Produit::where('module', $module)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->with('unites')
            ->orderBy('nom')
            ->paginate(15)
            ->withQueryString();

        return view('admin.produits.index', compact('produits', 'module', 'search'));
    }

    public function create(Request $request)
    {
        $module = $request->get('module', 'librairie');
        return view('admin.produits.create', compact('module'));
    }

    public function store(Request $request)
    {
        $isService = $request->module === 'services';

        $validated = $request->validate([
            'module' => 'required|in:secretariat,librairie,boissons,services',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_achat' => $isService ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => $isService ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'seuil_alerte_stock' => $isService ? 'nullable|integer|min:0' : 'required|integer|min:0',
        ]);

        $validated['type'] = $isService ? 'service' : 'stock';
        $validated['prix_achat'] = $validated['prix_achat'] ?? 0;
        $validated['quantite_stock'] = $validated['quantite_stock'] ?? 0;
        $validated['seuil_alerte_stock'] = $validated['seuil_alerte_stock'] ?? 0;

        $produit = Produit::create($validated);

        // Toujours créer l'unité "détail" de base
        ProduitUnite::create([
            'produit_id' => $produit->id,
            'type_unite' => 'detail',
            'quantite_equivalente_detail' => 1,
            'prix_vente_unite' => $produit->prix_vente,
        ]);

        if ($request->filled('paquet_quantite')) {
            ProduitUnite::create([
                'produit_id' => $produit->id,
                'type_unite' => 'paquet',
                'quantite_equivalente_detail' => $request->paquet_quantite,
                'prix_vente_unite' => $request->paquet_prix,
            ]);
        }

        if ($request->filled('carton_quantite')) {
            ProduitUnite::create([
                'produit_id' => $produit->id,
                'type_unite' => 'carton',
                'quantite_equivalente_detail' => $request->carton_quantite,
                'prix_vente_unite' => $request->carton_prix,
            ]);
        }

        return redirect()->route('admin.produits.index', ['module' => $produit->module])
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Produit $produit)
    {
        $produit->load('unites');
        return view('admin.produits.edit', compact('produit'));
    }

    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'seuil_alerte_stock' => 'required|integer|min:0',
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->boolean('actif');

        $produit->update($validated);

        return redirect()->route('admin.produits.index', ['module' => $produit->module])
            ->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Produit $produit)
    {
        if ($produit->venteLignes()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce produit : il possède déjà des ventes enregistrées.');
        }

        $module = $produit->module;
        $produit->delete();

        return redirect()->route('admin.produits.index', ['module' => $module])
            ->with('success', 'Produit supprimé avec succès.');
    }
}