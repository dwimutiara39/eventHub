# Task 3: Categories & Events

## Deskripsi
Membangun fitur utama manajemen event dan kategorisasi event untuk mengelola jalannya suatu acara/kegiatan.

## Detail Pekerjaan
1. **Skema Database:**
   - Pembuatan file migration untuk tabel `categories` dan `events`.
   - Pastikan relasi tabel terhubung dengan benar (`events` berelasi dengan `organization_id` dan `category_id`).
2. **Data Dummy (Seeder):**
   - Buat `CategorySeeder` untuk kategori event (misal: Seminar, Olahraga, Kesenian, Workshop).
   - Buat `EventSeeder` berupa contoh event dengan berbagai status ('draft', 'published', 'completed') yang terhubung ke organisasi dan kategori.
3. **Logika CRUD:**
   - Implementasi manajemen Kategori (dikelola oleh Superadmin).
   - Implementasi manajemen Event (Create, Read, Update, Delete) di mana Admin/Panitia dapat mengelola event milik organisasinya sendiri.
   - Perhatikan standar coding dan arsitektur repository/controller yang seragam dengan modul yang sudah ada.
