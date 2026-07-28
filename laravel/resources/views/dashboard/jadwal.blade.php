@extends('layouts.app')

@section('content')

<!-- Hamburger Menu Button (Mobile) -->
<button class="menu-toggle" id="mobile-menu-btn">
    <i class="fa-solid fa-bars text-xl"></i>
</button>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="space-y-8">
    <div class="flex items-center justify-between animate-entrance">
        <div>
            <h1 class="text-3xl font-extrabold text-darktext mb-2">Kontrol & Jadwal</h1>
            <p class="text-sm font-semibold text-softtext">Atur penyiraman otomatis berdasarkan jadwal jam manual atau ambang batas sensor.</p>
        </div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-soft border border-gray-100 anim-float">
            <i class="fa-regular fa-calendar-check text-nature-500 text-xl"></i>
        </div>
    </div>

    <!-- Unified Form holding both sections to submit together -->
    <form id="form-settings" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Jadwal Waktu Form Card -->
        <div class="glass-card p-8 animate-entrance stagger-1 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-nature-50 rounded-full opacity-60 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-8 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-nature-50 text-nature-600 flex items-center justify-center text-xl">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-darktext">Jadwal Waktu Harian</h2>
                        <p class="text-xs font-semibold text-softtext">Atur beberapa jadwal penyiraman otomatis</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6 relative z-10">
                <!-- Info Durasi Sinkron -->
                <div class="p-4 bg-slate-50 border border-gray-100 rounded-xl flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-nature-500 mt-0.5"></i>
                    <p class="text-xs text-softtext font-semibold leading-relaxed">
                        Durasi penyiraman otomatis ini disamakan dengan durasi tombol manual di Dashboard Utama, yaitu selama <strong class="text-darktext">10 Detik</strong>.
                    </p>
                </div>

                <!-- Container Waktu Kustom Dinamis -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-wider">Daftar Jam & Menit Penyiraman</label>
                        <button type="button" id="btn-add-time" class="bg-nature-50 text-nature-600 hover:bg-nature-500 hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm border border-nature-100">
                            <i class="fa-solid fa-plus"></i> Tambah Waktu
                        </button>
                    </div>
                    
                    <div id="times-list-container" class="space-y-3 max-h-64 overflow-y-auto pr-1">
                        @forelse($settings['scheduled_times'] as $time)
                        <div class="flex items-center gap-3 time-entry-row animate-entrance">
                            <div class="relative flex-1">
                                <input type="time" name="scheduled_times[]" value="{{ $time }}" class="w-full bg-white border border-gray-200 text-darktext text-sm rounded-xl focus:ring-4 focus:ring-nature-100 focus:border-nature-500 block p-3 font-semibold transition-all shadow-sm">
                            </div>
                            <button type="button" class="btn-delete-time w-11 h-11 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl flex items-center justify-center transition-all border border-red-100">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                        @empty
                        <!-- Default empty state if no times saved -->
                        <div class="flex items-center gap-3 time-entry-row animate-entrance">
                            <div class="relative flex-1">
                                <input type="time" name="scheduled_times[]" value="06:30" class="w-full bg-white border border-gray-200 text-darktext text-sm rounded-xl focus:ring-4 focus:ring-nature-100 focus:border-nature-500 block p-3 font-semibold transition-all shadow-sm">
                            </div>
                            <button type="button" class="btn-delete-time w-11 h-11 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl flex items-center justify-center transition-all border border-red-100">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-gray-100">
                    <div>
                        <p class="text-sm font-extrabold text-darktext mb-1">Status Jadwal</p>
                        <p class="text-xs font-semibold text-softtext">Aktifkan jadwal waktu harian kustom</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="scheduled_enabled" name="scheduled_enabled" class="sr-only peer" {{ ($settings['scheduled_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-nature-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-nature-500"></div>
                    </label>
                </div>

                <button type="submit" class="w-full text-white bg-gradient-to-r from-nature-500 to-teal-500 hover:from-nature-600 hover:to-teal-600 focus:ring-4 focus:outline-none focus:ring-nature-100 font-bold rounded-xl text-sm px-5 py-4 text-center shadow-lg shadow-nature-500/30 transition-all transform hover:-translate-y-1">
                    Simpan Semua Pengaturan
                </button>
            </div>
        </div>

        <!-- Ambang Batas Sensor Form Card -->
        <div class="glass-card p-8 animate-entrance stagger-2 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-water-50 rounded-full opacity-60 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
            
            <div class="flex items-center gap-4 mb-8 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-water-50 text-water-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-droplet"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-darktext">Ambang Batas (Threshold)</h2>
                    <p class="text-xs font-semibold text-softtext">Pompa menyala jika kelembaban di bawah target</p>
                </div>
            </div>

            <div class="space-y-6 relative z-10">
                <div>
                    <label class="block text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Batas Minimum Kelembaban (%)</label>
                    <div class="relative">
                        <input type="number" name="smart_sensor_threshold" id="smart_sensor_threshold" value="{{ $settings['smart_sensor_threshold'] ?? 40 }}" min="1" max="100" class="w-full bg-white border border-gray-200 text-darktext text-lg rounded-xl focus:ring-4 focus:ring-water-100 focus:border-water-500 block p-3 pl-12 font-black transition-all shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-water-500">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-softtext font-medium"><i class="fa-solid fa-circle-info text-blue-400 mr-1"></i> Rekomendasi: 40% - 60%</p>
                </div>

                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-gray-100">
                    <div>
                        <p class="text-sm font-extrabold text-darktext mb-1">Status Sensor Otomatis</p>
                        <p class="text-xs font-semibold text-softtext">Prioritaskan nyala berdasarkan sensor</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="smart_sensor_enabled" name="smart_sensor_enabled" class="sr-only peer" {{ ($settings['smart_sensor_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-water-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-water-500"></div>
                    </label>
                </div>

                <button type="submit" class="w-full text-white bg-gradient-to-r from-water-500 to-blue-600 hover:from-water-600 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-water-100 font-bold rounded-xl text-sm px-5 py-4 text-center shadow-lg shadow-water-500/30 transition-all transform hover:-translate-y-1">
                    Simpan Semua Pengaturan
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    const timesContainer = document.getElementById('times-list-container');
    const btnAddTime = document.getElementById('btn-add-time');

    // Handle adding dynamic time fields
    btnAddTime.addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-3 time-entry-row animate-entrance';
        div.innerHTML = `
            <div class="relative flex-1">
                <input type="time" name="scheduled_times[]" value="12:00" class="w-full bg-white border border-gray-200 text-darktext text-sm rounded-xl focus:ring-4 focus:ring-nature-100 focus:border-nature-500 block p-3 font-semibold transition-all shadow-sm">
            </div>
            <button type="button" class="btn-delete-time w-11 h-11 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl flex items-center justify-center transition-all border border-red-100">
                <i class="fa-regular fa-trash-can"></i>
            </button>
        `;
        timesContainer.appendChild(div);
        
        // Scroll to the bottom of container
        timesContainer.scrollTop = timesContainer.scrollHeight;
    });

    // Handle deleting time fields using Event Delegation
    timesContainer.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.btn-delete-time');
        if (deleteBtn) {
            const row = deleteBtn.closest('.time-entry-row');
            if (row) row.remove();
        }
    });

    // Submit settings form
    document.getElementById('form-settings').addEventListener('submit', function(e) {
        e.preventDefault();
        saveAllSettings();
    });

    function saveAllSettings() {
        const timeInputs = document.querySelectorAll('input[name="scheduled_times[]"]');
        const scheduledTimes = Array.from(timeInputs).map(input => input.value);

        const data = {
            smart_sensor_enabled: document.getElementById('smart_sensor_enabled').checked ? '1' : '0',
            smart_sensor_threshold: document.getElementById('smart_sensor_threshold').value,
            scheduled_enabled: document.getElementById('scheduled_enabled').checked ? '1' : '0',
            scheduled_times: scheduledTimes
        };

        cotaFetch('/api/jadwal/simpan', {
            method: 'POST',
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resData => {
            if(resData.success) {
                showToast('Pengaturan berhasil disimpan dan dikirim ke perangkat!', 'success');
            } else {
                showToast('Gagal menyimpan pengaturan: ' + (resData.message || ''), 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Terjadi kesalahan jaringan.', 'error');
        });
    }
</script>
@endpush