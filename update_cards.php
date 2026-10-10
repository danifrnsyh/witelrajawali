<?php

$file = 'resources/views/livewire/kinerja-teknisi.blade.php';
$content = file_get_contents($file);

// CARD 3
$card3 = <<<'EOT'
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white border border-green-400/50 rounded-3xl p-6 shadow-[0_8px_32px_0_rgba(34,197,94,0.25)] hover:shadow-[0_20px_40px_rgba(34,197,94,0.45)] relative overflow-hidden group transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20 blur-2xl"></div>
                    <div class="flex items-center space-x-3 mb-4 relative z-10">
                        <div class="p-3 bg-white/20 backdrop-blur-sm rounded-2xl text-white border border-white/30 shadow-inner">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-white/90 uppercase tracking-wider">Kecepatan (SLA)</h3>
                    </div>
                    @php
                        $hours = floor($stats['avg_sla_minutes'] / 60);
                        $mins = $stats['avg_sla_minutes'] % 60;
                        $isFast = $stats['avg_sla_minutes'] < 120; // Example: < 2 hours is fast
                    @endphp
                    <div class="flex items-baseline space-x-1 relative z-10">
                        <span class="text-4xl font-extrabold text-white tracking-tight drop-shadow-md">{{ $hours }}</span><span class="text-lg font-bold text-green-100 mr-2">J</span>
                        <span class="text-4xl font-extrabold text-white tracking-tight drop-shadow-md">{{ $mins }}</span><span class="text-lg font-bold text-green-100">M</span>
                    </div>
                    <div class="mt-5 pt-4 border-t border-white/20 flex items-center relative z-10">
                        @if($isFast)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-green-700 shadow-sm border border-white">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Rata-rata Cepat
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-white text-orange-700 shadow-sm border border-white">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Perlu Ditingkatkan
                            </span>
                        @endif
                    </div>
                </div>
EOT;

$card3_target = <<<'EOT'
                <div class="bg-white  border border-white/60 rounded-3xl p-6 shadow-[0_8px_32px_0_rgba(0,0,0,0.05)] hover:bg-white hover:shadow-[0_20px_40px_rgba(34,197,94,0.08)]    relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50   rounded-full -mr-16 -mt-16  "></div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="p-2.5 bg-white  rounded-xl text-green-600 shadow-sm border border-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wider">Kecepatan (SLA)</h3>
                    </div>
                    @php
                        $hours = floor($stats['avg_sla_minutes'] / 60);
                        $mins = $stats['avg_sla_minutes'] % 60;
                        $isFast = $stats['avg_sla_minutes'] < 120; // Example: < 2 hours is fast
                    @endphp
                    <div class="flex items-baseline space-x-1">
                        <span class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $hours }}</span><span class="text-lg font-bold text-gray-500 mr-2">J</span>
                        <span class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $mins }}</span><span class="text-lg font-bold text-gray-500">M</span>
                    </div>
                    <div class="mt-5 pt-4 border-t border-white/50 flex items-center">
                        @if($isFast)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-white  text-green-700 shadow-sm border border-white">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Rata-rata Cepat
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-white  text-orange-700 shadow-sm border border-white">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Perlu Ditingkatkan
                            </span>
                        @endif
                    </div>
                </div>
EOT;

$content = str_replace(str_replace("\r\n", "\n", $card3_target), $card3, $content);
$content = str_replace($card3_target, $card3, $content);

// CARD 4
$card4 = <<<'EOT'
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white border border-blue-500/50 rounded-3xl p-6 shadow-[0_8px_32px_0_rgba(37,99,235,0.25)] hover:shadow-[0_20px_40px_rgba(37,99,235,0.45)] relative overflow-hidden group transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20 blur-2xl"></div>
                    <div class="flex items-center space-x-3 mb-5 relative z-10">
                        <div class="p-3 bg-white/20 backdrop-blur-sm rounded-2xl text-white border border-white/30 shadow-inner">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-white/90 uppercase tracking-wider">Area Dominan</h3>
                    </div>
                    <div class="space-y-4 relative z-10">
                        @forelse($stats['top_areas'] as $area => $count)
                            @php
                                $percent = $stats['total_tiket'] > 0 ? round(($count / $stats['total_tiket']) * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-bold text-white/90 mb-1.5">
                                    <span class="truncate pr-2">{{ $area }}</span>
                                    <span class="text-white">{{ $percent }}%</span>
                                </div>
                                <div class="w-full bg-blue-900/50 rounded-full h-2 shadow-inner border border-blue-900/30">
                                    <div class="bg-white h-2 rounded-full shadow-[0_0_8px_rgba(255,255,255,0.5)]" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-blue-200 font-medium">Belum ada data area.</p>
                        @endforelse
                    </div>
                </div>
EOT;

$card4_target = <<<'EOT'
                <div class="bg-white  border border-white/60 rounded-3xl p-6 shadow-[0_8px_32px_0_rgba(0,0,0,0.05)] hover:bg-white hover:shadow-[0_20px_40px_rgba(59,130,246,0.08)]    relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50   rounded-full -mr-16 -mt-16  "></div>
                    <div class="flex items-center space-x-3 mb-5">
                        <div class="p-2.5 bg-white rounded-xl text-red-500 shadow-sm border border-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wider">Area Dominan</h3>
                    </div>
                    <div class="space-y-4">
                        @forelse($stats['top_areas'] as $area => $count)
                            @php
                                $percent = $stats['total_tiket'] > 0 ? round(($count / $stats['total_tiket']) * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-bold text-gray-700 mb-1.5">
                                    <span class="truncate pr-2">{{ $area }}</span>
                                    <span class="text-red-500">{{ $percent }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 shadow-inner border border-gray-200">
                                    <div class="bg-red-400 h-2 rounded-full shadow-[0_0_8px_rgba(237,28,36,0.3)]" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 font-medium">Belum ada data area.</p>
                        @endforelse
                    </div>
                </div>
EOT;

$content = str_replace(str_replace("\r\n", "\n", $card4_target), $card4, $content);
$content = str_replace($card4_target, $card4, $content);

file_put_contents($file, $content);
echo "Cards 3 & 4 updated!\n";
