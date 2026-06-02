<?php

session_start();

// Panggil library PHPMailer & Koneksi persis seperti file sebelumnya
require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../../config/koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Pastikan session email masih ada
if (!isset($_SESSION['reset_email'])) {
    header("Location: ../../lupa_pass/lupa_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

// Ambil nama dan ID user untuk email dan update
$query = $conn->prepare("SELECT id, nama FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Generate OTP baru (6 digit) & waktu kadaluwarsa (5 Menit)
    $otp = rand(100000, 999999);
    $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));

    // Update OTP di Database
    $update = $conn->prepare("UPDATE users SET otp_code = ?, otp_expire = ? WHERE id = ?");
    $update->bind_param("ssi", $otp, $expires, $user['id']);
    $update->execute();

    // Kirim Email Ulang menggunakan PHPMailer
    $mail = new PHPMailer(true);
    
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    try {
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shidqykhairudwijayaa@gmail.com'; // Email pengirim
        $mail->Password   = 'kyyzamirbteuyois';               // App Password Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('admin@campuscare.com', 'CampusCare Support');
        $mail->addAddress($email); 

        $mail->isHTML(true);
        $mail->Subject = 'Kirim Ulang Kode OTP - FasilkomCare';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
                <h2 style='color: #800000;'>Kirim Ulang OTP</h2>
                <p>Halo <b>" . htmlspecialchars($user['nama']) . "</b>,</p>
                <p>Sesuai permintaan Anda, berikut adalah kode OTP baru untuk mereset password akun CampusCare Anda:</p>
                <div style='background: #f4f4f4; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #800000; border-radius: 5px;'>
                    $otp
                </div>
                <p style='color: #666; font-size: 12px; margin-top: 20px;'>
                    *Kode ini berlaku selama 5 menit. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.
                </p>
            </div>
        ";

        $mail->send();

        // Redirect dengan session sukses untuk SweetAlert
        $_SESSION['sukses'] = "Kode OTP baru berhasil dikirim ulang! Silakan cek email Anda.";
        header("Location: ../../lupa_pass/verifikasi_otp.php");
        exit();

    } catch (Exception $e) {
        // REVISI: Pakai error_resend buat misahin dari error OTP (SweetAlert)
        $_SESSION['error_resend'] = "Gagal mengirim ulang email. Silakan coba lagi nanti.";
        header("Location: ../../lupa_pass/verifikasi_otp.php");
        exit();
    }
} else {
    // REVISI: Kalau tiba-tiba email ilang dari database, lempar pake session error_email
    $_SESSION['error_email'] = "Terjadi kesalahan, user tidak ditemukan!";
    header("Location: ../../lupa_pass/lupa_password.php");
    exit();
}
?>
