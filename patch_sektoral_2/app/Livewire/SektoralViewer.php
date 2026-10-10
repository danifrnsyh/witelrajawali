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
    public $leaderboardData = [];
    public $parameters = [];
    public $lastUpdated = '';
    
    // Sort State
    public $sortColumn = 'WorldSkills Score';
    public $sortDirection = 'desc';

    public function mount()
    {
        $this->loadData();
    }

    public function setTab($tabName)
    {
        $this->activeTab = $tabName;
        $this->loadData();
    }
    
    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'desc'; // Default to desc when clicking a new column
        }
        $this->applySort();
    }
    
    private function applySort()
    {
        if (empty($this->leaderboardData) || !$this->sortColumn) {
            return;
        }

        usort($this->leaderboardData, function ($a, $b) {
            $valA = $a[$this->sortColumn] ?? '';
            $valB = $b[$this->sortColumn] ?? '';
            
            // Handle numeric sorting correctly (remove commas if they are used as decimals)
            $numA = (float) str_replace(',', '.', preg_replace('/[^-0-9,.]/', '', $valA));
            $numB = (float) str_replace(',', '.', preg_replace('/[^-0-9,.]/', '', $valB));
            
            // If both are numeric, compare as numbers
            if (is_numeric($numA) && is_numeric($numB) && $valA !== '' && $valB !== '') {
                if ($numA == $numB) return 0;
                $result = ($numA < $numB) ? -1 : 1;
            } else {
                $result = strcmp($valA, $valB);
            }
            
            return $this->sortDirection === 'asc' ? $result : -$result;
        });
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

            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/'{$this->activeTab}'!A1:Z100";
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
        $this->leaderboardData = [];
        $this->lastUpdated = $rows[1][0] ?? 'Tidak diketahui';
        
        $isDataSection = false;
        $headers = [];
        
        foreach ($rows as $index => $row) {
            if (empty($row)) continue;
            
            // Look for Parameters
            if (strpos($row[0] ?? '', 'Bobot') !== false || strpos($row[0] ?? '', 'Total bobot') !== false) {
                $this->parameters[] = [
                    'name' => $row[0],
                    'value' => $row[1] ?? '',
                    'row_index' => $index + 1
                ];
            }
            
            // Look for Data Headers
            if (strpos(strtoupper($row[0] ?? ''), 'RANK') !== false) {
                $isDataSection = true;
                // Clean headers from newlines
                foreach ($row as $h) {
                    $headers[] = trim(preg_replace('/\s+/', ' ', $h));
                }
                continue;
            }
            
            // Look for Data Rows
            if ($isDataSection && is_numeric($row[0] ?? '')) {
                $rowData = [];
                foreach ($headers as $colIndex => $headerName) {
                    $rowData[$headerName] = $row[$colIndex] ?? '';
                }
                $rowData['original_row_index'] = $index + 1;
                
                $this->leaderboardData[] = $rowData;
            }
        }
        
        $this->applySort();
    }
    
    public function render()
    {
        return view('livewire.sektoral-viewer');
    }
}
