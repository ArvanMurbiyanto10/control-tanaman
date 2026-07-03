@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase">Detail {{ $jenis }} Tanah</h1>
            <p class="text-gray-400 text-sm mt-1">Pemantauan kondisi dan grafik pergerakan sensor.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-creamcard rounded-3xl p-6 shadow-lg">
                <h2 class="text-gray-900 font-bold text-lg mb-4 border-b border-gray-300 pb-2">Status Saat Ini</h2>

                <div class="text-5xl font-black text-gray-900 mb-4">
                    @if($jenis == 'kelembaban') {{ $latestData->kelembaban ?? 0 }}%
                    @elseif($jenis == 'ph') {{ $latestData->ph_tanah ?? 0 }}
                    @elseif($jenis == 'suhu') {{ $latestData->suhu_tanah ?? 0 }}°C
                    @endif
                </div>

                <div class="text-sm text-gray-700 space-y-3">
                    @if($jenis == 'kelembaban')
                        <p><strong>Alat Sensor:</strong> Capacitive Soil Moisture v1.2</p>
                        <p><strong>Ambang Batas Ideal:</strong> 60% - 80%</p>
                        <p class="bg-blue-100 p-3 rounded-xl border border-blue-200 mt-4 text-blue-800">
                            <i class="fa-solid fa-circle-info"></i> Kelembaban tanah saat ini terpantau normal. Sistem otomatis
                            akan menyiram jika persentase menyentuh angka 40%.
                        </p>
                    @elseif($jenis == 'ph')
                        <p><strong>Alat Sensor:</strong> Analog pH Meter Probe</p>
                        <p><strong>Ambang Batas Ideal:</strong> 5.5 - 6.5 (Netral)</p>
                        <p class="bg-red-100 p-3 rounded-xl border border-red-200 mt-4 text-red-800">
                            <i class="fa-solid fa-triangle-exclamation"></i> Keseimbangan pH sangat penting agar akar dapat
                            menyerap unsur hara pupuk (NPK) dengan maksimal.
                        </p>
                    @elseif($jenis == 'suhu')
                        <p><strong>Alat Sensor:</strong> DS18B20 Waterproof</p>
                        <p><strong>Rentang Ideal:</strong> 20°C - 28°C</p>
                        <p class="bg-green-100 p-3 rounded-xl border border-green-200 mt-4 text-green-800">
                            <i class="fa-solid fa-leaf"></i> Fluktuasi panas tanah terjaga dengan baik. Tanaman terhindar dari
                            potensi <i>heat stress</i>.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-creamcard rounded-3xl p-6 shadow-lg flex flex-col">
            <h2 class="text-gray-900 font-bold text-lg mb-1">Grafik Pergerakan Waktu</h2>
            <p class="text-gray-500 text-xs font-semibold mb-6">Data ditarik secara real-time dari NodeMCU</p>
            <div class="relative flex-1 w-full" style="min-height: 300px;">
                <canvas id="grafikDetail"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const historyData = {!! json_encode(isset($historyData) ? $historyData->reverse()->values() : []) !!};
        const labels = historyData.map(data => data.created_at.substring(11, 16));
        const jenisGrafik = '{{ $jenis }}';

        let chartData = [];
        let chartLabel = '';
        let chartColor = '';
        let chartBg = '';

        // Menyesuaikan data grafik yang dimuat berdasarkan halaman yang dibuka
        if (jenisGrafik === 'kelembaban') {
            chartData = historyData.map(d => d.kelembaban);
            chartLabel = 'Kelembaban (%)'; chartColor = '#3b4368'; chartBg = 'rgba(59, 67, 104, 0.15)';
        } else if (jenisGrafik === 'ph') {
            chartData = historyData.map(d => d.ph_tanah);
            chartLabel = 'pH Tanah'; chartColor = '#c94f3a'; chartBg = 'rgba(201, 79, 58, 0.15)';
        } else {
            chartData = historyData.map(d => d.suhu_tanah);
            chartLabel = 'Suhu (°C)'; chartColor = '#2d7a5b'; chartBg = 'rgba(45, 122, 91, 0.15)';
        }

        Chart.defaults.font.family = 'sans-serif';
        Chart.defaults.color = '#718096';

        new Chart(document.getElementById('grafikDetail').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels.length ? labels : ['00:00'],
                datasets: [{ label: chartLabel, data: chartData, borderColor: chartColor, backgroundColor: chartBg, tension: 0.4, fill: true, borderWidth: 2 }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
@endpush