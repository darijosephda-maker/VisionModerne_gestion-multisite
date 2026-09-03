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
                    <template x-if="produits.length === 0">
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 text-center text-gray-500 dark:text-gray-400">
                            Aucun produit actif dans ce module.
                        </div>
                    </template>

                    <template x-for="produit in produits" :key="produit.id">
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
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" id="modalClient" @click.self="fermerModalClient()">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full flex flex-col max-h-[80vh]" style="max-height: calc(100vh - 40px);">
                {{-- En-tête collant --}}
                <div class="bg-indigo-600 text-white px-6 py-4 rounded-t-lg flex-shrink-0">
                    <h3 class="font-semibold text-lg">Récapitulatif de la vente</h3>
                </div>

                {{-- Contenu scrollable avec hauteur contrôlée --}}
                <div class="flex-1 overflow-y-auto p-5 space-y-3" style="min-height: 0; max-height: 50vh;">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 space-y-2">
                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Infos client (optionnel)</p>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-0.5">Nom</label>
                            <input type="text" x-model="clientNom" placeholder="Dupont"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-xs p-2">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-0.5">Prénom</label>
                            <input type="text" x-model="clientPrenom" placeholder="Jean"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-xs p-2">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-0.5">Téléphone</label>
                            <input type="tel" x-model="clientTelephone" placeholder="+226 76 00 00 00"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-xs p-2">
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2">Détail de la vente</p>
                        <div class="max-h-20 overflow-y-auto"
                            <template x-for="(ligne, index) in panier" :key="index">
                                <div class="flex justify-between text-xs text-gray-700 dark:text-gray-300 pb-1 border-b border-gray-200 dark:border-gray-600 last:border-0">
                                    <div>
                                        <span x-text="ligne.nom"></span>
                                        <template x-if="ligne.type">
                                            <span> (<span x-text="ligne.type"></span>)</span>
                                        </template>
                                        <span class="ml-1 text-gray-500 dark:text-gray-400">x<span x-text="ligne.quantite"></span></span>
                                    </div>
                                    <span class="font-medium" x-text="(ligne.prix * ligne.quantite).toLocaleString('fr-FR') + ' F'"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-sm">TOTAL À PAYER</span>
                            <span class="font-bold text-lg" x-text="total.toLocaleString('fr-FR') + ' F'"></span>
                        </div>
                    </div>
                </div>

                {{-- Boutons d'action collants en bas - TOUJOURS VISIBLES --}}
                <div class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700 p-3 bg-gray-50 dark:bg-gray-900/50 flex gap-2">
                    <button type="button" @click="fermerModalClient()"
                            class="flex-1 px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold text-sm rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition whitespace-nowrap">
                        Annuler
                    </button>
                    <button type="button" @click="validerVente()"
                            class="flex-1 px-3 py-2 bg-emerald-600 text-white font-semibold text-sm rounded-md hover:bg-emerald-700 transition shadow-md whitespace-nowrap">
                        ✓ Confirmer
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
                panier: [],
                clientNom: '',
                clientPrenom: '',
                clientTelephone: '',
                formeVente: null,

                chargerPanierDuStorage() {
                    const panierStocke = localStorage.getItem('panier_caisse');
                    if (panierStocke) {
                        try {
                            this.panier = JSON.parse(panierStocke);
                        } catch (e) {
                            console.error('Erreur de chargement du panier:', e);
                            this.panier = [];
                        }
                    }
                },

                sauvegarderPanierDanStorage() {
                    localStorage.setItem('panier_caisse', JSON.stringify(this.panier));
                },

                ajouterAuPanier(produit, unite) {
                    const existant = this.panier.find(l => l.produit_id === produit.id && l.unite_id === unite.id);
                    if (existant) {
                        existant.quantite++;
                    } else {
                        this.panier.push({
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

                    form.querySelectorAll('input[name^="lignes"], input[name="client_nom"], input[name="client_prenom"], input[name="client_telephone"]').forEach(el => el.remove());

                    const moduleInput = form.querySelector('input[name="module"]');
                    if (moduleInput) {
                        moduleInput.value = '{{ $module }}';
                    }

                    this.panier.forEach((ligne, index) => {
                        if (ligne.produit_id !== undefined) {
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

                    form.submit();
                },

                get total() {
                    return this.panier.reduce((sum, l) => sum + (l.prix * l.quantite), 0);
                }
            }
        }
    </script>
    @endpush

</x-caisse-layout>