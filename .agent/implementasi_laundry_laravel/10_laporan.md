# 10 - Laporan Transaksi

## Tujuan

Membuat laporan transaksi laundry.

## Jenis Laporan

- Laporan harian
- Laporan bulanan
- Laporan pendapatan
- Laporan status laundry
- Riwayat transaksi pelanggan

## Filter

- Tanggal awal
- Tanggal akhir
- Status pembayaran
- Status laundry

## Route

```php
Route::get('/reports/transactions', [ReportController::class, 'transactions'])
    ->name('reports.transactions');
```

## Controller

```php
public function transactions(Request $request)
{
    $query = Transaction::with('booking.customer', 'booking.service');

    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    $transactions = $query->latest()->get();

    return view('reports.transactions', compact('transactions'));
}
```

## Export Opsional

Bisa ditambahkan:
- Export PDF
- Export Excel
