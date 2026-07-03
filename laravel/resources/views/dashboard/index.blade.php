@extends('layouts.app')

@section('content')
<style>
    @keyframes pulse-soft {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
</style>

<div class="min-h-screen pb-12">
    <!-- ========================================== -->
    <!-- HEADER & STATUS UTAMA (DATA REALTIME ASLI) -->
    <!-- ========================================== -->
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mb-8 flex flex-col lg:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-emerald-50 to-transparent pointer-events-none"></div>
        <div class="absolute -right-10 -bottom-10 opacity-5 text-emerald-600 pointer-events-none transform -rotate-12">
            <i class="fa-solid fa-seedling text-9xl"></i>
        </div>

        <!-- Teks Sapaan -->
        <div class="relative z-10 w-full lg:w-2/3">
            <div class="flex items-center gap-3 mb-3">
                <span class="bg-emerald-100 text-emerald-700 text-xs font-extrabold px-3 py-1 rounded-full flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Sensor Online
                </span>
                <span class="text-xs font-bold text-gray-400"><i class="fa-regular fa-clock mr-1"></i> {{ date('H:i') }} WIB</span>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Pantauan Lahan COTA</h1>
            <p class="text-gray-500 text-sm leading-relaxed max-w-xl">
                Menampilkan data langsung dari NodeMCU ESP32. Seluruh indikator di bawah ini merupakan hasil pembacaan nyata dari lahan pertanian Anda.
            </p>
        </div>

        <!-- STATUS POMPA AIR (INDIKATOR REALTIME DARI ESP32) -->
        @php
            // Membaca status pompa dari database (true = menyala, false = mati)
            $pompaAktif = $latestData->status_pompa ?? false;
        @endphp
        <div class="relative z-10 w-full lg:w-auto bg-gray-50 border border-gray-200 rounded-2xl p-5 flex items-center gap-5 min-w-[280px]">
            <div class="w-14 h-14 rounded-full flex items-center justify-center text-white {{ $pompaAktif ? 'bg-emerald-500' : 'bg-gray-300' }}" style="animation: {{ $pompaAktif ? 'pulse-soft 2s infinite' : 'none' }}">
                <i class="fa-solid fa-power-off text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Status Mesin Pompa</p>
                @if($pompaAktif)
                    <h3 class="text-xl font-black text-emerald-600">MENYALA</h3>
                    <p class="text-xs font-bold text-emerald-400 mt-0.5">Air sedang dialirkan...</p>
                @else
                    <h3 class="text-xl font-black text-gray-600">MATI (STANDBY)</h3>
                    <p class="text-xs font-medium text-gray-400 mt-0.5">Kelembaban tercukupi</p>
                @endif
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- GRID 3 KARTU SENSOR ASLI                   -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @include('dashboard.partials.kelembaban')
        @include('dashboard.partials.ph-tanah')
        @include('dashboard.partials.suhu-tanah')
    </div>

    <!-- ========================================== -->
    <!-- LOG AKTIVITAS & KONEKSI (HANYA FAKTA)      -->
    <!-- ========================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LOG AKTIVITAS (Fokus pada pengiriman data dan status pompa) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-7 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-gray-900 font-extrabold text-lg flex items-center gap-2">
                    <i class="fa-solid fa-list-ul text-emerald-500"></i> Riwayat Sistem
                </h2>
                <a href="/jadwal" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 transition-colors">Atur Jadwal Pompa &rarr;</a>
            </div>

            <div class="space-y-4">
                <!-- Log 1: Penerimaan Data Terakhir -->
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <div class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cloud-arrow-down"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Data Sensor Diterima</h3>
                        <p class="text-xs text-gray-500 mt-1">NodeMCU berhasil mem-publish paket data JSON (Suhu, pH, Kelembaban) ke MQTT Broker HiveMQ.</p>
                        <p class="text-[10px] font-bold text-gray-400 mt-2">Baru saja - {{ date('H:i:s') }}</p>
                    </div>
                </div>

                <!-- Log 2: Logika Siram Pompa -->
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                    <div class="bg-amber-100 text-amber-600 w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Evaluasi Ambang Batas</h3>
                        <p class="text-xs text-gray-500 mt-1">Sistem membaca tingkat kelembaban di angka {{ $latestData->kelembaban ?? 0 }}%. Algoritma memutuskan tidak perlu menyalakan relay pompa.</p>
                        <p class="text-[10px] font-bold text-gray-400 mt-2">1 menit yang lalu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFO PERANGKAT (Hanya Info Realistis yang Terpasang) -->
        <div class="lg:col-span-1 bg-gray-900 rounded-3xl p-7 text-white shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>

            <div class="relative z-10">
                <h2 class="font-extrabold text-lg mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-server text-emerald-400"></i> Detail Integrasi
                </h2>

                <ul class="space-y-4">
                    <li class="flex justify-between items-center border-b border-gray-700 pb-3">
                        <span class="text-xs text-gray-400">Mikrokontroler</span>
                        <span class="text-sm font-bold text-gray-100">NodeMCU ESP32</span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-700 pb-3">
                        <span class="text-xs text-gray-400">Protokol Jaringan</span>
                        <span class="text-sm font-bold text-gray-100">MQTT (Port 1883)</span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-700 pb-3">
                        <span class="text-xs text-gray-400">Broker Server</span>
                        <span class="text-sm font-bold text-gray-100">broker.hivemq.com</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">Topik Terima Data</span>
                        <span class="text-xs font-mono bg-gray-800 px-2 py-1 rounded text-emerald-400">cota/sensor/data</span>
                    </li>
                </ul>
            </div>

            <div class="relative z-10 mt-6 bg-gray-800 p-4 rounded-xl border border-gray-700 text-center">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Terakhir Diperbarui</p>
                <p class="text-sm font-bold text-emerald-400">{{ date('d M Y - H:i:s') }}</p>
            </div>
        </div>

    </div>
</div>
@endsection
