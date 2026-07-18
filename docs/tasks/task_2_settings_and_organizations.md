# Task 2: Settings & Organizations

## Deskripsi
Membangun fitur pengelolaan pengaturan sistem (Settings) dan profil organisasi penyelenggara event (Organizations) yang berafiliasi dengan akun panitia.

## Detail Pekerjaan
1. **Skema Database:**
   - Pembuatan file migration untuk tabel `settings` dan `organizations`.
   - Tabel `organizations` harus memiliki relasi `user_id` (sebagai ketua/admin organisasi).
2. **Data Dummy (Seeder):**
   - Buat seeder `SettingSeeder` dengan data default (seperti nama aplikasi, deskripsi, logo URL).
   - Buat seeder `OrganizationSeeder` berupa contoh BEM, Himpunan, atau UKM yang terhubung dengan data user yang memiliki role 'admin'.
3. **Logika CRUD:**
   - Implementasi manajemen (CRUD) untuk `settings`.
   - Implementasi manajemen (CRUD) untuk `organizations` (dimana Superadmin bisa mengelola semua, sedangkan Admin hanya mengelola organisasinya sendiri).
   - Pastikan standar koding disesuaikan dengan pola yang sudah ada.
