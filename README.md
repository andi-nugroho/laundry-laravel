# Laundry Laravel

Aplikasi manajemen laundry berbasis Laravel 12, Breeze, TailwindCSS, dan PostgreSQL. Project ini dibuat bertahap untuk mengelola layanan laundry, data pelanggan, booking laundry, dan monitoring status proses laundry.

## Fitur Utama

- Autentikasi Laravel Breeze.
- Role pengguna: `admin`, `kasir`, dan `user`.
- Dashboard terpisah untuk setiap role.
- CRUD layanan laundry untuk admin.
- CRUD customer dengan akses berbasis role.
- Booking laundry dengan kode otomatis format `LDY-YYYY-0001`.
- Perhitungan otomatis estimasi selesai dan total harga booking.
- Monitoring status laundry dengan badge dan timeline progress.

## Role dan Akses

Admin:
- Mengakses dashboard admin.
- Mengelola layanan laundry.
- Mengelola semua customer.
- Mengelola semua booking.
- Mengubah status semua booking.

Kasir:
- Mengakses dashboard kasir.
- Mengelola semua customer.
- Mengelola semua booking.
- Mengubah status semua booking.

User:
- Mengakses dashboard pelanggan.
- Melihat dan mengubah data customer miliknya sendiri.
- Membuat booking untuk dirinya sendiri.
- Melihat booking dan status miliknya sendiri.
- Mengubah booking miliknya selama status masih `booking_masuk`.
- Tidak bisa menghapus booking atau mengubah status.

## Status Laundry

Status booking yang digunakan:

- `booking_masuk`
- `diterima`
- `dicuci`
- `dikeringkan`
- `disetrika`
- `selesai`
- `diambil`
- `dibatalkan`

Admin dan kasir dapat mengubah status melalui halaman booking, detail booking, edit booking, atau route:

```bash
PATCH /bookings/{booking}/status
```

## Struktur Data Inti

Services:
- Data layanan laundry.
- Harga per kg dan estimasi hari digunakan untuk menghitung booking.

Customers:
- Data pelanggan.
- Bisa terhubung ke akun user melalui `user_id`.

Bookings:
- Terhubung ke `users`, `customers`, dan `services`.
- `booking_code` dibuat otomatis.
- `estimated_finish_date` dihitung dari `booking_date + estimated_days service`.
- `total_price` dihitung dari `weight x service.price_per_kg`.

## Akun Seeder

Seeder membuat akun contoh:

```text
Admin : admin@laundry.test / password
Kasir : kasir@laundry.test / password
User  : user@laundry.test / password
```

Seeder juga membuat contoh layanan, pelanggan, dan booking laundry.

## Menjalankan Project

Project ini menggunakan Laravel Sail dan PostgreSQL.

```bash
docker compose up -d
docker compose exec laravel.test php artisan migrate --seed
docker compose exec laravel.test npm run build
```

Buka aplikasi:

```text
http://localhost
```

## Command Berguna

Menjalankan migration dan seeder:

```bash
docker compose exec laravel.test php artisan migrate --seed
```

Melihat route booking:

```bash
docker compose exec laravel.test php artisan route:list --path=bookings
```

Menjalankan test:

```bash
docker compose exec laravel.test php artisan test
```

Merapikan format kode:

```bash
docker compose exec laravel.test ./vendor/bin/pint
```

## Modul yang Sudah Dibangun

Auth dan dashboard:
- Login, register, logout, profile.
- Redirect dashboard berdasarkan role.

Services:
- CRUD layanan laundry.
- Hanya admin yang bisa mengelola.

Customers:
- CRUD pelanggan.
- Admin/kasir mengelola semua pelanggan.
- User hanya melihat dan mengubah customer miliknya sendiri.

Bookings:
- CRUD booking laundry.
- Admin/kasir mengelola semua booking.
- User mengelola booking miliknya sendiri dengan batasan status.

Monitoring:
- Update status booking untuk admin/kasir.
- User hanya melihat status booking miliknya.
- Timeline progress status pada detail booking.
