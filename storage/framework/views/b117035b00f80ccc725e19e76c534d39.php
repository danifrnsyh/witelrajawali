<div class="space-y-6" id="export-heatmap-area">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-surface-card p-6 md:p-8 rounded-[32px] shadow-sm border border-border-card relative overflow-hidden mb-6">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bg-ambient rounded-bl-full -mt-8 -mr-8 blur-3xl opacity-50 pointer-events-none"></div>
        
        <div class="relative z-10">
            <h1 class="text-3xl font-bold tracking-tight text-text-main"><?php echo e($title); ?></h1>
            <p class="mt-2 text-sm text-text-muted"><?php echo e(array_sum(array_map('count', $segments))); ?> Teknisi &bull; Bulan Ini</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center relative z-10">
            <!-- Export Buttons -->
            <div class="flex space-x-2 bg-surface-subtle p-1 rounded-full border border-border-card/60">
                <button onclick="exportToJPG('export-heatmap-area', 'Heatmap_Harian')" class="export-btn-hide inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    JPG
                </button>
                <button onclick="exportToPDF('export-heatmap-area', 'Heatmap_Harian')" class="export-btn-hide inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-white text-text-muted hover:text-text-main shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    PDF
                </button>
            </div>
            
            <!-- Search Bar -->
            <div class="relative w-full sm:w-64 export-btn-hide">
                <svg class="absolute top-1/2 left-4 -translate-y-1/2 w-4 h-4 text-text-subtle pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama teknisi..." class="block w-full py-2.5 pl-10 pr-4 text-sm font-semibold text-text-main border border-border-card rounded-full bg-white focus:bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm transition-all outline-none">
            </div>

            <!-- Refresh Button -->
            <button wire:click="$set('search', '')" class="export-btn-hide inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-telkom-red hover:bg-telkom-red-hover rounded-full shadow-card transition-colors w-full sm:w-auto whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh
            </button>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $segmentTitle => $rows): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <div class="bg-surface-card border border-border-card rounded-[32px] p-6 shadow-sm overflow-hidden flex flex-col mb-6">
        <h2 class="text-xl font-bold text-telkom-red px-2 pb-4 uppercase tracking-wide"><?php echo e($segmentTitle); ?></h2>
        <!-- Legend -->
        <div class="flex flex-wrap justify-end items-center mb-3 gap-3 text-xs font-bold text-text-muted">
            <div class="flex items-center space-x-1.5">
                <div class="w-3.5 h-3.5 bg-green-500 rounded-sm shadow-sm"></div>
                <span>≥ 1.5</span>
            </div>
            <div class="flex items-center space-x-1.5">
                <div class="w-3.5 h-3.5 bg-yellow-400 rounded-sm shadow-sm"></div>
                <span>1.0 - 1.49</span>
            </div>
            <div class="flex items-center space-x-1.5">
                <div class="w-3.5 h-3.5 bg-red-500 rounded-sm shadow-sm"></div>
                <span>0 - 0.99</span>
            </div>
            <div class="flex items-center space-x-1.5">
                <div class="w-3.5 h-3.5 bg-gray-200 rounded-sm border border-gray-300"></div>
                <span>Libur</span>
            </div>
        </div>

        <div class="relative">
            <!-- Fixed Pill Background for Header -->
            <div class="absolute top-[8px] left-0 right-0 h-[32px] bg-gray-100 rounded-full pointer-events-none z-0"></div>
            
            <div class="overflow-x-auto pb-4 relative z-10">
                <table class="w-full text-left border-separate border-spacing-y-2 border-spacing-x-1">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 font-bold text-text-main text-xs uppercase tracking-wider min-w-[200px] bg-transparent">Teknisi</th>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dateCols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <th class="px-1 py-2 text-center font-bold text-text-muted text-[10px] w-6 bg-transparent" title="<?php echo e($date); ?>">
                                    <?php echo e(explode('-', $date)[0] ?? ''); ?>

                                </th>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <th class="px-4 py-2 text-center font-bold text-text-main text-xs uppercase tracking-wider bg-transparent">PROD</th>
                            <th class="px-4 py-2 text-left font-bold text-text-main text-xs uppercase tracking-wider bg-transparent w-28 min-w-[7rem] whitespace-nowrap">Vs Target</th>
                        </tr>
                    </thead>
                <tbody class="[&>tr>td]:border-b border-border-card/50">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-surface-subtle transition-colors group">
                            <td class="px-2 py-1.5 whitespace-nowrap rounded-l-full">
                                <div class="font-bold text-text-main text-xs"><?php echo e($row['name']); ?></div>
                            </td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $row['daily']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php
                                    $val = $day['val'];
                                    $bg = 'bg-gray-100/40 border border-border-card/20';
                                    if (is_numeric($val) && trim($val) !== '') {
                                        $num = (float)$val;
                                        if ($num >= 1.5) {
                                            $bg = 'bg-green-500 text-white';
                                        } elseif ($num >= 1.0) {
                                            $bg = 'bg-yellow-400 text-yellow-950';
                                        } else {
                                            $bg = 'bg-red-500 text-white';
                                        }
                                    } elseif (in_array(trim($val), ['L', 'S', 'CT'])) {
                                        $bg = 'bg-gray-200 border border-gray-300';
                                    }
                                ?>
                                <td class="px-0 py-1.5 text-center">
                                    <div class="w-5 h-5 mx-auto rounded-sm <?php echo e($bg); ?>" title="<?php echo e($day['date']); ?>: <?php echo e($val); ?>"></div>
                                </td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <td class="px-4 py-1.5 text-center font-extrabold text-sm font-variant-numeric <?php echo e($row['avg'] >= 1.5 ? 'text-green-600' : ($row['avg'] >= 1.0 ? 'text-yellow-500' : 'text-telkom-red')); ?>">
                                <?php echo e(number_format($row['avg'], 2)); ?>

                            </td>
                            <td class="px-4 py-1.5 rounded-r-full align-middle min-w-[7rem] w-28">
                                <?php
                                    $pct = $target > 0 ? min(100, ($row['avg'] / $target) * 100) : 0;
                                    $barBg = $row['avg'] >= 1.5 ? 'bg-green-600' : ($row['avg'] >= 1.0 ? 'bg-yellow-500' : 'bg-telkom-red');
                                ?>
                                <div class="w-full h-2.5 bg-surface-subtle rounded-full overflow-hidden border border-border-card relative">
                                    <div class="h-full rounded-full <?php echo e($barBg); ?> transition-all" style="width: <?php echo e($pct); ?>%"></div>
                                    <!-- Target Marker -->
                                    <div class="absolute top-0 bottom-0 w-0.5 bg-black z-10" style="left: <?php echo e(min(100, (1.5 / max(1.5, $row['avg'] ?: 1.5)) * 100)); ?>%"></div>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <div class="bg-surface-card border border-border-card rounded-[32px] p-6 shadow-sm overflow-hidden flex flex-col mb-6">
        <div class="py-12 text-center text-text-muted font-medium">Tidak ada data teknisi.</div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\itzda\Documents\webwitelrajawali\resources\views/livewire/heatmap-viewer.blade.php ENDPATH**/ ?>