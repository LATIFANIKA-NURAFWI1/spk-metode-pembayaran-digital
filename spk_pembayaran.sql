DROP DATABASE IF EXISTS spk_pembayaran;
CREATE DATABASE IF NOT EXISTS spk_pembayaran;
USE spk_pembayaran;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (username, password) VALUES ('admin', MD5('admin'));

CREATE TABLE kriteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    tipe ENUM('Benefit', 'Cost') NOT NULL,
    bobot FLOAT NOT NULL
);

INSERT INTO kriteria (kode, nama, tipe, bobot) VALUES
('C1', 'Biaya Transaksi', 'Cost', 0.40),
('C2', 'Kemudahan Penggunaan', 'Benefit', 0.27),
('C3', 'Keamanan Transaksi', 'Benefit', 0.24),
('C4', 'Kecepatan Transaksi', 'Benefit', 0.06),
('C5', 'Popularitas', 'Benefit', 0.04);

CREATE TABLE alternatif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL,
    nama VARCHAR(100) NOT NULL
);

INSERT INTO alternatif (kode, nama) VALUES
('A1', 'QRIS'),
('A2', 'E-Wallet'),
('A3', 'Transfer Bank'),
('A4', 'Kartu Debit/Kredit');

CREATE TABLE nilai_matriks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alternatif INT NOT NULL,
    id_kriteria INT NOT NULL,
    nilai FLOAT NOT NULL,
    FOREIGN KEY (id_alternatif) REFERENCES alternatif(id) ON DELETE CASCADE,
    FOREIGN KEY (id_kriteria) REFERENCES kriteria(id) ON DELETE CASCADE
);

INSERT INTO nilai_matriks (id_alternatif, id_kriteria, nilai) VALUES
(1, 1, 1.61), (1, 2, 4.39), (1, 3, 4.24), (1, 4, 4.24), (1, 5, 4.24),
(2, 1, 2.25), (2, 2, 3.88), (2, 3, 3.64), (2, 4, 3.91), (2, 5, 3.91),
(3, 1, 3.36), (3, 2, 3.59), (3, 3, 4.12), (3, 4, 3.65), (3, 5, 3.65),
(4, 1, 1.63), (4, 2, 3.53), (4, 3, 4.06), (4, 4, 3.88), (4, 5, 3.88);
