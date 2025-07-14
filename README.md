# 📘 SITA-BI (Sistem Informasi Tugas Akhir Bahasa Inggris)

**SITA-BI** adalah sistem informasi berbasis web yang dirancang untuk mempermudah pengelolaan proses tugas akhir di Jurusan Bahasa Inggris, mulai dari pengajuan judul, proses bimbingan, hingga pelaksanaan sidang. Sistem ini mendukung transparansi, efisiensi, dan aksesibilitas bagi mahasiswa, dosen pembimbing/penguji, dan kaprodi.

## 👥 Tim Pengembang
- **Nabil Achmad** – 2311082032 (PM)
- **Erland Agsya** – 2311083007
- **Gilang Dwi Y** – 2311081016
- **Kasih Ananda** – 2311081021

---

## 📌 Fitur Utama
- ✅ Pengajuan dan persetujuan judul tugas akhir
- 📄 Upload dokumen proposal dan laporan akhir
- ✍️ Log bimbingan dan komentar dosen pembimbing
- 📅 Pendaftaran dan penjadwalan sidang akhir
- 🧾 Penilaian hasil sidang oleh dosen penguji
- 📊 Dashboard monitoring untuk Kaprodi/Admin

---

## 💡 Latar Belakang
Sebelum adanya SITA-BI, pengelolaan tugas akhir di Jurusan Bahasa Inggris dilakukan secara manual, yang sering menyebabkan keterlambatan, miskomunikasi, dan kurangnya transparansi. Sistem ini dibangun untuk mendigitalisasi proses tersebut agar lebih terstruktur dan mudah diakses oleh seluruh pihak terkait.

---

## 🛠️ Teknologi yang Digunakan
- **Laravel 11** – Backend Framework
- **MySQL** – Database Management
- **HTML, CSS, JavaScript** – Frontend
- **Blade Template Engine** – Laravel View
- **Spatie Laravel-Permission** – Manajemen hak akses
- **Git & GitHub** – Version Control
- **XAMPP / Laravel Valet** – Local Development

---

## 🚀 Cara Menjalankan Proyek Secara Lokal
Clone repositori:
```bash
git clone https://github.com/NabilAchmad/SITA-BI.git
cd SITA-BI
Install semua dependencies Laravel:
```
```bash
composer install
Install package permission dari Spatie (jika belum):
```

```bash
composer require spatie/laravel-permission
Salin file konfigurasi .env:
```

```bash
cp .env.example .env
Generate application key:
```

```bash
php artisan key:generate
Switch ke branch admin_mhs:
```

```bash
git switch admin_mhs
Migrasi database:
```

```bash
php artisan migrate
Seed database awal (roles, permissions, admin):
```
```bash
php artisan db:seed --class=RolesSeeder
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=AdminSeeder
```
