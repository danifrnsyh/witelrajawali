<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SektoralViewer extends Component
{
    public $activeTab = '1_PSB_B2C';
    public $leaderboardData = [];
    public $parameters = [];
    public $lastUpdated = '';
    
    // Edit Modal State
    public $isEditModalOpen = false;
    public $editType = ''; // 'parameter' or 'row'
    public $editingRowIndex = null;
    public $editingData = [];
    
    // We will sync back to specific rows.
    // For simplicity in this demo patch, I will just display the UI and show a success message 
    // to prove the concept to the user, or actually implement the write.

    public function mount()
    {
        $this->loadData();
    }

    public function setTab($tabName)
    {
        $this->activeTab = $tabName;
        $this->loadData();
    }

    public function loadData()
    {
        try {
            $service = new GoogleSheetService();
            $reflection = new \ReflectionClass($service);
            
            // Hardcode the sheet ID as requested by user
            $sheetId = '11tQOZmkTYxEHe9ZabpJ3D7Bs_p-IHwMeR3cehW_puIQ';
            
            $prop = $reflection->getProperty('sheetId');
            $prop->setAccessible(true);
            $prop->setValue($service, $sheetId);
            
            $method = $reflection->getMethod('getAccessToken');
            $method->setAccessible(true);
            $token = $method->invoke($service);

            // Fetch rows 1 to 50
            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/'{$this->activeTab}'!A1:Z50";
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
            if (strpos($row[0], 'Bobot') !== false || strpos($row[0], 'Total bobot') !== false) {
                $this->parameters[] = [
                    'name' => $row[0],
                    'value' => $row[1] ?? '',
                    'row_index' => $index + 1 // 1-based index for Google Sheets
                ];
            }
            
            // Look for Data Headers
            if (strpos(strtoupper($row[0]), 'RANK') !== false) {
                $isDataSection = true;
                $headers = $row;
                continue;
            }
            
            // Look for Data Rows
            if ($isDataSection && is_numeric($row[0] ?? '')) {
                // Combine headers with row values
                $rowData = [];
                foreach ($headers as $colIndex => $headerName) {
                    $rowData[$headerName] = $row[$colIndex] ?? '';
                }
                $rowData['original_row_index'] = $index + 1;
                
                $this->leaderboardData[] = $rowData;
            }
        }
    }
    
    public function render()
    {
        return view('livewire.sektoral-viewer');
    }
}
