<?php

namespace App\Console\Commands;

use App\Models\SheetData;
use App\Services\GoogleSheetService;
use App\Services\ProductivityService;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

#[Signature('sheet:sync')]
#[Description('Tarik data dari Google Sheets API')]
class SyncSheetData extends Command
{
    public function handle()
    {
        $sheetId = env('GOOGLE_SHEET_ID');
        if (! $sheetId) {
            $this->error('GOOGLE_SHEET_ID belum diset di file .env');

            return;
        }

        $service = new GoogleSheetService;

        $this->info('Mengambil token akses...');
        try {
            $token = $service->getAccessToken();
            $this->info('Menarik data dari Spreadsheet...');

            $hasError = false;

            // Ambil data Sheet pertama (biasanya Sheet1).
            $datasheetId = env('GOOGLE_SHEET_ID_DATASHEET', $sheetId);
            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$datasheetId}/values/Sheet1!A1:AZ10000";
            $response = Http::withOptions(['force_ip_resolve' => 'v4'])->connecttimeout(30)->timeout(30)->withToken($token)->get($url);

            if (! $response->successful()) {
                $this->error('Gagal mengambil data Sheet1: '.$response->body());
                $hasError = true;
            } else {
                $rows = $response->json()['values'] ?? [];
                if (! empty($rows)) {
                    SheetData::truncate(); // Hapus data lama
                    $dataToInsert = [];
                    $needsSheetUpdate = false;
                    $columnRValues = [];

                    foreach ($rows as $index => $row) {
                        if ($index > 0 && is_array($row)) {
                            $closeDt = $row[15] ?? ($row[14] ?? null);
                            if ($closeDt && trim((string) $closeDt) !== '') {
                                try {
                                    $formatted = Carbon::parse($closeDt)->format('d-M-y');
                                    while (count($row) <= 17) {
                                        $row[] = '';
                                    }
                                    if (($row[17] ?? '') !== $formatted) {
                                        $needsSheetUpdate = true;
                                    }
                                    $row[17] = $formatted;
                                } catch (\Exception $e) {
                                }
                            }
                            $columnRValues[] = [$row[17] ?? ''];
                        }

                        $dataToInsert[] = [
                            'row_index' => $index + 1,
                            'data' => json_encode($row),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    foreach (array_chunk($dataToInsert, 500) as $chunk) {
                        SheetData::insert($chunk);
                    }
                    $this->info('Sinkronisasi Sheet 1 Selesai! Berhasil memproses '.count($rows).' data.');

                    // Auto-sync submission_key kembali ke Google Sheet jika ada data baru/kosong
                    if ($needsSheetUpdate && ! empty($columnRValues)) {
                        $endRow = count($columnRValues) + 1;
                        $rangeR = "Sheet1!R2:R{$endRow}";
                        try {
                            $service->updateRange($rangeR, $columnRValues);
                            $this->info('Auto-update kolom submission_key ke Google Sheets berhasil!');
                        } catch (\Exception $e) {
                            $this->warn('Gagal auto-update submission_key ke Google Sheets: '.$e->getMessage());
                        }
                    }

                    // Auto-generate Tabel Produktivitas dari SheetData secara real-time
                    $this->info('Menghitung ulang Tabel Produktivitas dari Data Sheet Utama...');
                    $prodService = new ProductivityService;
                    $prodCount = $prodService->recalculateAndSave();
                    $this->info("Tabel Produktivitas berhasil diperbarui ({$prodCount} baris)!");
                }
            }

        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: '.$e->getMessage());

            return 1;
        } finally {
            // Bersihkan cache selalu
            Artisan::call('cache:clear');
            $this->info('Cache aplikasi berhasil dibersihkan (Update selesai).');
        }

        return 0;
    }
}
