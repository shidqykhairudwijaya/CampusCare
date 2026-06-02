<?php
session_start();
// Panggil koneksi database
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cari user berdasarkan email
    $stmt = $conn->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            
            // Set Session Data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];

            // Redirect sesuai Role
            if ($user['role'] == 'admin') {
                header("Location: ../../admin/dashboard.php");
            } elseif ($user['role'] == 'teknisi') {
                header("Location: ../../teknisi/dashboard.php");
            } elseif ($user['role'] == 'mahasiswa') {
                header("Location: ../../mahasiswa/dashboard.php");
            }
            exit();

        } else {
            // Password salah
            $_SESSION['error'] = "Password salah!";
            header("Location: ../../index.php");
            exit(); 
        }
    } else {
        // Email tidak terdaftar
        $_SESSION['error'] = "Email tidak ditemukan!";
        header("Location: ../../index.php");
        exit(); 
    }
} else {
    header("Location: ../../index.php");
    exit();
}
?>
