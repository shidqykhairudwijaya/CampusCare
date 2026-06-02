<?php
session_start();

// Panggil library PHPMailer (Sesuaikan path-nya jika folder src_admin berada di dalam folder src)
require '../src/phpmailer/Exception.php';
require '../src/phpmailer/PHPMailer.php';
require '../src/phpmailer/SMTP.php';
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Proteksi Halaman Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// 1. PROSES ASSIGN TEKNISI + KIRIM EMAIL
if (isset($_POST['assign_teknisi'])) {
    $id_laporan = $_POST['id_laporan'];
    $id_teknisi = $_POST['id_teknisi'];
    $tenggat_waktu = $_POST['tenggat_waktu']; 
    $status = 'Menunggu Respon'; 

    $query_info = "SELECT u.email, u.nama as nama_teknisi, l.nama_fasilitas, mr.fakultas, mr.lantai, mr.ruangan 
                   FROM laporan l 
                   JOIN users u ON u.id = ? 
                   LEFT JOIN master_ruangan mr ON l.id_ruangan = mr.id
                   WHERE l.id = ?";
    $stmt_info = $conn->prepare($query_info);
    $stmt_info->bind_param("ii", $id_teknisi, $id_laporan);
    $stmt_info->execute();
    $info = $stmt_info->get_result()->fetch_assoc();
    
    $email_teknisi = $info['email'];
    $nama_teknisi = $info['nama_teknisi'];
    $nama_fasilitas = $info['nama_fasilitas'];
    
    // REVISI: Setup format lokasi untuk email murni dari master_ruangan
    $lokasi_email = !empty($info['fakultas']) ? "{$info['fakultas']} - Lantai {$info['lantai']} - {$info['ruangan']}" : "Lokasi Tidak Diketahui";

    // B. Update Database
    $stmt = $conn->prepare("UPDATE laporan SET id_teknisi = ?, status = ?, tenggat_waktu = ? WHERE id = ?");
    $stmt->bind_param("issi", $id_teknisi, $status, $tenggat_waktu, $id_laporan);
    
    if ($stmt->execute()) {
        
        // C. PROSES KIRIM EMAIL PAKE PHPMAILER
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
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'shidqykhairudwijayaa@gmail.com'; // Email lu
            $mail->Password   = 'kyyzamirbteuyois';           // App Password lu
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('admin@campuscare.com', 'CampusCare Admin');
            $mail->addAddress($email_teknisi); 

            $mail->isHTML(true);
            $mail->Subject = 'Tugas Perbaikan Baru - CampusCare';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
                    <h2 style='color: #800000;'>Halo, $nama_teknisi!</h2>
                    <p>Admin telah menugaskan Anda untuk melakukan perbaikan fasilitas berikut:</p>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr><td style='width: 30%; color: #666;'>Fasilitas</td><td>: <b>$nama_fasilitas</b></td></tr>
                        <tr><td style='color: #666;'>Lokasi</td><td>: $lokasi_email</td></tr>
                        <tr><td style='color: #666;'>Deadline</td><td>: <b style='color: #d33;'>" . date('d M Y', strtotime($tenggat_waktu)) . "</b></td></tr>
                    </table>
                    <p style='margin-top: 20px;'>Harap segera login ke dashboard teknisi untuk <b>menerima tugas</b> ini.</p>
                    <hr style='border: 0; border-top: 1px solid #eee;'>
                    <p style='font-size: 11px; color: #999;'>Ini adalah email otomatis, mohon tidak membalas email ini.</p>
                </div>
            ";

            $mail->send();
            $_SESSION['sukses'] = "Teknisi berhasil ditugaskan, Email notifikasi berhasil dikirim!";
        } catch (Exception $e) {
            // Tetap sukses update db tapi kasih tau email gagal
            $_SESSION['sukses'] = "Teknisi ditugaskan, tapi email gagal dikirim. (Error: {$mail->ErrorInfo})";
        }

    } else {
        $_SESSION['error'] = "Gagal menugaskan teknisi: " . $conn->error;
    }
    
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

// 2. PROSES HAPUS LAPORAN
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];

    $sql_foto = $conn->prepare("SELECT foto FROM laporan WHERE id = ? AND status = 'Selesai'");
    $sql_foto->bind_param("i", $id_hapus);
    $sql_foto->execute();
    $res_foto = $sql_foto->get_result();
    
    if ($row_foto = $res_foto->fetch_assoc()) {
        $file_path = dirname(__DIR__, 2) . "/uploads/" . $row_foto['foto'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $stmt_del = $conn->prepare("DELETE FROM laporan WHERE id = ?");
        $stmt_del->bind_param("i", $id_hapus);
        
        if ($stmt_del->execute()) {
            $_SESSION['sukses'] = "Laporan selesai telah dihapus permanen!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data dari database.";
        }
    } else {
        $_SESSION['error'] = "Gagal menghapus! Laporan belum selesai atau tidak ditemukan.";
    }
    
    header("Location: dashboard.php");
    exit();
}

// 3. AMBIL DATA UNTUK TABEL & STATISTIK
// A. Data Laporan 
$laporan_data = [];
$query_laporan = "SELECT l.*, u.nama as nama_pelapor, t.nama as nama_teknisi, mr.fakultas, mr.lantai, mr.ruangan as nama_ruangan 
                  FROM laporan l 
                  JOIN users u ON l.id_mahasiswa = u.id 
                  LEFT JOIN users t ON l.id_teknisi = t.id 
                  LEFT JOIN master_ruangan mr ON l.id_ruangan = mr.id
                  ORDER BY l.tanggal_lapor DESC";
$result_laporan = $conn->query($query_laporan);
if($result_laporan){
    while($row = $result_laporan->fetch_assoc()) { $laporan_data[] = $row; }
}

// B. Data Teknisi
$teknisi_data = [];
$query_tek = "SELECT u.id, u.nama, u.email, tek.keahlian 
              FROM users u 
              JOIN teknisi tek ON u.id = tek.id_teknisi 
              WHERE u.role='teknisi'";
$result_tek = $conn->query($query_tek);
if($result_tek){
    while($t = $result_tek->fetch_assoc()){ $teknisi_data[] = $t; }
}

// C. Statistik
$query_stat = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu,
    SUM(CASE WHEN status IN ('Menunggu Respon', 'Diproses', 'Sedang Dikerjakan') THEN 1 ELSE 0 END) as proses,
    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai
    FROM laporan";
$stat = $conn->query($query_stat)->fetch_assoc();
?>
