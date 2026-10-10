<div class="space-y-6 relative z-10">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$selectedTeknisi): ?>
        <!-- MODE: DAFTAR TEKNISI -->
        <!-- Header Card -->
        <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card p-6 md:p-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-bg-ambient rounded-bl-full -mt-8 -mr-8 blur-3xl opacity-50 pointer-events-none"></div>
            
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-text-main tracking-tight">Daftar Teknisi Witel Rajawali</h1>
                <p class="text-text-muted text-sm mt-2">Pilih nama teknisi untuk melihat Rapor Kinerja & Statistik lengkapnya.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center relative z-10">
                <!-- Search Bar -->
                <div class="relative w-full sm:w-64 md:w-80">
                    <svg class="absolute top-1/2 left-4 -translate-y-1/2 w-4 h-4 text-text-subtle pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pl-10 pr-4 text-sm text-text-main border border-border-card rounded-full bg-surface-subtle focus:bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm outline-none transition-all placeholder:text-text-subtle" placeholder="Cari NIK atau Nama...">
                </div>

                <!-- Refresh Button -->
                <button wire:click="$set('search', '')" class="export-btn-hide inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-telkom-red hover:bg-telkom-red-hover rounded-full shadow-card transition-colors w-full sm:w-auto whitespace-nowrap shrink-0">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
            </div>
        </div>

        <div class="relative bg-surface-card border border-border-card rounded-[32px] shadow-sm overflow-hidden flex flex-col p-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-0">
                        <thead>
                            <tr>
                                <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100 rounded-l-full">Labor Code / NIK</th>
                                <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100">Nama Teknisi</th>
                                <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs text-right whitespace-nowrap bg-gray-100 rounded-r-full">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="[&>tr:not(:last-child)>td]:border-b [&>tr:not(:last-child)>td]:border-border-card/50 text-text-main text-[13px]">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $technicians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'tech-'.e($tech->labor_code ?? $loop->index).''; ?>wire:key="tech-<?php echo e($tech->labor_code ?? $loop->index); ?>" class="hover:bg-surface-subtle transition-colors group">
                                    <td class="px-5 py-4 whitespace-nowrap font-variant-numeric tabular-nums font-mono text-text-muted">
                                        <?php echo e($tech->labor_code ?? '-'); ?>



                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap font-medium text-text-main">
                                        <?php echo e(strtoupper($tech->nama)); ?>



                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right transition-colors">
                                        <button wire:click="viewDetail('<?php echo e(addslashes($tech->nama)); ?>', '<?php echo e($tech->labor_code); ?>')" class="inline-flex items-center px-5 py-2 text-xs font-bold text-white bg-telkom-red hover:bg-telkom-red-hover rounded-full shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-telkom-red">
                                            Detail Kinerja
                                            <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-surface-subtle rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg>
                                            </div>
                                            <p class="text-text-main font-medium">Tidak ada teknisi yang ditemukan dengan kata kunci "<?php echo e($search); ?>".</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($technicians->hasPages()): ?>
                    <div class="px-6 py-4 bg-surface-card border-t border-border-card flex justify-center sm:justify-end">
                        <?php echo e($technicians->links('vendor.livewire.pill-pagination')); ?>



                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

    <?php else: ?>
        <!-- MODE: DETAIL KINERJA TEKNISI -->
        
        <!-- Wrapper for Export -->
        <div id="export-kinerja-area" class="pb-8 space-y-6">
            <!-- Navigation Back & Profile Header -->
            <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                
                <div class="flex items-center space-x-4 w-full sm:w-auto">
                    <button wire:click="backToList" class="export-btn-hide shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-full bg-surface-subtle text-text-muted hover:text-text-main hover:bg-border-card/50 transition-colors focus:outline-none shadow-sm border border-border-card">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </button>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-text-subtle uppercase tracking-widest mb-0.5 font-mono"><?php echo e($selectedLaborCode); ?></span>
                            <h2 class="text-xl sm:text-2xl font-bold text-text-main truncate leading-tight"><?php echo e($selectedTeknisi); ?></h2>
                        </div>
                    </div>
                </div>

                <!-- Export & Date Filter -->
                <div class="flex flex-col sm:flex-row gap-3 items-center w-full sm:w-auto">
                    <!-- Export Actions -->
                    <div class="flex space-x-2 bg-surface-subtle p-1 rounded-full border border-border-card/60 export-btn-hide w-full sm:w-auto justify-center">
                        <button onclick="exportToJPG('export-kinerja-area', 'Rapor_<?php echo e(str_replace(' ', '_', $selectedTeknisi)); ?>')" class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            JPG
                        </button>
                        <button onclick="exportToPDF('export-kinerja-area', 'Rapor_<?php echo e(str_replace(' ', '_', $selectedTeknisi)); ?>')" class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            PDF
                        </button>
                    </div>

                    <!-- Filter Tanggal -->
                    <div class="flex items-center w-full sm:w-auto export-btn-hide bg-surface-subtle rounded-full border border-border-card shadow-sm overflow-hidden">
                        <div class="relative w-1/2 sm:w-[130px] flex items-center border-r border-border-card">
                            <div class="absolute left-3 text-text-muted pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="date" wire:model.live="filterStartDate" class="block w-full py-2.5 pl-8 pr-2 text-xs font-semibold text-text-main bg-transparent focus:bg-white focus:outline-none transition-all" title="Mulai">
                        </div>
                        <div class="relative w-1/2 sm:w-[130px] flex items-center">
                            <div class="absolute left-3 text-text-muted pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="date" wire:model.live="filterEndDate" class="block w-full py-2.5 pl-8 pr-2 text-xs font-semibold text-text-main bg-transparent focus:bg-white focus:outline-none transition-all" title="Akhir">
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterStartDate || $filterEndDate): ?>
                        <button wire:click="$set('filterStartDate', null); $set('filterEndDate', null)" class="export-btn-hide inline-flex items-center justify-center w-8 h-8 rounded-full bg-surface-subtle text-text-muted hover:text-text-main hover:bg-border-card/50 transition-colors focus:outline-none shadow-sm border border-border-card" title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <!-- Hidden Text when exported -->
                    <div class="hidden export-show-text text-sm font-bold text-text-muted bg-surface-subtle px-4 py-2 rounded-full border border-border-card">
                        Periode: <?php echo e($filterStartDate ? date('d M Y', strtotime($filterStartDate)) : 'Awal'); ?> - <?php echo e($filterEndDate ? date('d M Y', strtotime($filterEndDate)) : 'Akhir'); ?>



                    </div>
                </div>
            </div>

            <!-- TAB Navigation (Segmented Pill) -->
            <div class="flex justify-center export-btn-hide">
                <div class="inline-flex bg-surface-subtle p-1.5 rounded-full border border-border-card/60 shadow-sm relative w-[340px] sm:w-[460px]">
                    <button wire:click="setTab('kinerja')" class="relative z-10 w-1/2 py-2.5 text-xs sm:text-sm font-bold rounded-full transition-colors <?php echo e($activeTab === 'kinerja' ? 'text-white' : 'text-text-muted hover:text-text-main'); ?>">
                        Analisa Kinerja
                    </button>
                    <button wire:click="setTab('kendala')" class="relative z-10 w-1/2 py-2.5 text-xs sm:text-sm font-bold rounded-full transition-colors <?php echo e($activeTab === 'kendala' ? 'text-white' : 'text-text-muted hover:text-text-main'); ?>">
                        Analisa Kendala (L/S/CT)
                    </button>
                    <!-- Active Indicator Background -->
                    <div class="absolute top-1.5 bottom-1.5 w-[calc(50%-0.375rem)] bg-telkom-red rounded-full shadow-sm transition-all duration-300 ease-in-out" style="<?php echo e($activeTab === 'kinerja' ? 'left: 0.375rem;' : 'left: 50%;'); ?>"></div>
                </div>
            </div>

            <!-- TAB CONTENT: KINERJA -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'kinerja'): ?>
            
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['total_tiket'] > 0): ?>
                    <!-- Top 4 Summary Cards -->
                    <div class="grid grid-cols-2 xl:grid-cols-4 gap-6">
                        
                        <!-- Card 1: Produktivitas -->
                        <div class="bg-surface-subtle border border-border-card rounded-[24px] p-4 sm:p-6 shadow-sm hover:shadow-card transition-shadow relative overflow-hidden group">
                            <!-- Red Accent Bar -->
                            <div class="absolute top-4 sm:top-6 left-0 w-1.5 h-12 bg-telkom-red rounded-r-md"></div>
                            
                            <div class="flex items-center space-x-2 sm:space-x-3 mb-4 relative z-10">
                                <div class="p-2 sm:p-3 bg-white border border-border-card rounded-full text-text-main shadow-sm">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">Total Produktivitas</h3>
                            </div>
                            <div class="flex items-baseline space-x-2 relative z-10">
                                <span class="text-2xl sm:text-4xl font-extrabold text-text-main tracking-tight font-variant-numeric"><?php echo e($stats['total_tiket']); ?></span>
                                <span class="text-text-muted font-semibold">Tiket</span>
                            </div>
                            <div class="mt-6 pt-4 border-t border-border-card/50 flex justify-between text-sm relative z-10">
                                <div class="text-text-muted font-medium"><span class="font-bold text-text-main bg-surface-subtle px-2.5 py-1.5 rounded-lg mr-1.5 border border-border-card"><?php echo e($stats['total_utama']); ?></span> Utama</div>
                                <div class="text-text-muted font-medium"><span class="font-bold text-text-main bg-surface-subtle px-2.5 py-1.5 rounded-lg mr-1.5 border border-border-card"><?php echo e($stats['total_pendamping']); ?></span> Pendamping</div>
                            </div>
                        </div>

                        <!-- Card 2: Spesialisasi -->
                        <div class="bg-surface-subtle border border-border-card rounded-[24px] p-4 sm:p-6 shadow-sm hover:shadow-card transition-shadow relative overflow-hidden group">
                            <!-- Red Accent Bar -->
                            <div class="absolute top-4 sm:top-6 left-0 w-1.5 h-12 bg-telkom-red rounded-r-md"></div>
                            
                            <div class="flex items-center space-x-2 sm:space-x-3 mb-4 relative z-10">
                                <div class="p-2 sm:p-3 bg-white rounded-full text-text-main border border-border-card shadow-sm">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                                </div>
                                <h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">Spesialisasi Order</h3>
                            </div>
                            <?php
$b2cPercent = $stats['total_tiket'] > 0 ? round(($stats['b2c'] / $stats['total_tiket']) * 100) : 0;
                                $b2bPercent = 100 - $b2cPercent;
?>
                            <div class="flex items-baseline space-x-2 mb-4 relative z-10">
                                <span class="text-2xl sm:text-4xl font-extrabold text-text-main tracking-tight font-variant-numeric"><?php echo e($b2cPercent); ?>%</span>
                                <span class="text-text-muted font-semibold">B2C</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full h-2.5 bg-surface-subtle rounded-full flex overflow-hidden border border-border-card relative z-10">
                                <div class="bg-telkom-red h-full rounded-full" style="width: <?php echo e($b2cPercent); ?>%"></div>
                                <div class="bg-gray-300 h-full rounded-r-full" style="width: <?php echo e($b2bPercent); ?>%"></div>
                            </div>
                            <div class="mt-4 flex justify-between text-xs font-bold text-text-muted relative z-10">
                                <span class="text-text-main flex items-center"><div class="w-2 h-2 rounded-full bg-telkom-red mr-1.5"></div> B2C (<?php echo e($stats['b2c']); ?>)</span>
                                <span class="flex items-center"><div class="w-2 h-2 rounded-full bg-gray-300 mr-1.5"></div> B2B (<?php echo e($stats['b2b']); ?>)</span>
                            </div>
                        </div>

                        <!-- Card 3: Kecepatan SLA -->
                        <div class="bg-surface-subtle border border-border-card rounded-[24px] p-4 sm:p-6 shadow-sm hover:shadow-card transition-shadow relative overflow-hidden group">
                            <!-- Red Accent Bar -->
                            <div class="absolute top-4 sm:top-6 left-0 w-1.5 h-12 bg-telkom-red rounded-r-md"></div>
                            
                            <div class="flex items-center space-x-2 sm:space-x-3 mb-4 relative z-10">
                                <div class="p-2 sm:p-3 bg-white rounded-full text-text-main border border-border-card shadow-sm">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">Kecepatan (SLA)</h3>
                            </div>
                            <?php
$hours = floor($stats['avg_sla_minutes'] / 60);
                                $mins = $stats['avg_sla_minutes'] % 60;
                                $isFast = $stats['avg_sla_minutes'] < 120; // Example: < 2 hours is fast
?>
                            <div class="flex items-baseline space-x-1 relative z-10 font-variant-numeric">
                                <span class="text-2xl sm:text-4xl font-extrabold text-text-main tracking-tight"><?php echo e($hours); ?></span><span class="text-lg font-bold text-text-muted mr-2">J</span>
                                <span class="text-2xl sm:text-4xl font-extrabold text-text-main tracking-tight"><?php echo e($mins); ?></span><span class="text-lg font-bold text-text-muted">M</span>
                            </div>
                            <div class="mt-6 pt-4 border-t border-border-card/50 flex items-center relative z-10">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isFast): ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Rata-rata Cepat
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Perlu Ditingkatkan
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Card 4: Top Area -->
                        <div class="bg-surface-subtle border border-border-card rounded-[24px] p-4 sm:p-6 shadow-sm hover:shadow-card transition-shadow relative overflow-hidden group">
                            <!-- Red Accent Bar -->
                            <div class="absolute top-4 sm:top-6 left-0 w-1.5 h-12 bg-telkom-red rounded-r-md"></div>
                            
                            <div class="flex items-center space-x-2 sm:space-x-3 mb-5 relative z-10">
                                <div class="p-2 sm:p-3 bg-white rounded-full text-text-main border border-border-card shadow-sm">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <h3 class="text-[10px] sm:text-xs font-bold text-text-subtle uppercase tracking-wider leading-tight">Area Dominan</h3>
                            </div>
                            <div class="space-y-4 relative z-10">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $stats['top_areas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?><?php
$percent = $stats['total_tiket'] > 0 ? round(($count / $stats['total_tiket']) * 100) : 0;
?>
                                    <div>
                                        <div class="flex justify-between text-xs font-bold text-text-main mb-2">
                                            <span class="truncate pr-2"><?php echo e($area); ?></span>
                                            <span class="text-blue-600 font-variant-numeric"><?php echo e($percent); ?>%</span>
                                        </div>
                                        <div class="w-full bg-surface-subtle rounded-full h-2 border border-border-card overflow-hidden">
                                            <div class="bg-blue-500 h-full rounded-full" style="width: <?php echo e($percent); ?>%"></div>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <p class="text-sm text-text-muted font-medium">Belum ada data area.</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Table: Riwayat Tiket -->
                    <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card flex flex-col overflow-hidden p-4">
                        <div class="px-2 py-2 mb-2 flex justify-between items-center bg-surface-card shrink-0">
                            <h3 class="text-base sm:text-lg font-bold text-text-main">Riwayat Tiket & Pekerjaan</h3>
                            <span class="text-xs sm:text-sm font-bold text-text-muted bg-surface-subtle px-4 py-1.5 rounded-full border border-border-card shadow-sm"><?php echo e(count($stats['history'])); ?> Baris</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-separate border-spacing-0">
                                <thead>
                                    <tr>
                                        <?php
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
?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100 <?php echo e($loop->first ? 'rounded-l-full' : ''); ?> <?php echo e($loop->last ? 'rounded-r-full' : ''); ?>">
                                                <button wire:click="sortBy('<?php echo e($key); ?>')" class="flex items-center group focus:outline-none w-full text-left hover:text-text-main transition-colors">
                                                    <?php echo e($label); ?>



                                                    <span class="ml-2 flex-none rounded text-text-subtle/50 group-hover:text-text-main">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortColumn === $key): ?>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortDirection === 'asc'): ?>
                                                                <svg class="w-3.5 h-3.5 text-telkom-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                                                            <?php else: ?>
                                                                <svg class="w-3.5 h-3.5 text-telkom-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php else: ?>
                                                            <svg class="w-3.5 h-3.5 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </span>
                                                </button>
                                            </th>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody class="[&>tr:not(:last-child)>td]:border-b [&>tr:not(:last-child)>td]:border-border-card/50 text-[13px] text-text-main">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $stats['history']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <tr class="hover:bg-surface-subtle transition-colors group">
                                            <td class="px-5 py-4 whitespace-nowrap font-medium text-text-main font-variant-numeric tabular-nums"><?php echo e($item['start_dt'] ? date('d M Y, H:i', strtotime($item['start_dt'])) : '-'); ?></td>
                                            <td class="px-5 py-4 whitespace-nowrap text-xs font-bold text-text-muted"><?php echo e($item['segment']); ?></td>
                                            <td class="px-5 py-4 whitespace-nowrap text-text-main"><?php echo e($item['jenis_order']); ?></td>
                                            <td class="px-5 py-4 whitespace-nowrap text-text-main">
                                                <?php echo e(strtoupper($item['peran'] ?? '-')); ?>



                                            </td>
                                            <td class="px-5 py-4 whitespace-nowrap font-medium"><?php echo e($item['workzone']); ?></td>
                                            <td class="px-5 py-4 whitespace-nowrap text-text-muted font-variant-numeric tabular-nums"><?php echo e($item['durasi_teks']); ?></td>
                                            <td class="px-5 py-4 font-mono text-xs text-text-muted font-variant-numeric tabular-nums"><?php echo e($item['tiket_no']); ?></td>
                                            <td class="px-5 py-4 whitespace-nowrap"><?php echo e($item['partner']); ?></td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-surface-subtle rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-text-main">Belum Ada Data Produktivitas</h3>
                        <p class="text-text-muted mt-2">Tidak ditemukan tiket yang dikerjakan pada bulan ini.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
            <?php elseif($activeTab === 'kendala'): ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kendalaStats['total_kendala'] > 0): ?>
                    
                    <div class="bg-surface-card border border-border-card rounded-[32px] p-6 shadow-sm relative overflow-hidden">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-3 bg-amber-50 rounded-xl text-amber-600 border border-amber-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="text-xs font-bold text-text-subtle uppercase tracking-wider">Total Kendala</h3>
                        </div>
                        <div class="flex items-baseline space-x-2 mb-4">
                            <span class="text-4xl font-extrabold text-text-main tracking-tight font-variant-numeric"><?php echo e($kendalaStats['total_kendala']); ?></span>
                            <span class="text-text-muted font-semibold">Tiket</span>
                        </div>
                        <div class="flex flex-wrap gap-2 pt-4 border-t border-border-card/50">
                            <span class="text-xs font-bold text-text-main bg-surface-subtle px-3 py-1.5 rounded-full border border-border-card">Libur: <?php echo e($kendalaStats['total_libur']); ?></span>
                            <span class="text-xs font-bold text-text-main bg-surface-subtle px-3 py-1.5 rounded-full border border-border-card">Sakit: <?php echo e($kendalaStats['total_sakit']); ?></span>
                            <span class="text-xs font-bold text-text-main bg-surface-subtle px-3 py-1.5 rounded-full border border-border-card">CT: <?php echo e($kendalaStats['total_ct']); ?></span>
                        </div>
                    </div>

                    
                    <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card flex flex-col overflow-hidden p-4">
                        <div class="px-2 py-2 mb-2 flex justify-between items-center shrink-0">
                            <h3 class="text-base sm:text-lg font-bold text-text-main">Riwayat Kendala</h3>
                            <span class="text-xs sm:text-sm font-bold text-text-muted bg-surface-subtle px-4 py-1.5 rounded-full border border-border-card shadow-sm"><?php echo e(count($kendalaStats['history'])); ?> Baris</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-separate border-spacing-0">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100 rounded-l-full">TANGGAL</th>
                                        <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100">JENIS KENDALA</th>
                                        <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100">NAMA TEKNISI</th>
                                        <th class="px-5 py-4 font-bold text-text-main uppercase tracking-wider text-xs whitespace-nowrap bg-gray-100 rounded-r-full">KETERANGAN/NILAI</th>
                                    </tr>
                                </thead>
                                <tbody class="[&>tr:not(:last-child)>td]:border-b [&>tr:not(:last-child)>td]:border-border-card/50 text-[13px] text-text-main">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kendalaStats['history']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <tr class="hover:bg-surface-subtle transition-colors group">
                                            <td class="px-5 py-4 whitespace-nowrap font-medium text-text-main font-variant-numeric tabular-nums"><?php echo e(date('d M Y', strtotime($item['tanggal']))); ?></td>
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200"><?php echo e($item['jenis']); ?></span>
                                            </td>
                                            <td class="px-5 py-4 whitespace-nowrap text-text-muted font-bold"><?php echo e($item['nama']); ?></td>
                                            <td class="px-5 py-4 whitespace-nowrap font-mono text-text-subtle"><?php echo e($item['nilai']); ?></td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-surface-subtle rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-text-main">Hebat! Tidak Ada Kendala</h3>
                        <p class="text-text-muted mt-2">Teknisi ini 100% hadir tanpa catatan Libur, Sakit, atau CT pada bulan ini.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>













<?php /**PATH C:\Users\itzda\Documents\webwitelrajawali\resources\views/livewire/kinerja-teknisi.blade.php ENDPATH**/ ?>