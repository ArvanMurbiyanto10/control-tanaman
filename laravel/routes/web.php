<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Halaman
Route::get('/', [DashboardController::class, 'index']);
Route::get('/detail/{jenis}', [DashboardController::class, 'detail']);
Route::get('/jadwal', [DashboardController::class, 'jadwal']);

// API Endpoints
Route::get('/api/sensor/latest', [DashboardController::class, 'latestSensor']);
Route::post('/api/pompa/toggle', [DashboardController::class, 'togglePompa']);
Route::post('/api/jadwal/simpan', [DashboardController::class, 'simpanJadwal']);
