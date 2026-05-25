# 03 - Auth dan Role User

## Tujuan

Membuat sistem login dan hak akses pengguna.

## Role User

1. Admin / Owner
2. Kasir
3. User Biasa / Customer

## Tambahkan Kolom Role di Tabel Users

Buat migration:

```bash
./vendor/bin/sail artisan make:migration add_role_to_users_table
```

Isi migration:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('role')->default('user');
});
```

Jalankan:

```bash
./vendor/bin/sail artisan migrate
```

## Middleware Role

Buat middleware:

```bash
./vendor/bin/sail artisan make:middleware RoleMiddleware
```

Contoh konsep:

```php
public function handle($request, Closure $next, ...$roles)
{
    if (! in_array(auth()->user()->role, $roles)) {
        abort(403);
    }

    return $next($request);
}
```

## Hak Akses

Admin:
- Mengelola user
- Mengelola layanan
- Melihat laporan
- Monitoring semua transaksi

Kasir:
- Input booking
- Update status cucian
- Cetak nota
- Melihat riwayat transaksi

User:
- Booking laundry
- Melihat status cucian
- Melihat riwayat pribadi
