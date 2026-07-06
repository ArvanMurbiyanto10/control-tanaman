<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>COTA — Monitoring & Kontrol Tanaman Cerdas</title>
    <meta name="description" content="Sistem monitoring dan kontrol tanaman berbasis IoT dengan ESP32 dan MQTT">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'] },
                    colors: {
                        nature: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                        },
                        water: {
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                        },
                        sun: {
                            100: '#fef3c7',
                            500: '#f59e0b',
                        },
                        soil: {
                            100: '#fdf4ff',
                            500: '#d946ef',
                        },
                        darktext: '#1e293b',
                        softtext: '#64748b',
                    },
                    boxShadow: {
                        'soft': '0 8px 30px rgba(0, 0, 0, 0.04)',
                        'hover': '0 20px 40px rgba(0, 0, 0, 0.08)',
                        'glow-green': '0 0 20px rgba(34, 197, 94, 0.4)',
                        'glow-blue': '0 0 20px rgba(14, 165, 233, 0.4)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* === NEW WOW ANIMATIONS === */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes leafSway {

            0%,
            100% {
                transform: rotate(-5deg);
            }

            50% {
                transform: rotate(5deg);
            }
        }

        @keyframes waterRipple {
            0% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.3);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(14, 165, 233, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(14, 165, 233, 0);
            }
        }

        @keyframes pumpRipple {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            }

            70% {
                box-shadow: 0 0 0 20px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        @keyframes sunPulse {

            0%,
            100% {
                transform: scale(1);
                filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.4));
            }

            50% {
                transform: scale(1.1);
                filter: drop-shadow(0 0 15px rgba(245, 158, 11, 0.7));
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes countScale {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.15);
                color: #22c55e;
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes fluidFill {
            from {
                width: 0%;
                opacity: 0.5;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-100%);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-100%);
            }
        }

        /* === ANIMATION CLASSES === */
        .animate-entrance {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .stagger-1 {
            animation-delay: 0.1s;
        }

        .stagger-2 {
            animation-delay: 0.2s;
        }

        .stagger-3 {
            animation-delay: 0.3s;
        }

        .stagger-4 {
            animation-delay: 0.4s;
        }

        .stagger-5 {
            animation-delay: 0.5s;
        }

        .anim-leaf {
            animation: leafSway 4s ease-in-out infinite;
            transform-origin: bottom center;
            display: inline-block;
        }

        .anim-water {
            animation: waterRipple 2s linear infinite;
            border-radius: 50%;
        }

        .anim-pump-active {
            animation: pumpRipple 1.5s cubic-bezier(0.16, 1, 0.3, 1) infinite;
            border-radius: 20px;
        }

        .anim-sun {
            animation: sunPulse 3s ease-in-out infinite;
        }

        .anim-float {
            animation: float 4s ease-in-out infinite;
        }

        .anim-count {
            animation: countScale 0.6s ease-out;
            display: inline-block;
        }

        .anim-fluid {
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* === CARD & UI ELEMENTS === */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
            border-radius: 1.5rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: rgba(34, 197, 94, 0.2);
        }

        .gradient-text {
            background: linear-gradient(135deg, #22c55e, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* === SIDEBAR BASE === */
        #sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.02);
            border-right: 1px solid rgba(34, 197, 94, 0.1);
        }

        .sidebar-link {
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            color: #64748b;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: #dcfce7;
            border-radius: 12px;
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: -1;
        }

        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            width: 100%;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #16a34a;
            transform: translateX(4px);
            font-weight: 700;
        }

        /* === SCROLLBAR === */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* === TOAST === */
        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            pointer-events: none;
        }

        .toast {
            pointer-events: auto;
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 380px;
            background: white;
            border-left: 4px solid;
        }

        .toast-success {
            border-color: #22c55e;
            color: #16a34a;
        }

        .toast-error {
            border-color: #ef4444;
            color: #b91c1c;
        }

        .toast-info {
            border-color: #3b82f6;
            color: #1d4ed8;
        }

        .animate-toast-in {
            animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-toast-out {
            animation: slideUp 0.3s ease-in forwards;
        }

        /* ============================================
           COTA HAMBURGER MENU - MOBILE & TABLET (1024px)
           ============================================ */

        /* Hamburger Button - Kanan Atas */
        .cota-hamburger {
            display: none;
            /* Hidden by default on desktop */
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            padding: 10px;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
            transition: all 0.3s ease;
        }

        .cota-hamburger:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
        }

        .cota-hamburger span {
            display: block;
            width: 22px;
            height: 2.5px;
            background: white;
            margin: 4px auto;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        /* Hamburger Animation - X */
        .cota-hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .cota-hamburger.active span:nth-child(2) {
            opacity: 0;
            transform: translateX(-10px);
        }

        .cota-hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Overlay - TANPA BLUR */
        .cota-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Dark overlay without blur */
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .cota-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Responsive Logic for Tablet & Mobile */
        @media (max-width: 1024px) {

            /* Show Hamburger */
            .cota-hamburger {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            /* Sidebar Positioning - Hidden Right by default */
            #sidebar {
                position: fixed !important;
                top: 0 !important;
                right: 0 !important;
                left: auto !important;
                /* Remove left alignment */
                width: 280px !important;
                max-width: 85vw !important;
                height: 100vh !important;

                /* Initial State: Hidden to the Right */
                transform: translateX(100%) !important;
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;

                z-index: 1000 !important;
                border-radius: 0 !important;
                /* Remove rounded corners */
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.15) !important;
                /* Shadow on left side */
            }

            /* Sidebar Active State: Slide In */
            #sidebar.active {
                transform: translateX(0) !important;
            }

            /* Main Content Adjustment */
            main {
                margin-left: 0 !important;
                width: 100% !important;
            }

            header {
                padding-left: 1rem !important;
                padding-right: 5rem !important;
                /* Space for hamburger */
            }
        }

        /* Scrollbar styling for sidebar */
        #sidebar::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #22c55e;
            border-radius: 4px;
        }

        /* ============================================
   POWER BUTTON - POMPA CONTROL
   ============================================ */

        /* ============================================
   POWER BUTTON - POMPA CONTROL
   ============================================ */

        .power-button {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            position: relative;
            /* WARNA MERAH untuk OFF */
            background: linear-gradient(145deg, #ef4444, #dc2626);
            box-shadow:
                0 0 20px rgba(239, 68, 68, 0.4),
                0 0 40px rgba(239, 68, 68, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            outline: none;
        }

        .power-button:hover {
            transform: scale(1.05);
            box-shadow:
                0 0 30px rgba(239, 68, 68, 0.6),
                0 0 60px rgba(239, 68, 68, 0.4);
        }

        .power-button:active {
            transform: scale(0.95);
            box-shadow:
                0 0 15px rgba(239, 68, 68, 0.5),
                0 0 30px rgba(239, 68, 68, 0.3);
        }

        .power-button .power-icon {
            width: 40px;
            height: 40px;
            color: white;
            transition: all 0.3s ease;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.6));
        }

        /* Active State - Pompa ON (HIJAU) */
        .power-button.active {
            background: linear-gradient(145deg, #22c55e, #16a34a);
            box-shadow:
                0 0 30px rgba(34, 197, 94, 0.5),
                0 0 60px rgba(34, 197, 94, 0.3);
            animation: pulse-glow-green 2s ease-in-out infinite;
        }

        .power-button.active:hover {
            box-shadow:
                0 0 40px rgba(34, 197, 94, 0.7),
                0 0 80px rgba(34, 197, 94, 0.5);
        }

        .power-button.active .power-icon {
            filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.9));
        }

        @keyframes pulse-glow-green {

            0%,
            100% {
                box-shadow:
                    0 0 30px rgba(34, 197, 94, 0.5),
                    0 0 60px rgba(34, 197, 94, 0.3);
            }

            50% {
                box-shadow:
                    0 0 45px rgba(34, 197, 94, 0.7),
                    0 0 90px rgba(34, 197, 94, 0.5);
            }
        }

        /* Click Animation */
        .power-button.clicked {
            animation: button-click 0.3s ease;
        }

        @keyframes button-click {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="flex h-screen overflow-hidden">

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- COTA Hamburger Button - Kanan Atas -->
    <button class="cota-hamburger" id="cotaHamburger">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- COTA Overlay -->
    <div class="cota-overlay" id="cotaOverlay"></div>

    <!-- Sidebar (Hapus class rounded agar tidak bentrok) -->
    <aside id="sidebar" class="w-72 flex flex-col h-full z-30 overflow-y-auto shrink-0">
        <!-- Logo -->
        <div class="px-7 pt-10 pb-6 flex items-center gap-4">
            <div
                class="w-12 h-12 bg-gradient-to-br from-nature-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-glow-green anim-leaf">
                <i class="fa-solid fa-leaf text-white text-2xl drop-shadow-md"></i>
            </div>
            <div>
                <span class="font-extrabold text-2xl tracking-tight text-darktext">COTA</span>
                <p class="text-[10px] font-bold text-nature-600 tracking-[0.2em] uppercase">Control Tanaman</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-5 mt-6 space-y-2">
            <a href="/"
                class="sidebar-link {{ request()->is('/') ? 'active' : '' }} flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-semibold">
                <i class="fa-solid fa-house w-6 text-center text-lg"></i>
                <span>Dasbor Utama</span>
            </a>

            <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] mt-8 mb-3 px-4">Pantauan
                Lingkungan</div>

            <a href="/detail/kelembaban"
                class="sidebar-link {{ request()->is('detail/kelembaban') ? 'active' : '' }} flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-semibold">
                <i class="fa-solid fa-droplet w-6 text-center text-lg text-water-500 group-hover:anim-water"></i>
                <span>Kelembaban Air</span>
            </a>
            <a href="/detail/ph"
                class="sidebar-link {{ request()->is('detail/ph') ? 'active' : '' }} flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-semibold">
                <i class="fa-solid fa-flask w-6 text-center text-lg text-soil-500"></i>
                <span>Kadar pH Tanah</span>
            </a>
            <a href="/detail/suhu"
                class="sidebar-link {{ request()->is('detail/suhu') ? 'active' : '' }} flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-semibold">
                <i class="fa-solid fa-sun w-6 text-center text-lg text-sun-500 group-hover:anim-sun"></i>
                <span>Suhu Lingkungan</span>
            </a>

            <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] mt-8 mb-3 px-4">Sistem
                Pompa</div>

            <a href="/jadwal"
                class="sidebar-link {{ request()->is('jadwal') ? 'active' : '' }} flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-semibold">
                <i class="fa-solid fa-faucet-drip w-6 text-center text-lg text-amber-500"></i>
                <span>Kontrol & Jadwal</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="px-6 pb-8 mt-auto">
            <div
                class="bg-white rounded-2xl p-4 shadow-soft border border-gray-100 flex items-center gap-3 hover:shadow-hover transition-shadow">
                <div class="w-10 h-10 bg-nature-50 rounded-full flex items-center justify-center anim-water">
                    <i class="fa-solid fa-wifi text-nature-500"></i>
                </div>
                <div>
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="text-xs font-bold text-darktext">ESP32 Online</span>
                    </div>
                    <p class="text-[10px] text-softtext font-medium">Terkoneksi via MQTT</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main
        class="flex-1 flex flex-col overflow-y-auto bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        <!-- Top Header -->
        <header
            class="sticky top-0 z-20 px-6 md:px-10 py-5 flex justify-between items-center backdrop-blur-md bg-slate-50/80 border-b border-gray-200/50">
            <div class="flex items-center gap-3 text-darktext">
                <div
                    class="w-8 h-8 bg-white shadow-sm rounded-full flex items-center justify-center text-nature-500 border border-gray-100">
                    <i class="fa-solid fa-map-location-dot text-sm"></i>
                </div>
                <span class="font-bold text-sm">Blok Lahan Pertanian COTA</span>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 flex items-center gap-2">
                    <i class="fa-regular fa-clock text-nature-500"></i>
                    <span id="live-clock" class="text-sm font-extrabold text-darktext">{{ date('H:i') }}</span>
                    <span class="text-xs font-bold text-softtext">WIB</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 px-6 md:px-10 py-8 relative">
            <!-- Decorative background blobs for light theme -->
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-nature-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none anim-float">
            </div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-water-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none anim-float"
                style="animation-delay: 2s;"></div>

            <div class="relative z-10">
                @yield('content')
            </div>
        </div>
    </main>

    <script src="{{ asset('js/responsive.js') }}"></script>

    <script>
        // === LIVE CLOCK ===
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const el = document.getElementById('live-clock');
            if (el) el.textContent = h + ':' + m;
        }
        setInterval(updateClock, 1000);

        // === TOAST SYSTEM ===
        function showToast(message, type = 'success', duration = 3500) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const icons = { success: 'fa-circle-check text-nature-500', error: 'fa-circle-xmark text-red-500', info: 'fa-circle-info text-blue-500' };
            toast.className = `toast toast-${type} animate-toast-in`;
            toast.innerHTML = `<i class="fa-solid ${icons[type] || icons.info} text-xl"></i><span class="text-darktext">${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('animate-toast-in');
                toast.classList.add('animate-toast-out');
                setTimeout(() => toast.remove(), 350);
            }, duration);
        }

        // === AJAX HELPER ===
        function cotaFetch(url, options = {}) {
            const defaultHeaders = {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            };
            return fetch(url, { ...options, headers: { ...defaultHeaders, ...options.headers } });
        }

        // === COTA HAMBURGER MENU TOGGLE ===
        const cotaHamburger = document.getElementById('cotaHamburger');
        const cotaSidebar = document.getElementById('sidebar');
        const cotaOverlay = document.getElementById('cotaOverlay');

        function openCotaSidebar() {
            cotaHamburger.classList.add('active');
            cotaSidebar.classList.add('active');
            cotaOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeCotaSidebar() {
            cotaHamburger.classList.remove('active');
            cotaSidebar.classList.remove('active');
            cotaOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // FORCE HIDE sidebar on load if mobile/tablet to prevent "stuck open" bug
        function checkSidebarState() {
            if (window.innerWidth <= 1024 && cotaSidebar) {
                cotaSidebar.classList.remove('active');
                cotaHamburger.classList.remove('active');
                cotaOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        // Run check immediately
        checkSidebarState();

        if (cotaHamburger && cotaSidebar && cotaOverlay) {
            // Toggle sidebar
            cotaHamburger.addEventListener('click', function () {
                if (cotaSidebar.classList.contains('active')) {
                    closeCotaSidebar();
                } else {
                    openCotaSidebar();
                }
            });

            // Close with overlay click
            cotaOverlay.addEventListener('click', closeCotaSidebar);

            // Close when clicking menu link
            const menuItems = cotaSidebar.querySelectorAll('.sidebar-link');
            menuItems.forEach(item => {
                item.addEventListener('click', function () {
                    if (window.innerWidth <= 1024) {
                        setTimeout(closeCotaSidebar, 150);
                    }
                });
            });

            // Close on escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && cotaSidebar.classList.contains('active')) {
                    closeCotaSidebar();
                }
            });

            // Auto-close if resized to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) {
                    closeCotaSidebar();
                }
            });
        }
    </script>

    @stack('scripts')
</body>

</html>