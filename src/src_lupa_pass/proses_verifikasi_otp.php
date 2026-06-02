<?php
session_start();
require '../../config/koneksi.php';

// Pastikan ada session email dan tombol verifikasi diklik
if (isset($_POST['verifikasi']) && isset($_SESSION['reset_email'])) {
    $otp_input = mysqli_real_escape_string($conn, $_POST['otp_input']);
    $email = $_SESSION['reset_email'];
    $now = date("Y-m-d H:i:s");

    // Menggunakan kolom 'email' sesuai database terbaru
    $query = $conn->prepare("SELECT id FROM users WHERE email = ? AND otp_code = ? AND otp_expire > ?");
    $query->bind_param("sss", $email, $otp_input, $now);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Simpan ID user ke session untuk proses ganti password di tahap akhir
        $_SESSION['reset_id'] = $user['id']; 
        
        // Kosongkan OTP di database agar tidak bisa dipakai lagi (keamanan)
        $clear_otp = $conn->prepare("UPDATE users SET otp_code = NULL, otp_expire = NULL WHERE id = ?");
        $clear_otp->bind_param("i", $user['id']);
        $clear_otp->execute();

        header("Location: ../../lupa_pass/ganti_password.php");
        exit();
    } else {
        // Ini udah bener, ngelempar session error yang bakal ditangkep sama class is-invalid di atas
        $_SESSION['error'] = "Kode OTP salah atau sudah kadaluwarsa! Silakan coba lagi.";
        header("Location: ../../lupa_pass/verifikasi_otp.php");
        exit();
    }
} else {
    header("Location: ../../lupa_pass/lupa_password.php");
    exit();
}
?>
