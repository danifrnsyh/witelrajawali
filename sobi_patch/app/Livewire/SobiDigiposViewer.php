<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SobiDigiposData;
use App\Models\SheetData;
use Illuminate\Support\Facades\Auth;

class SobiDigiposViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $sortColumn = 'capaian';
    public $sortDirection = 'desc';

    // Admin controls
    public $isEditModalOpen = false;
    public $availableMonths = [];
    public $selectedMonthsInput = [];
    public $selectedYearInput = '';

    public function mount()
    {
        $this->loadAvailableMonths();
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
        $this->resetPage();
    }

    public function loadAvailableMonths()
    {
        // Get unique months from data (skip header row)
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
        $this->resetPage();
        session()->flash('success', 'Bulan dan Tahun berhasil diperbarui!');
    }

    public function render()
    {
        // 1. Build NIK Dictionary from SheetData
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
            
            // Filter by active months
            if (!empty($activeMonths) && !in_array($bulan, $activeMonths)) {
                continue;
            }

            // Determine Name
            $name = trim($d[8] ?? ''); // TEKNISI RJW
            if (empty($name)) {
                $name = trim($d[7] ?? ''); // NON TEKNISI RJW
            }
            if (empty($name)) continue;

            $nameKey = strtoupper($name);
            $sto = trim($d[6] ?? '');

            if (!isset($aggregated[$nameKey])) {
                $aggregated[$nameKey] = [
                    'nik' => $nikDict[$nameKey] ?? '-',
                    'nama' => $name,
                    'unit' => $sto,
                    'capaian' => 0,
                    'target' => $targetValue,
                    'monthly_scores' => []
                ];
                // Initialize monthly scores
                foreach ($activeMonths as $m) {
                    $aggregated[$nameKey]['monthly_scores'][$m] = 0;
                }
            }

            $aggregated[$nameKey]['capaian'] += 1;
            if (isset($aggregated[$nameKey]['monthly_scores'][$bulan])) {
                $aggregated[$nameKey]['monthly_scores'][$bulan] += 1;
            } else if (empty($activeMonths)) {
                // If no filter, dynamically create column if needed
                if (!isset($aggregated[$nameKey]['monthly_scores'][$bulan])) {
                    $aggregated[$nameKey]['monthly_scores'][$bulan] = 0;
                }
                $aggregated[$nameKey]['monthly_scores'][$bulan] += 1;
            }

            if (!empty($sto)) {
                $aggregated[$nameKey]['unit'] = $sto;
            }
        }

        $rows = collect(array_values($aggregated));

        // Filter Search
        if (!empty($this->search)) {
            $searchLower = strtolower($this->search);
            $rows = $rows->filter(function ($row) use ($searchLower) {
                return str_contains(strtolower($row['nama']), $searchLower) ||
                       str_contains(strtolower($row['nik']), $searchLower) ||
                       str_contains(strtolower($row['unit']), $searchLower);
            });
        }

        // Sorting
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

        // Pagination
        $perPage = 50;
        $currentPage = $this->getPage();
        $total = $rows->count();
        $paginatedRows = new \Illuminate\Pagination\LengthAwarePaginator(
            $rows->forPage($currentPage, $perPage),
            $total,
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $activeMonthsString = empty($activeMonths) ? 'Semua Bulan' : implode(', ', $activeMonths) . ' ' . $activeYear;

        return view('livewire.sobi-digipos-viewer', [
            'rows' => $paginatedRows,
            'activeMonths' => $activeMonths,
            'activeYear' => $activeYear,
            'activeMonthsString' => $activeMonthsString
        ])->layout('layouts.app');
    }
}
