@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center sm:justify-end w-full">
        <div class="flex items-center space-x-0.5 sm:space-x-1 bg-surface-subtle p-1 sm:p-1.5 rounded-full border border-border-card/60 shadow-sm max-w-full overflow-x-auto hide-scrollbar">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-1.5 sm:min-w-8 sm:h-8 sm:px-2 shrink-0 rounded-full text-text-muted/50 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="inline-flex items-center justify-center min-w-[28px] h-7 px-1.5 sm:min-w-8 sm:h-8 sm:px-2 shrink-0 rounded-full text-text-muted hover:text-text-main hover:bg-white hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-telkom-red focus:ring-offset-1 focus:ring-offset-surface-subtle">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center min-w-[20px] h-7 px-1 sm:min-w-6 sm:h-8 shrink-0 rounded-full text-text-muted/70 font-semibold text-xs sm:text-sm">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-1.5 sm:min-w-8 sm:h-8 sm:px-2 shrink-0 rounded-full bg-telkom-red text-white font-bold text-xs sm:text-sm shadow-sm" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="inline-flex items-center justify-center min-w-[28px] h-7 px-1.5 sm:min-w-8 sm:h-8 sm:px-2 shrink-0 rounded-full text-text-muted font-semibold text-xs sm:text-sm hover:text-text-main hover:bg-white hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-telkom-red focus:ring-offset-1 focus:ring-offset-surface-subtle">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="inline-flex items-center justify-center min-w-[28px] h-7 px-1.5 sm:min-w-8 sm:h-8 sm:px-2 shrink-0 rounded-full text-text-muted hover:text-text-main hover:bg-white hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-telkom-red focus:ring-offset-1 focus:ring-offset-surface-subtle">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
            @else
                <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-1.5 sm:min-w-8 sm:h-8 sm:px-2 shrink-0 rounded-full text-text-muted/50 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </span>
            @endif
        </div>
    </nav>
@endif





