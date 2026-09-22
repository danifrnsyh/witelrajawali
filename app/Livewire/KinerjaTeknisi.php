<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\SheetData;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class KinerjaTeknisi extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedTeknisi = null;
    public $selectedLaborCode = null;
    public $sortColumn = 'start_dt';
    public $sortDirection = 'desc';

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

    public function backToList()
    {
        $this->selectedTeknisi = null;
        $this->selectedLaborCode = null;
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
                'history' => []
            ];

            $searchTerm = '%' . strtolower($this->selectedTeknisi) . '%';
            
            // Get all records where nama_teknisi_1 or nama_teknisi_2 matches
            $query = SheetData::whereRaw("LOWER(json_extract(data, '$[11]')) LIKE ?", [$searchTerm])
                ->orWhereRaw("LOWER(json_extract(data, '$[13]')) LIKE ?", [$searchTerm])
                ->get();

            $totalMinutes = 0;
            $validSlaCount = 0;
            $areas = [];

            foreach ($query as $item) {
                $row = $item->data;
                $isUtama = str_contains(strtolower($row[11] ?? ''), strtolower($this->selectedTeknisi));
                $isPendamping = str_contains(strtolower($row[13] ?? ''), strtolower($this->selectedTeknisi));
                
                // Track productivity
                if ($isUtama) $techStats['total_utama']++;
                if ($isPendamping) $techStats['total_pendamping']++;
                $techStats['total_tiket']++;

                // Track segment (B2C/B2B)
                $segment = strtolower($row[2] ?? '');
                if (str_contains($segment, 'b2c')) {
                    $techStats['b2c']++;
                } else if (str_contains($segment, 'b2b') || str_contains($segment, 'ebis')) {
                    $techStats['b2b']++;
                } else {
                    $techStats['b2c']++; 
                }

                // Track SLA
                $startDt = $row[14] ?? null;
                $closeDt = $row[15] ?? null;
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
                $area = $row[16] ?? 'Unknown';
                if (!empty(trim($area))) {
                    if (!isset($areas[$area])) $areas[$area] = 0;
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
                    'start_dt' => $startDt,
                    'close_dt' => $closeDt,
                    'segment' => $row[2] ?? '',
                    'jenis_order' => $row[3] ?? '',
                    'workzone' => $area,
                    'tiket_no' => $row[7] ?? '-',
                    'order_no' => $row[8] ?? '-',
                    'partner' => $isUtama ? ($row[13] ?? '-') : ($row[11] ?? '-'),
                    'peran' => $isUtama ? 'Utama' : 'Pendamping',
                    'durasi_menit' => $durasiMenitRow,
                    'durasi_teks' => $durasiTeksRow
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
            usort($techStats['history'], function($a, $b) use ($col, $dir) {
                $valA = $a[$col] ?? '';
                $valB = $b[$col] ?? '';
                if ($valA == $valB) return 0;
                return ($valA < $valB ? -1 : 1) * $dir;
            });

            return view('livewire.kinerja-teknisi', [
                'stats' => $techStats,
                'technicians' => null
            ]);
        }

        // === MODE DAFTAR TEKNISI ===
        
        // Menggunakan SQLite json_extract untuk menarik data unik teknisi
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
            
        // Gabungkan, bersihkan, dan filter pencarian
        $allTechs = $techs1->merge($techs2)
            ->filter(fn($t) => !empty(trim($t->nama)))
            ->unique(fn($t) => strtoupper(trim($t->nama)))
            ->values();

        if (!empty($this->search)) {
            $searchTerm = strtolower($this->search);
            $allTechs = $allTechs->filter(function($t) use ($searchTerm) {
                return str_contains(strtolower($t->nama), $searchTerm) || 
                       str_contains(strtolower($t->labor_code), $searchTerm);
            })->values();
        }

        // Manual Pagination for Collections
        $perPage = 15;
        $page = $this->getPage();
        $paginatedTechs = new \Illuminate\Pagination\LengthAwarePaginator(
            $allTechs->slice(($page - 1) * $perPage, $perPage),
            $allTechs->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('livewire.kinerja-teknisi', [
            'stats' => null,
            'technicians' => $paginatedTechs
        ]);
    }
}
