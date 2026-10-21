<<<<<<< HEAD
CREATE TABLE mata_kuliah (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    dosen VARCHAR(100) NOT NULL,
    semester TINYINT UNSIGNED NOT NULL
);

CREATE TABLE materi (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mata_kuliah_id INT UNSIGNED NOT NULL,
    judul VARCHAR(150) NOT NULL,
    isi TEXT NOT NULL,
    tautan VARCHAR(255) NOT NULL,
    FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE RESTRICT
);

CREATE TABLE tugas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mata_kuliah_id INT UNSIGNED NOT NULL,
    judul VARCHAR(150) NOT NULL,
    instruksi TEXT NOT NULL,
    tenggat DATE NOT NULL,
    status VARCHAR(20) NOT NULL,
    FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE RESTRICT
);

INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES
('ALP-101', 'Algoritma dan Pemrograman', 'Dosen ALP', 1),
('PAW-101', 'Pengembangan Aplikasi Website', 'Dosen PAW', 3);
=======
CREATE DATABASE IF NOT EXISTS paw_modul2_showroom
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE paw_modul2_showroom;

CREATE TABLE showroom_mobil (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_mobil VARCHAR(20) NOT NULL UNIQUE,
    nama_mobil VARCHAR(100) NOT NULL,
    brand_mobil VARCHAR(50) NOT NULL,
    warna_mobil VARCHAR(30) NOT NULL,
    tipe_mobil VARCHAR(20) NOT NULL,
    harga_mobil DECIMAL(13,2) NOT NULL
);

INSERT INTO showroom_mobil
(kode_mobil, nama_mobil, brand_mobil, warna_mobil, tipe_mobil, harga_mobil)
VALUES
('MBL-001', 'Avanza Veloz', 'Toyota', 'Putih', 'MPV', 310000000),
('MBL-002', 'Honda City', 'Honda', 'Abu-abu', 'Sedan', 370000000),
('MBL-003', 'Suzuki Baleno', 'Suzuki', 'Biru', 'Hatchback', 285000000);
>>>>>>> 06701a8a15f16d083bf42b7e6edc71bc6b9bdbd8
