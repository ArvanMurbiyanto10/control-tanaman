<div class="glass-card p-8 animate-entrance relative overflow-hidden group">
    <div class="absolute right-0 bottom-0 w-64 h-64 bg-sun-100 rounded-full opacity-60 group-hover:scale-110 transition-transform duration-1000 pointer-events-none anim-sun transform translate-x-1/3 translate-y-1/3"></div>
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 relative z-10">
        <div>
            <h2 class="text-3xl font-extrabold text-darktext mb-2">Suhu Lingkungan</h2>
            <p class="text-sm font-semibold text-softtext">Temperatur udara di sekitar area budidaya.</p>
        </div>
        <div class="w-16 h-16 rounded-2xl bg-sun-50 text-sun-500 flex items-center justify-center text-3xl shadow-[0_0_20px_rgba(245,158,11,0.3)] mt-4 md:mt-0 anim-sun">
            <i class="fa-solid fa-temperature-half"></i>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-10 items-center relative z-10">
        <!-- Main Value -->
        <div class="text-center md:text-left flex-1">
            <div class="text-7xl font-black text-transparent bg-clip-text bg-gradient-to-br from-yellow-400 to-orange-500 tracking-tighter mb-4" id="detail-val-suhu">
                {{ $latestData->suhu_tanah ?? 0 }}<span class="text-3xl text-slate-400 ml-1">°C</span>
            </div>
            
            @php 
                $s = $latestData->suhu_tanah ?? 28;
                if($s < 20) { $sStatus = 'Dingin'; $sColor = 'text-blue-500'; $sBg = 'bg-blue-50'; }
                elseif($s > 35) { $sStatus = 'Panas'; $sColor = 'text-red-500'; $sBg = 'bg-red-50'; }
                else { $sStatus = 'Hangat/Ideal'; $sColor = 'text-nature-500'; $sBg = 'bg-nature-50'; }
            @endphp
            <div class="inline-flex items-center gap-2 {{ $sBg }} px-4 py-2 rounded-full border border-gray-100">
                <span class="w-2.5 h-2.5 rounded-full {{ str_replace('text', 'bg', $sColor) }} animate-pulse"></span>
                <span class="text-sm font-bold {{ $sColor }}" id="detail-status-suhu">Kondisi: {{ $sStatus }}</span>
            </div>
        </div>

        <!-- Gauge/Bar representation -->
        <div class="flex-1 w-full">
            <div class="flex justify-between text-xs font-bold text-softtext mb-3 px-1">
                <span>Dingin (0°C)</span>
                <span>Panas (50°C)</span>
            </div>
            <div class="h-6 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner relative border border-gray-200">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diagonal-stripes.png')] opacity-20 z-10 pointer-events-none"></div>
                @php $suhuPos = (($latestData->suhu_tanah ?? 28) / 50) * 100; @endphp
                <div id="detail-bar-suhu" class="h-full bg-gradient-to-r from-yellow-300 via-orange-400 to-red-500 anim-fluid relative z-0" style="width: {{ $suhuPos }}%"></div>
            </div>
            
            <div class="mt-8 p-4 bg-orange-50/50 rounded-xl border border-orange-100 flex gap-4">
                <i class="fa-solid fa-sun text-orange-500 mt-0.5 anim-sun"></i>
                <p class="text-xs font-semibold text-slate-600 leading-relaxed">
                    Suhu hangat memicu proses transpirasi dan fotosintesis. Rentang <strong class="text-darktext">25°C - 32°C</strong> adalah kondisi paling optimal siang ini.
                </p>
            </div>
        </div>
    </div>
</div>