# Task 6: Feedbacks & Notifications

## Deskripsi
Menambahkan sistem ulasan/umpan balik (feedback) dari peserta terkait sebuah event, serta sistem notifikasi in-app untuk memberikan pemberitahuan secara real-time.

## Detail Pekerjaan
1. **Skema Database:**
   - Pembuatan migration untuk tabel `feedbacks` dan `notifications`.
2. **Data Dummy (Seeder):**
   - Buat seeder untuk `feedbacks` berisi ulasan dummy (rating dan komentar) dari user untuk event yang berstatus `completed`.
   - Buat seeder `notifications` berisi pemberitahuan contoh untuk pengguna (baik yang belum dibaca maupun sudah dibaca).
3. **Logika Fitur:**
   - Fitur bagi mahasiswa (peserta) untuk dapat memberikan rating dan ulasan setelah event selesai.
   - Fitur melihat rating rata-rata dari sebuah event bagi panitia penyelenggara.
   - Fitur trigger notifikasi sistem untuk dikirimkan kepada user pada aksi-aksi tertentu (contoh: pendaftaran event berhasil diverifikasi, H-1 pengingat event).
