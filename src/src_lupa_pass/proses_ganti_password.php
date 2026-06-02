<?php
session_start();
require '../../config/koneksi.php';

// Pastikan data yang dibutuhkan ada
if (isset($_POST['submit_password']) && isset($_SESSION['reset_id'])) {
    $id_user = $_SESSION['reset_id'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // 1. Validasi Kecocokan Password
    if ($new_pass !== $confirm_pass) {
        // REVISI: Lempar session error
        $_SESSION['error'] = "Konfirmasi password tidak cocok!";
        header("Location: ../../lupa_pass/ganti_password.php");
        exit();
    }

    // 2. Hash password baru
    $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);

    // 3. Update ke Database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $id_user);

    if ($stmt->execute()) {
        // Hapus semua session reset agar aman
        session_unset();
        session_destroy();
        
        // Memulai session baru hanya untuk pesan sukses
        session_start();
        $_SESSION['reset_sukses'] = "Password Anda telah berhasil diperbarui! Silakan login dengan password baru.";
        
        // Arahkan ke halaman login
        header("Location: ../../index.php");
        exit();
    } else {
        // REVISI: Lempar session error
        $_SESSION['error'] = "Gagal memperbarui password. Silakan coba lagi.";
        header("Location: ../../lupa_pass/ganti_password.php");
        exit();
    }
} else {
    header("Location: ../../lupa_pass/lupa_password.php");
    exit();
}
?>
