<div class="space-y-6 animate-fade-in">
    @if(!$selectedTeknisi)
        <!-- MODE: DAFTAR TEKNISI -->
        <div class="bg-white/70 backdrop-blur-md border border-gray-200/60 rounded-3xl p-8 shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Teknisi Witel Rajawali</h1>
                    <p class="text-gray-500 text-sm mt-1">Pilih nama teknisi untuk melihat Rapor Kinerja & Statistik lengkapnya.</p>
                </div>
                
                <!-- Search Bar -->
                <div class="relative w-full md:w-80" style="position: relative;">
                    <svg style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); pointer-events: none;" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input style="padding-left: 2.75rem;" wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pr-4 text-sm text-gray-900 border-2 border-gray-200 rounded-full bg-white focus:ring-telkom-red focus:border-telkom-red shadow-sm transition-all outline-none" placeholder="Cari NIK atau Nama...">
                </div>
            </div>

            <!-- Table of Technicians -->
            <div class="bg-white border border-gray-200/60 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-100">
                                <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs">Labor Code / NIK</th>
                                <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs">Nama Teknisi</th>
                                <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($technicians as $tech)
                                <tr class="hover:bg-gray-50/80 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-500">
                                        {{ $tech->labor_code ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ strtoupper($tech->nama) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button wire:click="viewDetail('{{ $tech->nama }}', '{{ $tech->labor_code }}')" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition-all duration-150 ease-in-out bg-telkom-red border border-transparent rounded-full hover:bg-telkom-dark active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300">
                                            Detail Kinerja
                                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                        Tidak ada teknisi yang ditemukan dengan kata kunci "{{ $search }}".
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($technicians->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                        {{ $technicians->links() }}
                    </div>
                @endif
            </div>
        </div>

    @else
        <!-- MODE: DETAIL KINERJA TEKNISI -->
        
        <!-- Navigation Back -->
        <div class="flex items-center justify-between bg-white/70 backdrop-blur-md border border-gray-200/60 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center space-x-4">
                <button wire:click="backToList" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ strtoupper($selectedTeknisi) }}</h2>
                    <p class="text-gray-500 text-sm">Labor Code / NIK: <span class="font-mono font-medium">{{ $selectedLaborCode ?? '-' }}</span></p>
                </div>
            </div>
            
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-telkom-red border border-red-100 shadow-sm">
                Rapor Kinerja Bulan Ini
            </span>
        </div>

        @if($stats['total_tiket'] > 0)
            <!-- Top 4 Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Card 1: Produktivitas -->
                <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-telkom-red/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Produktivitas</h3>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-5xl font-extrabold text-gray-900 tracking-tight">{{ $stats['total_tiket'] }}</span>
                        <span class="text-gray-400 font-medium text-lg">Tiket</span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-sm">
                        <div class="text-gray-600"><span class="font-bold text-gray-900">{{ $stats['total_utama'] }}</span> Utama</div>
                        <div class="text-gray-600"><span class="font-bold text-gray-900">{{ $stats['total_pendamping'] }}</span> Pendamping</div>
                    </div>
                </div>

                <!-- Card 2: Spesialisasi -->
                <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gray-100 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Spesialisasi Order</h3>
                    @php
                        $b2cPercent = $stats['total_tiket'] > 0 ? round(($stats['b2c'] / $stats['total_tiket']) * 100) : 0;
                        $b2bPercent = 100 - $b2cPercent;
                    @endphp
                    <div class="flex items-baseline space-x-2 mb-4">
                        <span class="text-3xl font-extrabold text-gray-900">{{ $b2cPercent }}%</span>
                        <span class="text-gray-400 font-medium">B2C</span>
                    </div>
                    <!-- Progress Bar -->
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden flex">
                        <div class="bg-telkom-red h-full rounded-full" style="width: {{ $b2cPercent }}%"></div>
                        <div class="bg-gray-400 h-full rounded-full" style="width: {{ $b2bPercent }}%"></div>
                    </div>
                    <div class="mt-3 flex justify-between text-xs font-medium text-gray-500">
                        <span class="text-telkom-red">B2C ({{ $stats['b2c'] }})</span>
                        <span class="text-gray-500">B2B/Ebis ({{ $stats['b2b'] }})</span>
                    </div>
                </div>

                <!-- Card 3: Kecepatan SLA -->
                <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden group">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Kecepatan Kerja (SLA)</h3>
                    @php
                        $hours = floor($stats['avg_sla_minutes'] / 60);
                        $mins = $stats['avg_sla_minutes'] % 60;
                        $isFast = $stats['avg_sla_minutes'] < 120; // Example: < 2 hours is fast
                    @endphp
                    <div class="flex items-baseline space-x-2">
                        <span class="text-4xl font-extrabold text-gray-900">{{ $hours }}<span class="text-2xl font-semibold text-gray-400">J</span> {{ $mins }}<span class="text-2xl font-semibold text-gray-400">M</span></span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center">
                        @if($isFast)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                Rata-rata Cepat
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-telkom-red">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Perlu Ditingkatkan
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card 4: Top Area -->
                <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Area Kerja Dominan</h3>
                    <div class="space-y-3">
                        @forelse($stats['top_areas'] as $area => $count)
                            @php
                                $percent = $stats['total_tiket'] > 0 ? round(($count / $stats['total_tiket']) * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-medium text-gray-700 mb-1">
                                    <span>{{ $area }}</span>
                                    <span>{{ $percent }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-gray-800 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">Belum ada data area.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Bottom Table: Riwayat Tiket -->
            <div class="bg-white border border-gray-200/60 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden mt-8">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Tiket & Pekerjaan</h3>
                    <span class="text-sm font-medium text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm">{{ count($stats['history']) }} Baris Data</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead>
                            <tr class="bg-white border-b-2 border-gray-100">
                                @php
                                    $headers = [
                                        'start_dt' => 'Waktu Mulai',
                                        'segment' => 'Segmen',
                                        'jenis_order' => 'Jenis Order',
                                        'peran' => 'Peran',
                                        'workzone' => 'Workzone',
                                        'durasi_menit' => 'Kecepatan',
                                        'tiket_no' => 'No. Tiket',
                                        'partner' => 'Partner'
                                    ];
                                @endphp
                                @foreach($headers as $key => $label)
                                    <th class="px-6 py-4 font-bold text-gray-900 uppercase tracking-wider text-xs border-b-2 border-gray-200">
                                        <button wire:click="sortBy('{{ $key }}')" class="flex items-center group focus:outline-none w-full">
                                            {{ $label }}
                                            <span class="ml-2 flex-none rounded text-gray-400 group-hover:bg-gray-200 group-hover:text-gray-900">
                                                @if($sortColumn === $key)
                                                    @if($sortDirection === 'asc')
                                                        <svg class="w-4 h-4 text-telkom-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-telkom-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    @endif
                                                @else
                                                    <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                                @endif
                                            </span>
                                        </button>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($stats['history'] as $item)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $item['start_dt'] ? date('d M Y, H:i', strtotime($item['start_dt'])) : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-500">{{ $item['segment'] }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $item['jenis_order'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        {{ $item['peran'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item['workzone'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $item['durasi_teks'] }}</td>
                                    <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $item['tiket_no'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item['partner'] && $item['partner'] !== '-')
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-xs font-bold mr-2">
                                                    {{ substr($item['partner'], 0, 1) }}
                                                </div>
                                                {{ $item['partner'] }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Mandiri</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-20 bg-white border border-gray-200/60 rounded-3xl shadow-sm mt-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Data Tidak Ditemukan</h3>
                <p class="mt-1 text-gray-500">Tidak ada data riwayat pekerjaan untuk {{ strtoupper($selectedTeknisi) }}.</p>
                <button wire:click="backToList" class="mt-4 px-4 py-2 text-sm font-medium text-telkom-red bg-red-50 hover:bg-red-100 rounded-full transition-colors">
                    Kembali ke Daftar
                </button>
            </div>
        @endif
    @endif
</div>
