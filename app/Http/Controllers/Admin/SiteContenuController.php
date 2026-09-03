<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContenu;
use Illuminate\Http\Request;

class SiteContenuController extends Controller
{
    public function index()
    {
        $contenus = SiteContenu::orderBy('ordre_affichage')
            ->orderBy('id')
            ->get();

        return view('admin.site-contenu.index', compact('contenus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cle' => ['required', 'string', 'max:255'],
            'titre' => ['nullable', 'string', 'max:255'],
            'contenu' => ['nullable', 'string'],
            'ordre_affichage' => ['nullable', 'integer', 'min:0'],
        ]);

        SiteContenu::create($validated);

        return redirect()->route('admin.site-contenu.index')
            ->with('success', 'Contenu ajouté avec succès.');
    }
}
