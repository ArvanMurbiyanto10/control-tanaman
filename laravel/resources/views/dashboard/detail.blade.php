@extends('layouts.app')

@section('content')

<!-- Hamburger Menu Button (Mobile) -->
<button class="menu-toggle" id="mobile-menu-btn">
    <i class="fa-solid fa-bars text-xl"></i>
</button>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="space-y-8">
    <!-- Top Navigation -->
    <div class="flex items-center gap-4 mb-2 animate-entrance">
        <a href="/" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-darktext transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-extrabold text-darktext">Analisis Detail</h1>
    </div>

    <!-- Parameter Detail Card (Dynamic Partial) -->
    <div id="parameter-detail-container">
        @if($jenis == 'kelembaban')
            @include('dashboard.partials.kelembaban')
        @elseif($jenis == 'ph')
            @include('dashboard.partials.ph-tanah')
        @elseif($jenis == 'suhu')
            @include('dashboard.partials.suhu-tanah')
        @endif
    </div>

    <!-- Chart Card -->
    <div class="glass-card p-6 md:p-8 animate-entrance stagger-2">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-darktext flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-nature-500"></i> Grafik Tren Data
                </h3>
                <p class="text-xs font-semibold text-softtext">Pergerakan {{ ucfirst($jenis) }} dalam 24 Jam Terakhir</p>
            </div>
            
            <div class="bg-slate-100 p-1 rounded-lg flex text-xs font-bold w-full md:w-auto">
                <button class="flex-1 md:flex-none px-4 py-2 rounded-md bg-white text-darktext shadow-sm border border-gray-200">Hari Ini</button>
                <button class="flex-1 md:flex-none px-4 py-2 rounded-md text-slate-500 hover:text-darktext transition-colors">7 Hari</button>
                <button class="flex-1 md:flex-none px-4 py-2 rounded-md text-slate-500 hover:text-darktext transition-colors">Bulan</button>
            </div>
        </div>

        <div class="relative h-[300px] md:h-[400px] w-full">
            <canvas id="historyChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data Preparation
    const rawData = @json($historyData);
    const chartType = '{{ $jenis }}';
    
    // Sort chronological for chart
    rawData.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    
    const labels = rawData.map(item => {
        const d = new Date(item.created_at);
        return String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
    });
    
    // Map parameter to database column name
    const dbField = chartType === 'ph' ? 'ph_tanah' : (chartType === 'suhu' ? 'suhu_tanah' : 'kelembaban');
    const dataPoints = rawData.map(item => item[dbField]);

    // Theme Colors configuration
    let borderColor = '#22c55e'; // default nature green
    let bgColor = 'rgba(34, 197, 94, 0.1)';
    let gradientStart = 'rgba(34, 197, 94, 0.4)';
    let gradientEnd = 'rgba(34, 197, 94, 0.0)';

    if(chartType === 'kelembaban') {
        borderColor = '#0ea5e9'; // sky blue
        gradientStart = 'rgba(14, 165, 233, 0.4)';
        gradientEnd = 'rgba(14, 165, 233, 0.0)';
    } else if (chartType === 'ph') {
        borderColor = '#d946ef'; // fuchsia
        gradientStart = 'rgba(217, 70, 239, 0.4)';
        gradientEnd = 'rgba(217, 70, 239, 0.0)';
    } else if (chartType === 'suhu') {
        borderColor = '#f59e0b'; // amber
        gradientStart = 'rgba(245, 158, 11, 0.4)';
        gradientEnd = 'rgba(245, 158, 11, 0.0)';
    }

    const ctx = document.getElementById('historyChart').getContext('2d');
    
    // Create gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, gradientStart);
    gradient.addColorStop(1, gradientEnd);

    Chart.defaults.color = '#94a3b8'; // Slate 400 for axis text
    Chart.defaults.font.family = '"Plus Jakarta Sans", sans-serif';
    Chart.defaults.font.weight = '600';

    const historyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: chartType.toUpperCase(),
                data: dataPoints,
                borderColor: borderColor,
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: borderColor,
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Smooth curves
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            let unit = chartType === 'suhu' ? '°C' : (chartType === 'kelembaban' ? '%' : ' pH');
                            return context.parsed.y + unit;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: chartType !== 'ph',
                    grid: {
                        color: 'rgba(226, 232, 240, 0.6)', // Light gray grid
                        drawBorder: false,
                    },
                    border: { dash: [5, 5] }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false,
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });

    // === LIVE DATA POLLING (every 5 seconds) ===
    function fetchLatestData() {
        cotaFetch('/api/sensor/latest')
        .then(res => res.json())
        .then(data => {
            if (data) {
                // Update Main Detail Value dynamically
                const valEl = document.getElementById(`detail-val-${chartType}`);
                const barEl = document.getElementById(`detail-bar-${chartType}`);
                const pinEl = document.getElementById(`detail-pin-${chartType}`);
                const statusEl = document.getElementById(`detail-status-${chartType}`);
                
                if (valEl) {
                    let valInner = '';
                    if(chartType === 'kelembaban') {
                        valInner = `${data.kelembaban}<span class="text-3xl text-slate-400 ml-1">%</span>`;
                        if(barEl) barEl.style.width = data.kelembaban + '%';
                        
                        let kelStatus = 'Kering';
                        if(data.kelembaban >= 60 && data.kelembaban <= 80) kelStatus = 'Optimal';
                        else if(data.kelembaban > 80) kelStatus = 'Basah';
                        if(statusEl) statusEl.textContent = 'Kondisi: ' + kelStatus;
                        
                    } else if (chartType === 'ph') {
                        valInner = `${data.ph_tanah}<span class="text-3xl text-slate-400 ml-2">pH</span>`;
                        if(pinEl) pinEl.style.left = `calc(${((data.ph_tanah / 14) * 100)}% - 4px)`;
                        
                        let phStatus = 'Normal';
                        if(data.ph_tanah < 6.0) phStatus = 'Terlalu Asam';
                        else if(data.ph_tanah > 7.5) phStatus = 'Terlalu Basa';
                        if(statusEl) statusEl.textContent = 'Kondisi: ' + phStatus;
                        
                    } else if (chartType === 'suhu') {
                        valInner = `${data.suhu_tanah}<span class="text-3xl text-slate-400 ml-1">°C</span>`;
                        if(barEl) barEl.style.width = ((data.suhu_tanah / 50) * 100) + '%';
                        
                        let suhuStatus = 'Hangat/Ideal';
                        if(data.suhu_tanah < 20) suhuStatus = 'Dingin';
                        else if(data.suhu_tanah > 35) suhuStatus = 'Panas';
                        if(statusEl) statusEl.textContent = 'Kondisi: ' + suhuStatus;
                    }
                    
                    if(valEl.innerHTML !== valInner) {
                        valEl.innerHTML = valInner;
                    }
                }
            }
        });
    }

    setInterval(fetchLatestData, 5000);
</script>
@endpush