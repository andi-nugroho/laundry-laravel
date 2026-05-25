# 05 - Booking Laundry

## Tujuan

User atau kasir dapat membuat booking laundry.

## Model dan Migration

```bash
./vendor/bin/sail artisan make:model Booking -mcr
```

Field tabel bookings:

```php
$table->id();
$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
$table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
$table->foreignId('service_id')->constrained()->cascadeOnDelete();
$table->string('booking_code')->unique();
$table->date('booking_date');
$table->date('estimated_finish_date')->nullable();
$table->decimal('weight', 8, 2)->nullable();
$table->decimal('total_price', 10, 2)->default(0);
$table->enum('pickup_type', ['antar_sendiri', 'pickup'])->default('antar_sendiri');
$table->text('notes')->nullable();
$table->enum('status', ['booking_masuk', 'diterima', 'dicuci', 'dikeringkan', 'disetrika', 'selesai', 'diambil', 'dibatalkan'])->default('booking_masuk');
$table->timestamps();
```

## Flow Booking

1. User memilih layanan
2. User memilih tanggal booking
3. User memilih pickup atau antar sendiri
4. Sistem membuat kode booking otomatis
5. Status awal: booking_masuk

## Contoh Kode Booking

Format:

```text
LDY-2026-0001
```

## View

```text
resources/views/bookings/
├── index.blade.php
├── create.blade.php
├── show.blade.php
└── edit.blade.php
```
