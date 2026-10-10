<?php

namespace App\Livewire;

use App\Models\DashboardData;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class PeringkatViewer extends Component
{
    public $search = '';

    public function render()
    {
        $query = DashboardData::orderBy('id', 'asc');
        $dashboardRows = $query->get();

        $headers = [];
        $title = 'Papan Peringkat Teknisi';
        $rows = [];
        $dateCols = [];

        if ($dashboardRows->count() >= 2) {
            $headers = $dashboardRows[1]->data ?? [];
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
                }

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

                if (! empty($this->search) && stripos($name, $this->search) === false) {
                    continue;
                }

                $dailyData = [];
                $totalTickets = 0;
                $daysPresent = 0;
                $daysAbsent = 0;

                foreach ($dateCols as $i => $dateHeader) {
                    $val = $data[$i] ?? '';
                    $dailyData[] = $val;
                    if (is_numeric($val) && (float) $val > 0) {
                        $totalTickets += (float) $val;
                        $daysPresent++;
                    } elseif (in_array(trim($val), ['L', 'S', 'CT'])) {
                        $daysAbsent++;
                    }
                }

                $prodIdx = array_search('PROD', $headers);
                $totalIdx = array_search('TOTAL', $headers);
                $masukIdx = array_search('MASUK', $headers);
                $absentIdx = array_search('L+S+CT', $headers);
                $gapIdx = array_search('GAP', $headers);

                $avg = ($prodIdx !== false && isset($data[$prodIdx]) && is_numeric($data[$prodIdx]))
                    ? (float) $data[$prodIdx]
                    : ($daysPresent > 0 ? round($totalTickets / $daysPresent, 2) : 0);

                if ($totalIdx !== false && isset($data[$totalIdx]) && is_numeric($data[$totalIdx])) {
                    $totalTickets = (float) $data[$totalIdx];
                }
                if ($masukIdx !== false && isset($data[$masukIdx]) && is_numeric($data[$masukIdx])) {
                    $daysPresent = (int) $data[$masukIdx];
                }
                if ($absentIdx !== false && isset($data[$absentIdx]) && is_numeric($data[$absentIdx])) {
                    $daysAbsent = (int) $data[$absentIdx];
                }

                $target = 1.5;
                $gap = ($gapIdx !== false && isset($data[$gapIdx]) && is_numeric($data[$gapIdx]))
                    ? (float) $data[$gapIdx]
                    : round($totalTickets - ($daysPresent * $target), 2);

                if (! isset($segments[$currentSegment])) {
                    $segments[$currentSegment] = [];
                }

                $segments[$currentSegment][] = [
                    'nik' => $data[1] ?? '',
                    'name' => $name,
                    'daily' => $dailyData,
                    'total' => $totalTickets,
                    'avg' => $avg,
                    'present' => $daysPresent,
                    'absent' => $daysAbsent,
                    'gap' => $gap,
                ];
            }
        }

        // Sort each segment by average descending
        foreach ($segments as $key => $rows) {
            usort($rows, function ($a, $b) {
                return $b['avg'] <=> $a['avg'];
            });
            $segments[$key] = $rows;
        }

        // Remove empty segments
        $segments = array_filter($segments, fn ($rows) => count($rows) > 0);

        $allRows = [];
        foreach ($segments as $sRows) {
            $allRows = array_merge($allRows, $sRows);
        }

        // Calculate summary
        $summary = [
            'avg_team' => count($allRows) > 0 ? round(array_sum(array_column($allRows, 'avg')) / count($allRows), 2) : 0,
            'total_team' => array_sum(array_column($allRows, 'total')),
            'achieved' => count(array_filter($allRows, fn ($r) => $r['avg'] >= 1.5)),
            'no_data' => count(array_filter($allRows, fn ($r) => $r['present'] == 0)),
        ];

        return view('livewire.peringkat-viewer', [
            'title' => $title,
            'segments' => $segments,
            'summary' => $summary,
            'target' => 1.5,
        ]);
    }
}
