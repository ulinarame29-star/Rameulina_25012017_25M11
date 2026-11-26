PENAMBAHAN CRUD PELANGGAN & JENIS PELANGGAN
Project database: web1_pertemuan09

ISI:
- config.php                 koneksi PDO ke database
- index.php                  halaman beranda sederhana
- pelanggan.php              CRUD data pelanggan
- jenis_pelanggan.php        CRUD jenis pelanggan
- database_tambahan.sql      membuat dua tabel dan data jenis contoh
- style.css                  tampilan halaman

CARA MEMASANG:
1. Ekstrak ZIP ini.
2. Salin file PHP dan style.css ke folder project PHP kamu di htdocs.
   Jika sudah ada config.php, buat cadangan dahulu lalu gabungkan pengaturan koneksi
   yang sesuai. File config.php di paket ini menggunakan database web1_pertemuan09,
   user root, password kosong (default XAMPP).
3. Jalankan Apache dan MySQL dari XAMPP.
4. Buka http://localhost/phpmyadmin
5. Pilih database web1_pertemuan09, buka tab SQL, lalu jalankan database_tambahan.sql.
6. Buka http://localhost/NAMA_FOLDER_PROJECT/index.php
7. Buka Jenis Pelanggan dan pastikan jenis Member/Biasa ada. Tambahkan atau edit bila perlu.
8. Buka Pelanggan untuk tambah, lihat, edit, dan hapus data.

CATATAN:
- File ZIP sumber yang diunggah hanya memuat laporan, dokumentasi, petunjuk, dan praktikum.sql.
  File PHP dari repository tidak ikut di dalam ZIP tersebut. Paket ini merupakan modul tambahan
  yang bisa digabungkan dengan file PHP project asli.
- Koneksi menggunakan PDO dan prepared statements.
- Penghapusan jenis pelanggan ditolak database jika masih dipakai oleh pelanggan.
- Sesuaikan kredensial MySQL di config.php bila tidak menggunakan pengaturan default XAMPP.
