<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleSheetService
{
    protected $sheetId;

    protected $credentialsPath;

    public function __construct($sheetId = null)
    {
        $this->sheetId = $sheetId ?? env('GOOGLE_SHEET_ID');
        $this->credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS');
    }

    public function setSheetId($sheetId)
    {
        $this->sheetId = $sheetId;
        return $this;
    }

    public function getAccessToken()
    {
        if (! $this->credentialsPath || ! file_exists($this->credentialsPath)) {
            if (file_exists(storage_path('app/credentials.json'))) {
                $this->credentialsPath = storage_path('app/credentials.json');
            } else {
                throw new \Exception('File credentials tidak ditemukan di: '.$this->credentialsPath);
            }
        }

        $json = json_decode(file_get_contents($this->credentialsPath), true);
        $privateKey = $json['private_key'] ?? '';
        $clientEmail = $json['client_email'] ?? '';

        if (! $privateKey || ! $clientEmail) {
            throw new \Exception('Format file credentials.json tidak valid.');
        }

        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $now = time();
        $payload = json_encode([
            'iss' => $clientEmail,
            // SCOPE diubah menjadi full access (tanpa .readonly) agar bisa write/edit
            'scope' => 'https://www.googleapis.com/auth/spreadsheets',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signatureInput = $base64UrlHeader.'.'.$base64UrlPayload;

        openssl_sign($signatureInput, $signature, $privateKey, 'sha256WithRSAEncryption');
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $signatureInput.'.'.$base64UrlSignature;

        $response = Http::connectTimeout(300)->timeout(300)->asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Gagal mendapatkan akses token. Pastikan kredensial benar.');
    }

    public function updateRow($range, $values, $customSheetId = null)
    {
        $token = $this->getAccessToken();

        $targetSheetId = $customSheetId ?? $this->sheetId;
        if (! $customSheetId) {
            if (strpos(strtoupper($range), 'SHEET1') !== false) {
                $targetSheetId = env('GOOGLE_SHEET_ID_DATASHEET', $this->sheetId);
            } elseif (strpos(strtoupper($range), 'DASHBOARD HARIAN') !== false) {
                $targetSheetId = env('GOOGLE_SHEET_ID_DASHBOARD', $this->sheetId);
            } elseif (
                strpos($range, '1_PSB_B2C') !== false ||
                strpos($range, '2_ASSR_B2C') !== false ||
                strpos($range, '3_ASSR_B2B') !== false
            ) {
                $targetSheetId = env('GOOGLE_SHEET_ID_SEKTORAL', env('GOOGLE_SHEET_ID_SOBI', $this->sheetId));
            }
        }

        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$targetSheetId}/values/{$range}?valueInputOption=USER_ENTERED";

        $response = Http::connectTimeout(300)->timeout(300)->withToken($token)->put($url, [
            'range' => $range,
            'majorDimension' => 'ROWS',
            'values' => [$values],
        ]);

        if (! $response->successful()) {
            throw new \Exception('Gagal mengupdate Google Sheets: '.$response->body());
        }

        return $response->json();
    }

    public function appendRow($range, $values)
    {
        $token = $this->getAccessToken();

        $targetSheetId = $this->sheetId;
        if (strpos(strtoupper($range), 'SHEET1') !== false) {
            $targetSheetId = env('GOOGLE_SHEET_ID_DATASHEET', $this->sheetId);
        } elseif (strpos(strtoupper($range), 'DASHBOARD HARIAN') !== false) {
            $targetSheetId = env('GOOGLE_SHEET_ID_DASHBOARD', $this->sheetId);
        }

        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$targetSheetId}/values/{$range}:append?valueInputOption=USER_ENTERED&insertDataOption=INSERT_ROWS";

        $response = Http::connectTimeout(300)->timeout(300)->withToken($token)->post($url, [
            'range' => $range,
            'majorDimension' => 'ROWS',
            'values' => [$values],
        ]);

        if (! $response->successful()) {
            throw new \Exception('Gagal menambah data ke Google Sheets: '.$response->body());
        }

        return $response->json();
    }

    public function updateRange($range, array $rows)
    {
        $token = $this->getAccessToken();

        $targetSheetId = $this->sheetId;
        if (strpos(strtoupper($range), 'SHEET1') !== false) {
            $targetSheetId = env('GOOGLE_SHEET_ID_DATASHEET', $this->sheetId);
        } elseif (strpos(strtoupper($range), 'DASHBOARD HARIAN') !== false) {
            $targetSheetId = env('GOOGLE_SHEET_ID_DASHBOARD', $this->sheetId);
        }

        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$targetSheetId}/values/{$range}?valueInputOption=USER_ENTERED";

        $response = Http::connectTimeout(300)->timeout(300)->withToken($token)->put($url, [
            'range' => $range,
            'majorDimension' => 'ROWS',
            'values' => $rows,
        ]);

        if (! $response->successful()) {
            throw new \Exception('Gagal mengupdate Google Sheets: '.$response->body());
        }

        return $response->json();
    }
}
