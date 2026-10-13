<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// Javni, server-rendered dio sajta (SEO / AdSense). Flutter web aplikacija je na /app.
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/recepti', [SiteController::class, 'recepti'])->name('recepti');
Route::get('/recepti/{slug}', [SiteController::class, 'recept'])->name('recept');
Route::get('/kalkulatori', [SiteController::class, 'kalkulatori'])->name('kalkulatori');
Route::get('/kalkulatori/{slug}', [SiteController::class, 'kalkulator'])->name('kalkulator');
Route::get('/vodici', [SiteController::class, 'vodiciIndex'])->name('vodici');
Route::get('/vodici/{slug}', [SiteController::class, 'vodic'])->name('vodic');
Route::view('/o-nama', 'site.o-nama')->name('o-nama');
Route::view('/kontakt', 'site.kontakt')->name('kontakt');
Route::view('/privatnost', 'site.privatnost')->name('privatnost');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');

// Stare putanje Flutter weba -> nove stranice / aplikacija
Route::permanentRedirect('/posts/{slug}', '/recepti/{slug}');
Route::permanentRedirect('/privacy', '/privatnost');
foreach (['login', 'register', 'profil', 'posts/create'] as $p) {
    Route::redirect('/'.$p, '/app/#/'.$p);
}
