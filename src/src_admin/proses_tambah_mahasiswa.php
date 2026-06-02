<?php
session_start();
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

// Keamanan: Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// PROSES TAMBAH MAHASISWA
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas']; 
    $email = $_POST['email']; 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role = 'mahasiswa';

    // 1. Cek apakah email sudah dipakai
    $cek_user = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $cek_user->bind_param("s", $email);
    $cek_user->execute();
    $result_cek = $cek_user->get_result();

    if ($result_cek->num_rows > 0) {
        $_SESSION['error_form'] = "Email sudah digunakan! Silakan gunakan email lain.";
        header("Location: ../../admin/tambah_mahasiswa.php");
        exit();
    } else {
        $conn->begin_transaction();

        try {
            // 2. Insert ke tabel induk 'users'
            $stmt = $conn->prepare("INSERT INTO users (email, password, nama, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $email, $password, $nama, $role);
            $stmt->execute();

            // 3. Ambil ID yang baru saja digenerate
            $id_mahasiswa_baru = $conn->insert_id;

            // 4. Insert ke tabel detail 'mahasiswa'
            $stmt2 = $conn->prepare("INSERT INTO mahasiswa (id_mahasiswa, kelas) VALUES (?, ?)");
            $stmt2->bind_param("is", $id_mahasiswa_baru, $kelas);
            $stmt2->execute();

            $conn->commit();

            $_SESSION['sukses'] = "Akun mahasiswa baru berhasil ditambahkan!";
            header("Location: ../../admin/mahasiswa.php"); 
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error_form'] = "Gagal menyimpan data: " . $e->getMessage();
            header("Location: ../../admin/tambah_mahasiswa.php");
            exit();
        }
    }
} else {
    // Jika diakses tanpa POST
    header("Location: ../../admin/mahasiswa.php");
    exit();
}
