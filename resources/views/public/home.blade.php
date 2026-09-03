<x-public-layout>
    <div class="max-w-6xl mx-auto px-4 pb-12 pt-8 sm:px-6 lg:px-8">

        <div class="flex flex-col items-center justify-center text-center">
            <div class="mb-8 flex h-28 w-28 items-center justify-center rounded-[2rem] bg-white p-5 shadow-[0_20px_40px_rgba(0,0,0,0.15)] dark:shadow-[0_20px_40px_rgba(0,0,0,0.25)] ring-1 ring-slate-200/80">
                <img src="{{ asset('images/Logo.jpeg') }}" alt="Vision Moderne Construction" class="h-full w-full rounded-[1.2rem] object-cover">
            </div>

            <h1 class="text-4xl font-black tracking-tight text-slate-900 dark:text-white sm:text-5xl lg:text-6xl">
                VISION MODERNE <span class="text-emerald-600 dark:text-emerald-400">CONSTRUCTION</span> SARL
            </h1>

            <p class="mt-6 max-w-4xl text-lg text-slate-600 dark:text-slate-300 sm:text-xl">
                Ventes de Fournitures, Bureautique &amp; Informatique • Bâtiment &amp; Travaux Publics • Électricité &amp; Entretien
            </p>

            <p class="mt-4 max-w-4xl text-base text-slate-500 dark:text-slate-400 sm:text-lg">
                Vente de produits alimentaires • Personnalisation des maillots • Consultations en BTP • Produits Divers
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-500">
                    Nous contacter
                </a>
                <a href="{{ route('apropos') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white dark:border-white/15 dark:bg-white/5 px-6 py-3 text-base font-semibold text-slate-700 dark:text-slate-100 transition hover:bg-slate-50 dark:hover:bg-white/10">
                    En savoir plus
                </a>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-center text-2xl font-bold text-slate-900 dark:text-white sm:text-3xl">Nos domaines d'activité</h2>
            <p class="mt-3 text-center text-slate-500 dark:text-slate-400">Une offre diversifiée au service de nos clients à Gaoua et dans la région</p>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @php
                    $services = [
                        ['icon' => '🗂️', 'title' => 'Secrétariat', 'text' => 'Services et prestations administratives : saisie, mise en forme de documents, et autres travaux de bureau.'],
                        ['icon' => '📚', 'title' => 'Librairie', 'text' => 'Fournitures scolaires et de bureau : cahiers, stylos, matériel d’école et d’entreprise, en détail comme en gros.'],
                        ['icon' => '🥤', 'title' => 'Boissons', 'text' => 'Produits alimentaires et boissons de toutes marques, pour particuliers et professionnels.'],
                        ['icon' => '🖨️', 'title' => 'Services', 'text' => 'Impression, photocopie, reliure, et personnalisation de maillots sur mesure.'],
                        ['icon' => '📶', 'title' => 'Unités & WiFi', 'text' => 'Recharges télécom (Orange, Moov, Telecel) et accès WiFi public à la journée ou à l’heure.'],
                        ['icon' => '🏗️', 'title' => 'Bâtiment & BTP', 'text' => 'Travaux de construction, électricité, entretien, et consultations en bâtiment et travaux publics.']
                    ];
                @endphp

                @foreach ($services as $service)
                    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5 p-6 shadow-[0_15px_30px_rgba(0,0,0,0.08)] dark:shadow-[0_15px_30px_rgba(0,0,0,0.15)] backdrop-blur-sm transition hover:-translate-y-1 hover:bg-slate-50 dark:hover:bg-white/8">
                        <div class="text-4xl">{{ $service['icon'] }}</div>
                        <p class="mt-4 text-xl font-semibold text-slate-900 dark:text-white">{{ $service['title'] }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $service['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-16 rounded-[2rem] border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5 p-8 shadow-[0_20px_40px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_40px_rgba(0,0,0,0.18)] backdrop-blur-sm">
            <h2 class="text-center text-2xl font-bold text-slate-900 dark:text-white">Pourquoi choisir Vision Moderne Construction</h2>
            <div class="mt-8 grid gap-8 md:grid-cols-3">
                <div class="text-center">
                    <div class="mb-3 text-4xl">✅</div>
                    <p class="font-semibold text-slate-900 dark:text-white">Entreprise formelle</p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Immatriculée au RCCM et enregistrée auprès des services fiscaux.</p>
                </div>
                <div class="text-center">
                    <div class="mb-3 text-4xl">🤝</div>
                    <p class="font-semibold text-slate-900 dark:text-white">Offre diversifiée</p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Plusieurs domaines réunis pour répondre à vos besoins du quotidien.</p>
                </div>
                <div class="text-center">
                    <div class="mb-3 text-4xl">📍</div>
                    <p class="font-semibold text-slate-900 dark:text-white">Basée à Gaoua</p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Proche de sa clientèle dans la province du Poni.</p>
                </div>
            </div>
        </div>

        <div class="mt-16 text-center">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Une question, un besoin ?</h2>
            <p class="mt-3 text-slate-600 dark:text-slate-300">Notre équipe est disponible pour vous accompagner dans vos projets.</p>
            <a href="{{ route('contact') }}" class="mt-6 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-500">
                Contactez-nous dès maintenant
            </a>
        </div>
    </div>
</x-public-layout>