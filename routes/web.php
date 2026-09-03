<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/a-propos', [\App\Http\Controllers\HomeController::class, 'apropos'])->name('apropos');
Route::get('/contact', [\App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

Route::get('dashboard', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('caisse.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('admin/produits', \App\Http\Controllers\Admin\ProduitController::class)
        ->names('admin.produits')
        ->parameters(['produits' => 'produit']);

    Route::resource('admin/fournisseurs', \App\Http\Controllers\Admin\FournisseurController::class)
        ->names('admin.fournisseurs')
        ->parameters(['fournisseurs' => 'fournisseur']);

    Route::resource('admin/type-services', \App\Http\Controllers\Admin\TypeServiceController::class)
    ->names('admin.type-services')
    ->parameters(['type-services' => 'typeService']);

    Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class)
        ->names('admin.users')
        ->parameters(['users' => 'user']);

    Route::get('admin/stocks-unites', [\App\Http\Controllers\Admin\StockUniteController::class, 'index'])
        ->name('admin.stocks-unites.index');

    Route::get('admin/stocks-unites/create', [\App\Http\Controllers\Admin\StockUniteController::class, 'create'])
        ->name('admin.stocks-unites.create');

    Route::post('admin/stocks-unites', [\App\Http\Controllers\Admin\StockUniteController::class, 'store'])
        ->name('admin.stocks-unites.store');

    Route::post('admin/stocks-unites/{stockUnite}/recharger', [\App\Http\Controllers\Admin\StockUniteController::class, 'recharger'])
        ->name('admin.stocks-unites.recharger');

    Route::get('admin/wifi-forfaits', [\App\Http\Controllers\Admin\WifiForfaitController::class, 'index'])
        ->name('admin.wifi-forfaits.index');

    Route::get('admin/wifi-forfaits/create', [\App\Http\Controllers\Admin\WifiForfaitController::class, 'create'])
        ->name('admin.wifi-forfaits.create');

    Route::post('admin/wifi-forfaits', [\App\Http\Controllers\Admin\WifiForfaitController::class, 'store'])
        ->name('admin.wifi-forfaits.store');

    Route::get('admin/wifi-forfaits/{wifiForfait}/edit', [\App\Http\Controllers\Admin\WifiForfaitController::class, 'edit'])
        ->name('admin.wifi-forfaits.edit');

    Route::put('admin/wifi-forfaits/{wifiForfait}', [\App\Http\Controllers\Admin\WifiForfaitController::class, 'update'])
        ->name('admin.wifi-forfaits.update');

    Route::post('admin/wifi-forfaits/{wifiForfait}/toggle-actif', [\App\Http\Controllers\Admin\WifiForfaitController::class, 'toggleActif'])
        ->name('admin.wifi-forfaits.toggle-actif');

    Route::delete('admin/wifi-forfaits/{wifiForfait}', [\App\Http\Controllers\Admin\WifiForfaitController::class, 'destroy'])
        ->name('admin.wifi-forfaits.destroy');

    Route::get('admin/rapports', [\App\Http\Controllers\Admin\RapportController::class, 'index'])
        ->name('admin.rapports.index');

    Route::get('admin/factures', [\App\Http\Controllers\Admin\RapportController::class, 'factures'])
        ->name('admin.factures.index');

    Route::get('admin/site-contenu', [\App\Http\Controllers\Admin\SiteContenuController::class, 'index'])
        ->name('admin.site-contenu.index');

    Route::post('admin/site-contenu', [\App\Http\Controllers\Admin\SiteContenuController::class, 'store'])
        ->name('admin.site-contenu.store');

});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('caisse', [\App\Http\Controllers\CaisseController::class, 'index'])
        ->name('caisse.index');

    Route::post('caisse', [\App\Http\Controllers\CaisseController::class, 'store'])
        ->name('caisse.store');

    Route::post('caisse/vente-unite', [\App\Http\Controllers\CaisseController::class, 'venteUnite'])
        ->name('caisse.vente-unite');

    Route::post('caisse/vente-wifi', [\App\Http\Controllers\CaisseController::class, 'venteWifi'])
        ->name('caisse.vente-wifi');

    Route::get('caisse/facture/{vente}', [\App\Http\Controllers\CaisseController::class, 'facture'])
        ->name('caisse.facture');

    Route::get('caisse/mes-ventes', [\App\Http\Controllers\CaisseController::class, 'mesVentes'])
    ->middleware(['auth', 'verified'])
    ->name('caisse.mes-ventes');

});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

require __DIR__.'/auth.php';