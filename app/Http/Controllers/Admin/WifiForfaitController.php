<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WifiForfait;
use Illuminate\Http\Request;

class WifiForfaitController extends Controller
{
    public function index()
    {
        $wifiForfaits = WifiForfait::orderBy('nom_forfait')->get();

        return view('admin.wifi-forfaits.index', compact('wifiForfaits'));
    }

    public function create()
    {
        return view('admin.wifi-forfaits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_forfait' => 'required|string|max:255|unique:wifi_forfaits,nom_forfait',
            'prix_cout' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0|gte:prix_cout',
        ]);

        WifiForfait::create([
            'nom_forfait' => $validated['nom_forfait'],
            'prix_cout' => $validated['prix_cout'],
            'prix_vente' => $validated['prix_vente'],
            'actif' => true,
        ]);

        return redirect()->route('admin.wifi-forfaits.index')
            ->with('success', "Forfait « {$validated['nom_forfait']} » créé avec succès.");
    }

    public function edit(WifiForfait $wifiForfait)
    {
        return view('admin.wifi-forfaits.edit', compact('wifiForfait'));
    }

    public function update(Request $request, WifiForfait $wifiForfait)
    {
        $validated = $request->validate([
            'nom_forfait' => 'required|string|max:255|unique:wifi_forfaits,nom_forfait,' . $wifiForfait->id,
            'prix_cout' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0|gte:prix_cout',
        ]);

        $wifiForfait->update($validated);

        return redirect()->route('admin.wifi-forfaits.index')
            ->with('success', "Forfait « {$wifiForfait->nom_forfait} » mis à jour.");
    }

    public function toggleActif(WifiForfait $wifiForfait)
    {
        $wifiForfait->update(['actif' => ! $wifiForfait->actif]);

        $statut = $wifiForfait->actif ? 'activé' : 'désactivé';

        return redirect()->route('admin.wifi-forfaits.index')
            ->with('success', "Forfait « {$wifiForfait->nom_forfait} » {$statut}.");
    }

    public function destroy(WifiForfait $wifiForfait)
    {
        if ($wifiForfait->transactions()->exists()) {
            return redirect()->route('admin.wifi-forfaits.index')
                ->with('error', "Impossible de supprimer « {$wifiForfait->nom_forfait} » : des ventes existent déjà pour ce forfait. Désactivez-le plutôt.");
        }

        $wifiForfait->delete();

        return redirect()->route('admin.wifi-forfaits.index')
            ->with('success', "Forfait « {$wifiForfait->nom_forfait} » supprimé.");
    }
}
