<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-surface-card p-6 md:p-8 rounded-[32px] shadow-sm border border-border-card relative overflow-hidden mb-6">
        <div class="absolute top-0 right-0 w-32 h-32 bg-bg-ambient rounded-bl-full -mt-8 -mr-8 blur-3xl opacity-50 pointer-events-none"></div>
        
        <div class="relative z-10">
            <h1 class="text-3xl font-bold tracking-tight text-text-main">Data Sheet Utama</h1>
            <p class="mt-2 text-sm text-text-muted">Tampilan data tersinkronisasi dari spreadsheet utama.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center relative z-10">
              
              <!-- Row 1: Search & Refresh -->
              <div class="flex flex-row items-center gap-2 w-full sm:w-auto order-1 sm:order-none">
                  <div class="relative flex-1 w-full sm:w-48 xl:w-64">
                      <svg class="absolute top-1/2 left-4 -translate-y-1/2 w-4 h-4 text-text-subtle pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                      <input wire:model.live.debounce.300ms="search" type="text" class="block w-full py-2.5 pl-10 pr-4 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red focus:border-telkom-red shadow-sm outline-none transition-all placeholder:text-text-subtle" placeholder="Cari data...">
                  </div>
                  
                  <button wire:click="resetData" class="export-btn-hide inline-flex items-center justify-center p-2.5 text-text-muted bg-surface-subtle hover:bg-white hover:text-text-main border border-border-card rounded-full shadow-sm transition-colors shrink-0" title="Refresh">
                      <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                  </button>
              </div>

              <!-- Date Filters -->
              <div class="flex flex-row items-center space-x-2 w-full sm:w-auto justify-center mt-2 sm:mt-0 order-2 sm:order-none">
                  <div class="relative w-1/2 sm:w-auto">
                      <span class="absolute -top-2 left-3 px-1 text-[10px] bg-white text-text-muted">Dari Tanggal</span>
                      <input wire:model.live="startDate" type="text" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" placeholder="dd/mm/yyyy" class="block w-full sm:w-[135px] py-2.5 px-3 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red shadow-sm outline-none transition-all min-h-[44px]">
                  </div>
                  <span class="text-text-muted font-bold hidden sm:block">-</span>
                  <div class="relative w-1/2 sm:w-auto">
                      <span class="absolute -top-2 left-3 px-1 text-[10px] bg-white text-text-muted">Sampai Tanggal</span>
                      <input wire:model.live="endDate" type="text" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" placeholder="dd/mm/yyyy" class="block w-full sm:w-[135px] py-2.5 px-3 text-sm text-text-main border border-border-card rounded-full bg-white focus:ring-1 focus:ring-telkom-red shadow-sm outline-none transition-all min-h-[44px]">
                  </div>
              </div>

              <!-- Add Data Button -->
              @auth
              <div class="w-full sm:w-auto order-3 sm:order-none mt-2 sm:mt-0">
                  <button wire:click="openAddModal" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-[#006400] hover:bg-[#004d00] rounded-full shadow-card transition-colors shrink-0">
                      <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                      <span class="inline">Tambah Data</span>
                  </button>
              </div>
              @endauth
              
          </div>
      </div>
  </div>

  <!-- Master Grid Container --><!-- Master Grid Container -->
    <div class="bg-surface-card rounded-[32px] shadow-sm border border-border-card flex flex-col overflow-hidden relative p-4">
        <div class="relative">
            <div class="overflow-x-auto relative z-10">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr>
                        @foreach($headers as $index => $header)
                            <th wire:key="header-{{ $index }}" class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-text-main whitespace-nowrap bg-gray-100 {{ $loop->first ? 'rounded-l-[26px]' : '' }} {{ !auth()->check() && $loop->last ? 'rounded-r-[26px]' : '' }}">
                                <button wire:click="sortBy({{ $index }})" class="flex items-center group focus:outline-none hover:text-text-main transition-colors w-full text-left">
                                    {{ $header }}
                                    <span class="ml-2 flex-none rounded text-text-subtle/50 group-hover:text-text-main">
                                        @if($sortColumn === $index)
                                            @if($sortDirection === 'asc')
                                                <svg class="w-3.5 h-3.5 text-telkom-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-telkom-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                            @endif
                                        @else
                                            <svg class="w-3.5 h-3.5 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                        @endif
                                    </span>
                                </button>
                            </th>
                        @endforeach
                        @auth
                            <th class="no-export px-3 sm:px-5 py-4 text-xs font-bold uppercase tracking-wider text-text-main text-right bg-gray-100 rounded-r-full sticky right-0 z-20 drop-shadow-[-2px_0_2px_rgba(0,0,0,0.02)]">Aksi</th>
                        @endauth
                    </tr>
                </thead>
                <tbody class="[&>tr:not(:last-child)>td]:border-b [&>tr:not(:last-child)>td]:border-border-card/50 font-variant-numeric tabular-nums text-[13px] text-text-main">
                    @forelse($paginatedData as $row)
                        <tr wire:key="row-{{ $row['row_index'] }}-{{ md5(json_encode($row['data'] ?? [])) }}" class="hover:bg-surface-subtle transition-colors group">
                            @for($i = 0; $i < count($headers); $i++)
                                <td wire:key="cell-{{ $row['row_index'] }}-{{ $i }}-{{ md5($row['data'][$i] ?? '') }}" class="px-5 py-4 whitespace-nowrap">{{ $row['data'][$i] ?? '-' }}</td>
                            @endfor
                            @auth
                                <td class="no-export px-3 sm:px-5 py-4 whitespace-nowrap text-right sticky right-0 bg-surface-card group-hover:bg-surface-subtle border-l border-border-card/50 transition-colors">
                                    <button wire:click="editRow({{ $row['row_index'] ?? 0 }})" class="p-1.5 sm:px-4 sm:py-1.5 rounded-lg sm:rounded-full bg-white border border-border-card text-text-muted hover:text-telkom-red hover:border-telkom-red shadow-sm transition-all flex items-center justify-center mx-auto sm:mx-0 sm:ml-auto" title="Edit">
                                        <svg class="w-4 h-4 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span class="hidden sm:inline text-xs font-bold">Edit</span>
                                    </button>
                                </td>
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-surface-subtle rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <p class="text-text-main font-medium">Data tidak ditemukan.</p>
                                    <p class="text-text-muted text-sm mt-1">Coba sesuaikan kata kunci pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>

        <!-- Floating Pagination Row -->
        <div class="px-2 sm:px-6 py-4 bg-surface-card border-t border-border-card flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm font-medium text-text-muted flex items-center bg-surface-subtle px-4 py-1.5 rounded-full border border-border-card">
                <span class="mr-2">Tampilkan:</span>
                <select wire:model.live="perPage" class="bg-transparent font-bold text-text-main focus:outline-none">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                </select>
                <span class="ml-2">data</span>
            </div>
            
            <div class="w-full sm:w-auto flex justify-center sm:justify-end">
                {{ $paginatedData->links('vendor.livewire.pill-pagination') }}
            </div>
        </div>
    </div>

    <!-- MODAL EDIT ADMIN -->
    @if($isEditModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-text-main/40 backdrop-blur-[2px]">
        <div class="bg-surface-card rounded-[32px] shadow-card border border-white/50 w-full max-w-5xl relative flex flex-col overflow-hidden transform transition-all" style="max-height: 90vh;">
            <div class="px-8 py-5 border-b border-border-card flex justify-between items-center bg-surface-subtle shrink-0">
                <h3 class="text-lg font-bold text-text-main">Edit Data (Baris {{ $editingRowIndex }})</h3>
                <button wire:click="closeEditModal" class="p-2 text-text-subtle hover:text-telkom-red hover:bg-telkom-red-light rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-8 overflow-y-auto grow  bg-surface-card">
                @if(session()->has('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 text-sm font-medium rounded-xl border border-green-200 flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="mb-6 p-4 bg-red-50 text-red-700 text-sm font-medium rounded-xl border border-red-200 flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($headers as $index => $header)
                        @if(trim($header) !== '')
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-text-muted mb-2 uppercase tracking-wide">{{ $header }}</label>
                            <input type="text" wire:model="editForm.{{ $index }}" class="w-full px-4 py-2.5 bg-surface-subtle border border-border-card rounded-xl text-sm font-medium focus:bg-white focus:ring-2 focus:ring-telkom-red focus:border-telkom-red transition-all outline-none">
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="px-8 py-5 border-t border-border-card bg-surface-subtle grid grid-cols-2 sm:flex sm:flex-row sm:justify-end gap-3 shrink-0">
                <button wire:click="closeEditModal" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-border-card rounded-full text-center flex items-center justify-center text-sm font-bold text-text-muted hover:text-text-main shadow-sm transition-colors">Batal</button>
                <button wire:click="saveRow" class="w-full sm:w-auto px-6 py-2.5 bg-telkom-red text-white rounded-full text-center flex items-center justify-center text-sm font-bold hover:bg-telkom-red-hover shadow-sm flex items-center transition-colors">
                    <svg wire:loading wire:target="saveRow" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL ADD ADMIN -->
    @if($isAddModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-text-main/40 backdrop-blur-[2px]">
        <div class="bg-surface-card rounded-[32px] shadow-card border border-white/50 w-full max-w-5xl relative flex flex-col overflow-hidden transform transition-all" style="max-height: 90vh;">
            <div class="px-8 py-5 border-b border-border-card flex justify-between items-center bg-surface-subtle shrink-0">
                <h3 class="text-lg font-bold text-text-main">Tambah Data Baru</h3>
                <button wire:click="closeAddModal" class="p-2 text-text-subtle hover:text-telkom-red hover:bg-telkom-red-light rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-8 overflow-y-auto grow bg-surface-card">
                @if(session()->has('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 text-sm font-medium rounded-xl border border-green-200 flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="mb-6 p-4 bg-red-50 text-red-700 text-sm font-medium rounded-xl border border-red-200 flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($headers as $index => $header)
                        @if(trim($header) !== '')
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-text-muted mb-2 uppercase tracking-wide">{{ $header }}</label>
                            <input type="text" wire:model="addForm.{{ $index }}" class="w-full px-4 py-2.5 bg-surface-subtle border border-border-card rounded-xl text-sm font-medium focus:bg-white focus:ring-2 focus:ring-telkom-red focus:border-telkom-red transition-all outline-none">
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="px-8 py-5 border-t border-border-card bg-surface-subtle grid grid-cols-2 sm:flex sm:flex-row sm:justify-end gap-3 shrink-0">
                <button wire:click="closeAddModal" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-border-card rounded-full text-center flex items-center justify-center text-sm font-bold text-text-muted hover:text-text-main shadow-sm transition-colors">Batal</button>
                <button wire:click="addRow" class="px-6 py-2.5 bg-[#006400] text-white rounded-full text-sm font-bold hover:bg-[#004d00] shadow-sm flex items-center transition-colors">
                    <svg wire:loading wire:target="addRow" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Simpan Data
                </button>
            </div>
        </div>
    </div>
    @endif
</div>














