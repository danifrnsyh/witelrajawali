<?php

namespace App\Services;

use App\Models\DashboardData;
use App\Models\SheetData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProductivityService
{
    /**
     * Bobot mapping per jenis pekerjaan
     */
    public const BOBOT_MAP = [
        'AKTIVASI CROSS CONNECT TDE' => 0.75,
        'AKTIVASI/MIGRASI/DISMANTEL DCS' => 0.75,
        'AKTIVASI/MIGRASI/DISMANTEL DIGISERVE' => 0.75,
        'AKTIVASI/MIGRASI/DISMANTEL HYPERNET' => 0.75,
        'CORRECTIVE AKSES TOWER CENTRATAMA' => 0.75,
        'CORRECTIVE AKSES TOWER LINTASARTA' => 0.75,
        'CORRECTIVE AKSES TOWER UMT' => 0.75,
        'CORRECTIVE CROSS CONNECT TDE' => 0.75,
        'CORRECTIVE CSA' => 0.75,
        'CORRECTIVE DCS' => 0.75,
        'CORRECTIVE DIGISERVE' => 0.75,
        'CORRECTIVE HYPERNET' => 0.75,
        'CORRECTIVE MMP' => 0.75,
        'CORRECTIVE MYREP' => 0.75,
        'CORRECTIVE NUTECH' => 0.38,
        'CORRECTIVE SNT' => 0.75,
        'CORRECTIVE SPBU' => 0.75,
        'CORRECTIVE TBG' => 0.75,
        'CORRECTIVE TOWER POLARIS' => 0.75,
        'CORRECTIVE TOWER TIS' => 0.75,
        'INVENTORY SPBU' => 0.75,
        'PREVENTIF MMP' => 0.38,
        'PREVENTIVE AKSES TOWER CENTRATAMA' => 0.38,
        'PREVENTIVE AKSES TOWER LINTASARTA' => 0.38,
        'PREVENTIVE AKSES TOWER UMT' => 0.38,
        'PREVENTIVE ASIANET' => 0.50,
        'PREVENTIVE CSA' => 0.38,
        'PREVENTIVE NUTECH' => 0.38,
        'PREVENTIVE SPBU' => 0.75,
        'PREVENTIVE TBG' => 0.38,
        'PREVENTIVE TOWER POLARIS' => 0.38,
        'PREVENTIVE TOWER TIS' => 0.38,
        'RELOKASI DCS' => 0.19,
        'RELOKASI DIGISERVE' => 0.19,
        'RELOKASI HYPERNET' => 0.19,
        'CORRECTIVE MITRATEL' => 0.75,
        'HSI INDIHOME RESELLER' => 0.38,
        'TIKET DATIN KATEGORI 1' => 0.50,
        'TIKET DATIN KATEGORI 2' => 0.50,
        'TIKET DATIN KATEGORI 3' => 0.50,
        'TIKET HSI INDIBIZ' => 0.38,
        'TIKET NODEB CNQ (PREVENTIVE/QUALITY)' => 0.75,
        'TIKET NODEB CRITICAL' => 1.13,
        'TIKET NODEB LOW' => 0.75,
        'TIKET NODEB MAJOR' => 0.75,
        'TIKET NODEB MINOR' => 0.75,
        'TIKET NODEB PREMIUM' => 1.13,
        'TIKET NODEB PREMIUM PREVENTIVE' => 0.75,
        'TIKET OLO DATIN GAMAS' => 0.75,
        'TIKET OLO DATIN NON GAMAS' => 0.75,
        'TIKET OLO DATIN QUALITY' => 0.75,
        'TIKET OLO SL WDM' => 0.75,
        'TIKET OLO SL WDM QUALITY' => 0.75,
        'TIKET PRA SQM GAUL HSI' => 0.38,
        'TIKET SIP TRUNK' => 0.50,
        'TIKET SQM DATIN' => 0.50,
        'TIKET SQM HSI' => 0.38,
        'TIKET WIFI ID' => 0.50,
        'TIKET WIFI LOGIC' => 0.50,
        'UNSPEC DATIN' => 0.50,
        'UNSPEC HSI' => 0.38,
        'UNSPEC SITE' => 0.38,
        'UNSPEC WIFI' => 0.38,
        'DISMANTLING DC' => 0.19,
        'INFRACARE' => 0.38,
        'IXSA FTM' => 0.75,
        'IXSA ODC' => 0.75,
        'IXSA OLT' => 0.38,
        'LAPSUNG (LAPORAN LANGSUNG)' => 0.38,
        'SQM REGULER' => 0.38,
        'TANGIBLE ODP' => 0.38,
        'TIKET REGULER' => 0.38,
        'UNSPEC REGULER' => 0.38,
        'VALIDASI TIANG' => 0.06,
        'VALINS FTM' => 0.75,
        'TIKET GAMAS DISTRIBUSI' => 0.75,
        'TIKET GAMAS FEEDER' => 1.89,
        'TIKET GAMAS ODC' => 3.40,
        'TIKET GAMAS ODP' => 0.57,
        'VALINS ODC' => 0.75,
        'VALINS REGULAR' => 0.09,
        'PSB INDIBIZ' => 1.00,
        'PSB OLO' => 1.19,
        'PSB DATIN' => 1.19,
        'PSB WIFI' => 1.19,
        'TIKET FFG DATIN' => 0.38,
        'TIKET FFG HSI' => 0.38,
        'TIKET FFG WIFI' => 0.38,
        'PREVENTIVE FIBERISASI' => 0.38,
        'PSB MYREP' => 0.38,
        'PSB SURGE' => 0.38,
        'FTTR IBU' => 0.94,
        'SPPG' => 1.51,
        'DISMANTLING ONT' => 0.13,
        'DISMANTLING PLC' => 0.13,
        'DISMANTLING STB' => 0.13,
        'DISMANTLING WIFI EXTENDER' => 0.13,
        'DISMANTLING FWA' => 0.13,
        'PDA' => 1.00,
        'ORBIT' => 0.21,
        'PSB INDIHOME' => 1.00,
        'REPLACEMENT ONT PREMIUM/DUAL BAND' => 0.25,
        'REPLACEMENT STB' => 0.25,
        'TIKET FFG INDIHOME' => 0.38,
        'SURVEY PT2' => 0.28,
        'PROGRESS PT2' => 1.51,
        'VALIDASI DISTRIBUSI' => 0.75,
        'VALIDASI FEEDER' => 1.13,
        'UPSELLING MO' => 0.21,
        'VALIDASI DISTRIBUSI 5 PORT' => 0.47,
        'VALIDASI DISTRIBUSI 10 PORT' => 0.94,
        'VALIDASI FEEDER 5 PORT' => 0.47,
        'VALIDASI FEEDER 10 PORT' => 0.94,
        'CORRECTIVE SURGE' => 0.75,
    ];

    /**
     * Hitung dan perbarui tabel DashboardData secara keseluruhan dari SheetData
     */
    public function recalculateAndSave(?array $customDates = null, bool $syncToSheet = false): int
    {
        // 1. Ambil struktur segmen dan teknisi dari DashboardData yang sudah ada
        $segments = $this->getTechnicianSegments();
        if (empty($segments)) {
            Log::warning('ProductivityService: Tidak ada segmen teknisi yang ditemukan.');

            return 0;
        }

        // 2. Ambil list tanggal dari header atau tentukan tanggal bulan ini
        $oldDates = $this->getDateColumns();
        $dateColumns = $customDates !== null ? $customDates : $oldDates;

        // 2.5 Ekstrak manual overrides dari DashboardData yang sedang aktif sebelum ditimpa
        $overridesPath = storage_path('app/dashboard_manual_overrides.json');
        $overrides = [];
        if (File::exists($overridesPath)) {
            $overrides = json_decode(File::get($overridesPath), true) ?: [];
        }

        $oldRows = DashboardData::all();
        foreach ($oldRows as $r) {
            $data = is_string($r->data) ? json_decode($r->data, true) : $r->data;
            if (!is_array($data) || !isset($data[1]) || empty($data[1]) || $data[1] == 'NIK' || str_contains(strtoupper($data[1]), 'TOTAL')) {
                continue;
            }
            $nik = trim($data[1]);
            
            for ($i = 0; $i < count($oldDates); $i++) {
                $colIndex = 4 + $i; // Offset for NO, NIK, LABOR, NAMA
                if (isset($data[$colIndex])) {
                    $val = trim($data[$colIndex]);
                    // Jangan timpa jika nilainya kosong, tapi simpan apapun yang ada (L, S, CT, angka)
                    if ($val !== '') {
                        $overrides[$nik][$oldDates[$i]] = $val;
                    }
                }
            }
        }
        File::put($overridesPath, json_encode($overrides, JSON_PRETTY_PRINT));

        // 3. Ambil semua tiket dari SheetData dan hitung poin harian per NIK
        $techDailyPoints = $this->calculateDailyPointsFromTickets();

        // 4. Susun baris-baris DashboardData baru
        $rowsToInsert = [];
        $rowIndex = 1;

        // Header 1 (Title)
        $titleRow = DashboardData::where('row_index', 1)->first();
        $firstSegmentTitle = 'PRODUKTIVITAS BULAN INI TA RAJAWALI';
        if ($titleRow && isset($titleRow->data[0])) {
            $firstSegmentTitle = $titleRow->data[0];
        }

        $rowsToInsert[] = [
            'row_index' => $rowIndex++,
            'data' => json_encode([$firstSegmentTitle]),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Header 2 (Columns)
        $columnsHeader = array_merge(
            ['NO', 'NIK', 'LABOR', 'NAMA'],
            $dateColumns,
            ['TOTAL', 'PROD', 'MASUK', 'L+S+CT', 'RANK', 'TARGET', 'GAP']
        );

        $rowsToInsert[] = [
            'row_index' => $rowIndex++,
            'data' => json_encode($columnsHeader),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $targetDaily = 1.5;

        // Loop per Segment
        foreach ($segments as $segmentTitle => $techs) {
            // Segment Title Row
            $rowsToInsert[] = [
                'row_index' => $rowIndex++,
                'data' => json_encode([$segmentTitle]),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Hitung data untuk tiap teknisi di segmen ini
            $segmentRowsData = [];

            foreach ($techs as $techIndex => $tech) {
                $nik = $tech['nik'];
                $labor = $tech['labor'] ?? $nik;
                $nama = $tech['nama'];

                $dailyVals = [];
                $totalPoints = 0;
                $daysPresent = 0;
                $daysAbsent = 0;

                foreach ($dateColumns as $dateKey) {
                    $points = $techDailyPoints[$nik][$dateKey] ?? 0;
                    $oldVal = $overrides[$nik][$dateKey] ?? null;

                    // Apakah ada nilai manual dari database lama yang bukan format angka perhitungan standar?
                    // Jika ada nilai manual (S, CT, atau angka ketikan manual), kita gunakan itu
                    // Namun jika point dari sistem tiba-tiba ada (tiket baru masuk), dan oldVal adalah 'L', 
                    // point dari sistem harus menang.
                    
                    if ($oldVal !== null && $oldVal !== '' && $oldVal !== 'L') {
                        // Override manual ada (S, CT, I, atau nilai angka kustom)
                        $dailyVals[] = strtoupper($oldVal);
                        if (is_numeric($oldVal)) {
                            $totalPoints += (float) $oldVal;
                            $daysPresent++;
                        } else {
                            $daysAbsent++;
                        }
                    } elseif ($points > 0) {
                        // Pakai hitungan poin sistem
                        $valStr = number_format($points, 2, '.', '');
                        $dailyVals[] = $valStr;
                        $totalPoints += $points;
                        $daysPresent++;
                    } else {
                        // Tidak ada poin, dan tidak ada override (atau override-nya cuma 'L')
                        try {
                            $colDate = Carbon::createFromFormat('d-M-y', $dateKey);
                            if ($colDate->isFuture()) {
                                $dailyVals[] = '';
                            } else {
                                $dailyVals[] = 'L';
                                $daysAbsent++;
                            }
                        } catch (\Exception $e) {
                            $dailyVals[] = 'L';
                            $daysAbsent++;
                        }
                    }
                }

                $prod = $daysPresent > 0 ? round($totalPoints / $daysPresent, 2) : 0;
                $gap = round($totalPoints - ($daysPresent * $targetDaily), 2);

                $segmentRowsData[] = [
                    'no' => $techIndex + 1,
                    'nik' => $nik,
                    'labor' => $labor,
                    'nama' => $nama,
                    'daily' => $dailyVals,
                    'total' => $totalPoints,
                    'prod' => $prod,
                    'masuk' => $daysPresent,
                    'absent' => $daysAbsent,
                    'target' => $targetDaily,
                    'gap' => $gap,
                ];
            }

            // Hitung RANK di dalam segmen berdasarkan TOTAL poin (descending)
            $sortedTotals = array_column($segmentRowsData, 'total');
            rsort($sortedTotals);

            foreach ($segmentRowsData as &$row) {
                $rank = array_search($row['total'], $sortedTotals);
                $row['rank'] = ($rank !== false) ? ($rank + 1) : 1;
            }
            unset($row);

            // Masukkan data baris teknisi
            $segmentTotalDaily = array_fill(0, count($dateColumns), 0.0);
            $segmentSumTotal = 0.0;
            $segmentSumMasuk = 0;
            $segmentSumAbsent = 0;

            foreach ($segmentRowsData as $row) {
                $rowArray = [
                    (string) $row['no'],
                    (string) $row['nik'],
                    (string) $row['labor'],
                    (string) $row['nama'],
                ];

                foreach ($row['daily'] as $dIdx => $dVal) {
                    $rowArray[] = $dVal;
                    if (is_numeric($dVal)) {
                        $segmentTotalDaily[$dIdx] += (float) $dVal;
                    }
                }

                $rowArray[] = number_format($row['total'], 2, '.', '');
                $rowArray[] = number_format($row['prod'], 2, '.', '');
                $rowArray[] = (string) $row['masuk'];
                $rowArray[] = (string) $row['absent'];
                $rowArray[] = (string) $row['rank'];
                $rowArray[] = number_format($row['target'], 1, '.', '');
                $rowArray[] = number_format($row['gap'], 2, '.', '');

                $segmentSumTotal += $row['total'];
                $segmentSumMasuk += $row['masuk'];
                $segmentSumAbsent += $row['absent'];

                $rowsToInsert[] = [
                    'row_index' => $rowIndex++,
                    'data' => json_encode($rowArray),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Segment TOTAL Row
            $totalRowArray = ['TOTAL', 'TOTAL', '', ''];
            foreach ($segmentTotalDaily as $dSum) {
                $totalRowArray[] = $dSum > 0 ? number_format($dSum, 2, '.', '') : '';
            }
            $avgProd = count($segmentRowsData) > 0 ? round($segmentSumTotal / max(1, $segmentSumMasuk), 2) : 0;
            $totalRowArray[] = number_format($segmentSumTotal, 2, '.', '');
            $totalRowArray[] = number_format($avgProd, 2, '.', '');
            $totalRowArray[] = (string) $segmentSumMasuk;
            $totalRowArray[] = (string) $segmentSumAbsent;
            $totalRowArray[] = '';
            $totalRowArray[] = '';
            $totalRowArray[] = '';

            $rowsToInsert[] = [
                'row_index' => $rowIndex++,
                'data' => json_encode($totalRowArray),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 5. Simpan ke database secara atomik
        DB::transaction(function () use ($rowsToInsert) {
            DashboardData::truncate();
            foreach (array_chunk($rowsToInsert, 100) as $chunk) {
                DashboardData::insert($chunk);
            }
        });

        Artisan::call('cache:clear');
        Log::info('ProductivityService: Berhasil menghitung ulang '.count($rowsToInsert).' baris produktivitas.');

        if ($syncToSheet && count($rowsToInsert) > 0) {
            try {
                $sheetService = new GoogleSheetService();
                
                // Construct 2D array for Google Sheets
                $sheetValues = [];
                foreach ($rowsToInsert as $r) {
                    $sheetValues[] = json_decode($r['data'], true) ?: [];
                }

                // Clear entire sheet first (or just overwrite a huge range)
                // Overwriting A1:AZ1000 should be safe.
                $maxRow = count($sheetValues);
                $range = "'DASHBOARD HARIAN1'!A1:AZ{$maxRow}";
                
                // Clear existing data in Google Sheet
                $emptyData = array_fill(0, 1000, array_fill(0, 50, ''));
                $sheetService->updateRange("'DASHBOARD HARIAN1'!A1:AZ1000", $emptyData);
                
                // Write new data
                $sheetService->updateRange($range, $sheetValues);
                Log::info('ProductivityService: Berhasil sync ke Google Sheets.');
            } catch (\Exception $e) {
                Log::error('ProductivityService: Gagal sync ke Google Sheets: ' . $e->getMessage());
            }
        }

        return count($rowsToInsert);
    }

    /**
     * Hitung poin harian untuk setiap teknisi dari tabel SheetData
     */
    public function calculateDailyPointsFromTickets(): array
    {
        $allTickets = SheetData::where('id', '>', 1)->get();
        $techDailyPoints = [];

        foreach ($allTickets as $ticket) {
            $row = $ticket->data;
            if (! is_array($row)) {
                continue;
            }

            $dateKey = $row[17] ?? null; // submission_key (e.g. 01-Sep-26)
            if (! $dateKey) {
                continue;
            }

            $jenisOrder = strtoupper(trim($row[3] ?? ''));
            $bobot = self::BOBOT_MAP[$jenisOrder] ?? (isset($row[5]) && is_numeric($row[5]) ? (float) $row[5] / 5.3333 : 0.38);
            if ($bobot <= 0) {
                $bobot = 0.38;
            }

            $tech1Nik = trim($row[10] ?? '');
            $tech1Name = trim($row[11] ?? '');
            $tech2Nik = trim($row[12] ?? '');
            $tech2Name = trim($row[13] ?? '');

            $hasTech2 = (! empty($tech2Nik) || ! empty($tech2Name));

            // Teknisi 1
            if (! empty($tech1Nik)) {
                $points = $hasTech2 ? ($bobot / 2) : $bobot;
                $techDailyPoints[$tech1Nik][$dateKey] = ($techDailyPoints[$tech1Nik][$dateKey] ?? 0) + $points;
            }

            // Teknisi 2
            if (! empty($tech2Nik)) {
                $points = $bobot / 2;
                $techDailyPoints[$tech2Nik][$dateKey] = ($techDailyPoints[$tech2Nik][$dateKey] ?? 0) + $points;
            }
        }

        return $techDailyPoints;
    }

    /**
     * Ambil kolom-kolom tanggal
     */
    public function getDateColumns(): array
    {
        $headerRow = DashboardData::find(2);
        if ($headerRow && is_array($headerRow->data)) {
            $headers = $headerRow->data;
            $dates = [];
            for ($i = 4; $i < count($headers); $i++) {
                if (str_contains(strtoupper($headers[$i] ?? ''), 'TOTAL')) {
                    break;
                }
                if (! empty($headers[$i])) {
                    $dates[] = $headers[$i];
                }
            }
            if (! empty($dates)) {
                return $dates;
            }
        }

        // Fallback: 01-Sep-26 s/d 30-Sep-26 + 01-Oct-26
        $dates = [];
        for ($d = 1; $d <= 30; $d++) {
            $dates[] = sprintf('%02d-Sep-26', $d);
        }
        $dates[] = '01-Oct-26';

        return $dates;
    }

    /**
     * Ambil daftar segmen dan teknisi yang ada di DashboardData
     */
    public function getTechnicianSegments(): array
    {
        $rows = DashboardData::orderBy('id', 'asc')->get();
        $currentSegment = null;
        $segments = [];

        foreach ($rows as $r) {
            $d = $r->data;
            if (empty($d) || ! is_array($d)) {
                continue;
            }

            $nonEmpty = collect($d)->filter(fn ($v) => trim($v ?? '') !== '')->count();
            if ($nonEmpty === 1 && ! empty($d[0])) {
                $currentSegment = $d[0];
                if (! isset($segments[$currentSegment])) {
                    $segments[$currentSegment] = [];
                }

                continue;
            }

            if (($d[0] ?? '') === 'NO' && ($d[1] ?? '') === 'NIK') {
                continue;
            }
            if (str_contains(strtoupper($d[1] ?? ''), 'TOTAL') || str_contains(strtoupper($d[0] ?? ''), 'TOTAL')) {
                continue;
            }

            $nik = trim($d[1] ?? '');
            $nama = trim($d[3] ?? '');
            if (! empty($nik) && ! empty($nama) && $currentSegment) {
                $segments[$currentSegment][] = [
                    'nik' => $nik,
                    'labor' => $d[2] ?? $nik,
                    'nama' => $nama,
                ];
            }
        }

        // Jika kosong, muat dari backup file
        if (empty($segments)) {
            $backupPath = storage_path('app/dashboard_data_backup_original.json');
            if (File::exists($backupPath)) {
                $backup = json_decode(File::get($backupPath), true);
                foreach ($backup as $r) {
                    $d = $r['data'] ?? [];
                    if (is_string($d)) {
                        $d = json_decode($d, true);
                    }
                    if (empty($d) || ! is_array($d)) {
                        continue;
                    }

                    $nonEmpty = collect($d)->filter(fn ($v) => trim($v ?? '') !== '')->count();
                    if ($nonEmpty === 1 && ! empty($d[0])) {
                        $currentSegment = $d[0];
                        if (! isset($segments[$currentSegment])) {
                            $segments[$currentSegment] = [];
                        }

                        continue;
                    }

                    if (($d[0] ?? '') === 'NO' && ($d[1] ?? '') === 'NIK') {
                        continue;
                    }
                    if (str_contains(strtoupper($d[1] ?? ''), 'TOTAL') || str_contains(strtoupper($d[0] ?? ''), 'TOTAL')) {
                        continue;
                    }

                    $nik = trim($d[1] ?? '');
                    $nama = trim($d[3] ?? '');
                    if (! empty($nik) && ! empty($nama) && $currentSegment) {
                        $segments[$currentSegment][] = [
                            'nik' => $nik,
                            'labor' => $d[2] ?? $nik,
                            'nama' => $nama,
                        ];
                    }
                }
            }
        }

        return $segments;
    }

    /**
     * Restore data asli dari backup jika ingin undo
     */
    public function restoreBackup(): bool
    {
        $backupPath = storage_path('app/dashboard_data_backup_original.json');
        if (! File::exists($backupPath)) {
            return false;
        }

        $backup = json_decode(File::get($backupPath), true);
        if (empty($backup)) {
            return false;
        }

        DB::transaction(function () use ($backup) {
            DashboardData::truncate();
            $toInsert = [];
            foreach ($backup as $r) {
                $toInsert[] = [
                    'row_index' => $r['row_index'],
                    'data' => is_string($r['data']) ? $r['data'] : json_encode($r['data']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            foreach (array_chunk($toInsert, 100) as $chunk) {
                DashboardData::insert($chunk);
            }
        });

        Artisan::call('cache:clear');

        return true;
    }
}
