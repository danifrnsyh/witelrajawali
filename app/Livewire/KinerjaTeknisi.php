<?php

namespace App\Livewire;

use App\Models\SheetData;
use App\Services\GoogleSheetService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class KinerjaTeknisi extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedTeknisi = null;

    public $selectedLaborCode = null;

    public $sortColumn = 'start_dt';

    public $sortDirection = 'desc';

    public $filterStartDate = null;

    public $filterEndDate = null;

    public $activeTab = 'kinerja';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function viewDetail($nama, $laborCode)
    {
        $this->selectedTeknisi = $nama;
        $this->selectedLaborCode = $laborCode;
        $this->sortColumn = 'start_dt';
        $this->sortDirection = 'desc';
    }

    public function editTicket($rowIndex)
    {
        if (! Auth::check()) {
            return;
        }

        $ticket = SheetData::where('row_index', $rowIndex)->first();
        if ($ticket) {
            $data = json_decode($ticket->data, true);
            $this->editingRowIndex = $rowIndex;
            // Load required fields into form (0-indexed)
            $this->editForm = [
                'segment' => $data[2] ?? '',
                'jenis_order' => $data[3] ?? '',
                'tiket_no' => $data[7] ?? '',
                'order_no' => $data[8] ?? '',
                'labor_1' => $data[11] ?? '',
                'labor_2' => $data[13] ?? '',
                'start_dt' => $data[14] ?? '',
                'close_dt' => $data[15] ?? '',
                'workzone' => $data[16] ?? '',
            ];
            $this->isEditModalOpen = true;
        }
    }

    public function saveTicket()
    {
        if (! Auth::check() || ! $this->editingRowIndex) {
            return;
        }

        $ticket = SheetData::where('row_index', $this->editingRowIndex)->first();
        if ($ticket) {
            $data = json_decode($ticket->data, true);

            // Pad array to ensure we have enough columns (up to index 16)
            $data = array_pad($data, 17, '');

            // Update with form values
            $data[2] = $this->editForm['segment'];
            $data[3] = $this->editForm['jenis_order'];
            $data[7] = $this->editForm['tiket_no'];
            $data[8] = $this->editForm['order_no'];
            $data[11] = $this->editForm['labor_1'];
            $data[13] = $this->editForm['labor_2'];
            $data[14] = $this->editForm['start_dt'];
            $data[15] = $this->editForm['close_dt'];
            $data[16] = $this->editForm['workzone'];

            // Save back to Google Sheets via API
            try {
                $service = new GoogleSheetService;
                $range = "Sheet1!A{$this->editingRowIndex}:Q{$this->editingRowIndex}";
                $service->updateRow($range, $data);

                // Re-sync specific row locally or run sync job
                $ticket->data = json_encode($data);
                $ticket->save();

                // Clear the cache so it pulls the fresh data immediately
                Cache::forget('all_ticket_data_v3');
                Cache::forget('all_technicians_list_v2');

                session()->flash('success', 'Data tiket berhasil diperbarui!');
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal update ke Google Sheets: '.$e->getMessage());
            }

            $this->isEditModalOpen = false;
        }
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->editingRowIndex = null;
    }

    public function backToList()
    {
        $this->selectedTeknisi = null;
        $this->selectedLaborCode = null;
        $this->filterStartDate = null;
        $this->filterEndDate = null;
    }

    public function render()
    {
        // === MODE DETAIL ===
        if ($this->selectedTeknisi) {
            $techStats = [
                'total_utama' => 0,
                'total_pendamping' => 0,
                'total_tiket' => 0,
                'b2c' => 0,
                'b2b' => 0,
                'avg_sla_minutes' => 0,
                'top_areas' => [],
                'history' => [],
            ];

            // Cache the whole simplified dataset once so detail filtering is instant in PHP
            $cachedTickets = Cache::remember('all_ticket_data_v3', 3600, function () {
                $raw = DB::table('sheet_data')->get();
                $tickets = [];
                foreach ($raw as $item) {
                    $row = json_decode($item->data, true);
                    $tickets[] = [
                        'row_index' => $item->row_index, // Menambahkan ID baris aslinya
                        'segment' => $row[2] ?? '',
                        'jenis_order' => $row[3] ?? '',
                        'tiket_no' => $row[7] ?? '-',
                        'order_no' => $row[8] ?? '-',
                        'labor_1_raw' => $row[11] ?? '',
                        'labor_2_raw' => $row[13] ?? '',
                        'start_dt' => $row[14] ?? null,
                        'close_dt' => $row[15] ?? null,
                        'workzone' => $row[16] ?? 'Unknown',
                    ];
                }

                return $tickets;
            });

            $totalMinutes = 0;
            $validSlaCount = 0;
            $areas = [];
            $searchTermLower = strtolower($this->selectedTeknisi);

            foreach ($cachedTickets as $row) {
                $labor1Lower = strtolower($row['labor_1_raw']);
                $labor2Lower = strtolower($row['labor_2_raw']);

                // Cek apakah teknisi yang dipilih ada di tiket ini
                $isUtama = str_contains($labor1Lower, $searchTermLower);
                $isPendamping = str_contains($labor2Lower, $searchTermLower);

                if (! $isUtama && ! $isPendamping) {
                    continue; // Skip jika tidak ada
                }

                $startDt = $row['start_dt'];
                $closeDt = $row['close_dt'];

                // Apply Date Filter based on start_dt
                if ($startDt) {
                    $timestamp = strtotime($startDt);
                    if ($timestamp) {
                        $dateOnly = date('Y-m-d', $timestamp);
                        if ($this->filterStartDate && $dateOnly < $this->filterStartDate) {
                            continue;
                        }
                        if ($this->filterEndDate && $dateOnly > $this->filterEndDate) {
                            continue;
                        }
                    }
                } elseif ($this->filterStartDate || $this->filterEndDate) {
                    continue; // Jika tidak ada tanggal namun filter aktif, skip
                }

                // Track productivity
                if ($isUtama) {
                    $techStats['total_utama']++;
                }
                if ($isPendamping) {
                    $techStats['total_pendamping']++;
                }
                $techStats['total_tiket']++;

                // Track segment (B2C/B2B)
                $segment = strtolower($row['segment']);
                if (str_contains($segment, 'b2c')) {
                    $techStats['b2c']++;
                } elseif (str_contains($segment, 'b2b') || str_contains($segment, 'ebis')) {
                    $techStats['b2b']++;
                } else {
                    $techStats['b2c']++;
                }

                // Track SLA
                if ($startDt && $closeDt) {
                    $start = strtotime($startDt);
                    $close = strtotime($closeDt);
                    if ($start && $close && $close > $start) {
                        $diffMinutes = ($close - $start) / 60;
                        $totalMinutes += $diffMinutes;
                        $validSlaCount++;
                    }
                }

                // Track Area (Workzone)
                $area = $row['workzone'];
                if (! empty(trim($area))) {
                    if (! isset($areas[$area])) {
                        $areas[$area] = 0;
                    }
                    $areas[$area]++;
                }

                // Calculate duration per row for display
                $durasiMenitRow = 0;
                $durasiTeksRow = '-';
                if ($startDt && $closeDt) {
                    $s = strtotime($startDt);
                    $c = strtotime($closeDt);
                    if ($s && $c && $c > $s) {
                        $durasiMenitRow = round(($c - $s) / 60);
                        $j = floor($durasiMenitRow / 60);
                        $m = $durasiMenitRow % 60;
                        $durasiTeksRow = $j > 0 ? "{$j}J {$m}M" : "{$m}M";
                    }
                }

                // History Table
                $techStats['history'][] = [
                    'row_index' => $row['row_index'],
                    'start_dt' => $startDt,
                    'close_dt' => $closeDt,
                    'segment' => $row['segment'],
                    'jenis_order' => $row['jenis_order'],
                    'workzone' => $area,
                    'tiket_no' => $row['tiket_no'],
                    'order_no' => $row['order_no'],
                    'partner' => $isUtama ? ($row['labor_2_raw'] ?? '-') : ($row['labor_1_raw'] ?? '-'),
                    'peran' => $isUtama ? 'Utama' : 'Pendamping',
                    'durasi_menit' => $durasiMenitRow,
                    'durasi_teks' => $durasiTeksRow,
                ];
            }

            if ($validSlaCount > 0) {
                $techStats['avg_sla_minutes'] = round($totalMinutes / $validSlaCount);
            }

            arsort($areas);
            $techStats['top_areas'] = array_slice($areas, 0, 3, true);

            // Apply Sorting to history
            $col = $this->sortColumn;
            $dir = $this->sortDirection === 'asc' ? 1 : -1;
            usort($techStats['history'], function ($a, $b) use ($col, $dir) {
                $valA = $a[$col] ?? '';
                $valB = $b[$col] ?? '';
                if ($valA == $valB) {
                    return 0;
                }

                return ($valA < $valB ? -1 : 1) * $dir;
            });

            $kendalaStats = ['total_kendala' => 0, 'total_libur' => 0, 'total_sakit' => 0, 'total_ct' => 0, 'top_kendala' => [], 'history' => []];

            // Atas permintaan Anda: Data absen/kendala dari Dashboard Harian tidak lagi digabungkan.
            // Halaman ini sekarang 100% murni hanya mengambil data tiket dari Sheet1 (GOOGLE_SHEET_ID).
            return view('livewire.kinerja-teknisi', [
                'kendalaStats' => $kendalaStats,
                'stats' => $techStats,
                'technicians' => null,
            ]);
        }

        // === MODE DAFTAR TEKNISI ===

        // Caching data teknisi agar tidak berat dan lag saat pencarian/load
        // Cache sebagai array murni untuk menghindari masalah unserialize object Collection
        $cachedTechs = Cache::remember('all_technicians_list_v2', 3600, function () {
            // Query Teknisi Utama
            $techs1 = DB::table('sheet_data')
                ->selectRaw("json_extract(data, '$[11]') as nama, json_extract(data, '$[10]') as labor_code")
                ->whereRaw("json_extract(data, '$[11]') IS NOT NULL")
                ->whereRaw("json_extract(data, '$[11]') != ''")
                ->whereRaw("LOWER(json_extract(data, '$[11]')) != 'nama_teknisi_1'")
                ->distinct()
                ->get();

            // Query Teknisi Pendamping
            $techs2 = DB::table('sheet_data')
                ->selectRaw("json_extract(data, '$[13]') as nama, json_extract(data, '$[12]') as labor_code")
                ->whereRaw("json_extract(data, '$[13]') IS NOT NULL")
                ->whereRaw("json_extract(data, '$[13]') != ''")
                ->whereRaw("LOWER(json_extract(data, '$[13]')) != 'nama_teknisi_2'")
                ->distinct()
                ->get();

            // Gabungkan, bersihkan, jadikan array murni
            $merged = $techs1->merge($techs2)
                ->filter(fn ($t) => ! empty(trim($t->nama)))
                ->unique(fn ($t) => strtoupper(trim($t->nama)))
                ->values();

            // Konversi ke array asosiatif agar aman di-serialize oleh cache
            return json_decode(json_encode($merged), true);
        });

        // Konversi kembali ke Collection
        $allTechs = collect($cachedTechs)->map(fn ($item) => (object) $item);

        if (! empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $allTechs = $allTechs->filter(function ($t) use ($searchTerm) {
                return str_contains(strtolower($t->nama), $searchTerm) ||
                       str_contains(strtolower($t->labor_code), $searchTerm);
            })->values();
        }

        // Manual Pagination for Collections
        $perPage = 15;
        $page = $this->getPage();
        $paginatedTechs = new LengthAwarePaginator(
            $allTechs->slice(($page - 1) * $perPage, $perPage),
            $allTechs->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $kendalaStats = ['total_kendala' => 0, 'total_libur' => 0, 'total_sakit' => 0, 'total_ct' => 0, 'top_kendala' => [], 'history' => []];

        return view('livewire.kinerja-teknisi', [
            'kendalaStats' => $kendalaStats,
            'stats' => null,
            'technicians' => $paginatedTechs,
        ]);
    }
}
