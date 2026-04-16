<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\AdminController;

// Auth
Route::get('/', function () { return redirect()->route('login'); });
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/cadastro', [AuthController::class, 'showCadastro'])->name('cadastro');
Route::post('/cadastro', [AuthController::class, 'cadastro']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Usuário comum
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [RegistroController::class, 'home'])->name('home');
    Route::get('/registros', [RegistroController::class, 'index'])->name('registros.index');
    Route::get('/registros/novo', [RegistroController::class, 'create'])->name('registros.create');
    Route::post('/registros', [RegistroController::class, 'store'])->name('registros.store');
    Route::get('/registros/{id}', [RegistroController::class, 'show'])->name('registros.show');
});

// Admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/lista', [AdminController::class, 'lista'])->name('lista');
    Route::get('/mapa', [AdminController::class, 'mapa'])->name('mapa');
    Route::get('/registros/{id}', [AdminController::class, 'show'])->name('show');
    Route::put('/registros/{id}/status', [AdminController::class, 'atualizarStatus'])->name('atualizarStatus');
});
