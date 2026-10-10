<?php

namespace App\Livewire;

use App\Models\DashboardData;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class HeatmapViewer extends Component
{
    public $search = '';

    public function render()
    {
        $query = DashboardData::orderBy('id', 'asc');
        $dashboardRows = $query->get();

        $headers = [];
        $title = 'Heatmap Harian';
        $rows = [];
        $dateCols = [];

        if ($dashboardRows->count() >= 2) {
            $title = $dashboardRows[0]->data[0] ?? 'Heatmap Harian';
            $headers = $dashboardRows[1]->data ?? [];

            // Find date columns (assuming they are between index 4 and the 'TOTAL TIKET' column)
            for ($i = 4; $i < count($headers); $i++) {
                if (str_contains(strtoupper($headers[$i]), 'TOTAL')) {
                    break;
                }
                $dateCols[$i] = $headers[$i];
            }
        }

        $segments = [];
        $currentSegment = 'PROVISIONING';

        if ($dashboardRows->count() > 2) {
            foreach ($dashboardRows as $index => $dbRow) {
                if ($index < 2) {
                    continue;
                } // Skip title and header

                $data = $dbRow->data;
                $name = trim($data[3] ?? '');

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

                // FILTER: Skip empty rows, Header rows, Total rows
                if (empty($name) || strtoupper($name) === 'NAMA' || strtoupper($name) === 'UNKNOWN') {
                    continue;
                }
                if (isset($data[1]) && str_contains(strtoupper($data[1]), 'TOTAL')) {
                    continue;
                }
                if (isset($data[0]) && str_contains(strtoupper($data[0]), 'TOTAL')) {
                    continue;
                }

                // Skip if name doesn't match search
                if (! empty($this->search) && stripos($name, $this->search) === false) {
                    continue;
                }

                $dailyData = [];
                $totalTickets = 0;
                $daysPresent = 0;

                foreach ($dateCols as $i => $dateHeader) {
                    $val = $data[$i] ?? '';
                    $dailyData[] = [
                        'date' => $dateHeader,
                        'val' => $val,
                    ];
                    if (is_numeric($val) && (float) $val > 0) {
                        $totalTickets += (float) $val;
                        $daysPresent++;
                    }
                }

                $prodIdx = array_search('PROD', $headers);
                $totalIdx = array_search('TOTAL', $headers);

                if ($prodIdx !== false && isset($data[$prodIdx]) && is_numeric($data[$prodIdx])) {
                    $avg = (float) $data[$prodIdx];
                } else {
                    $avg = $daysPresent > 0 ? round($totalTickets / $daysPresent, 2) : 0;
                }

                if ($totalIdx !== false && isset($data[$totalIdx]) && is_numeric($data[$totalIdx])) {
                    $totalTickets = (float) $data[$totalIdx];
                }

                if (! isset($segments[$currentSegment])) {
                    $segments[$currentSegment] = [];
                }

                $segments[$currentSegment][] = [
                    'name' => $name,
                    'labor' => $data[2] ?? '',
                    'daily' => $dailyData,
                    'total' => $totalTickets,
                    'avg' => $avg,
                ];
            }
        }

        // Remove empty segments
        $segments = array_filter($segments, fn ($rows) => count($rows) > 0);

        return view('livewire.heatmap-viewer', [
            'title' => 'Heatmap Harian', // Static title based on page context
            'dateCols' => $dateCols,
            'segments' => $segments,
            'target' => 1.5,
        ]);
    }
}
