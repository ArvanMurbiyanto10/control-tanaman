@php
    $suhu = $latestData->suhu_tanah ?? 0;
    if ($suhu >= 20 && $suhu <= 28) {
        $statusText = 'Aman (Suhu Ideal)';
        $bgColor = 'bg-emerald-50';
        $borderColor = 'border-emerald-200';
        $textColor = 'text-emerald-700';
        $barColor = 'bg-emerald-500';
    } elseif ($suhu > 28) {
        $statusText = 'Peringatan (Terlalu Panas)';
        $bgColor = 'bg-red-50';
        $borderColor = 'border-red-200';
        $textColor = 'text-red-700';
        $barColor = 'bg-red-500';
    } else {
        $statusText = 'Peringatan (Terlalu Dingin)';
        $bgColor = 'bg-blue-50';
        $borderColor = 'border-blue-200';
        $textColor = 'text-blue-700';
        $barColor = 'bg-blue-500';
    }
    // Asumsi maksimal baca suhu di 50 derajat untuk bar
    $suhuPos = min(($suhu / 50) * 100, 100);
@endphp

<div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start mb-4">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Sensor Suhu (DS18B20)</p>
            <h3 class="text-gray-900 text-4xl font-black">{{ $suhu }}<span
                    class="text-xl text-gray-400 font-bold">°C</span></h3>
        </div>
        <div class="{{ $bgColor }} {{ $textColor }} w-12 h-12 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-temperature-half"></i>
        </div>
    </div>

    <!-- Bar Termal -->
    <div class="w-full bg-gray-100 rounded-full h-2 mb-3">
        <div class="{{ $barColor }} h-2 rounded-full transition-all duration-1000" style="width: {{ $suhuPos }}%"></div>
    </div>

    <div class="flex items-center gap-2">
        <span
            class="text-[10px] font-bold px-2.5 py-1 rounded-md border {{ $bgColor }} {{ $borderColor }} {{ $textColor }}">
            {{ $statusText }}
        </span>
    </div>
</div>