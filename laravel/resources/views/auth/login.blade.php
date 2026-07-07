<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - COTA Smart Farming</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<!-- Tambahkan background-image dan atur properti cover/center -->

<body class="h-screen flex items-center justify-center p-4 relative overflow-hidden bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ asset('images/gambar.jpg') }}');">

    <!-- Lapisan Gelap Transparan (Overlay) agar background tidak menutupi kotak form -->
    <div class="absolute inset-0 bg-emerald-950/60 z-0"></div>

    <!-- Dekorasi Background -->
    <div class="absolute -left-20 -top-20 text-emerald-100/20 transform -rotate-12 pointer-events-none z-0">
        <i class="fa-solid fa-leaf text-[15rem]"></i>
    </div>

    <!-- Kotak Form Login -->
    <div
        class="bg-white/80 backdrop-blur-xl rounded-3xl p-8 shadow-2xl w-full max-w-md border border-white/50 relative z-10">

        <div class="text-center mb-8">
            <div
                class="bg-gradient-to-br from-emerald-400 to-emerald-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white text-3xl shadow-lg shadow-emerald-200 transform hover:rotate-6 transition-all">
                <i class="fa-solid fa-seedling"></i>
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Admin COTA</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Sistem Monitoring Lahan Cerdas</p>
        </div>

        <form action="/" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <input type="text" name="username" required value="{{ old('username') }}"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl pl-11 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all font-medium"
                        placeholder="Masukkan username">
                </div>
                @error('username') <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" name="password" required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl pl-11 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all font-medium"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-0.5 mt-2 flex justify-center items-center gap-2">
                Masuk Sistem <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <i class="fa-solid fa-shield-halved mr-1"></i> Jalur Khusus Admin
            </p>
        </div>
    </div>

</body>

</html>