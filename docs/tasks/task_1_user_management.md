# Task 1: User Management & Authentication

## Deskripsi
Menyesuaikan sistem autentikasi dan manajemen pengguna (user management) yang sudah ada di Laravel agar selaras dengan PRD.md, khususnya terkait peran pengguna (user role). 

## Detail Pekerjaan
1. **Penyesuaian Struktur Tabel User:**
   - Sesuaikan struktur tabel `users` (di database migration) agar sesuai dengan PRD. Pastikan kolom `role` menggunakan `ENUM('superadmin', 'admin', 'student')` dan tambahkan kolom `nim` (nullable).
2. **Data Dummy (Seeder):**
   - Buat atau perbarui data dummy untuk setiap role (Superadmin, Admin/Panitia, dan Student) menggunakan Seeder (misal: `UserSeeder` dan update `DatabaseSeeder`) agar visualisasi data dapat dilihat dengan jelas saat testing.
3. **Logika CRUD & Role:**
   - Sesuaikan logika Create, Read, Update, dan Delete pengguna agar mengenali dan memproses role pengguna yang baru.
4. **Standar & Konvensi:**
   - **WAJIB** mengikuti standar coding style, pola arsitektur, dan konvensi penamaan yang sudah ada (existing) pada modul user saat ini. 
   - Dilarang membuat pola baru yang tidak konsisten dengan codebase saat ini.
