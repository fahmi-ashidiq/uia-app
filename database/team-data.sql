USE master;

-- Kalau tabel tb_team masih kosong / mau isi baru, pakai INSERT:
-- (kalau data contoh dari schema.sql sudah ada, jalankan dulu
--  bagian DELETE di bawah supaya tidak dobel)

DELETE FROM tb_team; -- hapus data contoh lama (Nama Anggota 1/2)

INSERT INTO tb_team (nim, name, photo, contribution, sort_order) VALUES
('3420240015', 'Fahmi Ashidiq', 'fahmi.jpeg', 'Set up database,backend,dan integrasi region', 1),
('3420240010', 'Muhammad Iqbal','iqbal.jpeg', 'Backend PHP dan Integrasi Database', 2);

-- Kalau anggota lebih dari 2, tambahkan baris lagi di atas,
-- lalu buka file module/account/team.php dan ganti:
--   LIMIT 2
-- menjadi jumlah anggota kelompokmu, misal LIMIT 4

-- Kalau foto belum ada / tidak mau pakai foto dulu, isi photo dengan NULL:
-- ('2210511003', 'Nama Anggota Ketiga', NULL, 'Deskripsi kontribusi', 3);
