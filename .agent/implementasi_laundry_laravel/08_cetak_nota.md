# 08 - Cetak Nota

## Tujuan

Membuat halaman nota transaksi yang bisa dicetak.

## Informasi Nota

- Nama laundry
- Nomor invoice
- Tanggal transaksi
- Nama pelanggan
- Layanan laundry
- Berat laundry
- Harga per kg
- Total pembayaran
- Status pembayaran
- Estimasi selesai

## Route

```php
Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])
    ->name('transactions.invoice');
```

## Controller

```php
public function invoice(Transaction $transaction)
{
    $transaction->load('booking.customer', 'booking.service', 'payment');

    return view('transactions.invoice', compact('transaction'));
}
```

## Tombol Cetak

Di Blade:

```html
<button onclick="window.print()">Cetak Nota</button>
```

## File View

```text
resources/views/transactions/invoice.blade.php
```
