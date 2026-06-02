<?php
session_start();
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

// Keamanan: Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// PROSES HAPUS MAHASISWA
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    // 1. Cek apakah mahasiswa ini masih punya laporan yang statusnya bukan 'Selesai'
    $stmt_cek = $conn->prepare("SELECT COUNT(*) as jml_tugas FROM laporan WHERE id_mahasiswa = ? AND status != 'Selesai'");
    $stmt_cek->bind_param("i", $id_hapus);
    $stmt_cek->execute();
    $res_cek = $stmt_cek->get_result()->fetch_assoc();
    
    if ($res_cek['jml_tugas'] > 0) {
        // Jika ada laporan yang belum beres, lempar error ke session
        $_SESSION['error'] = "Mahasiswa tidak bisa dihapus karena laporannya masih ada yang belum selesai!";
        header("Location: ../admin/mahasiswa.php");
        exit();
    } else {
        // 2. Kalau semua laporan sudah 'Selesai', hapus dulu riwayat laporannya 
        // (Supaya nggak kena Error Foreign Key di database)
        $stmt_del_laporan = $conn->prepare("DELETE FROM laporan WHERE id_mahasiswa = ?");
        $stmt_del_laporan->bind_param("i", $id_hapus);
        $stmt_del_laporan->execute();
        $stmt_del_laporan->close();

        // 3. Baru hapus akun mahasiswanya di tabel users
        $stmt_del = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'mahasiswa'");
        $stmt_del->bind_param("i", $id_hapus);
        
        if ($stmt_del->execute()) {
            $_SESSION['sukses'] = "Data mahasiswa dan seluruh riwayat laporannya berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data mahasiswa.";
        }
        $stmt_del->close();
    }
    
    header("Location: ../admin/mahasiswa.php");
    exit();
}

// AMBIL DATA MAHASISWA UNTUK TAMPILAN
$q_mhs = "SELECT u.id, u.nama, u.email, m.kelas 
          FROM users u 
          JOIN mahasiswa m ON u.id = m.id_mahasiswa 
          WHERE u.role = 'mahasiswa' 
          ORDER BY u.nama ASC";
$res_mhs = $conn->query($q_mhs);
?>
