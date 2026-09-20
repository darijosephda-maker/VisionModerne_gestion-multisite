<x-caisse-layout>
        <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Point de vente - Caisse') }} — {{ ucfirst(str_replace('_', ' ', $module)) }}
        </h2>
    </x-slot>

    <div class="py-8"
        x-data="caisse({{ $produits->map(function ($p) {
            return [
                'id' => $p->id,
                'nom' => $p->nom,
                'stock' => $p->quantite_stock,
                'unites' => $p->unites->map(fn($u) => [
                    'id' => $u->id,
                    'type' => $u->type_unite,
                    'prix' => (float) $u->prix_vente_unite,
                    'equivalent' => $u->quantite_equivalente_detail,
                ]),
            ];
        })->toJson() }})"
        @load.window="chargerPanierDuStorage()">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

                    @php
                $heureCaisse = now()->hour;
                if ($heureCaisse < 12) {
                    $salutationCaisse = 'Bonjour';
                    $iconeCaisse = '☀️';
                } elseif ($heureCaisse < 18) {
                    $salutationCaisse = 'Bon après-midi';
                    $iconeCaisse = '🌤️';
                } else {
                    $salutationCaisse = 'Bonsoir';
                    $iconeCaisse = '🌙';
                }
            @endphp

            <div class="rounded-2xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 p-5 text-white shadow-xl ring-1 ring-indigo-400/30">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="font-semibold text-lg tracking-wide">{{ $iconeCaisse }} {{ $salutationCaisse }}, {{ explode(' ', auth()->user()->name)[0] }} !</p>
                        <p class="text-sm text-indigo-100">Bonne vente aujourd'hui, {{ now()->translatedFormat('l d F Y') }}.</p>
                    </div>
                    <div class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-indigo-50 ring-1 ring-white/15">
                        Caisse active
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-center">
                    <span class="shrink-0 bg-amber-500 text-white text-xs font-bold px-3 py-2 flex items-center gap-1">
                        📢 INFO
                    </span>
                    <div class="relative flex-1 overflow-hidden whitespace-nowrap py-2">
                        <div class="inline-block animate-marquee text-sm text-gray-700 dark:text-gray-300">
                            Bienvenue sur l'espace caisse Vision Moderne Construction SARL &nbsp;•&nbsp;
                            Vérifiez toujours le montant avant d'encaisser &nbsp;•&nbsp;
                            En cas d'erreur de vente, contactez l'administrateur pour correction &nbsp;•&nbsp;
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm rounded-lg p-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm rounded-lg p-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($module === 'services')
                {{-- MODULE SERVICES : saisie libre à la vente --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ selection: null, nomLibre: '', prixSaisi: '', quantiteSaisie: 1 }">

                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                            <p class="font-medium text-gray-800 dark:text-gray-200 mb-3">Services courants</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($typeServices as $ts)
                                    <button type="button"
                                            @click="selection = { id: {{ $ts->id }}, nom: @js($ts->nom) }; nomLibre = ''; prixSaisi = ''; quantiteSaisie = 1"
                                            :class="selection && selection.id === {{ $ts->id }} ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200'"
                                            class="px-3 py-2 text-sm rounded-md border hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                                        {{ $ts->nom }}
                                    </button>
                                @endforeach
                                <button type="button"
                                        @click="selection = { id: null, nom: null }; nomLibre = ''; prixSaisi = ''; quantiteSaisie = 1"
                                        :class="selection && selection.id === null ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200'"
                                        class="px-3 py-2 text-sm rounded-md border hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition font-semibold">
                                    ➕ Autre service
                                </button>
                            </div>
                        </div>

                        <template x-if="selection !== null">
                            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 space-y-3">
                                <p class="font-medium text-gray-800 dark:text-gray-200" x-text="selection.id ? selection.nom : 'Nouveau service'"></p>

                                <template x-if="selection.id === null">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nom du service</label>
                                        <input type="text" x-model="nomLibre" placeholder="ex: Agrafage de dossier"
                                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                    </div>
                                </template>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Prix (F)</label>
                                        <input type="number" x-model.number="prixSaisi" min="1"
                                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Quantité</label>
                                        <input type="number" x-model.number="quantiteSaisie" min="1"
                                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                    </div>
                                </div>

                                <button type="button"
                                        @click="ajouterService(selection.id, selection.id ? selection.nom : nomLibre, prixSaisi, quantiteSaisie); selection = null"
                                        :disabled="!prixSaisi || prixSaisi <= 0 || !quantiteSaisie || quantiteSaisie <= 0 || (selection.id === null && !nomLibre)"
                                        :class="(!prixSaisi || prixSaisi <= 0 || !quantiteSaisie || quantiteSaisie <= 0 || (selection.id === null && !nomLibre)) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                                        class="w-full bg-indigo-600 text-white text-sm font-semibold py-2 rounded-md transition">
                                    Ajouter au panier
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Panier --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 h-fit sticky top-24">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">🛒 Panier</h3>

                        <template x-if="panier.length === 0">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Le panier est vide.</p>
                        </template>

                        <div class="space-y-2 mb-4">
                            <template x-for="(ligne, index) in panier" :key="index">
                                <div class="flex items-center justify-between text-sm border-b border-gray-100 dark:border-gray-700 pb-2">
                                    <div class="flex-1">
                                        <p class="text-gray-800 dark:text-gray-200" x-text="ligne.nom"></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <button type="button" @click="changerQuantite(index, -1)" class="w-6 h-6 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">-</button>
                                            <span x-text="ligne.quantite" class="w-6 text-center"></span>
                                            <button type="button" @click="changerQuantite(index, 1)" class="w-6 h-6 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">+</button>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-800 dark:text-gray-200" x-text="(ligne.prix * ligne.quantite).toLocaleString('fr-FR') + ' F'"></p>
                                        <button type="button" @click="retirer(index)" class="text-xs text-red-500 hover:underline">Retirer</button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex justify-between items-center font-bold text-gray-800 dark:text-gray-200 border-t border-gray-200 dark:border-gray-700 pt-3 mb-4">
                            <span>Total</span>
                            <span x-text="total.toLocaleString('fr-FR') + ' F'"></span>
                        </div>

                        <button type="button"
                                @click="ouvrirModalClient()"
                                :disabled="panier.length === 0"
                                :class="panier.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                                class="w-full bg-indigo-600 text-white font-semibold py-2 rounded-md transition">
                            Continuer vers le paiement
                        </button>
                    </div>

                </div>
            @elseif ($module === 'unites_wifi')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- VENTE UNITÉS TÉLÉCOM --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">📱 Vente d'unités télécom</h3>

                    <form method="POST" action="{{ route('caisse.vente-unite') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Opérateur</label>
                            <select name="stock_unite_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                <option value="">-- Choisir --</option>
                                @foreach ($stocksUnites as $stock)
                                    <option value="{{ $stock->id }}">
                                        {{ $stock->operateur }} (solde : {{ number_format($stock->solde_actuel, 0, ',', ' ') }} F)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Montant vendu (F)</label>
                            <input type="number" name="montant_transige" min="1" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bénéfice réalisé (F)</label>
                            <input type="number" name="benefice" min="0" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Note (optionnel)</label>
                            <input type="text" name="note" maxlength="255" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 rounded-md transition">
                            Enregistrer la vente
                        </button>
                    </form>
                </div>

                {{-- VENTE WIFI --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">📶 Vente forfait WiFi</h3>

                    <form method="POST" action="{{ route('caisse.vente-wifi') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Forfait</label>
                            <select name="forfait_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                <option value="">-- Choisir --</option>
                                @foreach ($wifiForfaits as $forfait)
                                    <option value="{{ $forfait->id }}">
                                        {{ $forfait->nom_forfait }} — {{ number_format($forfait->prix_vente, 0, ',', ' ') }} F
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <p class="text-xs text-gray-500 dark:text-gray-400">Le montant et le bénéfice sont calculés automatiquement selon le forfait choisi.</p>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 rounded-md transition">
                            Enregistrer la vente WiFi
                        </button>
                    </form>
                </div>

            </div>
            @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Liste des produits --}}
                <div class="lg:col-span-2 space-y-3">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-3">
                        <label for="recherche-produits" class="sr-only">Rechercher un produit</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400" aria-hidden="true">🔎</span>
                            <input id="recherche-produits" type="search" x-model="rechercheProduit"
                                   placeholder="Rechercher rapidement un produit..."
                                   autocomplete="off"
                                   class="w-full rounded-md border-gray-300 bg-gray-50 py-2.5 pl-10 pr-10 text-sm text-gray-800 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                            <button type="button" x-show="rechercheProduit" x-cloak @click="rechercheProduit = ''"
                                    class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                                    aria-label="Effacer la recherche">&times;</button>
                        </div>
                    </div>

                    <template x-if="produits.length === 0">
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                            Aucun produit actif dans ce module.
                        </div>
                    </template>

                    <template x-if="produits.length > 0 && produitsFiltres.length === 0">
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                            Aucun produit ne correspond à votre recherche.
                        </div>
                    </template>

                    <template x-for="produit in produitsFiltres" :key="produit.id">
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <p class="font-medium text-gray-800 dark:text-gray-200" x-text="produit.nom"></p>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Stock: <span x-text="produit.stock"></span></span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="unite in produit.unites" :key="unite.id">
                                    <button type="button"
                                            @click="ajouterAuPanier(produit, unite)"
                                            class="px-3 py-2 text-sm rounded-md border border-gray-200 dark:border-gray-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:border-indigo-300 transition text-left">
                                        <span class="block font-medium text-gray-700 dark:text-gray-200 capitalize" x-text="unite.type"></span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400" x-text="unite.prix.toLocaleString('fr-FR') + ' F'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Panier --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 h-fit sticky top-24">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">🛒 Panier</h3>

                    <template x-if="panier.length === 0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Le panier est vide.</p>
                    </template>

                    <div class="space-y-2 mb-4">
                        <template x-for="(ligne, index) in panier" :key="index">
                            <div class="flex items-center justify-between text-sm border-b border-gray-100 dark:border-gray-700 pb-2">
                                <div class="flex-1">
                                    <p class="text-gray-800 dark:text-gray-200" x-text="ligne.nom + ' (' + ligne.type + ')'"></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <button type="button" @click="changerQuantite(index, -1)" class="w-6 h-6 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">-</button>
                                        <span x-text="ligne.quantite" class="w-6 text-center"></span>
                                        <button type="button" @click="changerQuantite(index, 1)" class="w-6 h-6 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">+</button>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-800 dark:text-gray-200" x-text="(ligne.prix * ligne.quantite).toLocaleString('fr-FR') + ' F'"></p>
                                    <button type="button" @click="retirer(index)" class="text-xs text-red-500 hover:underline">Retirer</button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-between items-center font-bold text-gray-800 dark:text-gray-200 border-t border-gray-200 dark:border-gray-700 pt-3 mb-4">
                        <span>Total</span>
                        <span x-text="total.toLocaleString('fr-FR') + ' F'"></span>
                    </div>

                    <button type="button"
                            @click="ouvrirModalClient()"
                            :disabled="panier.length === 0"
                            :class="panier.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                            class="w-full bg-indigo-600 text-white font-semibold py-2 rounded-md transition">
                        Continuer vers le paiement
                    </button>
                </div>

            </div>
            @endif
        </div>
        <form id="formCaisse" method="POST" action="{{ route('caisse.store') }}" class="hidden">
            @csrf
            <input type="hidden" name="module" value="{{ $module }}">
        </form>

        <!-- Modal Client et Confirmation -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4" id="modalClient" @click.self="fermerModalClient()">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-[0_24px_60px_rgba(15,23,42,0.28)] max-w-lg w-full h-[calc(100dvh-1.5rem)] max-h-[calc(100dvh-1.5rem)] sm:h-auto sm:max-h-[calc(100dvh-2rem)] flex flex-col overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="bg-gradient-to-r from-indigo-600 via-violet-600 to-sky-600 text-white px-6 py-4 flex-shrink-0">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-indigo-100">Validation</p>
                            <h3 class="font-semibold text-lg mt-1">Récapitulatif de la vente</h3>
                        </div>
                        <button type="button" @click="fermerModalClient()" class="text-white/80 hover:text-white text-xl leading-none">×</button>
                    </div>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto p-4 sm:p-5 space-y-4">
                    <div class="rounded-xl border border-blue-100 bg-blue-50/80 dark:border-blue-900/60 dark:bg-blue-950/20 p-4 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-white text-xs font-bold">i</span>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:text-slate-300">Infos client (optionnel)</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Nom</label>
                            <input type="text" x-model="clientNom" placeholder="Dupont"
                                   class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-slate-100 text-sm p-2.5 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-900 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Prénom</label>
                            <input type="text" x-model="clientPrenom" placeholder="Jean"
                                   class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-slate-100 text-sm p-2.5 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-900 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1.5">Téléphone</label>
                            <input type="tel" x-model="clientTelephone" placeholder="+226 76 00 00 00"
                                   class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-slate-100 text-sm p-2.5 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-900 outline-none">
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/40 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:text-slate-300">Détail de la vente</p>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400" x-text="panier.length"></span>
                        </div>

                        <div class="max-h-36 overflow-y-auto space-y-2 pr-1">
                            <template x-for="(ligne, index) in panier" :key="index">
                                <div class="flex justify-between gap-3 text-sm text-slate-700 dark:text-slate-200 py-2 border-b border-slate-200 dark:border-slate-600 last:border-0 last:pb-0">
                                    <div class="min-w-0">
                                        <p class="font-medium truncate" x-text="ligne.nom"></p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            <template x-if="ligne.type">
                                                <span x-text="ligne.type"></span>
                                            </template>
                                            <span class="ml-1">× <span x-text="ligne.quantite"></span></span>
                                        </p>
                                    </div>
                                    <span class="font-semibold whitespace-nowrap" x-text="(ligne.prix * ligne.quantite).toLocaleString('fr-FR') + ' F'"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white p-4 shadow-lg shadow-indigo-500/20">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.2em] text-indigo-100">Montant total</p>
                                <p class="text-sm text-indigo-100 mt-1">à payer</p>
                            </div>
                            <span class="font-bold text-2xl" x-text="total.toLocaleString('fr-FR') + ' F'"></span>
                        </div>
                    </div>
                </div>

                <div class="flex-shrink-0 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/60 p-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] flex gap-2">
                    <button type="button" @click="fermerModalClient()"
                            class="flex-1 px-4 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-semibold text-sm rounded-xl hover:bg-slate-300 dark:hover:bg-slate-600 transition whitespace-nowrap">
                        Annuler
                    </button>
                    <button type="button" @click="validerVente()"
                            class="flex-1 px-4 py-2.5 bg-emerald-600 text-white font-semibold text-sm rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20 whitespace-nowrap">
                        ✓ Confirmer la vente
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')

    <script>
        function caisse(produitsInitiaux) {
            return {
                produits: produitsInitiaux,
                rechercheProduit: '',
                panier: [],
                module: @js($module),
                clientNom: '',
                clientPrenom: '',
                clientTelephone: '',
                formeVente: null,

                chargerPanierDuStorage() {
                    const panierStocke = localStorage.getItem(this.clePanier);
                    if (panierStocke) {
                        try {
                            const panier = JSON.parse(panierStocke);
                            this.panier = Array.isArray(panier)
                                ? panier
                                    .map(ligne => ({
                                        ...ligne,
                                        module: ligne.module || (!ligne.produit_id ? 'services' : (this.module !== 'services' ? this.module : null)),
                                    }))
                                    .filter(ligne => ['secretariat', 'librairie', 'boissons', 'services'].includes(ligne.module))
                                : [];
                            this.sauvegarderPanierDanStorage();
                        } catch (e) {
                            console.error('Erreur de chargement du panier:', e);
                            this.panier = [];
                            localStorage.removeItem(this.clePanier);
                        }
                    }
                },

                sauvegarderPanierDanStorage() {
                    localStorage.setItem(this.clePanier, JSON.stringify(this.panier));
                },

                get clePanier() {
                    return 'panier_caisse';
                },

                ajouterAuPanier(produit, unite) {
                    const existant = this.panier.find(l => l.produit_id === produit.id && l.unite_id === unite.id);
                    if (existant) {
                        existant.quantite++;
                    } else {
                        this.panier.push({
                            module: this.module,
                            produit_id: produit.id,
                            unite_id: unite.id,
                            nom: produit.nom,
                            type: unite.type,
                            prix: unite.prix,
                            quantite: 1,
                        });
                    }
                    this.sauvegarderPanierDanStorage();
                },

                ajouterService(typeServiceId, nom, prix, quantite) {
                    this.panier.push({
                        module: this.module,
                        type_service_id: typeServiceId,
                        description_libre: typeServiceId ? null : nom,
                        nom: nom,
                        prix: parseFloat(prix),
                        quantite: parseInt(quantite),
                    });
                    this.sauvegarderPanierDanStorage();
                },

                changerQuantite(index, delta) {
                    this.panier[index].quantite += delta;
                    if (this.panier[index].quantite <= 0) {
                        this.panier.splice(index, 1);
                    }
                    this.sauvegarderPanierDanStorage();
                },

                retirer(index) {
                    this.panier.splice(index, 1);
                    this.sauvegarderPanierDanStorage();
                },

                ouvrirModalClient() {
                    this.formeVente = document.querySelector('form[action*="caisse.store"]') || document.querySelector('form[action*="caisse.vente"]');
                    document.getElementById('modalClient').classList.remove('hidden');
                },

                fermerModalClient() {
                    document.getElementById('modalClient').classList.add('hidden');
                },

                validerVente() {
                    const form = document.getElementById('formCaisse');

                    if (!form) {
                        console.error('Formulaire de caisse non trouvé');
                        return;
                    }

                    if (this.panier.some(ligne => !['secretariat', 'librairie', 'boissons', 'services'].includes(ligne.module))) {
                        window.alert('Une ligne de vente est invalide. Retirez-la puis ajoutez-la de nouveau.');
                        return;
                    }

                    form.querySelectorAll('input[name^="lignes"], input[name="client_nom"], input[name="client_prenom"], input[name="client_telephone"]').forEach(el => el.remove());

                    const moduleInput = form.querySelector('input[name="module"]');
                    if (moduleInput) {
                        const modules = this.panier.map(ligne => ligne.module).filter((module, index, lignes) => lignes.indexOf(module) === index);
                        moduleInput.value = modules.length > 1 ? 'mixte' : modules[0];
                    }

                    this.panier.forEach((ligne, index) => {
                        if (ligne.produit_id !== undefined) {
                            const inputModule = document.createElement('input');
                            inputModule.type = 'hidden';
                            inputModule.name = `lignes[${index}][module]`;
                            inputModule.value = ligne.module;
                            form.appendChild(inputModule);

                            const inputProduitId = document.createElement('input');
                            inputProduitId.type = 'hidden';
                            inputProduitId.name = `lignes[${index}][produit_id]`;
                            inputProduitId.value = ligne.produit_id;
                            form.appendChild(inputProduitId);

                            const inputProduitUniteId = document.createElement('input');
                            inputProduitUniteId.type = 'hidden';
                            inputProduitUniteId.name = `lignes[${index}][produit_unite_id]`;
                            inputProduitUniteId.value = ligne.unite_id;
                            form.appendChild(inputProduitUniteId);

                            const inputQuantite = document.createElement('input');
                            inputQuantite.type = 'hidden';
                            inputQuantite.name = `lignes[${index}][quantite]`;
                            inputQuantite.value = ligne.quantite;
                            form.appendChild(inputQuantite);
                        } else {
                            const inputModule = document.createElement('input');
                            inputModule.type = 'hidden';
                            inputModule.name = `lignes[${index}][module]`;
                            inputModule.value = ligne.module;
                            form.appendChild(inputModule);

                            const inputTypeServiceId = document.createElement('input');
                            inputTypeServiceId.type = 'hidden';
                            inputTypeServiceId.name = `lignes[${index}][type_service_id]`;
                            inputTypeServiceId.value = ligne.type_service_id ?? '';
                            form.appendChild(inputTypeServiceId);

                            const inputDescriptionLibre = document.createElement('input');
                            inputDescriptionLibre.type = 'hidden';
                            inputDescriptionLibre.name = `lignes[${index}][description_libre]`;
                            inputDescriptionLibre.value = ligne.description_libre ?? '';
                            form.appendChild(inputDescriptionLibre);

                            const inputPrix = document.createElement('input');
                            inputPrix.type = 'hidden';
                            inputPrix.name = `lignes[${index}][prix]`;
                            inputPrix.value = ligne.prix;
                            form.appendChild(inputPrix);

                            const inputQuantite = document.createElement('input');
                            inputQuantite.type = 'hidden';
                            inputQuantite.name = `lignes[${index}][quantite]`;
                            inputQuantite.value = ligne.quantite;
                            form.appendChild(inputQuantite);
                        }
                    });

                    const inputNom = document.createElement('input');
                    inputNom.type = 'hidden';
                    inputNom.name = 'client_nom';
                    inputNom.value = this.clientNom;
                    form.appendChild(inputNom);

                    const inputPrenom = document.createElement('input');
                    inputPrenom.type = 'hidden';
                    inputPrenom.name = 'client_prenom';
                    inputPrenom.value = this.clientPrenom;
                    form.appendChild(inputPrenom);

                    const inputTelephone = document.createElement('input');
                    inputTelephone.type = 'hidden';
                    inputTelephone.name = 'client_telephone';
                    inputTelephone.value = this.clientTelephone;
                    form.appendChild(inputTelephone);

                    localStorage.removeItem(this.clePanier);
                    form.submit();
                },

                get total() {
                    return this.panier.reduce((sum, l) => sum + (l.prix * l.quantite), 0);
                },

                get produitsFiltres() {
                    const recherche = this.rechercheProduit.trim().toLocaleLowerCase('fr-FR');

                    if (!recherche) {
                        return this.produits;
                    }

                    return this.produits.filter(produit => produit.nom.toLocaleLowerCase('fr-FR').includes(recherche));
                }
            }
        }
    </script>
    @endpush

</x-caisse-layout>