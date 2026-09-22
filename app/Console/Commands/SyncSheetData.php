<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\SheetData;

#[Signature('sheet:sync')]
#[Description('Tarik data dari Google Sheets API')]
class SyncSheetData extends Command
{
    public function handle()
    {
        $sheetId = env('GOOGLE_SHEET_ID');
        $credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS');
        
        if (!$sheetId || !$credentialsPath) {
            $this->error('GOOGLE_SHEET_ID atau GOOGLE_APPLICATION_CREDENTIALS belum diset di file .env');
            return;
        }

        if (!file_exists($credentialsPath)) {
            $this->error("File credentials tidak ditemukan di: " . $credentialsPath);
            return;
        }

        $this->info("Mengambil token akses...");
        try {
            $token = $this->getAccessToken($credentialsPath);
            $this->info("Menarik data dari Spreadsheet...");
            
            // Ambil data Sheet pertama (biasanya Sheet1). Kita ambil range A:O (15 kolom)
            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/Sheet1!A1:AZ";
            
            $response = Http::withToken($token)->get($url);
            
            if (!$response->successful()) {
                $this->error('Gagal mengambil data: ' . $response->body());
                return;
            }

            $rows = $response->json()['values'] ?? [];
            if (empty($rows)) {
                $this->info("Data kosong.");
            } else {
                $this->info("Ditemukan " . count($rows) . " baris data. Memulai sinkronisasi...");

                \App\Models\SheetData::truncate();

                $dataToInsert = [];
                foreach ($rows as $row) {
                    $dataToInsert[] = [
                        'data' => json_encode($row),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                foreach (array_chunk($dataToInsert, 500) as $chunk) {
                    \App\Models\SheetData::insert($chunk);
                }

                $this->info("Sinkronisasi Sheet 1 Selesai! Berhasil memproses " . count($rows) . " data.");
            }

            // ---------------------------------------------------------
            // SINKRONISASI DASHBOARD HARIAN
            // ---------------------------------------------------------
            $this->info("Menarik data DASHBOARD HARIAN...");
            $dashboardName = urlencode("'DASHBOARD HARIAN'");
            $urlDashboard = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/{$dashboardName}!A1:AZ";
            $responseDashboard = Http::withToken($token)->get($urlDashboard);

            if ($responseDashboard->successful()) {
                $dashboardRows = $responseDashboard->json()['values'] ?? [];
                if (!empty($dashboardRows)) {
                    \App\Models\DashboardData::truncate(); // Hapus data lama

                    $dashboardInsert = [];
                    foreach ($dashboardRows as $row) {
                        $dashboardInsert[] = [
                            'data' => json_encode($row),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    // Insert array chunks to DB
                    foreach (array_chunk($dashboardInsert, 100) as $chunk) {
                        \App\Models\DashboardData::insert($chunk);
                    }
                    $this->info("Sinkronisasi Dashboard Harian Selesai! Berhasil memproses " . count($dashboardRows) . " baris.");
                } else {
                    $this->info("Data Dashboard Harian kosong.");
                }
            } else {
                $this->error('Gagal mengambil data Dashboard Harian: ' . $responseDashboard->body());
            }

            
        } catch (\Exception $e) {
            $this->error("Terjadi kesalahan: " . $e->getMessage());
        }
    }

    private function getAccessToken($credentialsPath)
    {
        $json = json_decode(file_get_contents($credentialsPath), true);
        $privateKey = $json['private_key'] ?? '';
        $clientEmail = $json['client_email'] ?? '';

        if (!$privateKey || !$clientEmail) {
            throw new \Exception("Format file credentials.json tidak valid.");
        }

        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $now = time();
        $payload = json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/spreadsheets.readonly',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signatureInput = $base64UrlHeader . '.' . $base64UrlPayload;

        openssl_sign($signatureInput, $signature, $privateKey, 'sha256WithRSAEncryption');
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $signatureInput . '.' . $base64UrlSignature;

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception("Gagal mendapatkan akses token. Cek kembali credentials.json Anda.");
    }
}

