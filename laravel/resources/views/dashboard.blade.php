<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard COTA - Kontrol Tanaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkbg: '#1c1d21',
                        creamcard: '#f4ebd8',
                        iconblue: '#3b4368',
                        iconred: '#c94f3a',
                        icongreen: '#2d7a5b',
                        iconorange: '#d97746',
                        btnblue: '#2c3e50'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-darkbg text-gray-200 font-sans h-screen flex overflow-hidden">

    <aside class="w-64 bg-creamcard text-gray-800 flex flex-col h-full rounded-r-3xl shadow-2xl z-10 transition-all">
        <div class="px-6 pt-8 pb-6 flex items-center gap-3 cursor-pointer hover:opacity-80" onclick="alert('Menu Profil & Pengaturan Perangkat')">
            <i class="fa-solid fa-leaf text-icongreen text-2xl animate-pulse"></i>
            <span class="font-bold text-lg tracking-wide">Monitoring COTA</span>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-gray-200/80 rounded-xl font-semibold text-gray-900 shadow-sm transition-transform transform hover:scale-105 active:scale-95">
                <i class="fa-solid fa-desktop w-5"></i> Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-200/60 hover:text-gray-900 rounded-xl transition-all transform hover:scale-105 active:scale-95" onclick="alert('Membuka menu Pengaturan Ambang Batas & Jadwal')">
                <i class="fa-solid fa-calendar-alt w-5"></i> Jadwal Penyiraman
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-200/60 hover:text-gray-900 rounded-xl transition-all transform hover:scale-105 active:scale-95">
                <i class="fa-solid fa-clock-rotate-left w-5"></i> Riwayat Data
            </a>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col p-8 overflow-y-auto">

        <header class="flex justify-between items-center mb-8 text-sm text-gray-400">
            <div class="flex items-center gap-2">
                <span>... / dashboard / </span>
                <span class="font-semibold text-gray-200 cursor-pointer hover:text-white">Dashboard COTA</span>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 px-3 py-1 bg-green-500/20 text-green-400 rounded-full cursor-pointer hover:bg-green-500/30 transition">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    NodeMCU Online
                </div>
                <button class="relative hover:text-white transition transform hover:scale-110 active:scale-90" onclick="alert('Tidak ada peringatan. Kondisi tanaman optimal.')">
                    <i class="fa-solid fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 w-2.5 h-2.5 rounded-full border border-darkbg"></span>
                </button>
                <a href="#" class="flex items-center gap-2 hover:text-white transition group" onclick="alert('Membuka menu Logout')">
                    <img src="https://ui-avatars.com/api/?name=Arvan+Murbiyanto&background=2c3e50&color=fff" alt="User" class="w-8 h-8 rounded-full group-hover:ring-2 ring-gray-400 transition">
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"><div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-auto">
            <div class="bg-creamcard rounded-3xl p-5 flex justify-between items-center shadow-lg transition-all duration-300 transform hover:-translate-y-2 hover:shadow-xl cursor-pointer" onclick="alert('Lihat detail log Kelembaban Tanah')">
                <div>
                    <p class="text-gray-600 text-xs font-bold uppercase tracking-wider mb-1">Kelembaban</p>
                    <h3 class="text-gray-900 text-2xl font-extrabold">{{ $latestData->kelembaban ?? '45' }} %</h3>
                </div>
                <div class="bg-iconblue w-12 h-12 rounded-full flex items-center justify-center text-white shadow-inner group-hover:animate-bounce">
                    <i class="fa-solid fa-droplet text-xl"></i>
                </div>
            </div>

            <div class="bg-creamcard rounded-3xl p-5 flex justify-between items-center shadow-lg transition-all duration-300 transform hover:-translate-y-2 hover:shadow-xl cursor-pointer" onclick="alert('Lihat detail log pH Tanah')">
                <div>
                    <p class="text-gray-600 text-xs font-bold uppercase tracking-wider mb-1">pH Tanah</p>
                    <h3 class="text-gray-900 text-2xl font-extrabold">{{ $latestData->ph_tanah ?? '6.5' }}</h3>
                </div>
                <div class="bg-iconred w-12 h-12 rounded-full flex items-center justify-center text-white shadow-inner">
                    <i class="fa-solid fa-flask text-xl"></i>
                </div>
            </div>

            <div class="bg-creamcard rounded-3xl p-5 flex justify-between items-center shadow-lg transition-all duration-300 transform hover:-translate-y-2 hover:shadow-xl cursor-pointer">
                <div>
                    <p class="text-gray-600 text-xs font-bold uppercase tracking-wider mb-1">Suhu Tanah</p>
                    <h3 class="text-gray-900 text-2xl font-extrabold">{{ $latestData->suhu_tanah ?? '28.5' }} °C</h3>
                </div>
                <div class="bg-icongreen w-12 h-12 rounded-full flex items-center justify-center text-white shadow-inner">
                    <i class="fa-solid fa-temperature-half text-xl"></i>
                </div>
            </div>

            <div class="bg-creamcard rounded-3xl p-5 shadow-lg border-2 border-transparent hover:border-iconorange transition-all duration-300">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-gray-600 text-xs font-bold uppercase tracking-wider">Pompa Air</p>
                    <button id="modeToggle" class="text-[10px] font-bold px-2 py-0.5 rounded border border-gray-400 text-gray-500 hover:bg-gray-200 transition" onclick="toggleMode()">
                        MODE: MANUAL
                    </button>
                </div>
                <div class="flex justify-between items-center">
                    <button id="btnSiram" class="bg-btnblue hover:bg-blue-900 active:scale-95 transition-all text-white px-5 py-2 rounded-full text-xs font-bold shadow-md flex items-center gap-2" onclick="siramAir()">
                        <i class="fa-solid fa-power-off"></i> <span>Siram Air</span>
                    </button>
                    <div class="bg-iconorange w-10 h-10 rounded-full flex items-center justify-center text-white shadow-inner">
                        <i id="pumpIcon" class="fa-solid fa-seedling text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-auto">

            <div class="lg:col-span-7 bg-creamcard rounded-3xl p-6 shadow-lg flex flex-col transition-all duration-300 hover:shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-gray-900 font-bold text-lg mb-1 cursor-pointer">Grafik PH & Suhu</h2>
                        <p class="text-gray-500 text-xs font-semibold">Tren pergerakan hara tanah</p>
                    </div>
                    <div class="flex gap-1 bg-gray-200 p-1 rounded-lg">
                        <button class="px-3 py-1 text-xs font-bold rounded bg-white shadow-sm text-gray-800 transition">7 Hari</button>
                        <button class="px-3 py-1 text-xs font-bold rounded text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition" onclick="alert('Memuat data 30 hari...')">30 Hari</button>
                    </div>
                </div>
                <div class="relative flex-1 w-full cursor-crosshair" style="min-height: 250px;">
                    <canvas id="chartSuhuPh"></canvas>
                </div>
            </div>

            <div class="lg:col-span-5 bg-creamcard rounded-3xl p-6 shadow-lg flex flex-col transition-all duration-300 hover:shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-gray-900 font-bold text-lg mb-1">Grafik Kelembaban</h2>
                        <p class="text-gray-500 text-xs font-semibold">Pemantauan volume air</p>
                    </div>
                    <button class="text-gray-400 hover:text-iconblue transition" onclick="alert('Unduh data format CSV')">
                        <i class="fa-solid fa-download"></i>
                    </button>
                </div>
                <div class="relative flex-1 w-full cursor-crosshair" style="min-height: 250px;">
                    <canvas id="chartKelembaban"></canvas>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Logika Toggle Mode Auto/Manual
        let isAuto = false;
        function toggleMode() {
            const modeBtn = document.getElementById('modeToggle');
            const siramBtn = document.getElementById('btnSiram');
            isAuto = !isAuto;

            if(isAuto) {
                modeBtn.innerText = "MODE: AUTO";
                modeBtn.classList.add('bg-green-100', 'text-green-700', 'border-green-400');
                modeBtn.classList.remove('text-gray-500', 'border-gray-400');

                siramBtn.classList.add('opacity-50', 'cursor-not-allowed');
                siramBtn.querySelector('span').innerText = 'Sensor Aktif';
            } else {
                modeBtn.innerText = "MODE: MANUAL";
                modeBtn.classList.remove('bg-green-100', 'text-green-700', 'border-green-400');
                modeBtn.classList.add('text-gray-500', 'border-gray-400');

                siramBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                siramBtn.querySelector('span').innerText = 'Siram Air';
            }
        }

        // Logika Tombol Siram Air
        function siramAir() {
            if(isAuto) return; // Cegah klik jika mode auto

            const btn = document.getElementById('btnSiram');
            const icon = document.getElementById('pumpIcon');
            const originalText = btn.querySelector('span').innerText;

            // Efek Loading menyiram
            btn.classList.add('bg-blue-600');
            btn.querySelector('span').innerText = "Menyiram...";
            icon.classList.remove('fa-seedling');
            icon.classList.add('fa-droplet', 'animate-bounce');

            // Simulasi request selesai setelah 2 detik
            setTimeout(() => {
                btn.classList.remove('bg-blue-600');
                btn.querySelector('span').innerText = originalText;
                icon.classList.add('fa-seedling');
                icon.classList.remove('fa-droplet', 'animate-bounce');
                alert("Perintah siram berhasil dikirim ke NodeMCU!");
            }, 2000);
        }

        // --- Konfigurasi Chart.js (Tetap sama seperti sebelumnya) ---
        Chart.defaults.font.family = 'sans-serif';
        Chart.defaults.color = '#718096';

        const ctx1 = document.getElementById('chartSuhuPh').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['1 Sep', '2 Sep', '3 Sep', '4 Sep', '5 Sep', '6 Sep', '7 Sep'],
                datasets: [
                    { label: 'pH', data: [6.0, 6.0, 6.0, 6.0, 6.0, 6.2, 7.5], borderColor: '#2c3e50', backgroundColor: '#2c3e50', tension: 0.4 },
                    { label: 'Suhu', data: [25, 25, 25, 25, 25, 26, 32], borderColor: '#c94f3a', backgroundColor: 'rgba(201, 79, 58, 0.1)', tension: 0.4, fill: true }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { position: 'top' } }, scales: { y: { display: false }, x: { grid: { display: false } } } }
        });

        const ctx2 = document.getElementById('chartKelembaban').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['1 Sep', '2 Sep', '3 Sep', '4 Sep', '5 Sep', '6 Sep', '7 Sep'],
                datasets: [{ label: 'Kelembaban (%)', data: [40, 40, 39, 40, 40, 39, 85], borderColor: '#3b4368', backgroundColor: 'rgba(59, 67, 104, 0.15)', tension: 0.4, fill: true }]
            },
            options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { position: 'top' } }, scales: { x: { grid: { display: false } } } }
        });
    </script>
</body>
</html>
