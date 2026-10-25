# Vulnerability Management Dashboard

Aplikasi web untuk melacak, memprioritaskan, dan mendokumentasikan temuan vulnerability dari hasil pentest.

---

## Deskripsi

Aplikasi ini mencatat temuan vulnerability dengan CVSS score, severity, dan status remediation, lalu menampilkannya di dashboard dengan visualisasi dan mengekspornya sebagai laporan PDF bergaya pentest report. Project ini dibangun sebagai portofolio untuk Kerja Praktik, menggabungkan pemahaman proses vulnerability management dengan pengembangan full-stack (React, Laravel, MySQL di Docker).

## Tech Stack & Tools

- React (Vite): antarmuka dashboard
- Tailwind CSS: styling
- Recharts: chart remediation rate, distribusi severity, dan breakdown status
- Laravel: REST API dan validasi input
- Laravel Sanctum: autentikasi berbasis token
- MySQL 8.0: penyimpanan data temuan
- Docker Compose: menjalankan MySQL dan phpMyAdmin
- phpMyAdmin: melihat dan memeriksa isi database
- laravel-dompdf: generate laporan PDF dari template Blade

## Struktur Folder

```
vuln-mgmt-dashboard/
├── backend/        # Laravel REST API
├── frontend/       # React app
├── docker/         # docker-compose.yml (MySQL + phpMyAdmin)
├── docs/
│   ├── screenshots/
│   │   ├── 00-setup/
│   │   ├── 01-backend/
│   │   └── 02-frontend/
│   └── notes/
├── writeups/
│   └── 01-setup-environment.md
│   └── 02-backend-laravel.md
│   └── 03-rest-api.md
│   └── 04-frontend-dashboard.md
│   └── 05-autentikasi.md
│   └── 06-remediation-chart-data-contoh.md
│   └── 07-export-pdf.md
│   └── 08-keamanan-dan-penutup.md
│   └── README.md
└── README.md
```

## Cara Menjalankan (Local Development)

Prasyarat: PHP 8.3+, Composer, Node.js 20+, npm, Docker dan Docker Compose.

1. Clone repo dan masuk ke foldernya.

```bash
git clone <url-repo>
cd <nama-folder-repo>
```

2. Jalankan MySQL dan phpMyAdmin (phpMyAdmin di `http://localhost:8081`).

```bash
docker compose -f docker/docker-compose.yml up -d
```

3. Setup dan jalankan backend.

```bash
cd backend
composer install
cp .env.example .env
```

Isi blok `DB_*` di `.env`: `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=vuln_mgmt`, `DB_USERNAME=vulnapp`, dan `DB_PASSWORD` sesuai `docker/docker-compose.yml`.

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed --class=DummyDataSeeder
php artisan serve
```

4. Setup dan jalankan frontend di terminal lain (`http://localhost:5173`).

```bash
cd frontend
npm install
npm run dev
```

5. Login dengan akun contoh hasil seeder (hanya untuk lokal).

| Role | Email | Password |
|---|---|---|
| Pentester | pentester@vulnmgmt.local | password123 |
| Manager | manager@vulnmgmt.local | password123 |
| Client | client@vulnmgmt.local | password123 |

## Progress

| Fase | Status |
|---|---|
| Setup & Environment | ✅ Selesai |
| Backend (Laravel) | 🔄 Berjalan (otorisasi per role di API belum diterapkan) |
| Frontend (React) | 🔄 Berjalan (chart trend, tampilan manager dan client belum dibuat) |
| Dokumentasi & Portofolio | 🔄 Berjalan |
| Kualitas & Deployment | ⬜ Belum |

**Progress keseluruhan: 76%** (35 dari 46 item)

## Dokumentasi

Writeup lengkap dengan screenshot per fase tersedia di [`WRITEUP.md`](WRITEUP.md). Semua screenshot ada di folder `docs/screenshots/`.

## Disclaimer

Project ini dibuat untuk tujuan pembelajaran dan portofolio. Kredensial database dan akun contoh hanya untuk lingkungan lokal dan tidak mewakili praktik yang aman untuk production. Saat ini role pengguna belum ditegakkan di sisi API (hanya memengaruhi tampilan), jadi aplikasi ini belum siap dipakai di lingkungan nyata tanpa perbaikan tersebut. Data temuan pada seeder adalah data fiktif.
