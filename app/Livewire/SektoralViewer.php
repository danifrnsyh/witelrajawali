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
            $data = $this->calculateDynamicSektoral($data);
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
    
    /**
     * Engine perhitungan otomatis dan akurat untuk metrik, SKOR AKHIR, dan WorldSkills Score
     * berdasarkan periode tanggal tiket mentah (submission_key).
     */
    private function calculateDynamicSektoral(array $baseData): array
    {
        try {
            $start = Carbon::parse($this->startDate)->startOfDay();
            $end = Carbon::parse($this->endDate)->endOfDay();

            // 1. Ambil semua tiket dari SheetData dalam rentang tanggal
            $tickets = SheetData::all();
            
            // Map tech NIK / Name ke data akumulasi
            $techStats = [];

            foreach ($tickets as $ticket) {
                $row = $ticket->data;
                if (!is_array($row)) continue;

                $dateKey = $row[17] ?? null; // submission_key
                if (!$dateKey) continue;

                try {
                    $ticketDate = Carbon::parse($dateKey);
                    if ($ticketDate->lt($start) || $ticketDate->gt($end)) {
                        continue;
                    }
                } catch (\Exception $e) {
                    continue;
                }

                $seg = strtoupper(trim($row[2] ?? ''));
                $jenisOrder = strtoupper(trim($row[3] ?? ''));
                $manhour = isset($row[5]) && is_numeric($row[5]) 
                    ? (float)$row[5] 
                    : ((\App\Services\ProductivityService::BOBOT_MAP[$jenisOrder] ?? 0.38) * 5.3333);

                $tech1Nik = trim($row[10] ?? ($row[1] ?? ''));
                $tech1Name = strtolower(trim($row[11] ?? ''));
                $tech2Nik = trim($row[12] ?? '');
                $tech2Name = strtolower(trim($row[13] ?? ''));

                $hasTech2 = (!empty($tech2Nik) || !empty($tech2Name));
                $orderWeight = $hasTech2 ? 0.5 : 1.0;
                $mhWeight = $hasTech2 ? ($manhour / 2.0) : $manhour;
                $dayStr = $ticketDate->format('Y-m-d');

                // Helper to record for a tech
                $recordTech = function ($nik, $name) use (&$techStats, $seg, $orderWeight, $mhWeight, $dayStr) {
                    if (empty($nik) && empty($name)) return;
                    $key = !empty($nik) ? $nik : $name;

                    if (!isset($techStats[$key])) {
                        $techStats[$key] = [
                            'nik' => $nik,
                            'name' => $name,
                            'active_days' => [],
                            'prov_orders' => 0.0,
                            'prov_mh' => 0.0,
                            'nonprov_orders' => 0.0,
                            'nonprov_mh' => 0.0,
                            'ass_orders' => 0.0,
                            'ass_mh' => 0.0,
                            'other_orders' => 0.0,
                            'other_mh' => 0.0,
                            'sobi' => 0,
                        ];
                    }

                    $techStats[$key]['active_days'][$dayStr] = true;

                    // Klasifikasi segmen
                    $isProv = str_contains($seg, 'PROVISIONING') || str_contains($seg, 'PSB');
                    $isAss = str_contains($seg, 'ASSURANCE') || str_contains($seg, 'ASSR') || str_contains($seg, 'GANGGUAN');

                    if ($isProv) {
                        $techStats[$key]['prov_orders'] += $orderWeight;
                        $techStats[$key]['prov_mh'] += $mhWeight;
                    } else {
                        $techStats[$key]['nonprov_orders'] += $orderWeight;
                        $techStats[$key]['nonprov_mh'] += $mhWeight;
                    }

                    if ($isAss) {
                        $techStats[$key]['ass_orders'] += $orderWeight;
                        $techStats[$key]['ass_mh'] += $mhWeight;
                    } else {
                        $techStats[$key]['other_orders'] += $orderWeight;
                        $techStats[$key]['other_mh'] += $mhWeight;
                    }
                };

                if (!empty($tech1Nik) || !empty($tech1Name)) {
                    $recordTech($tech1Nik, $tech1Name);
                }
                if (!empty($tech2Nik) || !empty($tech2Name)) {
                    $recordTech($tech2Nik, $tech2Name);
                }
            }

            // 2. Sinkronkan dengan SOBI / Digipos jika ada data
            try {
                $sobiRows = \App\Models\SobiDigiposData::where('row_index', '>', 1)->get();
                foreach ($sobiRows as $sobi) {
                    $sData = is_string($sobi->data) ? json_decode($sobi->data, true) : $sobi->data;
                    if (!is_array($sData)) continue;
                    $sNik = trim($sData[4] ?? ($sData[1] ?? ''));
                    $sCount = isset($sData[10]) && is_numeric($sData[10]) ? (int)$sData[10] : (isset($sData[6]) && is_numeric($sData[6]) ? (int)$sData[6] : 0);
                    if ($sNik && isset($techStats[$sNik])) {
                        $techStats[$sNik]['sobi'] += $sCount;
                    }
                }
            } catch (\Exception $e) {}

            // 3. Bangun baris-baris data baru yang dihitung ulang
            $calculatedRows = [];

            foreach ($baseData as $item) {
                $itemNik = trim($item['NIK/Labor'] ?? ($item['NIK'] ?? ''));
                $itemName = strtolower(trim($item['Nama Teknisi'] ?? ''));

                $matchedStats = $techStats[$itemNik] ?? ($techStats[$itemName] ?? null);
                if (!$matchedStats) {
                    // Teknisi tidak aktif dalam rentang tanggal ini
                    continue;
                }

                $activeDaysCount = max(1, count($matchedStats['active_days']));
                $newItem = $item;

                if ($this->activeTab === '1_PSB_B2C') {
                    $provOrders = $matchedStats['prov_orders'];
                    $provMh = $matchedStats['prov_mh'];
                    $nonOrders = $matchedStats['nonprov_orders'];
                    $nonMh = $matchedStats['nonprov_mh'];
                    $sobi = $matchedStats['sobi'];

                    $idxP = $provMh / $activeDaysCount;
                    $idxN = $nonMh / $activeDaysCount;
                    $idxS = $sobi / $activeDaysCount;

                    $skorAkhir = ($idxP * 3.3375) + ($idxN * 30.35) + ($idxS * 10.0);

                    $newItem["Hari\n Aktif"] = (string)$activeDaysCount;
                    $newItem["Provisioning\n (order)"] = number_format($provOrders, 1, ',', '.');
                    $newItem["Provisioning\n (manhour)"] = number_format($provMh, 1, ',', '.');
                    $newItem["Non-Prov\n (order)"] = number_format($nonOrders, 1, ',', '.');
                    $newItem["Non-Prov\n (manhour)"] = number_format($nonMh, 1, ',', '.');
                    $newItem["SOBI/\n Digipos"] = (string)$sobi;
                    $newItem["Gangguan\n Pasca PSB"] = '0';
                    $newItem["Idx Prov\n /hari"] = number_format($idxP, 1, ',', '.');
                    $newItem["Idx Non\n /hari"] = number_format($idxN, 1, ',', '.');
                    $newItem["SKOR\n AKHIR"] = number_format($skorAkhir, 2, ',', '.');
                    $newItem['_raw_skor'] = $skorAkhir;
                } else {
                    // Tab 2 (ASSR B2C) atau Tab 3 (ASSR B2B)
                    $assOrders = $matchedStats['ass_orders'];
                    $assMh = $matchedStats['ass_mh'];
                    $otherOrders = $matchedStats['other_orders'];
                    $otherMh = $matchedStats['other_mh'];
                    $sobi = $matchedStats['sobi'];

                    $idxAss = $assMh / $activeDaysCount;
                    $idxOther = $otherMh / $activeDaysCount;
                    $idxS = $sobi / $activeDaysCount;

                    $skorAkhir = ($idxAss * 7.1056) + ($idxOther * 6.597) + ($idxS * 45.03);

                    $newItem["Hari\n Aktif"] = (string)$activeDaysCount;
                    $newItem["Tiket Ass\n (jumlah)"] = number_format($assOrders, 1, ',', '.');
                    $newItem["Tiket Ass\n (manhour)"] = number_format($assMh, 1, ',', '.');
                    $newItem["Order Lain\n (jumlah)"] = number_format($otherOrders, 1, ',', '.');
                    $newItem["Order Lain\n (manhour)"] = number_format($otherMh, 1, ',', '.');
                    $newItem["SOBI/\n Digipos"] = (string)$sobi;
                    $newItem["Idx Ass\n /hari"] = number_format($idxAss, 1, ',', '.');
                    $newItem["Idx Lain\n /hari"] = number_format($idxOther, 1, ',', '.');
                    $newItem["SKOR\n AKHIR"] = number_format($skorAkhir, 2, ',', '.');
                    $newItem['_raw_skor'] = $skorAkhir;
                }

                $calculatedRows[] = $newItem;
            }

            // 4. Hitung WorldSkills Score secara Statistik Robust
            if (!empty($calculatedRows)) {
                $rawScores = array_column($calculatedRows, '_raw_skor');
                sort($rawScores);
                $cnt = count($rawScores);
                $mid = (int)floor($cnt / 2);
                $median = ($cnt % 2 === 0) ? ($rawScores[$mid - 1] + $rawScores[$mid]) / 2.0 : $rawScores[$mid];

                $diffs = [];
                foreach ($rawScores as $s) {
                    $diffs[] = abs($s - $median);
                }
                sort($diffs);
                $mad = ($cnt % 2 === 0) ? ($diffs[$mid - 1] + $diffs[$mid]) / 2.0 : $diffs[$mid];
                $robustSD = ($mad > 0) ? ($mad * 1.4826) : 1.0;

                foreach ($calculatedRows as &$cRow) {
                    $s = $cRow['_raw_skor'];
                    $wsScore = round((($s - $median) / $robustSD) * 30 + 700, 1);
                    $cRow["WorldSkills\n Score"] = number_format($wsScore, 1, ',', '.');
                    $cRow['_raw_ws'] = $wsScore;
                }
                unset($cRow);

                // Urutkan berdasarkan WorldSkills Score Tertinggi
                usort($calculatedRows, function($a, $b) {
                    if ($a['_raw_ws'] == $b['_raw_ws']) return 0;
                    return ($a['_raw_ws'] > $b['_raw_ws']) ? -1 : 1;
                });

                // Perbarui No Rank 1, 2, 3...
                foreach ($calculatedRows as $idx => &$cRow) {
                    $cRow['Rank'] = (string)($idx + 1);
                }
                unset($cRow);
            }

            return $calculatedRows;
        } catch (\Exception $e) {
            Log::error('Dynamic Sektoral Calculation Error: ' . $e->getMessage());
            return $baseData;
        }
    }

    public function render()
    {
        return view('livewire.sektoral-viewer', [
            'displayData' => $this->filteredAndSortedData
        ]);
    }
}
