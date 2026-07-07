<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - COTA Monitoring</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-emerald-50 h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl p-8 shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-gray-900">Daftar Admin COTA</h1>
            <p class="text-sm text-gray-500 mt-1">Buat akun untuk mengelola lahan</p>
        </div>

        <form action="/register" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Lengkap</label>
                <input type="text" name="name" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500">
            </div>

            <button type="submit"
                class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 px-4 rounded-xl mt-4 transition-colors">
                Buat Akun
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun? <a href="/login" class="text-emerald-600 font-bold hover:underline">Login di sini</a>
        </p>
    </div>

</body>

</html>