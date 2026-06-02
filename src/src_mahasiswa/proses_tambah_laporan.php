<?php
session_start();

// Panggil koneksi database pake absolute path
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

// Proteksi halaman khusus mahasiswa
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../../index.php");
    exit();
}

// Cek apakah form dikirim lewat metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil data dari form
    $fasilitas = $_POST['nama_fasilitas'];
    $kategori = $_POST['kategori'];
    
    // Ambil id ruangan dari dropdown
    $id_ruangan = $_POST['ruangan'];
    
    $deskripsi = $_POST['deskripsi'];
    $id_mhs = $_SESSION['user_id'];

    // Ambil data file foto
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    
    // Validasi jika foto belum dipilih
    if (empty($foto)) {
        $_SESSION['error'] = "File foto wajib diunggah!";
        header("Location: ../../mahasiswa/tambah_laporan.php");
        exit();
    }

    // Buat nama foto baru agar tidak bentrok
    $ext = pathinfo($foto, PATHINFO_EXTENSION);
    $nama_foto_baru = time()."_".$id_mhs.".".$ext;
    
    // Path folder upload
    $path = dirname(__DIR__, 2) . "/uploads/" . $nama_foto_baru;

    // Proses upload file
    if (move_uploaded_file($tmp, $path)) {

        // Simpan data laporan ke database
        $stmt = $conn->prepare("INSERT INTO laporan (id_mahasiswa, id_ruangan, nama_fasilitas, kategori, deskripsi, foto, status) VALUES (?, ?, ?, ?, ?, ?, 'Menunggu')");
        
        if ($stmt) {

            // Bind parameter ke query
            $stmt->bind_param("iissss", $id_mhs, $id_ruangan, $fasilitas, $kategori, $deskripsi, $nama_foto_baru);
            
            // Eksekusi query
            if ($stmt->execute()) {

                // Session notifikasi sukses
                $_SESSION['sukses'] = "Laporan fasilitas berhasil dikirimkan!";

                // Redirect ke dashboard mahasiswa
                header("Location: ../../mahasiswa/dashboard.php");
                exit();

            } else {

                // Notifikasi jika gagal simpan database
                $_SESSION['error'] = "Gagal nyimpen ke database: " . $stmt->error;
                header("Location: ../../mahasiswa/tambah_laporan.php");
                exit();
            }

        } else {

            // Notifikasi jika query prepare gagal
            $_SESSION['error'] = "Error SQL: " . $conn->error;
            header("Location: ../../mahasiswa/tambah_laporan.php");
            exit();
        }

    } else {

        // Notifikasi jika upload foto gagal
        $_SESSION['error'] = "Gagal upload! Pastikan folder 'uploads' ada dan lokasinya bener.";
        header("Location: ../../mahasiswa/tambah_laporan.php");
        exit();
    }

} else {

    // Redirect jika file diakses langsung tanpa submit form
    header("Location: ../../mahasiswa/tambah_laporan.php");
    exit();
}
?>