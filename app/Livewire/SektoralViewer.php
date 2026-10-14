<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\GoogleSheetService;
use App\Models\SheetData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class SektoralViewer extends Component
{
    public $sheetId;
    public $activeTab = '1_PSB_B2C';
    public $allData = [];
    public $tableHeaders = [];
    public $parameters = [];
    public $lastUpdated = '';
    
    // Date Range Filter State
    public $startDate = '';
    public $endDate = '';
    public $activePeriodText = '16 Agustus - 30 September 2026 (46 hari)';

    // Sort & Search State
    public $search = '';
    public $sortColumn = 'WorldSkills Score';
    public $sortDirection = 'desc';

    // Edit Parameter Logic
    public $isEditParamModalOpen = false;
    public $editingParamRowIndex = null;
    public $editingParamName = '';
    public $editingParamValue = '';

    // Edit Row Data Logic
    public $isEditRowModalOpen = false;
    public $editingRowIndex = null;
    public $editForm = [];

    public function mount()
    {
        $this->sheetId = env('GOOGLE_SHEET_ID_SEKTORAL', env('GOOGLE_SHEET_ID_SOBI', env('GOOGLE_SHEET_ID', '')));
        $this->loadData();
    }

    public function setTab($tabName)
    {
        $this->activeTab = $tabName;
        // Reset sort column to WorldSkills Score when changing tabs since columns differ
        $this->sortColumn = 'WorldSkills Score';
        $this->sortDirection = 'desc';
        $this->loadData();
    }
    
    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'desc';
        }
    }

    public function loadData()
    {
        try {
            $service = new GoogleSheetService($this->sheetId);
            $token = $service->getAccessToken();

            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$this->sheetId}/values/'{$this->activeTab}'!A1:Z150";
            $response = Http::withToken($token)->get($url);
            
            if ($response->successful()) {
                $rows = $response->json()['values'] ?? [];
                $this->parseRows($rows);
            } else {
                Log::error('Failed fetching sektoral: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('SektoralViewer error: ' . $e->getMessage());
        }
    }

    private function parseRows($rows)
    {
        $this->parameters = [];
        $this->allData = [];
        // Parsing info periode dan tanggal dibuat dari header sheet
        $rawHeaderInfo = $rows[1][0] ?? ($rows[2][0] ?? '');
        $sheetPeriod = '16 Agustus - 30 September 2026 (46 hari)';
        $sheetUpdated = 'Tidak diketahui';

        if (stripos($rawHeaderInfo, 'Dibuat:') !== false) {
            $parts = preg_split('/Dibuat:/i', $rawHeaderInfo);
            $sheetUpdated = trim($parts[1] ?? 'Tidak diketahui');
            $periodPart = trim(str_replace(['Periode:', '|'], '', $parts[0] ?? ''));
            if (!empty($periodPart)) {
                $sheetPeriod = $periodPart;
            }
        } elseif (!empty($rawHeaderInfo)) {
            $sheetUpdated = trim(str_ireplace('Dibuat:', '', $rawHeaderInfo));
        }

        $this->lastUpdated = $sheetUpdated;

        if (empty($this->startDate) || empty($this->endDate)) {
            $this->activePeriodText = $sheetPeriod;
        }

        $isDataSection = false;
        $headers = [];
        
        foreach ($rows as $index => $row) {
            if (empty($row)) continue;
            
            if (strpos($row[0] ?? '', 'Bobot') !== false || strpos($row[0] ?? '', 'Total bobot') !== false) {
                // Sembunyikan 'Bobot (pengurang) Gangguan pasca PSB' dari tampilan web saja
                if (stripos($row[0] ?? '', 'Gangguan pasca PSB') !== false) {
                    continue;
                }

                $this->parameters[] = [
                    'name' => $row[0],
                    'value' => $row[1] ?? '',
                    'row_index' => $index + 1
                ];
            }
            
            if (strtoupper(trim($row[0] ?? '')) === 'RANK') {
                $isDataSection = true;
                foreach ($row as $h) {
                    $headers[] = trim($h);
                }
                $this->tableHeaders = $headers;
                continue;
            }
            
            if ($isDataSection && is_numeric($row[0] ?? '')) {
                $rowData = [];
                foreach ($headers as $colIndex => $headerName) {
                    $rowData[$headerName] = $row[$colIndex] ?? '';
                }
                $rowData['original_row_index'] = $index + 1;
                $this->allData[] = $rowData;
            }
        }
    }

    // --- DATE RANGE FILTER LOGIC (ADMIN ONLY) ---
    public function applyDateFilter()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (empty($this->startDate) || empty($this->endDate)) {
            session()->flash('error', 'Silakan pilih tanggal awal dan tanggal akhir.');
            return;
        }

        try {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);

            if ($end->lt($start)) {
                session()->flash('error', 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.');
                return;
            }

            $diffDays = $start->diffInDays($end) + 1;
            $startFormatted = $start->translatedFormat('d F Y');
            $endFormatted = $end->translatedFormat('d F Y');
            $this->activePeriodText = "{$startFormatted} - {$endFormatted} ({$diffDays} hari)";

            session()->flash('success', "Filter periode berhasil diterapkan: {$this->activePeriodText} berdasarkan data submission_key.");
        } catch (\Exception $e) {
            session()->flash('error', 'Format tanggal tidak valid: ' . $e->getMessage());
        }
    }

    public function resetDateFilter()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->startDate = '';
        $this->endDate = '';
        $this->activePeriodText = '16 Agustus - 30 September 2026 (46 hari)';
        session()->flash('success', 'Filter periode berhasil direset ke default.');
    }

    // --- PARAMETER EDIT LOGIC ---
    public function openEditParamModal($rowIndex)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $param = collect($this->parameters)->firstWhere('row_index', $rowIndex);
        if ($param) {
            $this->editingParamRowIndex = $rowIndex;
            $this->editingParamName = $param['name'];
            $this->editingParamValue = $param['value'];
            $this->isEditParamModalOpen = true;
        }
    }

    public function closeEditParamModal()
    {
        $this->isEditParamModalOpen = false;
        $this->editingParamRowIndex = null;
        $this->editingParamName = '';
        $this->editingParamValue = '';
    }

    public function saveParameter()
    {
        if (! Auth::check() || ! $this->editingParamRowIndex) {
            return;
        }

        try {
            $service = new GoogleSheetService($this->sheetId);
            $range = "'{$this->activeTab}'!B{$this->editingParamRowIndex}:B{$this->editingParamRowIndex}";
            
            $service->updateRow($range, [$this->editingParamValue], $this->sheetId);

            $this->isEditParamModalOpen = false;
            session()->flash('success', "Bobot \"{$this->editingParamName}\" berhasil diperbarui di web dan Google Sheets!");
            $this->loadData();
        } catch (\Exception $e) {
            Log::error('Google Sheet Update Parameter Error: ' . $e->getMessage());
            session()->flash('error', 'Gagal update parameter ke Google Sheets: ' . $e->getMessage());
        }
    }

    // --- DATA ROW EDIT LOGIC ---
    public function editRow($rowIndex)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $row = collect($this->allData)->firstWhere('original_row_index', $rowIndex);
        if ($row) {
            $this->editingRowIndex = $rowIndex;
            $this->editForm = [];
            foreach ($this->tableHeaders as $header) {
                $this->editForm[$header] = $row[$header] ?? '';
            }
            $this->isEditRowModalOpen = true;
        }
    }

    public function closeEditRowModal()
    {
        $this->isEditRowModalOpen = false;
        $this->editingRowIndex = null;
        $this->editForm = [];
    }

    public function saveRow()
    {
        if (! Auth::check() || ! $this->editingRowIndex) {
            return;
        }

        try {
            $service = new GoogleSheetService($this->sheetId);
            $values = [];
            foreach ($this->tableHeaders as $header) {
                $values[] = $this->editForm[$header] ?? '';
            }

            $colLetter = $this->getColumnLetter(count($this->tableHeaders));
            $range = "'{$this->activeTab}'!A{$this->editingRowIndex}:{$colLetter}{$this->editingRowIndex}";

            $service->updateRow($range, $values, $this->sheetId);

            $this->isEditRowModalOpen = false;
            session()->flash('success', 'Data teknisi berhasil diperbarui di web dan Google Sheets!');
            $this->loadData();
        } catch (\Exception $e) {
            Log::error('Google Sheet Update Row Error: ' . $e->getMessage());
            session()->flash('error', 'Gagal update data ke Google Sheets: ' . $e->getMessage());
        }
    }

    private function getColumnLetter($colNumber)
    {
        $letter = '';
        while ($colNumber > 0) {
            $mod = ($colNumber - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $colNumber = (int)(($colNumber - $mod) / 26);
        }
        return $letter ?: 'Z';
    }
    
    public function getFilteredAndSortedDataProperty()
    {
        $data = $this->allData;

        // Apply Filter Periode Tanggal Berdasarkan submission_key dari SheetData
        if (!empty($this->startDate) && !empty($this->endDate)) {
            try {
                $start = Carbon::parse($this->startDate)->startOfDay();
                $end = Carbon::parse($this->endDate)->endOfDay();

                // Ambil tiket dari SheetData yang berada dalam rentang tanggal submission_key
                $activeTechs = [];
                $techOrderCounts = [];

                $tickets = SheetData::all();
                foreach ($tickets as $ticket) {
                    $row = $ticket->data;
                    if (!is_array($row)) continue;

                    $dateKey = $row[17] ?? null; // submission_key
                    if (!$dateKey) continue;

                    try {
                        $ticketDate = Carbon::parse($dateKey);
                        if ($ticketDate->gte($start) && $ticketDate->lte($end)) {
                            // Cek kecocokan segmen dengan tab aktif
                            $seg = strtoupper($row[2] ?? '');
                            $matchesTab = false;
                            if ($this->activeTab === '1_PSB_B2C' && (str_contains($seg, 'PROVISIONING') || str_contains($seg, 'PSB'))) {
                                $matchesTab = true;
                            } elseif ($this->activeTab === '2_ASSR_B2C' && str_contains($seg, 'ASSURANCE B2C')) {
                                $matchesTab = true;
                            } elseif ($this->activeTab === '3_ASSR_B2B' && (str_contains($seg, 'ASSURANCE B2B') || str_contains($seg, 'B2B'))) {
                                $matchesTab = true;
                            } elseif (empty($this->activeTab)) {
                                $matchesTab = true;
                            }

                            if ($matchesTab) {
                                $nik1 = trim($row[10] ?? ($row[1] ?? ''));
                                $name1 = strtolower(trim($row[11] ?? ''));
                                $nik2 = trim($row[12] ?? '');
                                $name2 = strtolower(trim($row[13] ?? ''));

                                if (!empty($nik1)) {
                                    $activeTechs[$nik1] = true;
                                    $techOrderCounts[$nik1] = ($techOrderCounts[$nik1] ?? 0) + 1;
                                }
                                if (!empty($name1)) {
                                    $activeTechs[$name1] = true;
                                }
                                if (!empty($nik2)) {
                                    $activeTechs[$nik2] = true;
                                    $techOrderCounts[$nik2] = ($techOrderCounts[$nik2] ?? 0) + 1;
                                }
                                if (!empty($name2)) {
                                    $activeTechs[$name2] = true;
                                }
                            }
                        }
                    } catch (\Exception $e) {}
                }

                // Saring data teknisi berdasarkan keaktifan tiket pada periode tersebut
                if (!empty($activeTechs)) {
                    $filteredData = [];
                    foreach ($data as $item) {
                        $itemNik = trim($item['NIK/Labor'] ?? ($item['NIK'] ?? ''));
                        $itemName = strtolower(trim($item['Nama Teknisi'] ?? ''));

                        if (isset($activeTechs[$itemNik]) || isset($activeTechs[$itemName])) {
                            $orderCount = $techOrderCounts[$itemNik] ?? 0;
                            $item['orders_in_period'] = $orderCount;
                            $filteredData[] = $item;
                        }
                    }
                    $data = $filteredData;
                } else {
                    $data = [];
                }
            } catch (\Exception $e) {
                Log::error('Error filtering sektoral by submission_key: ' . $e->getMessage());
            }
        }

        // Apply Search
        if (!empty($this->search)) {
            $data = array_filter($data, function($row) {
                $searchStr = strtolower($this->search);
                $nama = strtolower($row['Nama Teknisi'] ?? '');
                $nik = strtolower($row['NIK/Labor'] ?? '');
                return strpos($nama, $searchStr) !== false || strpos($nik, $searchStr) !== false;
            });
        }
        
        // Apply Sort
        if (!empty($this->sortColumn) && in_array($this->sortColumn, $this->tableHeaders)) {
            usort($data, function ($a, $b) {
                $valA = $a[$this->sortColumn] ?? '';
                $valB = $b[$this->sortColumn] ?? '';
                
                $numA = (float) str_replace(',', '.', preg_replace('/[^-0-9,.]/', '', $valA));
                $numB = (float) str_replace(',', '.', preg_replace('/[^-0-9,.]/', '', $valB));
                
                if (is_numeric($numA) && is_numeric($numB) && $valA !== '' && $valB !== '') {
                    if ($numA == $numB) return 0;
                    $result = ($numA < $numB) ? -1 : 1;
                } else {
                    $result = strcmp((string)$valA, (string)$valB);
                }
                
                return $this->sortDirection === 'asc' ? $result : -$result;
            });
        }
        
        return $data;
    }
    
    public function render()
    {
        return view('livewire.sektoral-viewer', [
            'displayData' => $this->filteredAndSortedData
        ]);
    }
}
