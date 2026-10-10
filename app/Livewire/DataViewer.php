<?php

namespace App\Livewire;

use App\Models\SheetData;
use App\Services\GoogleSheetService;
use App\Services\ProductivityService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class DataViewer extends Component
{
    use WithPagination;

    public $search = '';

    public $sortColumn = null;

    public $sortDirection = 'asc';

    public $perPage = 10;

    public $editingRowIndex = null;

    public $editForm = [];

    public $addForm = [];

    public $isEditModalOpen = false;

    public $isAddModalOpen = false;
    
    public $startDate = null;
    
    public $endDate = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }
    
    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function openAddModal()
    {
        if (! Auth::check()) {
            return;
        }

        $headers = $this->getHeaders();
        $this->addForm = array_fill(0, count($headers), '');
        $this->isAddModalOpen = true;
    }

    public function closeAddModal()
    {
        $this->isAddModalOpen = false;
        $this->addForm = [];
    }

    public function addRow()
    {
        if (! Auth::check()) {
            return;
        }

        $data = $this->addForm;
        $headers = $this->getHeaders();

        // Convert to sequential array preserving empty gaps
        $maxIndex = empty($data) ? (count($headers) - 1) : max(array_keys($data));
        $sequentialData = [];
        for ($i = 0; $i <= $maxIndex; $i++) {
            $sequentialData[] = $data[$i] ?? '';
        }

        // Auto-format submission_key (index 17) based on close_dt (index 15) or start_dt (index 14)
        $closeDt = $sequentialData[15] ?? ($sequentialData[14] ?? null);
        if ($closeDt && trim((string) $closeDt) !== '') {
            try {
                $formatted = Carbon::parse($closeDt)->format('d-M-y');
                while (count($sequentialData) <= 17) {
                    $sequentialData[] = '';
                }
                $sequentialData[17] = $formatted;
            } catch (\Exception $e) {
            }
        }

        try {
            $service = new GoogleSheetService;
            $range = 'Sheet1!A:AZ';
            $response = $service->appendRow($range, $sequentialData);

            $newRowIndex = SheetData::max('row_index') + 1;

            if (isset($response['updates']['updatedRange'])) {
                if (preg_match('/!?[A-Z]+(\d+)/', $response['updates']['updatedRange'], $matches)) {
                    $newRowIndex = (int) $matches[1];
                }
            }

            // Update local DB to reflect changes immediately
            SheetData::create([
                'row_index' => $newRowIndex,
                'data' => json_encode($sequentialData),
            ]);

            // Recalculate productivity
            (new ProductivityService)->recalculateAndSave();

            // Clear caches so the rest of the app updates
            Artisan::call('cache:clear');

            session()->flash('success', 'Data baru berhasil ditambahkan!');
            $this->isAddModalOpen = false;
            $this->addForm = [];
        } catch (\Exception $e) {
            Log::error('Google API Error: '.$e->getMessage());
            session()->flash('error', 'Gagal menambah data ke Google Sheets: '.$e->getMessage());
        }
    }

    public function editRow($rowIndex)
    {
        if (! Auth::check()) {
            return;
        }

        $ticket = SheetData::where('row_index', $rowIndex)->first();
        if ($ticket) {
            $this->editingRowIndex = $rowIndex;
            $this->editForm = $ticket->data ?? [];
            $this->isEditModalOpen = true;
        }
    }

    public function saveRow()
    {
        if (! Auth::check() || ! $this->editingRowIndex) {
            return;
        }

        $data = $this->editForm;

        // Convert to sequential array preserving empty gaps
        $maxIndex = empty($data) ? 0 : max(array_keys($data));
        $sequentialData = [];
        for ($i = 0; $i <= $maxIndex; $i++) {
            $sequentialData[] = $data[$i] ?? '';
        }

        // Auto-format submission_key (index 17) based on close_dt (index 15) or start_dt (index 14)
        $closeDt = $sequentialData[15] ?? ($sequentialData[14] ?? null);
        if ($closeDt && trim((string) $closeDt) !== '') {
            try {
                $formatted = Carbon::parse($closeDt)->format('d-M-y');
                while (count($sequentialData) <= 17) {
                    $sequentialData[] = '';
                }
                $sequentialData[17] = $formatted;
            } catch (\Exception $e) {
            }
        }

        try {
            $service = new GoogleSheetService;
            $range = "Sheet1!A{$this->editingRowIndex}:AZ{$this->editingRowIndex}";
            $service->updateRow($range, $sequentialData);

            // Update local DB to reflect changes immediately
            SheetData::where('row_index', $this->editingRowIndex)->update([
                'data' => json_encode($sequentialData),
                'updated_at' => now(),
            ]);

            // Recalculate productivity
            (new ProductivityService)->recalculateAndSave();

            // Clear caches so the rest of the app updates
            Artisan::call('cache:clear');

            session()->flash('success', 'Data baris berhasil diperbarui!');
            $this->isEditModalOpen = false;
            $this->editingRowIndex = null;
            $this->editForm = [];
        } catch (\Exception $e) {
            Log::error('Google API Error: '.$e->getMessage());
            session()->flash('error', 'Gagal update ke Google Sheets: '.$e->getMessage());
        }
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->editingRowIndex = null;
    }

    public function sortBy($columnIndex)
    {
        $columnIndex = (int) $columnIndex;
        if ($this->sortColumn === $columnIndex) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $columnIndex;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    private function getHeaders()
    {
        $firstRow = SheetData::orderBy('id', 'asc')->first();

        return $firstRow ? ($firstRow->data ?? []) : [];
    }

    public function resetData()
    {
        $this->search = '';
        $this->sortColumn = null;
        $this->sortDirection = 'asc';
        $this->startDate = null;
        $this->endDate = null;
        $this->resetPage();
    }

    public function render()
    {
        $headers = [];
        $rows = [];

        // Ambil header dari baris paling pertama di database
        $firstRow = SheetData::orderBy('id', 'asc')->first();
        if ($firstRow) {
            $headers = $firstRow->data ?? [];
        }

        $query = SheetData::query();

        // Exclude the header row from results
        if ($firstRow) {
            $query->where('id', '!=', $firstRow->id);
        }

        // Search logic (mencari di dalam string JSON)
        if (! empty($this->search)) {
            $query->where('data', 'like', '%'.$this->search.'%');
        }

        // Filter by Date (menggunakan substring dari elemen index ke-0 timestamp_input, atau kita bisa menggunakan where raw JSON)
        if (!empty($this->startDate)) {
            // Karena SQLite string comparison bekerja dengan YYYY-MM-DD, kita membandingkan secara lexical. 
            // Namun format timestamp_input di database bisa berbeda-beda. Kita menggunakan fitur strftime/date dari SQLite.
            $query->whereRaw("date(substr(json_extract(data, '$[0]'), 1, 19)) >= date(?)", [$this->startDate]);
        }
        
        if (!empty($this->endDate)) {
            $query->whereRaw("date(substr(json_extract(data, '$[0]'), 1, 19)) <= date(?)", [$this->endDate]);
        }

        // Sort logic menggunakan json_extract SQLite
        if ($this->sortColumn !== null) {
            $query->orderByRaw("json_extract(data, '$[".$this->sortColumn."]') ".$this->sortDirection);
        } else {
            // Default sort (original spreadsheet order = by id asc)
            $query->orderBy('id', 'asc');
        }

        $paginatedData = $query->paginate($this->perPage)->onEachSide(0);

        foreach ($paginatedData as $item) {
            $rows[] = [
                'row_index' => $item->row_index,
                'data' => $item->data,
            ];
        }

        return view('livewire.data-viewer', [
            'headers' => $headers,
            'rows' => $rows,
            'paginatedData' => $paginatedData,
        ]);
    }
}
