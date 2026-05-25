# 07 - Transaksi dan Pembayaran

## Tujuan

Membuat transaksi pembayaran laundry.

## Model

```bash
./vendor/bin/sail artisan make:model Transaction -mcr
./vendor/bin/sail artisan make:model Payment -mcr
```

## Tabel Transactions

```php
$table->id();
$table->foreignId('booking_id')->constrained()->cascadeOnDelete();
$table->string('invoice_number')->unique();
$table->decimal('subtotal', 10, 2);
$table->decimal('discount', 10, 2)->default(0);
$table->decimal('total', 10, 2);
$table->enum('payment_status', ['belum_bayar', 'lunas'])->default('belum_bayar');
$table->timestamps();
```

## Tabel Payments

```php
$table->id();
$table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
$table->enum('method', ['cash', 'transfer', 'qris'])->default('cash');
$table->decimal('amount_paid', 10, 2);
$table->decimal('change_amount', 10, 2)->default(0);
$table->dateTime('paid_at')->nullable();
$table->timestamps();
```

## Rumus Total

```text
subtotal = berat x harga layanan per kg
total = subtotal - diskon
kembalian = uang dibayar - total
```

## Flow

1. Kasir membuka booking
2. Input berat laundry
3. Sistem menghitung subtotal
4. Kasir input pembayaran
5. Sistem membuat invoice
6. Status pembayaran berubah menjadi lunas
