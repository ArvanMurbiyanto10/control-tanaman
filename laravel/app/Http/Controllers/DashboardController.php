<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class DashboardController extends Controller
{
    // Halaman Dasbor Utama (Hanya Angka)
    public function index()
    {
        $latestData = SensorData::latest()->first();
        return view('dashboard.index', compact('latestData'));
    }

    // Halaman Detail & Grafik (Kelembaban, pH, Suhu)
    public function detail($jenis)
    {
        $latestData = SensorData::latest()->first();
        $historyData = SensorData::latest()->take(10)->get();
        return view('dashboard.detail', compact('latestData', 'historyData', 'jenis'));
    }

    // Halaman Jadwal & Automatisasi Pompa
    public function jadwal()
    {
        $latestData = SensorData::latest()->first();
        return view('dashboard.jadwal', compact('latestData'));
    }
}
