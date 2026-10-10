<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

#[Layout('components.layouts.app')]
class SektoralViewer extends Component
{
    public $activeTab = '1_PSB_B2C';
    public $allData = [];
    public $tableHeaders = [];
    public $parameters = [];
    public $lastUpdated = '';
    
    // Sort & Search State
    public $search = '';
    public $sortColumn = 'WorldSkills Score';
    public $sortDirection = 'desc';

    public function mount()
    {
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
            $service = new GoogleSheetService();
            $reflection = new \ReflectionClass($service);
            
            $sheetId = '11tQOZmkTYxEHe9ZabpJ3D7Bs_p-IHwMeR3cehW_puIQ';
            $prop = $reflection->getProperty('sheetId');
            $prop->setAccessible(true);
            $prop->setValue($service, $sheetId);
            
            $method = $reflection->getMethod('getAccessToken');
            $method->setAccessible(true);
            $token = $method->invoke($service);

            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/'{$this->activeTab}'!A1:Z150";
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
        $this->tableHeaders = [];
        $this->lastUpdated = $rows[1][0] ?? 'Tidak diketahui';
        
        $isDataSection = false;
        $headers = [];
        
        foreach ($rows as $index => $row) {
            if (empty($row)) continue;
            
            if (strpos($row[0] ?? '', 'Bobot') !== false || strpos($row[0] ?? '', 'Total bobot') !== false) {
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
    
    public function getFilteredAndSortedDataProperty()
    {
        $data = $this->allData;
        
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


