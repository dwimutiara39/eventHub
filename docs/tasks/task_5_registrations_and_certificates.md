# Task 5: Registrations & Certificates

## Deskripsi
Mengembangkan sistem pendaftaran (registrasi) mahasiswa ke dalam sebuah event, manajemen absensi/kehadiran, serta otomatisasi penerbitan sertifikat digital.

## Detail Pekerjaan
1. **Skema Database:**
   - Pembuatan file migration untuk tabel `registrations` (tabel pivot berelasi ke `users` dan `events`) dan tabel `certificates` (berelasi ke `registrations`).
2. **Data Dummy (Seeder):**
   - Buat `RegistrationSeeder` berisi pendaftaran dummy mahasiswa pada event (dengan status `registered` atau `cancelled` dan `is_attended` true/false).
   - Buat `CertificateSeeder` untuk pendaftaran yang memiliki status kehadiran true (is_attended = true).
3. **Logika Fitur:**
   - Fitur mahasiswa mendaftar event (cek ketersediaan kuota kapasitas event terlebih dahulu).
   - Fitur panitia melihat daftar peserta dan melakukan check-in / approval kehadiran.
   - Fitur sistem otomatis generate record sertifikat ketika status kehadiran diset menjadi true.
