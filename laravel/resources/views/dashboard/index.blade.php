@extends('layouts.app')

@section('content')

<!-- Hamburger Menu Button (Mobile) -->
<button class="menu-toggle" id="mobile-menu-btn">
    <i class="fa-solid fa-bars text-xl"></i>
</button>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="space-y-8 animate-entrance">
    <!-- Hero Section -->
    <div class="glass-card relative overflow-hidden bg-gradient-to-r from-white to-nature-50 border-0 shadow-soft">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-nature-100 rounded-full blur-3xl opacity-60 anim-float"></div>
        <div class="absolute right-20 -bottom-20 w-64 h-64 bg-water-100 rounded-full blur-3xl opacity-60 anim-float" style="animation-delay: 1.5s;"></div>
        
        <div class="relative z-10 p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-darktext mb-3">
                    Selamat Pagi, <br/>
                    <span class="gradient-text">Ladang Subur!</span> <span class="inline-block anim-sun text-yellow-400">☀️</span>
                </h1>
                <p class="text-softtext text-base md:text-lg font-medium max-w-xl">
                    Sistem cerdas memantau tanaman Anda setiap detik. Cuaca hari ini cerah, kelembaban ideal untuk pertumbuhan.
                </p>
                <div class="mt-6 flex gap-4">
                    <div class="bg-white/80 px-4 py-2 rounded-xl flex items-center gap-3 shadow-sm border border-gray-100">
                        <i class="fa-solid fa-leaf text-nature-500 anim-leaf"></i>
                        <span class="font-bold text-sm text-darktext">Fase Vegetatif</span>
                    </div>
                </div>
            </div>
            
            <!-- Pump Control Widget -->
            <div class="bg-white p-6 rounded-2xl shadow-hover border border-gray-100 w-full md:w-80 flex flex-col items-center group relative overflow-hidden">
                <!-- Decorative water drop -->
                <i class="fa-solid fa-droplet absolute -right-4 -bottom-4 text-7xl text-water-100 opacity-50 transform rotate-12 group-hover:scale-110 transition-transform"></i>

                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-6">Status Pompa Air</h3>
                
                @php
                    $isPumpOn = isset($latestData) && ($latestData->status_pompa == 1 || $latestData->status_pompa === true);
                @endphp

                <div id="pump-indicator-ring" class="w-28 h-28 rounded-[2rem] flex items-center justify-center mb-6 transition-all duration-500 {{ $isPumpOn ? 'bg-nature-50 anim-pump-active shadow-glow-green' : 'bg-slate-50' }}">
                    <div id="pump-indicator-icon" class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl transition-colors duration-500 {{ $isPumpOn ? 'bg-nature-500 text-white' : 'bg-slate-200 text-slate-400' }}">
                        <i class="fa-solid fa-faucet-drip {{ $isPumpOn ? 'anim-water' : '' }}"></i>
                    </div>
                </div>

                <!-- Power Button Baru -->
<div class="flex flex-col items-center gap-4 mb-6">
    <button id="pump-toggle" class="power-button {{ $isPumpOn ? 'active' : '' }}" type="button" {{ $isPumpOn ? 'disabled' : '' }}>
        <svg class="power-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
            <line x1="12" y1="2" x2="12" y2="12"></line>
        </svg>
        <span id="pump-countdown" class="absolute text-2xl font-black text-white"></span>
    </button>
    <div class="text-center">
        <span id="pump-status-text" class="text-lg font-bold {{ $isPumpOn ? 'text-green-500' : 'text-red-500' }}">
            {{ $isPumpOn ? 'MENYIRAM' : 'MATI' }}
        </span>
        <p class="text-xs text-gray-500 mt-1">Klik untuk mengubah status</p>
    </div>
</div>
                
                <div class="text-[10px] text-softtext font-semibold bg-slate-50 px-3 py-1 rounded-full">
                    Terakhir update: <span id="pump-last-update">{{ isset($latestData) ? \Carbon\Carbon::parse($latestData->created_at)->diffForHumans() : 'Belum ada data' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sensor Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Kelembaban -->
        <a href="/detail/kelembaban" class="glass-card p-6 block group stagger-1 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-water-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex justify-between items-start mb-6 relative z-10">
                <div>
                    <h3 class="text-softtext text-xs font-extrabold uppercase tracking-widest mb-1">Kelembaban</h3>
                    <div class="text-3xl font-black text-darktext flex items-baseline gap-1">
                        <span id="val-kelembaban" class="anim-count">{{ $latestData->kelembaban ?? 0 }}</span><span class="text-lg text-slate-400">%</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-water-50 text-water-500 flex items-center justify-center text-xl group-hover:bg-water-500 group-hover:text-white transition-colors duration-300">
                    <i class="fa-solid fa-droplet group-hover:anim-water"></i>
                </div>
            </div>
            <!-- Fluid Bar -->
            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden relative z-10">
                <div id="bar-kelembaban" class="h-full bg-water-500 anim-fluid" style="width: {{ $latestData->kelembaban ?? 0 }}%"></div>
            </div>
            @php
                $kelVal = $latestData->kelembaban ?? 0;
                $kelStatus = 'Kering';
                if($kelVal >= 60 && $kelVal <= 80) $kelStatus = 'Optimal';
                elseif($kelVal > 80) $kelStatus = 'Basah';
            @endphp
            <p class="text-xs text-softtext font-semibold mt-3 relative z-10">Kondisi: <span id="status-kelembaban" class="text-water-600 font-bold">{{ $kelStatus }}</span></p>
        </a>

        <!-- pH -->
        <a href="/detail/ph" class="glass-card p-6 block group stagger-2 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-soil-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex justify-between items-start mb-6 relative z-10">
                <div>
                    <h3 class="text-softtext text-xs font-extrabold uppercase tracking-widest mb-1">pH Tanah</h3>
                    <div class="text-3xl font-black text-darktext flex items-baseline gap-1">
                        <span id="val-ph" class="anim-count">{{ $latestData->ph_tanah ?? 0 }}</span><span class="text-lg text-slate-400">pH</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-soil-50 text-soil-500 flex items-center justify-center text-xl group-hover:bg-soil-500 group-hover:text-white transition-colors duration-300">
                    <i class="fa-solid fa-flask"></i>
                </div>
            </div>
            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden relative z-10">
                @php $phPercent = (($latestData->ph_tanah ?? 0) / 14) * 100; @endphp
                <div id="bar-ph" class="h-full bg-soil-500 anim-fluid" style="width: {{ $phPercent }}%"></div>
            </div>
            @php
                $phVal = $latestData->ph_tanah ?? 7;
                $phStatus = 'Normal';
                if($phVal < 6.0) $phStatus = 'Asam';
                elseif($phVal > 7.5) $phStatus = 'Basa';
            @endphp
            <p class="text-xs text-softtext font-semibold mt-3 relative z-10">Kondisi: <span id="status-ph" class="text-soil-600 font-bold">{{ $phStatus }}</span></p>
        </a>

        <!-- Suhu -->
        <a href="/detail/suhu" class="glass-card p-6 block group stagger-3 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-sun-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex justify-between items-start mb-6 relative z-10">
                <div>
                    <h3 class="text-softtext text-xs font-extrabold uppercase tracking-widest mb-1">Suhu Udara</h3>
                    <div class="text-3xl font-black text-darktext flex items-baseline gap-1">
                        <span id="val-suhu" class="anim-count">{{ $latestData->suhu_tanah ?? 0 }}</span><span class="text-lg text-slate-400">°C</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-sun-50 text-sun-500 flex items-center justify-center text-xl group-hover:bg-sun-500 group-hover:text-white transition-colors duration-300">
                    <i class="fa-solid fa-temperature-half group-hover:anim-sun"></i>
                </div>
            </div>
            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden relative z-10">
                @php $suhuPercent = (($latestData->suhu_tanah ?? 0) / 50) * 100; @endphp
                <div id="bar-suhu" class="h-full bg-sun-500 anim-fluid" style="width: {{ $suhuPercent }}%"></div>
            </div>
            @php
                $suhuVal = $latestData->suhu_tanah ?? 28;
                $suhuStatus = 'Ideal';
                if($suhuVal < 20) $suhuStatus = 'Dingin';
                elseif($suhuVal > 35) $suhuStatus = 'Panas';
            @endphp
            <p class="text-xs text-softtext font-semibold mt-3 relative z-10">Kondisi: <span id="status-suhu" class="text-sun-600 font-bold">{{ $suhuStatus }}</span></p>
        </a>
    </div>

    <!-- Log Riwayat -->
    <div class="glass-card stagger-4">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white/50 rounded-t-[1.5rem]">
            <h2 class="text-sm font-extrabold text-darktext uppercase tracking-widest">Aktivitas Terbaru</h2>
            <div class="flex gap-2">
                <span class="w-2 h-2 rounded-full bg-nature-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-softtext uppercase">Live Update</span>
            </div>
        </div>
        <div class="p-6">
            <div class="relative overflow-x-auto rounded-xl border border-gray-100 shadow-sm">
                <table class="w-full text-sm text-left text-softtext">
                    <thead class="text-xs text-darktext uppercase bg-slate-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-extrabold tracking-wider">Waktu</th>
                            <th scope="col" class="px-6 py-4 font-extrabold tracking-wider">Suhu</th>
                            <th scope="col" class="px-6 py-4 font-extrabold tracking-wider">Kelembaban</th>
                            <th scope="col" class="px-6 py-4 font-extrabold tracking-wider">pH</th>
                            <th scope="col" class="px-6 py-4 font-extrabold tracking-wider">Pompa</th>
                        </tr>
                    </thead>
                    <tbody id="log-table-body">
                        @forelse($historyData as $item)
                        <tr class="bg-white border-b border-gray-50 hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-darktext whitespace-nowrap">
                                <i class="fa-regular fa-clock text-slate-400 mr-2"></i>
                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-sun-50 text-sun-600 font-bold px-2.5 py-1 rounded-md">{{ $item->suhu_tanah }}°C</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-water-50 text-water-600 font-bold px-2.5 py-1 rounded-md">{{ $item->kelembaban }}%</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-soil-50 text-soil-600 font-bold px-2.5 py-1 rounded-md">{{ $item->ph_tanah }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status_pompa == 1 || $item->status_pompa === true)
                                    <span class="flex items-center gap-1.5 text-nature-600 font-bold text-xs bg-nature-50 px-3 py-1.5 rounded-full inline-flex border border-nature-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-nature-500 anim-sun"></span> Menyala
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-slate-500 font-bold text-xs bg-slate-100 px-3 py-1.5 rounded-full inline-flex">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Mati
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center font-semibold text-slate-400">
                                Belum ada riwayat data aktivitas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // === POWER BUTTON POMPA CONTROL ===
const pumpToggle = document.getElementById('pump-toggle');
const pumpStatusText = document.getElementById('pump-status-text');
const pumpCountdown = document.getElementById('pump-countdown');
let wateringInterval = null;
const WATERING_DURATION = 10; // 10 seconds countdown

pumpToggle.addEventListener('click', function() {
    const btn = this;
    
    // Add click animation
    btn.classList.add('clicked');
    setTimeout(() => btn.classList.remove('clicked'), 300);
    
    // Start watering sequence
    startWateringSequence();
});

function startWateringSequence() {
    // 1. Immediately update UI to watering state (instant response)
    showToast('Menyiram telah dimulai.', 'success');
    
    pumpToggle.classList.add('watering');
    pumpToggle.disabled = true;
    
    // Update indicator ring and icon above
    const ring = document.getElementById('pump-indicator-ring');
    const icon = document.getElementById('pump-indicator-icon');
    const iconI = icon.querySelector('i');
    ring.className = "w-28 h-28 rounded-[2rem] flex items-center justify-center mb-6 transition-all duration-500 bg-nature-50 anim-pump-active shadow-glow-green";
    icon.className = "w-16 h-16 rounded-2xl flex items-center justify-center text-3xl transition-colors duration-500 bg-nature-500 text-white";
    iconI.classList.add('anim-water');

    let timeLeft = WATERING_DURATION;
    pumpCountdown.textContent = timeLeft;
    
    pumpStatusText.textContent = `MENYIRAM (${timeLeft}s)`;
    pumpStatusText.className = 'text-lg font-bold text-green-500';
    
    // 2. Trigger API call to start pump
    cotaFetch('/api/pompa/toggle', {
        method: 'POST',
        body: JSON.stringify({ action: 'on' })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            showToast('Gagal menyalakan pompa: ' + (data.message || ''), 'error');
            resetPumpUI();
        }
    })
    .catch(err => {
        console.error(err);
        showToast('Terjadi kesalahan jaringan saat menyalakan pompa.', 'error');
        resetPumpUI();
    });

    // 3. Start countdown timer
    if (wateringInterval) clearInterval(wateringInterval);
    wateringInterval = setInterval(() => {
        timeLeft--;
        if (timeLeft > 0) {
            pumpCountdown.textContent = timeLeft;
            pumpStatusText.textContent = `MENYIRAM (${timeLeft}s)`;
        } else {
            clearInterval(wateringInterval);
            wateringInterval = null;
            
            // Countdown finished: turn off pump
            showToast('Menyiram telah selesai.', 'success');
            
            cotaFetch('/api/pompa/toggle', {
                method: 'POST',
                body: JSON.stringify({ action: 'off' })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    showToast('Gagal mematikan pompa: ' + (data.message || ''), 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Terjadi kesalahan jaringan saat mematikan pompa.', 'error');
            });
            
            resetPumpUI();
        }
    }, 1000);
}

function resetPumpUI() {
    if (wateringInterval) {
        clearInterval(wateringInterval);
        wateringInterval = null;
    }
    
    pumpToggle.classList.remove('watering', 'active');
    pumpToggle.disabled = false;
    pumpCountdown.textContent = '';
    
    // Update indicator ring and icon above
    const ring = document.getElementById('pump-indicator-ring');
    const icon = document.getElementById('pump-indicator-icon');
    const iconI = icon.querySelector('i');
    ring.className = "w-28 h-28 rounded-[2rem] flex items-center justify-center mb-6 transition-all duration-500 bg-slate-50";
    icon.className = "w-16 h-16 rounded-2xl flex items-center justify-center text-3xl transition-colors duration-500 bg-slate-200 text-slate-400";
    iconI.classList.remove('anim-water');
    
    // Status text back to MATI
    pumpStatusText.textContent = 'MATI';
    pumpStatusText.className = 'text-lg font-bold text-red-500';
    
    // Reset last update text
    document.getElementById('pump-last-update').textContent = 'Baru saja';
}

function updatePumpUI(isOn) {
    const ring = document.getElementById('pump-indicator-ring');
    const icon = document.getElementById('pump-indicator-icon');
    const iconI = icon.querySelector('i');
    const btn = document.getElementById('pump-toggle');
    const statusText = document.getElementById('pump-status-text');
    
    // Update indicator ring (icon di atas)
    if (isOn) {
        ring.className = "w-28 h-28 rounded-[2rem] flex items-center justify-center mb-6 transition-all duration-500 bg-nature-50 anim-pump-active shadow-glow-green";
        icon.className = "w-16 h-16 rounded-2xl flex items-center justify-center text-3xl transition-colors duration-500 bg-nature-500 text-white";
        iconI.classList.add('anim-water');
    } else {
        ring.className = "w-28 h-28 rounded-[2rem] flex items-center justify-center mb-6 transition-all duration-500 bg-slate-50";
        icon.className = "w-16 h-16 rounded-2xl flex items-center justify-center text-3xl transition-colors duration-500 bg-slate-200 text-slate-400";
        iconI.classList.remove('anim-water');
    }
    
    // Update power button
    if (btn) {
        if (isOn) {
            btn.classList.add('active');
            btn.disabled = true;
        } else {
            btn.classList.remove('active', 'watering');
            btn.disabled = false;
            if (pumpCountdown) pumpCountdown.textContent = '';
        }
    }
    
    // Update status text (MENYIRAM / MATI)
    if (statusText) {
        if (isOn) {
            statusText.textContent = 'MENYIRAM';
            statusText.className = 'text-lg font-bold text-green-500';
        } else {
            statusText.textContent = 'MATI';
            statusText.className = 'text-lg font-bold text-red-500';
        }
    }
    
    document.getElementById('pump-last-update').textContent = 'Baru saja';
}

    // === LIVE DATA POLLING (every 5 seconds) ===
    function fetchLatestData() {
        cotaFetch('/api/sensor/latest')
        .then(res => res.json())
        .then(data => {
            if (data) {
                // Update Cards (trigger animation by re-assigning value)
                updateAnimatedValue('val-kelembaban', data.kelembaban);
                document.getElementById('bar-kelembaban').style.width = data.kelembaban + '%';
                
                // Kelembaban status text
                let kelStatus = 'Kering';
                if(data.kelembaban >= 60 && data.kelembaban <= 80) kelStatus = 'Optimal';
                else if(data.kelembaban > 80) kelStatus = 'Basah';
                document.getElementById('status-kelembaban').textContent = kelStatus;

                updateAnimatedValue('val-ph', data.ph_tanah);
                document.getElementById('bar-ph').style.width = ((data.ph_tanah / 14) * 100) + '%';
                
                let phStatus = 'Normal';
                if(data.ph_tanah < 6.0) phStatus = 'Asam';
                else if(data.ph_tanah > 7.5) phStatus = 'Basa';
                document.getElementById('status-ph').textContent = phStatus;

                updateAnimatedValue('val-suhu', data.suhu_tanah);
                document.getElementById('bar-suhu').style.width = ((data.suhu_tanah / 50) * 100) + '%';
                
                let suhuStatus = 'Ideal';
                if(data.suhu_tanah < 20) suhuStatus = 'Dingin';
                else if(data.suhu_tanah > 35) suhuStatus = 'Panas';
                document.getElementById('status-suhu').textContent = suhuStatus;

                // Update Power Button silently if it changed externally
const isPumpOn = (data.status_pompa == 1 || data.status_pompa === true);
const btnIsOn = pumpToggle.classList.contains('active') || pumpToggle.classList.contains('watering');
if (!pumpToggle.disabled && btnIsOn !== isPumpOn) {
    updatePumpUI(isPumpOn);
}
            }
        });
    }

    function updateAnimatedValue(id, newValue) {
        const el = document.getElementById(id);
        if (el && el.textContent != newValue) {
            el.textContent = newValue;
            // Retrigger animation
            el.classList.remove('anim-count');
            void el.offsetWidth; // trigger reflow
            el.classList.add('anim-count');
        }
    }

    setInterval(fetchLatestData, 5000);
</script>
@endpush