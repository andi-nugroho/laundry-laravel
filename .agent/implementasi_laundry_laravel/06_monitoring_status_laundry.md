# 06 - Monitoring Status Laundry

## Tujuan

Menampilkan dan mengubah status cucian.

## Status Laundry

Urutan status:

1. booking_masuk
2. diterima
3. dicuci
4. dikeringkan
5. disetrika
6. selesai
7. diambil

## Fitur

Admin/Kasir:
- Melihat semua booking
- Mengubah status cucian
- Melihat detail pelanggan
- Melihat detail layanan

User:
- Melihat status cucian miliknya sendiri
- Melihat estimasi selesai

## Route Contoh

```php
Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])
    ->name('bookings.updateStatus');
```

## Controller Logic

```php
public function updateStatus(Request $request, Booking $booking)
{
    $request->validate([
        'status' => 'required|in:booking_masuk,diterima,dicuci,dikeringkan,disetrika,selesai,diambil,dibatalkan',
    ]);

    $booking->update([
        'status' => $request->status,
    ]);

    return back()->with('success', 'Status laundry berhasil diperbarui.');
}
```

## UI Monitoring

Gunakan badge warna:
- Abu-abu: booking_masuk
- Biru: diterima
- Kuning: dicuci
- Oranye: dikeringkan
- Ungu: disetrika
- Hijau: selesai
- Hitam: diambil
