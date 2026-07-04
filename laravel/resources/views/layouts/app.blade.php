<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring COTA - Kontrol Tanaman</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkbg: '#1c1d21', creamcard: '#f4ebd8',
                        iconblue: '#3b4368', iconred: '#c94f3a',
                        icongreen: '#2d7a5b', iconorange: '#d97746', btnblue: '#2c3e50'
                    }
                }
            }
        }
        
    </script>
</head>

<body class="bg-darkbg text-gray-200 font-sans h-screen flex overflow-hidden">

    <aside class="w-64 bg-creamcard text-gray-800 flex flex-col h-full rounded-r-3xl shadow-2xl z-10 overflow-y-auto">
        <div class="px-6 pt-8 pb-6 flex items-center gap-3">
            <i class="fa-solid fa-leaf text-icongreen text-2xl"></i>
            <span class="font-bold text-lg tracking-wide">Monitoring COTA</span>
        </div>

        <nav class="flex-1 px-4 space-y-1 mt-2 mb-6">
            <a href="/"
                class="flex items-center gap-3 px-4 py-3 {{ request()->is('/') ? 'bg-gray-200/80 font-bold text-gray-900 shadow-sm' : 'text-gray-600 hover:bg-gray-200/50' }} rounded-xl transition-all">
                <i class="fa-solid fa-desktop w-5"></i> Dasbor Utama
            </a>

            <div class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mt-6 mb-2 px-4">Detail Sensor
            </div>
            <a href="/detail/kelembaban"
                class="flex items-center gap-3 px-4 py-3 {{ request()->is('detail/kelembaban') ? 'bg-gray-200/80 font-bold text-gray-900' : 'text-gray-600 hover:bg-gray-200/50' }} rounded-xl transition-all">
                <i class="fa-solid fa-droplet w-5 text-iconblue"></i> Kelembaban
            </a>
            <a href="/detail/ph"
                class="flex items-center gap-3 px-4 py-3 {{ request()->is('detail/ph') ? 'bg-gray-200/80 font-bold text-gray-900' : 'text-gray-600 hover:bg-gray-200/50' }} rounded-xl transition-all">
                <i class="fa-solid fa-flask w-5 text-iconred"></i> pH Tanah
            </a>
            <a href="/detail/suhu"
                class="flex items-center gap-3 px-4 py-3 {{ request()->is('detail/suhu') ? 'bg-gray-200/80 font-bold text-gray-900' : 'text-gray-600 hover:bg-gray-200/50' }} rounded-xl transition-all">
                <i class="fa-solid fa-temperature-half w-5 text-icongreen"></i> Suhu Tanah
            </a>

            <div class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mt-6 mb-2 px-4">Kontrol Lahan
            </div>
            <a href="/jadwal"
                class="flex items-center gap-3 px-4 py-3 {{ request()->is('jadwal') ? 'bg-gray-200/80 font-bold text-gray-900' : 'text-gray-600 hover:bg-gray-200/50' }} rounded-xl transition-all">
                <i class="fa-solid fa-calendar-alt w-5 text-iconorange"></i> Jadwal Penyiraman
            </a>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col p-4 md:p-6 lg:p-8 overflow-y-auto">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 text-sm text-gray-400 gap-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-location-dot"></i>
                <span class="font-semibold text-gray-200">Purwokerto - Blok Lahan A</span>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 px-3 py-1 bg-green-500/20 text-green-400 rounded-full">
                    <span class="relative flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    Sensor Online
                </div>
            </div>
        </header>

        @yield('content')
    </main>
<script src="{{ asset('js/responsive.js') }}"></script>
    @stack('scripts')
</body>

</html>