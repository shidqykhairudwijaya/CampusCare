# CampusCare

## 📌 Informasi Kelompok

| Nama                    | NPM           |
| ----------------------- | ------------- |
| Shidqy Khairu Dwijaya   | 2410631170109 |
| Sarah Nur Faiza         | 2410631170108 |
| Faerul Akira Alfatio    | 2410631170140 |

---

# 📖 Deskripsi Project

**CampusCare** merupakan aplikasi berbasis web yang dirancang untuk membantu proses pelaporan dan penanganan kerusakan fasilitas kampus secara terstruktur dan terdokumentasi.

Sistem ini memungkinkan mahasiswa melaporkan kerusakan fasilitas seperti AC, proyektor, kursi, jaringan internet, maupun fasilitas lainnya. Laporan yang masuk akan dikelola oleh administrator dan diteruskan kepada teknisi yang sesuai dengan bidang keahliannya hingga proses perbaikan selesai.

Dengan adanya CampusCare, proses pelaporan kerusakan menjadi lebih cepat, transparan, dan mudah dipantau oleh seluruh pihak yang terlibat.

---

# 🎯 Tujuan Website

1. Mempermudah mahasiswa dalam melaporkan kerusakan fasilitas kampus.
2. Membantu admin mengelola laporan dan mendistribusikan pekerjaan kepada teknisi.
3. Membantu teknisi memantau dan menyelesaikan tugas perbaikan.
4. Menyediakan sistem monitoring status perbaikan secara real-time.
5. Mengurangi keterlambatan penanganan kerusakan fasilitas kampus.

---

# ✨ Fitur Utama

## 👨‍🎓 Mahasiswa

### Login Sistem

Mahasiswa dapat masuk ke sistem menggunakan email dan password yang telah terdaftar.

### Membuat Laporan Kerusakan

Mahasiswa dapat mengisi formulir pelaporan yang meliputi:

* Nama fasilitas
* Kategori kerusakan
* Lokasi/ruangan
* Deskripsi kerusakan
* Upload foto kerusakan

### Monitoring Status Laporan

Mahasiswa dapat melihat perkembangan laporan yang telah dibuat.

Status laporan meliputi:

* Menunggu
* Menunggu Respon
* Diproses
* Sedang Dikerjakan
* Selesai

---

## 🔧 Teknisi

### Dashboard Teknisi

Teknisi dapat melihat seluruh tugas yang diberikan sesuai keahlian.

### Pengelolaan Tugas

Teknisi dapat:

* Melihat detail laporan
* Memberikan catatan perbaikan
* Mengubah status pekerjaan
* Menyelesaikan tugas yang diberikan

### Filter Status Pekerjaan

Teknisi dapat memfilter pekerjaan berdasarkan status:

* Tugas Baru
* Sedang Dikerjakan
* Selesai

---

## 👨‍💼 Administrator

### Dashboard Administrator

Admin memiliki akses penuh terhadap seluruh laporan yang masuk.

### Manajemen Teknisi

Admin dapat:

* Menambahkan teknisi
* Menghapus teknisi
* Mengelola data teknisi
* Menentukan bidang keahlian teknisi

### Manajemen Mahasiswa

Admin dapat:

* Menambahkan akun mahasiswa
* Mengelola data mahasiswa
* Menghapus akun mahasiswa

### Manajemen Lokasi

Admin dapat mengelola:

* Fakultas
* Lantai
* Ruangan

### Monitoring Laporan

Admin dapat:

* Melihat seluruh laporan
* Menentukan teknisi yang bertugas
* Memantau progres pekerjaan
* Melihat detail laporan kerusakan

---

## 🔐 Fitur Keamanan

### Password Hashing

Password pengguna disimpan menggunakan fungsi hashing PHP sehingga lebih aman.

### Session Authentication

Sistem menggunakan session untuk menjaga autentikasi pengguna.

### Role Based Access Control

Hak akses dibedakan berdasarkan role:

* Admin
* Teknisi
* Mahasiswa

---

## 📧 Fitur Lupa Password

Sistem menyediakan fitur reset password menggunakan OTP yang dikirim melalui email menggunakan PHPMailer.

Alur proses:

1. Pengguna memasukkan email.
2. Sistem mengirim kode OTP.
3. Pengguna melakukan verifikasi OTP.
4. Pengguna membuat password baru.

---

# 🛠 Teknologi yang Digunakan

## Backend

* PHP Native
* MySQL / MariaDB

## Frontend

* HTML5
* CSS3
* Bootstrap 5
* Bootstrap Icons
* JavaScript

## Library Tambahan

* PHPMailer
* SweetAlert2

---

# 🗄 Struktur Database

Database yang digunakan bernama:

```sql
campuscare
```

### Tabel Users

Menyimpan data akun pengguna.

Kolom utama:

* id
* nama
* email
* password
* role
* otp_code
* otp_expire

### Tabel Mahasiswa

Menyimpan data tambahan mahasiswa.

Kolom:

* id_mahasiswa
* kelas

### Tabel Teknisi

Menyimpan data teknisi.

Kolom:

* id_teknisi
* no_wa
* keahlian

### Tabel Admin

Menyimpan data administrator.

Kolom:

* id_admin
* level_akses

### Tabel Master Ruangan

Menyimpan data lokasi kampus.

Kolom:

* fakultas
* lantai
* ruangan

### Tabel Laporan

Menyimpan seluruh laporan kerusakan.

Kolom utama:

* id
* id_mahasiswa
* id_teknisi
* id_ruangan
* nama_fasilitas
* kategori
* deskripsi
* foto
* status
* catatan_teknisi
* tanggal_lapor
* tenggat_waktu

---

# 📁 Struktur Project

```text
CampusCare/
│
├── admin/
│   ├── dashboard.php
│   ├── mahasiswa.php
│   ├── teknisi.php
│   ├── lokasi.php
│   ├── detail_lokasi.php
│   ├── tambah_mahasiswa.php
│   └── tambah_teknisi.php
│
├── mahasiswa/
│   ├── dashboard.php
│   └── tambah_laporan.php
│
├── teknisi/
│   └── dashboard.php
│
├── config/
│   └── koneksi.php
│
├── lupa_pass/
│   ├── lupa_password.php
│   ├── verifikasi_otp.php
│   └── ganti_password.php
│
├── src/
│   ├── src_login/
│   ├── src_admin/
│   ├── src_mahasiswa/
│   ├── src_teknisi/
│   ├── src_lupa_pass/
│   └── phpmailer/
│
├── assets/
│   ├── bootstrap/
│   ├── style.css
│   └── script.js
│
├── campuscare.sql
├── index.php
└── logout.php
```

---

# 📂 Penjelasan Folder Penting

## admin/

Berisi seluruh halaman yang digunakan administrator untuk mengelola sistem.

## mahasiswa/

Berisi halaman yang digunakan mahasiswa untuk membuat dan melihat laporan.

## teknisi/

Berisi halaman yang digunakan teknisi untuk mengelola tugas perbaikan.

## src/

Berisi seluruh proses backend aplikasi seperti login, CRUD data, pengelolaan laporan, dan reset password.

## config/

Berisi konfigurasi koneksi database.

## assets/

Berisi file CSS, JavaScript, Bootstrap, dan resource frontend lainnya.

## campuscare.sql

File database yang digunakan untuk menjalankan sistem.

---

# 🚀 Cara Menjalankan Aplikasi

## 1. Clone Repository

```bash
git clone https://github.com/USERNAME/CampusCare.git
```

atau download ZIP repository.

---

## 2. Pindahkan ke Web Server

Simpan project pada folder:

### XAMPP

```text
htdocs/CampusCare
```

### Laragon

```text
www/CampusCare
```

---

## 3. Import Database

1. Buka phpMyAdmin.
2. Buat database baru:

```sql
campuscare
```

3. Import file:

```text
campuscare.sql
```

---

## 4. Konfigurasi Database

Buka file:

```php
config/koneksi.php
```

Sesuaikan:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "campuscare";
```

---

## 5. Jalankan Project

Buka browser:

```text
http://localhost/CampusCare
```

---

# 🎥 Video Presentasi

Link Video Presentasi:

```text
https://youtu.be/ISI_LINK_VIDEO_DISINI
```
