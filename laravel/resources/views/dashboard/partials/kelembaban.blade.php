<div class="glass-card p-8 animate-entrance relative overflow-hidden group">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-water-50 rounded-full opacity-60 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 relative z-10">
        <div>
            <h2 class="text-3xl font-extrabold text-darktext mb-2">Kelembaban Tanah</h2>
            <p class="text-sm font-semibold text-softtext">Status air dan kelembaban pada area perakaran.</p>
        </div>
        <div class="w-16 h-16 rounded-2xl bg-water-50 text-water-500 flex items-center justify-center text-3xl shadow-glow-blue mt-4 md:mt-0">
            <i class="fa-solid fa-droplet anim-water"></i>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-10 items-center relative z-10">
        <!-- Main Value -->
        <div class="text-center md:text-left flex-1">
            <div class="text-7xl font-black text-transparent bg-clip-text bg-gradient-to-br from-water-400 to-blue-600 tracking-tighter mb-4" id="detail-val-kelembaban">
                {{ $latestData->kelembaban ?? 0 }}<span class="text-3xl text-slate-400 ml-1">%</span>
            </div>
            
            @php 
                $k = $latestData->kelembaban ?? 0;
                if($k < 40) { $kStatus = 'Kering'; $kColor = 'text-amber-500'; $kBg = 'bg-amber-50'; }
                elseif($k > 80) { $kStatus = 'Basah'; $kColor = 'text-water-500'; $kBg = 'bg-water-50'; }
                else { $kStatus = 'Optimal'; $kColor = 'text-nature-500'; $kBg = 'bg-nature-50'; }
            @endphp
            <div class="inline-flex items-center gap-2 {{ $kBg }} px-4 py-2 rounded-full border border-gray-100">
                <span class="w-2.5 h-2.5 rounded-full {{ str_replace('text', 'bg', $kColor) }} animate-pulse"></span>
                <span class="text-sm font-bold {{ $kColor }}" id="detail-status-kelembaban">Kondisi: {{ $kStatus }}</span>
            </div>
        </div>

        <!-- Gauge/Bar representation -->
        <div class="flex-1 w-full">
            <div class="flex justify-between text-xs font-bold text-softtext mb-3 px-1">
                <span>0% (Kering)</span>
                <span>100% (Banjir)</span>
            </div>
            <div class="h-6 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner relative border border-gray-200">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diagonal-stripes.png')] opacity-20 z-10 pointer-events-none"></div>
                <div id="detail-bar-kelembaban" class="h-full bg-gradient-to-r from-water-400 to-blue-500 anim-fluid relative z-0" style="width: {{ $k }}%"></div>
            </div>
            
            <div class="mt-8 p-4 bg-blue-50/50 rounded-xl border border-blue-100 flex gap-4">
                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                <p class="text-xs font-semibold text-slate-600 leading-relaxed">
                    Tanaman ini tumbuh optimal di rentang kelembaban <strong class="text-darktext">60% - 80%</strong>. 
                    Jika di bawah 40%, pompa akan disarankan menyala secara otomatis.
                </p>
            </div>
        </div>
    </div>
</div>