# Project Certificate

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

Project Certificate adalah aplikasi berbasis **Laravel 11** untuk manajemen siswa, guru, dan pembuatan sertifikat atau dokumen terkait pembelajaran. Aplikasi ini cocok digunakan di sekolah, lembaga pendidikan, atau organisasi yang membutuhkan sistem pencatatan digital siswa dan guru.

---

## Fitur Utama

- **Manajemen Siswa**
  - Tambah, edit, hapus, dan lihat data siswa
  - Pencatatan nilai: Kelancaran, Fasohah, Tajwid, Total Kesalahan, Nilai, Predikat
  - Filter dan pencarian siswa berdasarkan nama dan Juz
  - Upload CSV untuk import data siswa
  - Generate PDF per siswa

- **Manajemen Guru**
  - Tambah, edit, hapus guru
  - Upload foto guru
  - Status online/offline dengan last seen otomatis
  - Role-based access control (Admin & Teacher)

- **Dashboard**
  - Tampilan ringkas statistik siswa dan guru
  - Role-based view sesuai akses pengguna

- **Keamanan**
  - Autentikasi login
  - Proteksi multi-login (akun yang sama tidak bisa login di perangkat lain)
  - Middleware untuk update `last_seen` guru secara otomatis

---

## Teknologi

- **Backend:** Laravel 11, PHP 8.2
- **Database:** MySQL / MariaDB
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Export PDF:** DomPDF / Laravel Snappy (sesuai implementasi)
- **Version Control:** Git + GitHub

---

## Instalasi

1. Clone repository ini

```bash
git clone https://github.com/sayurtempee/project-certificate.git
cd project-certificate
