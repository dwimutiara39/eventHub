# Task 4: Event Details (Speakers, Schedules, Sponsors)

## Deskripsi
Melengkapi detail informasi dari sebuah event yang mencakup data pembicara, jadwal/rundown sesi acara, dan sponsor pendukung.

## Detail Pekerjaan
1. **Skema Database:**
   - Pembuatan migration untuk tabel `speakers`, `event_schedules`, dan `sponsors`.
   - Pastikan masing-masing tabel memiliki `event_id` sebagai foreign key.
2. **Data Dummy (Seeder):**
   - Buat seeder yang menghasilkan data dummy untuk `speakers` (lengkap dengan nama, title, perusahaan, foto).
   - Buat seeder jadwal `event_schedules` untuk event-event dummy yang sudah digenerate sebelumnya.
   - Buat seeder `sponsors` (dengan tier: platinum, gold, silver) untuk event.
3. **Logika CRUD:**
   - Implementasi manajemen Speakers, Event Schedules, dan Sponsors sebagai bagian detail di dalam sebuah Event.
   - Admin/Panitia dapat menambahkan, mengubah, atau menghapus pembicara, sesi, dan sponsor untuk event miliknya.
