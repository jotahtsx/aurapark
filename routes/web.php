<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\DashboardController; // Adicione esta linha
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {

    // Rotas para o dashboard e usuários
    Route::get('/home', [DashboardController::class, 'index'])->name('dashboard.home');
    Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/usuarios/cadastrar', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/usuarios', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/usuarios/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Rotas para precificações
    Route::get('/admin/precificacoes', [PricingController::class, 'index'])->name('admin.pricings.index');
    Route::get('/admin/precificacoes/cadastrar', [PricingController::class, 'create'])->name('admin.pricings.create');
    Route::post('/admin/precificacoes', [PricingController::class, 'store'])->name('admin.pricings.store');
    Route::get('/admin/precificacoes/{pricing}/editar', [PricingController::class, 'edit'])->name('admin.pricings.edit');
    Route::put('/admin/precificacoes/{pricing}', [PricingController::class, 'update'])->name('admin.pricings.update');
    Route::delete('/admin/precificacoes/{pricing}', [PricingController::class, 'destroy'])->name('admin.pricings.destroy');
});

Route::get('/', fn () => redirect()->route('dashboard.home'));