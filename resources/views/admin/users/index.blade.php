<x-admin-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl bg-gradient-to-r from-purple-700 via-purple-600 to-pink-600 p-5 sm:p-6 shadow-[0_20px_45px_rgba(126,34,206,0.22)] ring-1 ring-white/10">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-purple-100/90">Équipe</p>
                        <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-white">👥 Utilisateurs & Rôles</h2>
                    </div>
                    <a href="{{ route('admin.users.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-xl ring-1 ring-white/30 transition">
                        <x-icons.plus />
                        Ajouter utilisateur
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl p-4 ring-1 ring-slate-200 dark:ring-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3">
                    <div class="flex items-center gap-2">
                        <x-icons.search class="text-slate-400 shrink-0" />
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nom ou email..."
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-900 outline-none">
                    </div>
                    <select name="role" onchange="this.form.submit()"
                            class="px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-900 outline-none">
                        <option value="">Tous les rôles</option>
                        <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="caissiere" {{ $role === 'caissiere' ? 'selected' : '' }}>Caissière</option>
                    </select>
                    <select name="statut" onchange="this.form.submit()"
                            class="px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-900 outline-none">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ $statut === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ $statut === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-purple-500/20 transition">
                            Filtrer
                        </button>
                        @if ($search || $role || $statut)
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition hover:bg-slate-200 dark:hover:bg-slate-600">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

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
                    <table class="w-full min-w-[640px] text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-700 text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-300">
                            <tr>
                                <th class="px-6 py-3">Nom</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Rôle</th>
                                <th class="px-6 py-3">Statut</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($users as $user)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/70 transition">
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">
                                        {{ $user->name }}
                                        @if ($user->id === auth()->id())
                                            <span class="ml-2 text-xs text-slate-400 dark:text-slate-500">(vous)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300 text-xs">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        @if ($user->role === 'admin')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">Admin</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Caissière</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->actif)
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Actif</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1.5">
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition text-sm font-medium">
                                                <x-icons.edit />
                                                Modifier
                                            </a>
                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('⚠️ Supprimer définitivement « {{ $user->name }} » ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition text-sm font-medium">
                                                        <x-icons.trash />
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                        @if ($search || $role || $statut)
                                            Aucun utilisateur ne correspond à ces critères.
                                        @else
                                            Aucun utilisateur enregistré.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>

        </div>
    </div>
</x-admin-layout>