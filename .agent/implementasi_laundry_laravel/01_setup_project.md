# 01 - Setup Project Laravel Sail

## Tujuan

Membuat project Laravel dengan environment Docker menggunakan Laravel Sail.

## Command Awal

```bash
curl -s "https://laravel.build/laundry-app?with=pgsql,redis" | bash
cd laundry-app
./vendor/bin/sail up -d
```

## Cek Laravel

```bash
./vendor/bin/sail artisan --version
```

## Install Dependency Frontend

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

## Struktur Teknologi

- Laravel sebagai backend utama
- Blade sebagai view utama
- TailwindCSS sebagai styling
- PostgreSQL sebagai database
- Sail/Docker sebagai environment
