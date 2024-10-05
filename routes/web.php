<?php

use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman utama akan mengarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login'); // Arahkan ke halaman login
});

// Rute untuk halaman dashboard, hanya bisa diakses jika terautentikasi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [FeedController::class, 'index'])->name('dashboard');
    Route::post('/toggle-relay', [FeedController::class, 'toggleRelay']);
});

// Rute untuk mengelola profil pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Memuat rute otentikasi
require __DIR__.'/auth.php';
