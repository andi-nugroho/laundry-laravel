# 14 - UI Dashboard Sistem Laundry

## Tujuan

Membuat dashboard utama untuk admin/owner/kasir.

Dashboard digunakan untuk menampilkan ringkasan data sistem laundry.

## Role Dashboard

### Admin / Owner

Menampilkan:

- Total booking
- Total transaksi
- Total pendapatan
- Laundry dalam proses
- Laundry selesai
- Grafik pendapatan
- Laporan terbaru

### Kasir

Menampilkan:

- Booking masuk hari ini
- Laundry sedang diproses
- Transaksi hari ini
- Cucian siap diambil
- Riwayat transaksi terbaru

### User Biasa

Menampilkan:

- Booking aktif
- Status cucian
- Riwayat booking
- Estimasi selesai

## Struktur Dashboard

Dashboard terdiri dari:

1. Sidebar
2. Topbar
3. Statistic Cards
4. Recent Booking Table
5. Laundry Status Overview
6. Revenue Chart
7. Quick Action

## Sidebar Menu Admin

```text
Dashboard
Master Data
- Layanan Laundry
- Data User
- Data Pelanggan

Booking Laundry
Monitoring Laundry
Transaksi
Laporan
Pengaturan
```

## Sidebar Menu Kasir

```text
Dashboard
Booking Laundry
Data Pelanggan
Monitoring Laundry
Transaksi
Riwayat Transaksi
```

## Sidebar Menu User

```text
Dashboard
Booking Laundry
Status Cucian
Riwayat Transaksi
Profil
```

## Statistic Card

Card yang ditampilkan:

```text
Total Booking
Total Pendapatan
Laundry Diproses
Laundry Selesai
```

## Contoh Query Dashboard

```php
$totalBooking = Booking::count();

$totalPendapatan = Transaction::where('payment_status', 'lunas')
    ->sum('total');

$totalProses = Booking::whereIn('status', [
    'diterima',
    'dicuci',
    'dikeringkan',
    'disetrika'
])->count();

$totalSelesai = Booking::where('status', 'selesai')->count();
```

## Komponen React yang Cocok

React tidak wajib dipakai untuk semua halaman.

Gunakan React untuk bagian:

- Grafik pendapatan
- Animated statistic card
- Status tracking timeline
- Modal detail booking
- Interactive table

## Struktur File React

```text
resources/js/dashboard.jsx
resources/js/components/StatCard.jsx
resources/js/components/RevenueChart.jsx
resources/js/components/LaundryStatusChart.jsx
resources/js/components/RecentBookings.jsx
```

## Struktur File Blade

```text
resources/views/dashboard/index.blade.php
resources/views/layouts/app.blade.php
resources/views/components/sidebar.blade.php
resources/views/components/topbar.blade.php
```

## Mount React di Blade

```blade
<div id="dashboard-stats"></div>
<div id="revenue-chart"></div>

@viteReactRefresh
@vite('resources/js/dashboard.jsx')
```

## Contoh Desain Dashboard

Style:

- Card rounded-xl / rounded-2xl
- Shadow soft
- Background putih
- Sidebar gelap atau soft slate
- Font clean
- Icon minimalis
- Responsive grid

## Layout Grid

```text
Desktop:
4 statistic card dalam 1 baris

Tablet:
2 card per baris

Mobile:
1 card per baris
```

## Prioritas Implementasi

Tahap 1:

- Sidebar
- Topbar
- Statistic card
- Tabel booking terbaru

Tahap 2:

- Grafik pendapatan
- Status chart
- React animation

Tahap 3:

- Responsive polish
- Smooth transition
- Dark mode opsional
