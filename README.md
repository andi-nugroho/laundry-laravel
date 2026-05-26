<div align="center">
  <img src="public/logo.svg" alt="VAULTLAUNDRY Logo" width="86" height="86" />

# VAULTLAUNDRY

Modern laundry operation system built with Laravel, Breeze, TailwindCSS, PostgreSQL, and a warm orange premium interface.
<br />
Mengelola layanan laundry, pelanggan, booking, monitoring status, pembayaran, invoice PDF, dashboard statistik, dan laporan pendapatan dalam satu aplikasi role-based.

</div>

## Why VAULTLAUNDRY

- **Premium Laundry Dashboard**: Cream/off-white interface dengan aksen orange `#FF6626`, sidebar collapsible, card/table toggle, dan page loading bar.
- **Role-Based Workflow**: Admin, kasir, dan user memiliki dashboard, menu, dan batas akses yang berbeda.
- **Core Laundry Operations**: Booking laundry, monitoring status cucian, customer profile, dan transaksi pembayaran terhubung dalam satu alur.
- **Receipt-Ready PDF Invoice**: Nota pembayaran compact bergaya struk retail dengan logo VAULTLAUNDRY.
- **Real Data Reports**: Dashboard statistik, laporan transaksi, dan laporan pendapatan memakai data database asli.

## Tech Stack

- **Framework**: Laravel 12
- **Auth**: Laravel Breeze
- **Styling**: TailwindCSS + Blade Components + Alpine.js
- **Database**: PostgreSQL
- **PDF**: barryvdh/laravel-dompdf
- **Runtime**: Laravel Sail / Docker Compose

## Core Features

- Autentikasi, email verification, profile.
- Role pengguna: `admin`, `kasir`, `user`.
- Dashboard real-data untuk admin, kasir, dan user.
- CRUD layanan laundry untuk admin.
- CRUD customer dengan authorization per role.
- Booking laundry dengan kode otomatis `LDY-YYYY-0001`.
- Perhitungan estimasi selesai dan total harga dari berat x harga layanan.
- Monitoring status laundry dengan timeline.
- Menu user aktif:
  - `/user/status-cucian`
  - `/user/riwayat`
- Transaksi pembayaran dengan kode otomatis `PAY-YYYY-0001`.
- Invoice PDF compact receipt.
- Laporan transaksi dan pendapatan.

## Role Access

Admin:
- Mengelola layanan, customer, booking, monitoring, payment, invoice, dan semua laporan.

Kasir:
- Mengelola customer, booking, monitoring, payment, invoice, dan laporan transaksi.

User:
- Membuat booking untuk dirinya sendiri.
- Melihat booking, status cucian, riwayat booking, payment, dan invoice miliknya.
- Mengubah booking miliknya selama status masih `booking_masuk`.
- Tidak dapat mengubah status cucian, menghapus booking, atau mengelola payment.

## Status Laundry

```text
booking_masuk
diterima
dicuci
dikeringkan
disetrika
selesai
diambil
dibatalkan
```

Admin dan kasir dapat mengubah status melalui UI atau endpoint:

```bash
PATCH /bookings/{booking}/status
```

## Local Development

### Start containers

```bash
docker compose up -d
```

### Install dependencies

```bash
composer install
npm install
```

### Migrate and seed

```bash
docker compose exec laravel.test php artisan migrate --seed
```

### Build frontend

```bash
npm run build
```

Open:

```text
http://localhost
```

## Seeder Accounts

```text
Admin : admin@laundry.test / password
Kasir : kasir@laundry.test / password
User  : user@laundry.test / password
```

## Useful Commands

```bash
docker compose exec laravel.test php artisan route:list
docker compose exec laravel.test php artisan test
docker compose exec laravel.test ./vendor/bin/pint
```

## Main Routes

- `/` landing page
- `/dashboard` role redirect
- `/admin/dashboard`
- `/kasir/dashboard`
- `/user/dashboard`
- `/services`
- `/customers`
- `/bookings`
- `/monitoring`
- `/payments`
- `/payments/{payment}/invoice`
- `/reports/transactions`
- `/reports/revenue`
- `/user/status-cucian`
- `/user/riwayat`

## UI System

- Brand: **VAULTLAUNDRY**
- Primary: `#FF6626`
- Background: `#FAF4EA`
- Card: `#FFF9F1`
- Border: `#E8DCCB`
- Fonts: DM Sans / Instrument Sans / Instrument Serif / JetBrains Mono
- Dashboard shell: fixed collapsible sidebar, sticky topbar, responsive mobile drawer.
- Index pages: reusable premium panel with `Table` and `Card` modes.

## License

MIT © 2026 VAULTLAUNDRY.
