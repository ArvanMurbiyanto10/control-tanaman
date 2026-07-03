@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold text-white mb-6">Pengaturan Automatisasi & Jadwal</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-creamcard rounded-3xl p-6 shadow-lg border-t-4 border-icongreen">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-gray-900 font-bold text-xl"><i class="fa-solid fa-microchip text-icongreen mr-2"></i> Mode
                    Smart Sensor</h2>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" checked class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-icongreen">
                    </div>
                </label>
            </div>
            <p class="text-sm text-gray-600 mb-6">Penyiraman akan berjalan otomatis saat kelembaban tanah turun di bawah
                ambang batas yang ditentukan.</p>

            <div class="bg-white rounded-xl p-4 border border-gray-200 flex justify-between items-center">
                <span class="font-semibold text-gray-700">Ambang Batas Kelembaban (Siram jika < )</span>
                        <div class="flex items-center gap-2">
                            <input type="number" value="40"
                                class="w-16 text-center bg-gray-100 border border-gray-300 rounded-lg p-1 font-bold text-gray-800">
                            <span class="font-bold text-gray-600">%</span>
                        </div>
            </div>
        </div>

        <div class="bg-creamcard rounded-3xl p-6 shadow-lg border-t-4 border-iconblue">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-gray-900 font-bold text-xl"><i class="fa-solid fa-clock text-iconblue mr-2"></i> Mode
                    Terjadwal Waktu</h2>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-iconblue">
                    </div>
                </label>
            </div>
            <p class="text-sm text-gray-600 mb-6">Pompa air akan menyala secara otomatis setiap hari pada rentang waktu yang
                Anda jadwalkan di bawah ini.</p>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <span class="block text-xs font-bold text-gray-500 uppercase mb-1">Waktu Mulai Siram</span>
                    <input type="time" value="06:30"
                        class="w-full bg-transparent font-bold text-xl text-gray-800 outline-none cursor-pointer">
                </div>
                <div class="bg-white rounded-xl p-4 border border-gray-200">
                    <span class="block text-xs font-bold text-gray-500 uppercase mb-1">Waktu Selesai Siram</span>
                    <input type="time" value="06:45"
                        class="w-full bg-transparent font-bold text-xl text-gray-800 outline-none cursor-pointer">
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-creamcard rounded-3xl p-6 shadow-lg flex justify-between items-center">
            <div>
                <h2 class="text-gray-900 font-bold text-xl mb-1">Eksekusi Manual (Bypass)</h2>
                <p class="text-sm text-gray-600">Abaikan sensor dan jadwal, siram lahan secara paksa sekarang juga.</p>
            </div>
            <button id="btnManualSiram" onclick="siramAirManual()"
                class="bg-btnblue hover:bg-blue-900 text-white px-8 py-3 rounded-full font-bold shadow-md flex items-center gap-2 transition-all">
                <i class="fa-solid fa-power-off"></i> <span>Nyalakan Pompa Sekarang</span>
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function siramAirManual() {
            const btn = document.getElementById('btnManualSiram');
            btn.classList.replace('bg-btnblue', 'bg-iconorange');
            btn.querySelector('span').innerText = "Pompa Sedang Menyala...";
            btn.querySelector('i').classList.add('animate-spin');

            setTimeout(() => {
                btn.classList.replace('bg-iconorange', 'bg-btnblue');
                btn.querySelector('span').innerText = "Nyalakan Pompa Sekarang";
                btn.querySelector('i').classList.remove('animate-spin');
                alert('Selesai menyiram manual!');
            }, 3000);
        }
    </script>
@endpush