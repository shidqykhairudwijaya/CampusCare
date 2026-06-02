<?php

session_start();
// Memanggil library PHPMailer
require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../../config/koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cek input berdasarkan email
if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (empty($email)) {
        // Pakai session error
        $_SESSION['error_email'] = "Masukkan email Anda!";
        header("Location: ../../lupa_pass/lupa_password.php");
        exit();
    }

    // 1. Cek apakah user dengan email tersebut ada
    $query = $conn->prepare("SELECT id, nama, email FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // 2. Buat Kode OTP & Waktu Kadaluwarsa (5 Menit)
        $otp = rand(100000, 999999);
        $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        // 3. Simpan OTP ke Database pada kolom otp_code dan otp_expire
        $update = $conn->prepare("UPDATE users SET otp_code = ?, otp_expire = ? WHERE id = ?");
        $update->bind_param("ssi", $otp, $expires, $user['id']);
        $update->execute();

        // 4. Kirim Email ke User menggunakan PHPMailer
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
            $mail->Password   = 'kyyzamirbteuyois';           // App Password Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('admin@campuscare.com', 'FasilkomCare Support');
            $mail->addAddress($user['email']); // Mengirim ke email user yang ditemukan

            $mail->isHTML(true);
            $mail->Subject = 'Kode OTP Reset Password - CampusCare';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
                    <h2 style='color: #800000;'>Permintaan Reset Password</h2>
                    <p>Halo <b>" . htmlspecialchars($user['nama']) . "</b>,</p>
                    <p>Kami menerima permintaan untuk mereset password akun CampusCare Anda.</p>
                    <p>Gunakan kode OTP berikut untuk melanjutkan:</p>
                    <div style='background: #f4f4f4; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #800000; border-radius: 5px;'>
                        $otp
                    </div>
                    <p style='color: #666; font-size: 12px; margin-top: 20px;'>
                        *Kode ini berlaku selama 5 menit. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.
                    </p>
                </div>
            ";

            $mail->send();

            // 5. Simpan email di session untuk verifikasi di halaman selanjutnya
            $_SESSION['reset_email'] = $user['email'];
            header("Location: ../../lupa_pass/verifikasi_otp.php");
            exit();

        } catch (Exception $e) {
            $_SESSION['error_email'] = "Gagal mengirim email. Silakan coba lagi!";
            header("Location: ../../lupa_pass/lupa_password.php");
            exit();
        }
    } else {
        // REVISI: Pakai session error jika email ga ada
        $_SESSION['error_email'] = "Email tidak ditemukan dalam sistem kami!";
        header("Location: ../../lupa_pass/lupa_password.php");
        exit();
    }
} else {
    header("Location: ../../lupa_pass/lupa_password.php");
    exit();
}
