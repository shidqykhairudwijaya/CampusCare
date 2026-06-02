<?php
session_start();
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

// PROSES TAMBAH TEKNISI
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email']; 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $keahlian = $_POST['keahlian'];
    $no_wa = $_POST['no_wa'];
    $role = 'teknisi';

    // 1. Cek apakah email sudah dipakai
    $cek_user = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $cek_user->bind_param("s", $email);
    $cek_user->execute();
    $result_cek = $cek_user->get_result();

    if ($result_cek->num_rows > 0) {
        $_SESSION['error_form'] = "Email sudah digunakan! Silakan pilih email lain.";
    } else {
        // MENGGUNAKAN TRANSACTION
        $conn->begin_transaction();

        try {
            // 2. Insert ke tabel induk 'users'
            $stmt = $conn->prepare("INSERT INTO users (email, password, nama, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $email, $password, $nama, $role);
            $stmt->execute();

            // 3. Ambil ID yang baru saja dibuat
            $id_teknisi_baru = $conn->insert_id;

            // 4. Insert ke tabel detail 'teknisi'
            $stmt2 = $conn->prepare("INSERT INTO teknisi (id_teknisi, no_wa, keahlian) VALUES (?, ?, ?)");
            $stmt2->bind_param("iss", $id_teknisi_baru, $no_wa, $keahlian);
            $stmt2->execute();

            // Selesaikan transaksi
            $conn->commit();

            $_SESSION['sukses'] = "Akun teknisi baru berhasil ditambahkan!";
            header("Location: ../../admin/teknisi.php");
            exit();

        } catch (Exception $e) {
            // Jika ada gagal, batalkan semua
            $conn->rollback();
            $_SESSION['error_form'] = "Gagal menyimpan data teknisi: " . $e->getMessage();
        }
    }
    // Jika ada error (email duplikat atau catch), kembali ke form
    header("Location: ../../admin/tambah_teknisi.php");
    exit();
}
