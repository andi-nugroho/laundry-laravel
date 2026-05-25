# 09 - Dashboard Statistik

## Tujuan

Membuat dashboard untuk admin/owner.

## Data yang Ditampilkan

- Total booking
- Total transaksi
- Total pendapatan
- Laundry sedang diproses
- Laundry selesai
- Laundry belum dibayar

## Controller

```bash
./vendor/bin/sail artisan make:controller DashboardController
```

## Query Contoh

```php
$totalBooking = Booking::count();
$totalPendapatan = Transaction::where('payment_status', 'lunas')->sum('total');
$totalProses = Booking::whereIn('status', ['diterima', 'dicuci', 'dikeringkan', 'disetrika'])->count();
$totalSelesai = Booking::where('status', 'selesai')->count();
```

## View

```text
resources/views/dashboard.blade.php
```

## UI

Gunakan card statistik:
- Total Booking
- Pendapatan
- Proses
- Selesai
