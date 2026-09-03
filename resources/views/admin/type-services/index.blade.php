<x-admin-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl bg-gradient-to-r from-rose-700 via-rose-600 to-pink-600 p-5 sm:p-6 shadow-[0_20px_45px_rgba(190,24,93,0.22)] ring-1 ring-white/10">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-rose-100/90">Catalogue</p>
                        <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-white">🏷️ Types de services</h2>
                    </div>
                    <a href="{{ route('admin.type-services.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-xl ring-1 ring-white/30 transition">
                        <x-icons.plus />
                        Ajouter
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm rounded-2xl p-4 ring-1 ring-emerald-200 dark:ring-emerald-800/50">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm rounded-2xl p-4 ring-1 ring-red-200 dark:ring-red-800/50">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 shadow-[0_18px_40px_rgba(15,23,42,0.06)] rounded-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[400px] text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-700 text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-300">
                            <tr>
                                <th class="px-6 py-3">Nom</th>
                                <th class="px-6 py-3">Statut</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($typeServices as $typeService)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/70 transition">
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $typeService->nom }}</td>
                                    <td class="px-6 py-4">
                                        @if ($typeService->actif)
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Actif</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <a href="{{ route('admin.type-services.edit', $typeService) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition text-sm font-medium">
                                                <x-icons.edit />
                                                Modifier
                                            </a>
                                            <form action="{{ route('admin.type-services.destroy', $typeService) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('⚠️ Supprimer définitivement « {{ $typeService->nom }} » ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition text-sm font-medium">
                                                    <x-icons.trash />
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                        Aucun type de service enregistré.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>