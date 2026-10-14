# 📊 Witel Rajawali - Operations & Technician Performance Monitoring System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Flutter](https://img.shields.io/badge/Flutter-3.x-02569B?style=for-the-badge&logo=flutter&logoColor=white)](https://flutter.dev)
[![Google Sheets API](https://img.shields.io/badge/Google_Sheets_API-v4-34A853?style=for-the-badge&logo=google-sheets&logoColor=white)](https://developers.google.com/sheets/api)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)

Sistem Informasi Dashboard Pemantauan Operasional dan Analitik Kinerja Teknisi Terintegrasi (*Integrated Operations & Technician Performance Analytics Dashboard*). Dikembangkan menggunakan arsitektur modern **Laravel 11**, **Livewire 3**, integrasi dua arah **Google Sheets API**, serta aplikasi pendamping multiplatform **Flutter**.

---

## 🌟 Fitur Utama (Core Features)

1. **Executive Operational Dashboard**
   - Monitoring KPI operasional harian secara *real-time* (PSB B2C, ASSR B2C, ASSR B2B).
   - Visualisasi pencapaian target, total order selesai, kendala, dan rasio efisiensi.

2. **Papan Peringkat Sektoral (Sectoral Ranking)**
   - Klasifikasi data performa sektoral berdasarkan periode tanggal yang dinamis (diekstraksi dari *submission key*).
   - Perhitungan bobot performa otomatis dengan filter cerdas (pengecualian parameter penalti dari visualisasi publik).
   - Sinkronisasi dua arah (*bi-directional*) untuk parameter bobot dan data teknisi langsung dari/ke Google Sheets.

3. **Analitik Kinerja Teknisi & Leaderboard**
   - Rekapitulasi poin kinerja per teknisi berdasarkan jenis pekerjaan dan kepatuhan SLA.
   - Papan peringkat teknisi transparan untuk meningkatkan produktivitas lapangan.

4. **Tracking SOBI & DigiPOS**
   - Pemantauan distribusi dan aktivasi outlet SOBI & DigiPOS.
   - Sinkronisasi terjadwal melalui background scheduler / Artisan command.

5. **Visualisasi Spasial & Kendala (Heatmap & Obstacle Mapping)**
   - Pemetaan persebaran kendala jaringan dan provisioning di area operasional.
   - Analisis titik choke dan kendala lapangan untuk percepatan resolusi tiket.

6. **Export Laporan Beresolusi Tinggi**
   - Fitur ekspor tabel dan visualisasi ke format **PDF** dan **JPG High-Definition** menggunakan *html2canvas* dan *jsPDF*.
   - Filter tanggal terpadu dengan desain UI presisi dan ramah perangkat mobile.

7. **Aplikasi Mobile Multiplatform (Flutter)**
   - Companion mobile application untuk sistem operasi Android & iOS.
   - Akses cepat dan responsif bagi pimpinan dan tim operasional lapangan saat bertugas.

---

## 🏗️ Arsitektur & Teknologi (Tech Stack)

| Lapisan (Layer) | Teknologi |
|---|---|
| **Backend Framework** | Laravel 11 (PHP 8.3) |
| **Reactivity & State** | Laravel Livewire 3 + Alpine.js |
| **Frontend & Styling** | Tailwind CSS (Responsive Design, Inter Typography, Modern Red Theme) |
| **Mobile App** | Flutter 3.x (Dart), WebKit / WebView Platform Integration |
| **Integrasi Eksternal** | Google Cloud Services / Google Sheets API v4 |
| **Database** | SQLite / MySQL / PostgreSQL (Database-agnostic via Eloquent ORM) |
| **Reporting / Export** | html2canvas, jsPDF, Custom Print Engine |

---

## 🚀 Panduan Instalasi Lokal (Getting Started)

### Prasyarat
- PHP >= 8.3
- Composer >= 2.x
- Node.js >= 18.x & NPM
- Flutter SDK (Opsional, untuk kompilasi aplikasi mobile)

### 1. Kloning Repositori
```bash
git clone https://github.com/danifrnsyh/witelrajawali.git
cd witelrajawali
```

### 2. Instal Dependensi Backend & Frontend
```bash
# Instal dependensi PHP
composer install

# Instal dependensi JavaScript & CSS
npm install
npm run build
```

### 3. Konfigurasi Lingkungan (.env)
Salin berkas contoh konfigurasi:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi pada file `.env`:
```env
APP_NAME="Witel Rajawali"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Konfigurasi Database (Default SQLite)
DB_CONNECTION=sqlite
# Atau jika menggunakan MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=witelrajawali
# DB_USERNAME=root
# DB_PASSWORD=

# Integrasi Google Sheets (Gunakan credentials service account Anda)
GOOGLE_APPLICATION_CREDENTIALS=storage/app/credentials.json
GOOGLE_SHEET_ID_SEKTORAL=your_google_sheet_id_here
GOOGLE_SHEET_ID_SOBI=your_google_sheet_id_here
```

### 4. Menyiapkan Database & Migrasi
```bash
# Buat file database jika menggunakan SQLite
touch database/database.sqlite

# Jalankan migrasi
php artisan migrate
```

### 5. Menjalankan Server Lokal
```bash
php artisan serve
```
Aplikasi web kini dapat diakses di `http://127.0.0.1:8000`.

### 6. Menjalankan Aplikasi Mobile (Flutter)
```bash
cd flutter_application_1
flutter pub get
flutter run --dart-define=APP_URL=http://10.0.2.2:8000
```
*(Catatan: gunakan `http://10.0.2.2:8000` untuk emulator Android, atau alamat IP LAN Anda untuk perangkat fisik).*

---

## 🔒 Kebijakan Keamanan & Data (Security Notice)

Proyek ini dipersiapkan sebagai portofolio pengembangan perangkat lunak:
- **Zero Credentials in VCS**: Tidak ada API keys, service account private keys (`credentials.json`), spreadsheet ID sensitif, maupun kredensial basis data nyata yang disertakan dalam repositori ini.
- Semua konfigurasi sensitif dipisahkan secara ketat menggunakan environment variables (`.env`) yang telah dikecualikan oleh `.gitignore`.
- Repositori ini bersih dari file database internal, riwayat cache, maupun informasi server pribadi.

---

## 📄 Lisensi (License)

Proyek ini berlisensi [MIT License](LICENSE) dan dikembangkan untuk keperluan portofolio profesional.
