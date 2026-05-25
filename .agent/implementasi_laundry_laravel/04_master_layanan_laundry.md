# 04 - Master Data Layanan Laundry

## Tujuan

Membuat fitur CRUD layanan laundry.

## Contoh Layanan

- Cuci Kering
- Cuci Setrika
- Setrika Saja
- Laundry Express
- Laundry Sepatu
- Laundry Bedcover

## Model dan Migration

```bash
./vendor/bin/sail artisan make:model Service -mcr
```

Field tabel services:

```php
$table->id();
$table->string('name');
$table->text('description')->nullable();
$table->decimal('price_per_kg', 10, 2);
$table->integer('estimated_days')->default(2);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

## Controller

Gunakan ServiceController untuk:
- index
- create
- store
- edit
- update
- destroy

## View

Folder Blade:

```text
resources/views/services/
├── index.blade.php
├── create.blade.php
└── edit.blade.php
```
