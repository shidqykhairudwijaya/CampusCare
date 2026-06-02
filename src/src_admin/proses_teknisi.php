<?php
session_start();
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

// Keamanan: Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// PROSES HAPUS TEKNISI
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    // 1. Cek apakah teknisi masih punya laporan yang statusnya belum 'Selesai'
    $stmt_cek = $conn->prepare("SELECT COUNT(*) as jml_tugas FROM laporan WHERE id_teknisi = ? AND status != 'Selesai'");
    $stmt_cek->bind_param("i", $id_hapus);
    $stmt_cek->execute();
    $res_cek = $stmt_cek->get_result()->fetch_assoc();
    
    if ($res_cek['jml_tugas'] > 0) {
        // Jika masih ada tugas aktif (Menunggu/Diproses/Sedang Dikerjakan), batalkan hapus
        $_SESSION['error'] = "Teknisi tidak bisa dihapus karena masih memiliki tugas yang sedang diproses!";
        header("Location: ../admin/teknisi.php");
        exit();
    } else {
        // 2. Jika tugas sudah selesai semua, set id_teknisi jadi NULL di tabel laporan 
        // agar tidak terkena Error Foreign Key saat user dihapus
        $stmt_unlink = $conn->prepare("UPDATE laporan SET id_teknisi = NULL WHERE id_teknisi = ?");
        $stmt_unlink->bind_param("i", $id_hapus);
        $stmt_unlink->execute();
        $stmt_unlink->close();

        // 3. Baru hapus akun teknisinya di tabel users
        $stmt_del = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'teknisi'");
        $stmt_del->bind_param("i", $id_hapus);
        
        if ($stmt_del->execute()) {
            $_SESSION['sukses'] = "Akun teknisi berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus teknisi.";
        }
        $stmt_del->close();
    }
    
    header("Location: ../admin/teknisi.php");
    exit();
}

// AMBIL DATA TEKNISI UNTUK TAMPILAN
$q_teknisi = "SELECT u.id, u.nama, u.email, t.no_wa, t.keahlian 
              FROM users u 
              JOIN teknisi t ON u.id = t.id_teknisi 
              WHERE u.role = 'teknisi' 
              ORDER BY u.id DESC";
$res_teknisi = $conn->query($q_teknisi);
?>
