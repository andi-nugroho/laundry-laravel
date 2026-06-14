# VAULTLAUNDRY - Database Documentation

Direktori ini menyimpan skema, dokumentasi ERD, dan migration database aplikasi VAULTLAUNDRY.

## Struktur Direktori

- `migrations/`: Source of truth struktur database Laravel.
- `schema.sql`: Dokumentasi dan referensi manual import MySQL/MariaDB.
- `erd.md`: Dokumentasi desain database, relasi, dan alur bisnis.
- `Rancangan_ERD_VAULTLAUNDRY.docx`: Dokumen Word rancangan ERD yang siap diedit.
- `erd-vaultlaundry.mmd`: Source code Mermaid untuk ERD inti.
- `erd-vaultlaundry.png`: Diagram visual ERD beresolusi tinggi.

## Dokumentasi ERD

Lihat [Entity Relationship Diagram (ERD)](erd.md) untuk penjelasan tabel, relasi, dan alur data.

## Catatan Penting

1. Laravel migration tetap menjadi source of truth.
2. PostgreSQL adalah database utama untuk development dan CI.
3. `schema.sql` hanya digunakan sebagai dokumentasi atau alternatif manual import MySQL/MariaDB.
