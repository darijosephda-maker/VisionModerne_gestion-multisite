<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockUnite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockUniteController extends Controller
{
    public function index()
    {
        $stocksUnites = StockUnite::orderBy('operateur')->get();

        return view('admin.stocks-unites.index', compact('stocksUnites'));
    }

    public function create()
    {
        return view('admin.stocks-unites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'operateur' => 'required|string|max:255|unique:stocks_unites,operateur',
            'capital_initial' => 'required|numeric|min:0',
            'seuil_alerte' => 'required|numeric|min:0',
        ]);

        StockUnite::create([
            'operateur' => $validated['operateur'],
            'capital_initial' => $validated['capital_initial'],
            'solde_actuel' => $validated['capital_initial'],
            'seuil_alerte' => $validated['seuil_alerte'],
            'alimente_par' => auth()->id(),
            'date_alimentation' => now(),
        ]);

        return redirect()->route('admin.stocks-unites.index')
            ->with('success', "Opérateur « {$validated['operateur']} » créé avec succès.");
    }

    public function recharger(Request $request, StockUnite $stockUnite)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($stockUnite, $validated) {
            $stockUnite->lockForUpdate();
            $stockUnite->increment('solde_actuel', $validated['montant']);
            $stockUnite->update([
                'alimente_par' => auth()->id(),
                'date_alimentation' => now(),
            ]);
        });

        return redirect()->route('admin.stocks-unites.index')
            ->with('success', "{$stockUnite->operateur} rechargé de " . number_format($validated['montant'], 0, ',', ' ') . " F.");
    }
}
