# VAULTLAUNDRY - Database Documentation

Selamat datang di direktori dokumentasi database proyek VAULTLAUNDRY. Direktori ini menyimpan skema, struktur, dan migrasi untuk database aplikasi.

## Struktur Direktori

- `migrations/` : Berisi file migrasi database Laravel yang merupakan **Source of Truth** dari struktur tabel.
- `schema.sql` : Skema manual dalam bentuk SQL yang ditujukan untuk MySQL/MariaDB (digunakan untuk referensi dan dokumentasi).
- `erd.md` : Dokumentasi desain database, mencakup Entity Relationship Diagram (ERD), flow bisnis, dan penjelasan lengkap tabel.

## Dokumentasi ERD

Untuk melihat rincian tabel, relasi (relationship), alur bisnis, dan visualisasi ERD, silakan buka:
👉 **[Entity Relationship Diagram (ERD)](erd.md)**

## Catatan Penting

1. `schema.sql` adalah dokumentasi/manual import MySQL/MariaDB.
2. Source of truth tetap **Laravel migration**.
3. **PostgreSQL** adalah database utama yang disarankan untuk environment development.
