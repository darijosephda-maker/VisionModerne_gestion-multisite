<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeService;
use Illuminate\Http\Request;

class TypeServiceController extends Controller
{
    public function index()
    {
        $typeServices = TypeService::orderBy('nom')->paginate(15);

        return view('admin.type-services.index', compact('typeServices'));
    }

    public function create()
    {
        return view('admin.type-services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:type_services,nom',
        ]);

        TypeService::create($validated);

        return redirect()->route('admin.type-services.index')
            ->with('success', 'Type de service créé avec succès.');
    }

    public function edit(TypeService $typeService)
    {
        return view('admin.type-services.edit', compact('typeService'));
    }

    public function update(Request $request, TypeService $typeService)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:type_services,nom,' . $typeService->id,
            'actif' => 'boolean',
        ]);

        $validated['actif'] = $request->boolean('actif');

        $typeService->update($validated);

        return redirect()->route('admin.type-services.index')
            ->with('success', 'Type de service mis à jour avec succès.');
    }

    public function destroy(TypeService $typeService)
    {
        if ($typeService->venteLignes()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce type de service : il a déjà été utilisé dans des ventes.');
        }

        $typeService->delete();

        return redirect()->route('admin.type-services.index')
            ->with('success', 'Type de service supprimé avec succès.');
    }
}