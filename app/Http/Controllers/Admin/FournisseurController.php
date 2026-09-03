<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $module = $request->get('module');
        $statut = $request->get('statut');

        $fournisseurs = Fournisseur::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('telephone', 'like', "%{$search}%")
                      ->orWhere('localite', 'like', "%{$search}%");
                });
            })
            ->when($module, function ($query, $module) {
                $query->where('module', $module);
            })
            ->when($statut !== null && $statut !== '', function ($query) use ($statut) {
                $query->where('actif', $statut === 'actif');
            })
            ->orderBy('nom')
            ->paginate(15)
            ->withQueryString();

        return view('admin.fournisseurs.index', compact('fournisseurs', 'search', 'module', 'statut'));
    }

    public function create()
    {
        return view('admin.fournisseurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'localite' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'module' => 'required|in:secretariat,librairie,boissons,services,general',
            'produits_fournis' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Fournisseur::create($validated);

        return redirect()->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur créé avec succès.');
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('admin.fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'localite' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'module' => 'required|in:secretariat,librairie,boissons,services,general',
            'produits_fournis' => 'nullable|string',
            'notes' => 'nullable|string',
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->boolean('actif');

        $fournisseur->update($validated);

        return redirect()->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        if ($fournisseur->approvisionnements()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce fournisseur : il possède déjà des approvisionnements enregistrés.');
        }

        $fournisseur->delete();

        return redirect()->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }
}