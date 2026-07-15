— PHP + MySQL (Setup)

1. Foto profil (kanan atas) + logo UIA (kiri atas) — di `index.php`
2. Tabel `tb_region` (code, name, level, parent_code, parent_name) — `database/schema.sql` + `database/region.sql`
3. Dropdown wilayah bertingkat (Provinsi → Kab/Kota → Kecamatan → Kelurahan/Desa) di halaman Profile, tersimpan di `tb_users`
4. Halaman **Team** (menu sidebar baru) menampilkan NIM, Nama, Foto, Kontribusi dari tabel `tb_team`

## 1. Import database

Jalankan berurutan di MySQL (phpMyAdmin / CLI):

```bash
mysql -u root -p < database/schema.sql   # buat database `master` + tabel tb_users, tb_region, tb_team
mysql -u root -p master < database/region.sql   # isi data wilayah
```

`schema.sql` juga memasukkan 2 akun contoh dari `data.json` lama (password: `12345`,
sudah di-hash bcrypt)
## 2. Konfigurasi koneksi

Edit `config/database.php` sesuai kredensial MySQL lokal (host/user/password).

## 3. Install dependency (ramsey/uuid)

```bash
composer install
```

## 4. Logo UIA

file logo UIA di `asset/img/logo-uia.png` (ukuran persegi,
mis. 128x128). Sidebar sudah otomatis menampilkannya; jika file belum ada,
teks "UIA" tetap tampil tanpa gambar rusak.

## 5. Struktur folder baru

```
config/database.php          -> koneksi PDO (dipakai semua file)
database/schema.sql           -> CREATE TABLE tb_users, tb_region, tb_team
database/region.sql           -> data wilayah (INSERT ke tb_region)
module/account/get-region.php -> endpoint AJAX untuk dropdown wilayah
module/team/team.php          -> halaman Team baru
asset/profile/                -> upload foto profil user & tim
asset/img/                    -> taruh logo-uia.png di sini
css/custom.css                -> style tambahan (logo, dropdown, team card)
```

## Catatan keamanan

- Password sekarang di-hash dengan `password_hash()` (bcrypt) saat register, dan
  diverifikasi dengan `password_verify()` saat login — sebelumnya tersimpan plain text.
- Upload foto dibatasi ke ekstensi jpg/jpeg/png/gif/webp dan diberi nama unik
  (`user_id_timestamp.ext`) supaya tidak bentrok/timpa file orang lain.
- Dropdown wilayah menyimpan `code` (sumber kebenaran) sekaligus `name`
  (cache tampilan) di `tb_users`, dan saat halaman profile dibuka ulang,
  dropdown otomatis "mengunci" ke pilihan yang tersimpan sebelumnya
  (province → city → district → village dimuat berantai via AJAX).
