@php
    $ph = $latestData->ph_tanah ?? 0;
    // Logika pH: 0-14, Ideal: 5.5 - 6.5
    if ($ph >= 5.5 && $ph <= 6.5) {
        $statusText = 'Aman (Netral)';
        $bgColor = 'bg-emerald-50';
        $borderColor = 'border-emerald-200';
        $textColor = 'text-emerald-700';
        $phPos = ($ph / 14) * 100;
    } elseif ($ph < 5.5) {
        $statusText = 'Bahaya (Terlalu Asam)';
        $bgColor = 'bg-orange-50';
        $borderColor = 'border-orange-200';
        $textColor = 'text-orange-700';
        $phPos = ($ph / 14) * 100;
    } else {
        $statusText = 'Bahaya (Terlalu Basa)';
        $bgColor = 'bg-purple-50';
        $borderColor = 'border-purple-200';
        $textColor = 'text-purple-700';
        $phPos = ($ph / 14) * 100;
    }
@endphp

<div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start mb-4">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Sensor pH Tanah</p>
            <h3 class="text-gray-900 text-4xl font-black">{{ $ph }}</h3>
        </div>
        <div class="{{ $bgColor }} {{ $textColor }} w-12 h-12 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-flask"></i>
        </div>
    </div>

    <!-- Skala Spektrum Asam ke Basa -->
    <div class="w-full bg-gradient-to-r from-orange-400 via-emerald-400 to-purple-400 rounded-full h-2 mb-3 relative">
        <div class="absolute top-1/2 -translate-y-1/2 w-3 h-3 bg-white border border-gray-900 rounded-full transition-all duration-1000"
            style="left: calc({{ $phPos }}% - 6px);"></div>
    </div>

    <div class="flex items-center gap-2">
        <span
            class="text-[10px] font-bold px-2.5 py-1 rounded-md border {{ $bgColor }} {{ $borderColor }} {{ $textColor }}">
            {{ $statusText }}
        </span>
    </div>
</div>