<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\DashboardData;

#[Layout('components.layouts.app')]
class DashboardViewer extends Component
{
    public $search = '';

    public function render()
    {
        $query = DashboardData::orderBy('id', 'asc');
        
        if (!empty($this->search)) {
            $query->where('data', 'like', '%' . $this->search . '%');
        }
        
        $dashboardRows = $query->get();

        // Data JSON di database array of strings, kita pisahkan baris pertama sebagai Header.
        $headers = [];
        $title = '';
        $rows = [];

        // Always get title and header from the FIRST TWO rows of the original data, regardless of search
        $originalFirstTwo = DashboardData::orderBy('id', 'asc')->take(2)->get();
        if ($originalFirstTwo->count() >= 2) {
            $title = $originalFirstTwo[0]->data[0] ?? 'Dashboard Harian';
            $headers = $originalFirstTwo[1]->data ?? [];
        }

        if ($dashboardRows->count() > 0) {
            foreach ($dashboardRows as $dbRow) {
                // Jangan masukkan title dan header ke dalam rows data jika sedang disearch
                if ($originalFirstTwo->count() >= 2) {
                    if ($dbRow->id === $originalFirstTwo[0]->id || $dbRow->id === $originalFirstTwo[1]->id) {
                        continue;
                    }
                }
                $rows[] = $dbRow->data;
            }
        }

        return view('livewire.dashboard-viewer', [
            'title' => $title,
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }
}
