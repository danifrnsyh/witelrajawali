<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

$credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS');
$sheetId = env('GOOGLE_SHEET_ID');

$json = json_decode(file_get_contents($credentialsPath), true);
$privateKey = $json['private_key'];
$clientEmail = $json['client_email'];

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
$signatureInput = $base64UrlHeader.'.'.$base64UrlPayload;
openssl_sign($signatureInput, $signature, $privateKey, 'sha256WithRSAEncryption');
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$jwt = $signatureInput.'.'.$base64UrlSignature;

$res = Http::asForm()->post('https://oauth2.googleapis.com/token', [
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion' => $jwt,
]);
$token = $res->json()['access_token'];

$url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}/values/A1:AZ1";
$data = Http::withToken($token)->get($url)->json();

echo json_encode($data);
