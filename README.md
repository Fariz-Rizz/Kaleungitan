# Kaleungitan - Sistem Barang Hilang & Temuan Kampus

Aplikasi web untuk mengelola pelaporan dan klaim barang hilang/temuan di lingkungan kampus. Mahasiswa dapat melaporkan barang yang hilang atau ditemukan, mengklaim barang temuan milik mereka, dan admin dapat memverifikasi laporan serta klaim yang masuk.

## Fitur Utama

- Autentikasi terpisah untuk User dan Admin, dengan role & status akun (aktif/nonaktif)
- Lapor barang hilang & lapor barang temuan, lengkap dengan foto, lokasi, dan kategori
- Klaim barang temuan dengan deskripsi bukti kepemilikan
- Verifikasi laporan & klaim oleh admin (approve/reject)
- Dashboard User: ringkasan laporan & klaim milik sendiri
- Dashboard Admin: statistik real-time, grafik laporan per bulan, kategori paling sering dilaporkan
- Notifikasi in-app untuk laporan baru, klaim baru, dan perubahan status
- Manajemen kategori, manajemen user, dan manajemen laporan/klaim (admin)
- Export data aktivitas ke PDF
- Pencarian, sort, filter, dan pagination pada daftar barang

## Tech Stack

- **Backend:** Laravel 13, PHP 8.3
- **Frontend:** Livewire 4, Alpine.js, Tailwind CSS 4
- **Database:** MySQL 8
- **Build Tool:** Vite

## Instalasi

```bash
git clone https://github.com/Fariz-Rizz/Kaleungitan.git
cd Kaleungitan

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Atur koneksi database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kaleungitan
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build

php artisan serve
```

Aplikasi dapat diakses di `http://127.0.0.1:8000`.

## Akun Default

Setelah proses seeding, gunakan akun berikut untuk keperluan testing dan demo:

| Email | Password | Role |
|---|---|---|
| admin@kaleungitan.test | password | admin |

Selain akun admin, seeder juga membuat 10 akun user dummy secara acak (lihat `database/seeders/UserSeeder.php`) untuk mengisi data laporan dan klaim.

## Screenshot

![Dashboard Kaleungitan](Images_public/Images/landing.png)
