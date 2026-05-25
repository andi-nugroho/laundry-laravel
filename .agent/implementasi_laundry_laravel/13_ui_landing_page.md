# 13 - UI Landing Page Laundry

## Tujuan

Membuat halaman landing page untuk sistem booking dan monitoring laundry.

Landing page digunakan sebagai halaman awal sebelum user login/register.

## Konsep Landing Page

Tema desain:

- Modern
- Clean
- Responsive
- Soft rounded card
- Smooth animation
- Laundry service vibes
- Profesional untuk sistem berbasis web

## Struktur Halaman

Landing page terdiri dari beberapa section:

1. Navbar
2. Hero Section
3. Fitur Utama
4. Alur Booking Laundry
5. Layanan Laundry
6. Keunggulan Sistem
7. CTA Register/Login
8. Footer

## Navbar

Isi navbar:

- Logo/Nama aplikasi
- Home
- Layanan
- Cara Kerja
- Login
- Register

Contoh nama aplikasi:

```text
LaundryGo
Smart Laundry
CleanTrack
LaundryCare
```

## Hero Section

Konten utama:

```text
Booking Laundry Lebih Mudah dan Praktis

Kelola cucian Anda secara online, pantau status laundry secara real-time, dan nikmati layanan laundry yang cepat, rapi, dan terpercaya.
```

Button:

- Booking Sekarang
- Login

## Fitur Utama

Card fitur:

1. Booking Online
2. Monitoring Status Cucian
3. Riwayat Transaksi
4. Estimasi Selesai
5. Cetak Nota
6. Dashboard Statistik

## Alur Booking

Alur:

1. User melakukan booking
2. Laundry diterima oleh kasir
3. Cucian diproses
4. Status diperbarui
5. Cucian selesai
6. User mengambil laundry

## Layanan Laundry

Contoh layanan:

- Cuci Kering
- Cuci Setrika
- Setrika Saja
- Laundry Express
- Laundry Sepatu
- Laundry Bedcover

## Komponen UI

Gunakan:

- Blade Template Laravel
- TailwindCSS
- React Component via Vite untuk animasi tertentu
- Framer Motion untuk animated card
- Lenis untuk smooth scroll
- Lucide Icons untuk icon

## Struktur File

```text
resources/views/landing.blade.php
resources/js/components/LandingHero.jsx
resources/js/components/FeatureCards.jsx
resources/js/components/ProcessSteps.jsx
resources/js/landing.jsx
```

## Route

```php
Route::get('/', function () {
    return view('landing');
})->name('landing');
```

## Ide Warna

Gunakan warna yang soft dan profesional:

```text
Primary: Blue / Cyan
Secondary: Slate / Gray
Accent: Emerald / Green
Background: White / Soft Gray
```

## Elemen Animasi

Animasi yang cocok:

- Hero fade in
- Card hover animation
- Process step slide up
- Smooth scroll dengan Lenis
- CTA button hover effect

## Prioritas Implementasi

Tahap awal cukup buat:

1. Navbar
2. Hero section
3. Fitur utama
4. CTA login/register

Setelah sistem inti selesai, baru tambahkan animasi.
