<?php

namespace App\Livewire;

use App\Models\DashboardData;
use App\Services\GoogleSheetService;
use App\Services\ProductivityService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class DashboardViewer extends Component
{
    public $search = '';

    // Date Range Filter
    public $startDate = '';
    public $endDate = '';

    // Edit Logic
    public $editingRowIndex = null;

    public $editForm = [];

    public $isEditModalOpen = false;

    // Title Edit Logic
    public $isTitleEditModalOpen = false;
    public $titleForm = '';

    public function editTitle()
    {
        if (! Auth::check()) {
            return;
        }

        $titleRow = DashboardData::orderBy('id', 'asc')->first();
        if ($titleRow) {
            $this->titleForm = $titleRow->data[0] ?? '';
            $this->isTitleEditModalOpen = true;
        }
    }

    public function saveTitle()
    {
        if (! Auth::check()) {
            return;
        }

        $titleRow = DashboardData::orderBy('id', 'asc')->first();
        if ($titleRow) {
            try {
                $service = new GoogleSheetService;
                $rowIndex = $titleRow->row_index; // Should be 1
                $range = "'DASHBOARD HARIAN1'!A{$rowIndex}:A{$rowIndex}";
                
                $newData = $titleRow->data;
                $newData[0] = $this->titleForm;
                
                $service->updateRow($range, [$this->titleForm]);

                $titleRow->data = $newData;
                $titleRow->save();

                Artisan::call('cache:clear');
                session()->flash('success', 'Judul Dashboard berhasil diperbarui!');
                $this->isTitleEditModalOpen = false;
            } catch (\Exception $e) {
                Log::error('Google API Error: '.$e->getMessage());
                session()->flash('error', 'Gagal update judul ke Google Sheets: '.$e->getMessage());
            }
        }
    }

    public function closeTitleEditModal()
    {
        $this->isTitleEditModalOpen = false;
    }

    public function editRow($rowIndex)
    {
        if (! Auth::check()) {
            return;
        }

        $ticket = DashboardData::where('row_index', $rowIndex)->first();
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

        $ticket = DashboardData::where('row_index', $this->editingRowIndex)->first();
        if ($ticket) {
            $data = $this->editForm;

            // Convert to sequential array preserving empty gaps
            $maxIndex = empty($data) ? 0 : max(array_keys($data));
            $sequentialData = [];
            for ($i = 0; $i <= $maxIndex; $i++) {
                $sequentialData[] = $data[$i] ?? '';
            }

            try {
                $service = new GoogleSheetService;
                $range = "'DASHBOARD HARIAN1'!A{$this->editingRowIndex}:AZ{$this->editingRowIndex}";
                $service->updateRow($range, $sequentialData);

                // Update local DB
                $ticket->data = $sequentialData;
                $ticket->save();

                // Hapus auto-sync karena membuat UI super lambat
                // \Illuminate\Support\Facades\Artisan::call('sheet:sync');

                Artisan::call('cache:clear');
                session()->flash('success', 'Data Dashboard berhasil diperbarui!');
            } catch (\Exception $e) {
                // Log it to ensure we see the error
                Log::error('Google API Error: '.$e->getMessage());
                session()->flash('error', 'Gagal update ke Google Sheets: '.$e->getMessage());
            }

            // Do not close modal if error, but wait, it's fine.
            // We can just keep it open on error.
            if (! session()->has('error')) {
                $this->isEditModalOpen = false;
            }
        }
    }

    public function recalculate()
    {
        $this->search = '';
        (new ProductivityService)->recalculateAndSave(null, true);
        session()->flash('success', 'Tabel Produktivitas berhasil dihitung ulang dan disinkronisasi dengan Spreadsheet!');
    }

    public function applyDateFilter()
    {
        if (empty($this->startDate) || empty($this->endDate)) {
            session()->flash('error', 'Silakan pilih tanggal awal dan tanggal akhir.');
            return;
        }

        try {
            $start = \Carbon\Carbon::parse($this->startDate);
            $end = \Carbon\Carbon::parse($this->endDate);

            if ($end->lt($start)) {
                session()->flash('error', 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.');
                return;
            }

            if ($start->diffInDays($end) > 60) {
                session()->flash('error', 'Maksimal rentang tanggal adalah 60 hari untuk menjaga kinerja Spreadsheet.');
                return;
            }

            $customDates = [];
            $current = $start->copy();
            while ($current->lte($end)) {
                $customDates[] = $current->format('d-M-y'); // Format 03-Sep-26
                $current->addDay();
            }

            $this->search = '';
            (new ProductivityService)->recalculateAndSave($customDates, true);
            session()->flash('success', 'Kolom tanggal berhasil diperbarui dan disinkronisasi dengan Spreadsheet!');
        } catch (\Exception $e) {
            session()->flash('error', 'Format tanggal tidak valid atau terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetDateFilter()
    {
        $this->startDate = '';
        $this->endDate = '';
        $this->recalculate();
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->editingRowIndex = null;
    }

    public function render()
    {
        $dashboardRows = DashboardData::orderBy('id', 'asc')->get();

        $headers = [];
        $title = 'Produktivitas';
        $segments = [];
        $currentSegment = 'PROVISIONING';

        $headerRowIndex = 2;
        if ($dashboardRows->count() >= 2) {
            $title = $dashboardRows[0]->data[0] ?? 'Produktivitas';
            $headers = $dashboardRows[1]->data ?? [];
            $headerRowIndex = $dashboardRows[1]->row_index ?? 2;
        }

        foreach ($dashboardRows as $index => $dbRow) {
            if ($index < 2) {
                continue;
            }

            $data = $dbRow->data;

            // Is it a section title?
            $nonEmptyCount = collect($data)->filter(fn ($v) => trim($v ?? '') !== '')->count();
            if ($nonEmptyCount === 1 && ! empty($data[0])) {
                $rawTitle = strtoupper(trim($data[0]));
                $rawTitle = preg_replace('/^PRODUKTIVITAS\s+/', '', $rawTitle);
                $rawTitle = preg_replace('/\s+BULAN\s+.*$/', '', $rawTitle);
                if ($rawTitle === 'PSB B2C') {
                    $rawTitle = 'PROVISIONING';
                }
                $currentSegment = trim($rawTitle);

                continue;
            }

            // Is it a repeated header row?
            // if (($data[0] ?? '') == 'NO' && ($data[1] ?? '') == 'NIK') {
            //     continue;
            // }

            $isTotal = str_contains(strtoupper($data[1] ?? ''), 'TOTAL') || str_contains(strtoupper($data[0] ?? ''), 'TOTAL');

            // Apply search
            if (! empty($this->search) && ! $isTotal) {
                $rowStr = implode(' ', $data);
                if (stripos($rowStr, $this->search) === false) {
                    continue;
                }
            }

            // Only add segment if not initialized
            if (! isset($segments[$currentSegment])) {
                $segments[$currentSegment] = [];
            }

            // Don't add total row if search is active and it doesn't match anything
            // but normally total rows are fine. Let's just always include total rows if we didn't skip them
            $segments[$currentSegment][] = [
                'row_index' => $dbRow->row_index,
                'data' => $data,
                'is_total' => $isTotal,
            ];
        }

        // Filter out empty segments if searching
        if (! empty($this->search)) {
            foreach ($segments as $seg => $rows) {
                $hasRealData = collect($rows)->contains(fn ($r) => ! $r['is_total']);
                if (! $hasRealData) {
                    unset($segments[$seg]);
                }
            }
        }

        return view('livewire.dashboard-viewer', [
            'title' => $title,
            'headers' => $headers,
            'segments' => $segments,
            'headerRowIndex' => $headerRowIndex,
        ]);
    }
}
