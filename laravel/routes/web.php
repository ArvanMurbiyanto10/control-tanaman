<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/detail/{jenis}', [DashboardController::class, 'detail']);
Route::get('/jadwal', [DashboardController::class, 'jadwal']);
