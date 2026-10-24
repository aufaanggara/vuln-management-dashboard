# Fase 8: Keamanan dan Penutup

[← Fase 7: Export PDF](07-export-pdf.md) | [README](README.md)

---

## Catatan Keamanan

Ini keputusan keamanan di project ini, termasuk celah yang masih terbuka.

- **Database pakai user terbatas.** Aplikasi konek lewat `vulnapp` yang cuma punya akses ke database `vuln_mgmt`, bukan `root`. Kalau kredensial aplikasi bocor, isi server MySQL yang lain tetap aman.
- **Autentikasi dijaga di server.** Semua endpoint data ada di balik `auth:sanctum`. Aku tes dengan dan tanpa token, dan tanpa token hasilnya `Unauthenticated`. Menyembunyikan tombol di UI ga aku anggap sebagai kontrol akses.
- **Role belum ditegakkan di API.** Ini celah terbesar. Role disimpan dan dikirim balik, dan tombol "Tambah Temuan" disembunyikan buat non-pentester, tapi API belum ngecek role di endpoint tulis. User dengan token `client` masih bisa `POST`, `PUT`, atau `DELETE` langsung. Perbaikannya lewat Laravel policy atau middleware role, dan itu jadi prioritas pertama di langkah selanjutnya.
- **Validasi di server.** `severity` dan `status` dibatasi `Rule::in()`, foreign key dicek pakai `exists`, dan model pakai daftar `$fillable`. API ga bergantung ke dropdown frontend.
- **CORS pakai allowlist.** Cuma origin React dev server yang diizinkan. Wildcard paling berbahaya kalau dipakai bareng cookie. Project ini pakai bearer token jadi risikonya lebih kecil, tapi allowlist tetap pilihan default yang aman.
- **Token di `localStorage`.** Praktis karena bertahan pas refresh, tapi bisa dibaca script mana pun di halaman, jadi celah XSS bakal bocorin token. Cookie httpOnly lewat mode SPA Sanctum bakal ngurangin risiko ini.
- **Grup `docker` setara root.** Aku terima di VM pribadi, tapi bukan pola yang aku pakai di server bersama.
- **Kredensial dan port buat development doang.** Akun seeder pakai password demo yang lemah, dan MySQL dibuka di `0.0.0.0:3306`. Dua-duanya cukup buat VM lokal, tapi harus diganti (secret yang kuat dan bind ke loopback) sebelum dipakai di tempat lain.

## Alur Demo

1. Jalanin MySQL dan phpMyAdmin pakai `docker compose -f docker/docker-compose.yml up -d`.
2. Jalanin backend (`php artisan serve`) dan frontend (`npm run dev`).
3. Login di `http://localhost:5173` pakai akun hasil seeder.
4. Liat dashboard: chart ringkasan dan tabel temuan.
5. Sebagai pentester, tambah temuan baru. Field detail teknisnya ganti sesuai kategori.
6. Buka satu temuan, ubah status remediation, simpan.
7. Klik Export PDF buat download laporan yang isinya sesuai kondisi data terbaru.

## Pelajaran yang Didapat

- Masalah jaringan sering kelihatan kayak masalah Docker. Ngecek dari dalam VM dulu, baru dari host, bikin aku tau layer mana yang bermasalah.
- Pola kolom inti plus JSON cocok buat data yang intinya stabil tapi detailnya beda-beda per kategori.
- Autentikasi dan otorisasi itu dua hal yang beda. Nolak request tanpa token gampang dites. Ngatur apa yang boleh dilakukan tiap role di server itu kerjaan terpisah, dan di project ini baru setengah jalan.
- Setting kayak CORS lebih masuk akal kalau tau ancamannya. Aku jadi paham kapan wildcard bahaya dan kapan risikonya kecil.
- Satu setup bisa punya banyak layer jaringan sekaligus (host ke VM, VM ke container), dan masing-masing harus dipikirin sendiri-sendiri.
- Aku masih belum paham semua kode Laravel dan React yang kepake. Yang paling aku kuasai dari project ini Docker, jaringan, dan alur request sampai ke database. Sisanya masih perlu aku pelajari.

## Langkah Selanjutnya

- Tegakkan role di API (policy atau middleware) dan tambah tes bahwa token `client` ga bisa nulis.
- Bikin chart trend over time sesuai spesifikasi awal.
- Bikin tampilan terpisah buat manager dan client.
- Tampilin filter `severity` dan `status` di tabel, karena API-nya udah mendukung.
- Tambah feature test buat auth dan CRUD.
- Pindahin token ke cookie httpOnly, dan bind MySQL ke loopback.
- Deploy supaya bisa diakses publik. Rencananya Railway buat backend dan database, Vercel buat frontend.

## Catatan Data yang Disensor

Kredensial yang sempat tampil jelas selama pengerjaan (`MYSQL_ROOT_PASSWORD`, `MYSQL_PASSWORD`, `PMA_PASSWORD`, `DB_PASSWORD`, `APP_KEY`) aku ganti jadi `<REDACTED>` di tulisan ini. Akun dan data contoh seperti `pentester@vulnmgmt.local` sengaja ga disensor karena dibuat khusus buat tes lokal. Screenshot terminal dan config bisa aja masih nampilin nilai asli, jadi cek tiap gambar dulu sebelum repo dipublikasikan.

---

[← Fase 7: Export PDF](07-export-pdf.md) | [README](README.md)
