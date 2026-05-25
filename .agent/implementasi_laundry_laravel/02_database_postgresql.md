# 02 - Setup Database PostgreSQL

## File .env

Pastikan konfigurasi database seperti ini:

```env
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laundry_app
DB_USERNAME=sail
DB_PASSWORD=password
```

## Jalankan Migration

```bash
./vendor/bin/sail artisan migrate
```

## Tabel Utama

- users
- customers
- services
- bookings
- transactions
- transaction_details
- payments
- laundry_statuses
- pickup_orders

## Catatan

PostgreSQL berjalan di dalam Docker container melalui Laravel Sail.
Migration Laravel akan membuat struktur tabel ke database PostgreSQL.
