-- Jalankan di phpMyAdmin pada database web1_pertemuan09.
-- Struktur mengikuti tabel pada gambar tugas: pelanggan dan jenis_pelanggan.
USE web1_pertemuan09;

CREATE TABLE IF NOT EXISTS jenis_pelanggan (
    id_jenis INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    jenis VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pelanggan (
    id_pelanggan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_jenis INT UNSIGNED NOT NULL,
    nama_pelanggan VARCHAR(100) NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    no_handphone VARCHAR(20) NOT NULL,
    CONSTRAINT fk_pelanggan_jenis
        FOREIGN KEY (id_jenis) REFERENCES jenis_pelanggan(id_jenis)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Data awal contoh. Jalankan bagian ini satu kali saja.
INSERT INTO jenis_pelanggan (jenis)
SELECT 'Member' WHERE NOT EXISTS (SELECT 1 FROM jenis_pelanggan WHERE jenis = 'Member');

INSERT INTO jenis_pelanggan (jenis)
SELECT 'Biasa' WHERE NOT EXISTS (SELECT 1 FROM jenis_pelanggan WHERE jenis = 'Biasa');
