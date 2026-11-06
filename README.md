# REST API Perpustakaan

Implementasi praktikum Pemrograman Berbasis Objek (PBO) menggunakan PHP, MySQL, REST API, dan frontend JavaScript native.

## Identitas

- Nama: Rasya Akmal Sakhi
- NIM: H1101251041
- Kelas: A
- Program Studi: Sistem Informasi
- Mata Kuliah: Pemrograman Berbasis Objek (PBO)
- Universitas: Universitas Tanjungpura

## Deskripsi Proyek

Proyek ini menyediakan backend REST API Perpustakaan dengan PHP native, PDO, MySQL, Repository Pattern, Service, Controller, Router, dan Dependency Injection. Frontend terpisah menggunakan HTML, Tailwind CSS melalui CDN, dan JavaScript native. Komunikasi frontend-backend dilakukan dengan Fetch API.

Fitur yang tersedia di source code mencakup pengelolaan buku dan kategori, dashboard, halaman buku, halaman kategori, serta konfigurasi CORS pada backend. Bukti pengujian UI browser yang belum dilakukan secara manual tidak dianggap sebagai verifikasi end-to-end.

## Fitur

### Backend

- REST API buku.
- REST API kategori.
- Operasi CRUD buku dan kategori.
- Validasi input dan error handling.
- PDO dan MySQL.
- Composer PSR-4 autoloading.
- Repository Pattern.
- Dependency Injection.
- CORS.

### Frontend

- Dashboard statistik buku dan kategori.
- Halaman Buku dengan tabel dan modal tambah buku.
- Halaman Kategori dengan tabel dan modal CRUD kategori.
- Hash router untuk Dashboard, Buku, dan Kategori.
- Integrasi API menggunakan Fetch API.
- Utility `escapeHtml()` dan notifikasi toast.

## Teknologi

- PHP 8.4.12
- Composer 2.10.3
- MySQL 8.4.3
- PDO
- Composer PSR-4
- HTML5
- CSS
- Tailwind CSS CDN
- JavaScript native
- Fetch API

## Struktur Repository

```text
.
├── frontend-perpustakaan/
│   ├── index.html
│   ├── css/custom.css
│   └── js/
│       ├── config.js
│       ├── api.js
│       ├── utils.js
│       ├── app.js
│       └── pages/
│           ├── dashboard.js
│           ├── books.js
│           └── categories.js
├── rest-api-perpustakaan/
│   ├── database/schema.sql
│   ├── public/index.php
│   ├── src/
│   │   ├── Config/
│   │   ├── Contracts/
│   │   ├── Controllers/
│   │   ├── Core/
│   │   ├── Database/
│   │   ├── Models/
│   │   ├── Repositories/
│   │   └── Services/
│   ├── tests/api_test.sh
│   ├── composer.json
│   └── .env.example
├── pertemuan-01/ sampai pertemuan-07/
└── screenshot-final/
```

## Cara Menjalankan Proyek

Pastikan MySQL aktif dan konfigurasi `.env` backend sudah disiapkan. Jangan membagikan isi `.env` atau kredensial database.

### Backend — Terminal 1

```powershell
cd rest-api-perpustakaan
php -S localhost:8000 -t public
```

### Frontend — Terminal 2

```powershell
cd frontend-perpustakaan
php -S localhost:3000
```

Buka frontend melalui:

```text
http://localhost:3000
```

## Dokumentasi Praktikum

Link berikut dibuat berdasarkan file screenshot yang benar-benar tersedia di repository.

### Pertemuan 1

- [Langkah 1.1](pertemuan-01/Langkah-1.1.png)
- [Langkah 1.2](pertemuan-01/Langkah-1.2.png)
- [Langkah 2](pertemuan-01/Langkah-2.png)
- [Langkah 3](pertemuan-01/Langkah-3.png)
- [Langkah 4](pertemuan-01/Langkah-4.png)
- [Langkah 5](pertemuan-01/Langkah-5.png)

### Pertemuan 2

- [Langkah 6](pertemuan-02/Langkah-6.png)
- [Langkah 7](pertemuan-02/Langkah-7.png)
- [Langkah 8](pertemuan-02/Langkah-8.png)
- [Langkah 9.1](pertemuan-02/Langkah-9.1.png)
- [Langkah 9.2](pertemuan-02/Langkah-9.2.png)
- [Langkah 9.3](pertemuan-02/Langkah-9.3.png)
- [Langkah 9.4](pertemuan-02/Langkah-9.4.png)
- [Langkah 9.5](pertemuan-02/Langkah-9.5.png)

### Pertemuan 3

- [Langkah 10.1](pertemuan-03/langkah-10.1.png)
- [Langkah 10.2](pertemuan-03/langkah-10.2.png)
- [Langkah 10.3](pertemuan-03/langkah-10.3.png)
- [Langkah 11](pertemuan-03/langkah-11.png)

### Pertemuan 4

- [Langkah 12.1](pertemuan-04/Langkah-12.1.png)
- [Langkah 12.2](pertemuan-04/Langkah-12.2.png)

### Pertemuan 5

- [Langkah 13](pertemuan-05/Langkah-13.png)
- [Langkah 14](pertemuan-05/Langkah-14.png)
- [Langkah 15.1](pertemuan-05/Langkah-15.1.png)
- [Langkah 15.2](pertemuan-05/Langkah-15.2.png)

### Pertemuan 6

- [Langkah 16](pertemuan-06/Langkah-16.png)
- [Langkah 17](pertemuan-06/Langkah-17.png)
- [Langkah 18.1](pertemuan-06/Langkah-18.1.png)
- [Langkah 18.2](pertemuan-06/Langkah-18.2.png)

### Pertemuan 7

- [Langkah 19](pertemuan-07/Langkah-19.png)

### Screenshot Final

Belum tersedia. Screenshot asli harus diambil manual dari browser setelah verifikasi:

- `screenshot-final/dashboard.png` — Belum tersedia.
- `screenshot-final/halaman-buku.png` — Belum tersedia.
- `screenshot-final/halaman-kategori.png` — Belum tersedia.

## Status Verifikasi Langkah 17–19

- Langkah 17: file frontend, server `localhost:3000`, dan HTTP response telah diperiksa. Verifikasi tampilan browser manual masih diperlukan.
- Langkah 18: syntax JavaScript, server, dan endpoint API telah diperiksa. Pengujian submit buku melalui browser belum dilakukan.
- Langkah 19: halaman kategori, router, syntax, server, dan GET API telah diperiksa. Pengujian CRUD kategori, toast, dan pembaruan dashboard melalui browser belum dilakukan.

Screenshot yang tersedia menjadi dokumentasi file, tetapi tidak menggantikan pemeriksaan manual yang belum dilakukan.
