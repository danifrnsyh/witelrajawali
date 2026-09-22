<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\SheetData;

#[Layout('components.layouts.app')]
class DataViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $sortColumn = null;
    public $sortDirection = 'asc';
    public $perPage = 50;

    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($columnIndex)
    {
        if ($this->sortColumn === $columnIndex) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $columnIndex;
            $this->sortDirection = 'asc';
        }
    }

    public function resetData()
    {
        $this->search = '';
        $this->sortColumn = null;
        $this->sortDirection = 'asc';
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
        if (!empty($this->search)) {
            $query->where('data', 'like', '%' . $this->search . '%');
        }

        // Sort logic menggunakan json_extract SQLite
        if ($this->sortColumn !== null) {
            $query->orderByRaw("json_extract(data, '$[" . $this->sortColumn . "]') " . $this->sortDirection);
        } else {
            // Default sort (original spreadsheet order = by id asc)
            $query->orderBy('id', 'asc');
        }

        $paginatedData = $query->paginate($this->perPage);

        foreach ($paginatedData as $item) {
            $rows[] = $item->data;
        }

        return view('livewire.data-viewer', [
            'headers' => $headers,
            'rows' => $rows,
            'paginatedData' => $paginatedData,
        ]);
    }
}
