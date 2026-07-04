<div class="glass-card p-8 animate-entrance relative overflow-hidden group">
    <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-soil-50 rounded-full opacity-60 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 relative z-10">
        <div>
            <h2 class="text-3xl font-extrabold text-darktext mb-2">pH Tanah</h2>
            <p class="text-sm font-semibold text-softtext">Tingkat keasaman basa pada media tanam.</p>
        </div>
        <div class="w-16 h-16 rounded-2xl bg-soil-50 text-soil-500 flex items-center justify-center text-3xl shadow-[0_0_20px_rgba(217,70,239,0.3)] mt-4 md:mt-0">
            <i class="fa-solid fa-flask"></i>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-10 items-center relative z-10">
        <!-- Main Value -->
        <div class="text-center md:text-left flex-1">
            <div class="text-7xl font-black text-transparent bg-clip-text bg-gradient-to-br from-soil-400 to-purple-600 tracking-tighter mb-4" id="detail-val-ph">
                {{ $latestData->ph_tanah ?? 0 }}<span class="text-3xl text-slate-400 ml-2">pH</span>
            </div>
            
            @php 
                $p = $latestData->ph_tanah ?? 7.0;
                if($p < 6.0) { $pStatus = 'Terlalu Asam'; $pColor = 'text-red-500'; $pBg = 'bg-red-50'; }
                elseif($p > 7.5) { $pStatus = 'Terlalu Basa'; $pColor = 'text-blue-500'; $pBg = 'bg-blue-50'; }
                else { $pStatus = 'Normal'; $pColor = 'text-nature-500'; $pBg = 'bg-nature-50'; }
            @endphp
            <div class="inline-flex items-center gap-2 {{ $pBg }} px-4 py-2 rounded-full border border-gray-100">
                <span class="w-2.5 h-2.5 rounded-full {{ str_replace('text', 'bg', $pColor) }} animate-pulse"></span>
                <span class="text-sm font-bold {{ $pColor }}" id="detail-status-ph">Kondisi: {{ $pStatus }}</span>
            </div>
        </div>

        <!-- Color Scale representation -->
        <div class="flex-1 w-full">
            <div class="flex justify-between text-xs font-bold text-softtext mb-3 px-1">
                <span>Asam (0)</span>
                <span>Netral (7)</span>
                <span>Basa (14)</span>
            </div>
            
            <!-- Rainbow Bar -->
            <div class="h-6 w-full rounded-full overflow-hidden shadow-inner relative border border-gray-200" style="background: linear-gradient(to right, #ef4444, #f59e0b, #22c55e, #3b82f6, #8b5cf6);">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diagonal-stripes.png')] opacity-20 pointer-events-none"></div>
                <!-- Indicator Pin -->
                @php $phPos = (($latestData->ph_tanah ?? 7.0) / 14) * 100; @endphp
                <div id="detail-pin-ph" class="absolute top-0 bottom-0 w-2 bg-white shadow-lg transition-all duration-1000 border-x border-gray-300" style="left: calc({{ $phPos }}% - 4px);"></div>
            </div>
            
            <div class="mt-8 p-4 bg-purple-50/50 rounded-xl border border-purple-100 flex gap-4">
                <i class="fa-solid fa-seedling text-soil-500 mt-0.5"></i>
                <p class="text-xs font-semibold text-slate-600 leading-relaxed">
                    pH ideal untuk sebagian besar tanaman hortikultura adalah <strong class="text-darktext">6.0 hingga 7.0</strong>. 
                    pH yang tidak tepat menghambat penyerapan nutrisi akar.
                </p>
            </div>
        </div>
    </div>
</div>