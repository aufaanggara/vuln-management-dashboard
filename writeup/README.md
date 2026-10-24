# Writeup: Vulnerability Management Dashboard

**Tanggal:** Juli 2026
**Kategori:** Vulnerability Management, Web Application (Full-Stack)
**Peran di Portofolio:** Project 1 (flagship)
**Tools yang Digunakan:** React (Vite), Tailwind CSS, Recharts, Laravel, Sanctum, MySQL 8.0, Docker Compose, phpMyAdmin, laravel-dompdf, Ubuntu 24.04 di VirtualBox

---

## Ringkasan

Ini aplikasi web buat nyatet dan ngelacak temuan dari hasil pentest. Tiap temuan punya CVSS score, severity, dan status remediation. Datanya tampil di dashboard dan bisa diexport jadi laporan PDF yang formatnya mirip pentest report.

Aku bikin ini buat nyatuin dua hal yang udah aku pelajari: sisi pentest (cara nulis temuan, CVSS, prioritas) dan web development. Hasilnya jadi project utama di portofolio KP-ku.

Soal pengerjaan: kode Laravel dan React aku tulis dengan bantuan AI (Claude). Tiap langkah aku jalanin dan tes sendiri, dan keputusan desain kayak struktur data dan cakupan fitur aku yang tentuin. Bagian Docker dan jaringan aku kerjain langsung dan itu yang paling aku pahami.

## Daftar Fase

| No | Fase | Isi |
|---|---|---|
| 1 | [Setup Environment](01-setup-environment.md) | Docker, MySQL, phpMyAdmin, port forwarding VirtualBox |
| 2 | [Backend Laravel](02-backend-laravel.md) | PHP, Composer, Laravel, koneksi MySQL, skema data temuan |
| 3 | [REST API](03-rest-api.md) | Sanctum, controller CRUD, tes pakai curl |
| 4 | [Frontend dan Dashboard](04-frontend-dashboard.md) | Vite, React, Tailwind, CORS, tabel temuan |
| 5 | [Autentikasi](05-autentikasi.md) | Login token, role, halaman login |
| 6 | [Remediation, Chart, dan Data Contoh](06-remediation-chart-data-contoh.md) | Update status, chart, hapus temuan, seeder |
| 7 | [Export PDF](07-export-pdf.md) | Laporan PDF dari dashboard |
| 8 | [Keamanan dan Penutup](08-keamanan-dan-penutup.md) | Catatan keamanan, alur demo, pelajaran, langkah selanjutnya |

## Hasil Utama

- 10 endpoint API, 9 template kategori vulnerability, 3 role (pentester, manager, client)
- Dashboard dengan remediation rate, distribusi severity, dan breakdown status
- Export laporan PDF langsung dari tombol di dashboard
- MySQL dan phpMyAdmin jalan lewat satu perintah `docker compose up`
- 60 screenshot bernomor yang nunjukin tiap langkah

## Yang Belum Selesai

- Role belum ditegakkan di sisi API. Role baru dipakai buat nyembunyiin tombol di UI.
- Chart trend over time belum dibuat.
- Manager dan client belum punya tampilan sendiri.
- Belum ada tes otomatis dan belum di-deploy.

Detailnya ada di [fase 8](08-keamanan-dan-penutup.md).
