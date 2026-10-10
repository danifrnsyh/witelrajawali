<?php

namespace App\Console\Commands;

use App\Models\SobiDigiposData;
use App\Services\GoogleSheetService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

#[Signature('sheet:sync-sobi')]
#[Description('Tarik data Sobi Digipos dari Google Sheets API')]
class SyncSobiDigipos extends Command
{
    public function handle()
    {
        $sheetId = env('GOOGLE_SHEET_ID_SOBI');
        if (! $sheetId) {
            $this->error('GOOGLE_SHEET_ID_SOBI belum diset di file .env');
            return 1;
        }

        $service = new GoogleSheetService;
        $this->info('Mengambil token akses SOBI/DIGIPOS...');
        try {
            $token = $service->getAccessToken();
            $this->info('Menarik data dari Spreadsheet SOBI...');

            $range = urlencode("'SOBI/DIGIPOS'!A1:Z5000");
            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/{$range}";
            
            $response = Http::withOptions(['force_ip_resolve' => 'v4'])
                ->connecttimeout(30)
                ->timeout(30)
                ->withToken($token)
                ->get($url);

            if (! $response->successful()) {
                $this->error('Gagal mengambil data SOBI: '.$response->body());
                return 1;
            }

            $rows = $response->json()['values'] ?? [];
            if (! empty($rows)) {
                SobiDigiposData::truncate(); // Hapus data lama
                $dataToInsert = [];

                foreach ($rows as $index => $row) {
                    $dataToInsert[] = [
                        'row_index' => $index + 1,
                        'data' => json_encode($row),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                foreach (array_chunk($dataToInsert, 500) as $chunk) {
                    SobiDigiposData::insert($chunk);
                }
                $this->info('Sinkronisasi SOBI Selesai! Berhasil memproses '.count($rows).' baris.');
            }

        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan SOBI: '.$e->getMessage());
            return 1;
        } finally {
            Artisan::call('cache:clear');
            $this->info('Cache aplikasi berhasil dibersihkan.');
        }

        return 0;
    }
}
