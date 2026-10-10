<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SobiDigiposData;
use App\Models\SheetData;
use App\Models\DashboardData;
use Illuminate\Support\Facades\Auth;

class SobiDigiposViewer extends Component
{
    public $search = '';
    public $sortColumn = 'capaian';
    public $sortDirection = 'desc';

    // Admin controls
    public $isEditModalOpen = false;
    public $availableMonths = [];
    public $selectedMonthsInput = [];
    public $selectedYearInput = '';

    // Detail Modal controls
    public $isDetailModalOpen = false;
    public $detailTitle = '';
    public $detailRecords = [];

    public function mount()
    {
        $this->loadAvailableMonths();
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

    public function loadAvailableMonths()
    {
        $allData = SobiDigiposData::where('row_index', '>', 1)->get();
        $months = [];
        foreach ($allData as $item) {
            $data = is_string($item->data) ? json_decode($item->data, true) : $item->data;
            if (isset($data[2]) && trim($data[2]) !== '') {
                $months[trim($data[2])] = true;
            }
        }
        $this->availableMonths = array_keys($months);
    }

    public function getActiveConfig()
    {
        $configRow = SobiDigiposData::where('row_index', 0)->first();
        if ($configRow) {
            $config = is_string($configRow->data) ? json_decode($configRow->data, true) : $configRow->data;
            return [
                'months' => $config['selected_months'] ?? [],
                'year' => $config['selected_year'] ?? date('Y')
            ];
        }
        return [
            'months' => [],
            'year' => date('Y')
        ];
    }

    public function openEditModal()
    {
        $this->loadAvailableMonths();
        $config = $this->getActiveConfig();
        $this->selectedMonthsInput = $config['months'];
        $this->selectedYearInput = $config['year'];
        $this->isEditModalOpen = true;
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
    }

    public function saveMonths()
    {
        if (!Auth::check()) return;

        $configRow = SobiDigiposData::firstOrNew(['row_index' => 0]);
        $configRow->data = [
            'selected_months' => $this->selectedMonthsInput,
            'selected_year' => $this->selectedYearInput
        ];
        $configRow->save();

        $this->isEditModalOpen = false;
        session()->flash('success', 'Bulan dan Tahun berhasil diperbarui!');
    }

    public function showDetails($nama, $bulan)
    {
        $this->detailRecords = [];
        $this->detailTitle = $bulan === 'TOTAL' ? "Total Detail: $nama" : "Detail: $nama ($bulan)";
        
        $allData = SobiDigiposData::where('row_index', '>', 1)->get();
        
        $config = $this->getActiveConfig();
        $activeMonths = $config['months'];

        foreach ($allData as $item) {
            $d = is_string($item->data) ? json_decode($item->data, true) : $item->data;
            if (!is_array($d)) continue;
            
            $rowName = trim($d[8] ?? '');
            if (empty($rowName)) $rowName = trim($d[7] ?? '');
            
            if (strtoupper($rowName) === strtoupper($nama)) {
                $rowBulan = trim($d[2] ?? '');
                
                if ($bulan === 'TOTAL') {
                    if (!empty($activeMonths) && !in_array($rowBulan, $activeMonths)) {
                        continue;
                    }
                    $this->detailRecords[] = [
                        'bulan' => $rowBulan,
                        'aoid' => trim($d[3] ?? '-'),
                        'sto' => trim($d[6] ?? '-')
                    ];
                } 
                else if ($rowBulan === $bulan) {
                    $this->detailRecords[] = [
                        'bulan' => $rowBulan,
                        'aoid' => trim($d[3] ?? '-'),
                        'sto' => trim($d[6] ?? '-')
                    ];
                }
            }
        }
        $this->isDetailModalOpen = true;
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->detailRecords = [];
    }

    public function render()
    {
        // 1. Get Unit mapping from DashboardData
        $dashboardRows = DashboardData::orderBy('id', 'asc')->get();
        $currentSegment = 'PROVISIONING';
        $nikToUnit = [];
        foreach ($dashboardRows as $index => $dbRow) {
            if ($index < 2) continue;
            $data = is_string($dbRow->data) ? json_decode($dbRow->data, true) : $dbRow->data;
            $nonEmptyCount = collect($data)->filter(fn ($v) => trim($v ?? '') !== '')->count();
            if ($nonEmptyCount === 1 && !empty($data[0])) {
                $rawTitle = strtoupper(trim($data[0]));
                $rawTitle = preg_replace('/^PRODUKTIVITAS\s+/', '', $rawTitle);
                $rawTitle = preg_replace('/\s+BULAN\s+.*$/', '', $rawTitle);
                if ($rawTitle === 'PSB B2C') $rawTitle = 'PROVISIONING';
                $currentSegment = trim($rawTitle);
                continue;
            }
            $isTotal = str_contains(strtoupper($data[1] ?? ''), 'TOTAL') || str_contains(strtoupper($data[0] ?? ''), 'TOTAL');
            if (!$isTotal && !empty($data[1])) {
                $nikToUnit[trim($data[1])] = $currentSegment;
            }
        }

        // 2. Get NIK Dict
        $nikDict = [];
        $sheetDataRows = SheetData::where('row_index', '>', 1)->get();
        foreach ($sheetDataRows as $r) {
            $d = is_string($r->data) ? json_decode($r->data, true) : $r->data;
            if (is_array($d)) {
                if (!empty($d[11]) && !empty($d[10])) {
                    $nikDict[strtoupper(trim($d[11]))] = trim($d[10]);
                }
                if (!empty($d[13]) && !empty($d[12])) {
                    $nikDict[strtoupper(trim($d[13]))] = trim($d[12]);
                }
            }
        }

        // 3. Aggregate SOBI Data
        $config = $this->getActiveConfig();
        $activeMonths = $config['months'];
        $activeYear = $config['year'];
        
        $targetMultiplier = count($activeMonths) > 0 ? count($activeMonths) : 1;
        $targetValue = $targetMultiplier * 4;

        $sobiData = SobiDigiposData::where('row_index', '>', 1)->get();
        $aggregated = [];

        foreach ($sobiData as $item) {
            $d = is_string($item->data) ? json_decode($item->data, true) : $item->data;
            if (!is_array($d)) continue;

            $bulan = trim($d[2] ?? '');
            
            if (!empty($activeMonths) && !in_array($bulan, $activeMonths)) {
                continue;
            }

            $name = trim($d[8] ?? '');
            if (empty($name)) {
                $name = trim($d[7] ?? '');
            }
            if (empty($name)) continue;

            $nameKey = strtoupper($name);

            if (!isset($aggregated[$nameKey])) {
                $aggregated[$nameKey] = [
                    'nik' => $nikDict[$nameKey] ?? '-',
                    'nama' => $name,
                    'capaian' => 0,
                    'target' => $targetValue,
                    'monthly_scores' => [],
                    'unit' => 'OUTLIER'
                ];
                foreach ($activeMonths as $m) {
                    $aggregated[$nameKey]['monthly_scores'][$m] = 0;
                }
            }

            $aggregated[$nameKey]['capaian'] += 1;
            if (isset($aggregated[$nameKey]['monthly_scores'][$bulan])) {
                $aggregated[$nameKey]['monthly_scores'][$bulan] += 1;
            } else if (empty($activeMonths)) {
                if (!isset($aggregated[$nameKey]['monthly_scores'][$bulan])) {
                    $aggregated[$nameKey]['monthly_scores'][$bulan] = 0;
                }
                $aggregated[$nameKey]['monthly_scores'][$bulan] += 1;
            }
        }

        // 4. Map Unit
        foreach ($aggregated as $nameKey => &$data) {
            $nik = $data['nik'];
            if ($nik !== '-' && isset($nikToUnit[$nik])) {
                $data['unit'] = $nikToUnit[$nik];
            }
        }

        $rows = collect(array_values($aggregated));

        // 5. Apply Search
        if (!empty($this->search)) {
            $searchLower = strtolower($this->search);
            $rows = $rows->filter(function ($row) use ($searchLower) {
                return str_contains(strtolower($row['nama']), $searchLower) ||
                       str_contains(strtolower($row['nik']), $searchLower);
            });
        }

        // 6. Apply Sort
        $sortCol = $this->sortColumn;
        $sortDir = $this->sortDirection;
        if ($sortDir === 'asc') {
            $rows = $rows->sortBy(function ($row) use ($sortCol, $activeMonths) {
                if ($sortCol === 'capaian' || $sortCol === 'target') return (int)$row[$sortCol];
                if (in_array($sortCol, $activeMonths)) return (int)($row['monthly_scores'][$sortCol] ?? 0);
                return strtolower($row[$sortCol]);
            }, SORT_NATURAL | SORT_FLAG_CASE);
        } else {
            $rows = $rows->sortByDesc(function ($row) use ($sortCol, $activeMonths) {
                if ($sortCol === 'capaian' || $sortCol === 'target') return (int)$row[$sortCol];
                if (in_array($sortCol, $activeMonths)) return (int)($row['monthly_scores'][$sortCol] ?? 0);
                return strtolower($row[$sortCol]);
            }, SORT_NATURAL | SORT_FLAG_CASE);
        }

        // 7. Group by Unit
        $groupedRows = $rows->groupBy('unit')->sortBy(function ($items, $key) {
            return $key === 'OUTLIER' ? 'ZZZZ' : $key;
        });

        $activeMonthsString = empty($activeMonths) ? 'Semua Bulan' : implode(', ', $activeMonths) . ' ' . $activeYear;

        return view('livewire.sobi-digipos-viewer', [
            'groupedRows' => $groupedRows,
            'activeMonths' => $activeMonths,
            'activeYear' => $activeYear,
            'activeMonthsString' => $activeMonthsString
        ])->layout('layouts.app');
    }
}
