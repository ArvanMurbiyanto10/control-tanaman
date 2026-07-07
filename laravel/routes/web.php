<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
// Halaman
Route::get('/', [DashboardController::class, 'index']);
Route::get('/detail/{jenis}', [DashboardController::class, 'detail']);
Route::get('/jadwal', [DashboardController::class, 'jadwal']);

// --- RUTE GUEST (HANYA BISA DIAKSES JIKA BELUM LOGIN) ---
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

// API Endpoints
Route::get('/api/sensor/latest', [DashboardController::class, 'latestSensor']);
Route::post('/api/pompa/toggle', [DashboardController::class, 'togglePompa']);
Route::post('/api/jadwal/simpan', [DashboardController::class, 'simpanJadwal']);

// --- RUTE AUTH (KUNCI DASHBOARD: HARUS LOGIN DULU) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/detail/{jenis}', [DashboardController::class, 'detail']);
    Route::get('/jadwal', [DashboardController::class, 'jadwal']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
