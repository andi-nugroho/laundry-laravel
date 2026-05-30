# VAULTLAUNDRY Database Notes

Dokumen ini merangkum relasi database aplikasi saat ini. Struktur detail ada di `schema.sql`, sedangkan sumber kebenaran tetap Laravel migrations di `database/migrations`.

## ERD Text Summary

```text
Users
 |-- Customers
 |-- Bookings
 |   |-- Services
 |   `-- Payments
 `-- Payments (processed_by)
```

## Relasi Utama

- `users.id` memiliki banyak `bookings.user_id`.
- `users.id` memiliki satu/banyak `customers.user_id`.
- `users.id` memproses banyak `payments.processed_by`.
- `customers.id` memiliki banyak `bookings.customer_id`.
- `services.id` memiliki banyak `bookings.service_id`.
- `bookings.id` memiliki satu `payments.booking_id`.

## Catatan Invoice dan Report

- Invoice tidak memiliki tabel fisik. Nota PDF dibuat dari data `payments`, `bookings`, `customers`, `services`, dan `users` sebagai kasir atau pemroses.
- Report tidak memiliki tabel fisik. Laporan transaksi dan pendapatan dihitung langsung dari query `payments` beserta relasi booking/customer/service.
