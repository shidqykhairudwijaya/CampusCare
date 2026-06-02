<?php
session_start();
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// 1. PROSES TAMBAH FAKULTAS (DI HALAMAN UTAMA)
if (isset($_POST['tambah_fakultas'])) {
    $fakultas = trim($_POST['nama_fakultas']);
    
    // Cek apakah fakultas sudah ada biar gak dobel
    $cek = $conn->prepare("SELECT id FROM master_ruangan WHERE fakultas = ?");
    $cek->bind_param("s", $fakultas);
    $cek->execute();
    
    if ($cek->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Fakultas tersebut sudah terdaftar!";
    } else {
        // Insert ruangan & lantai kosong sebagai master pendaftaran fakultas
        $stmt = $conn->prepare("INSERT INTO master_ruangan (fakultas, lantai, ruangan) VALUES (?, '', '')");
        $stmt->bind_param("s", $fakultas);
        if ($stmt->execute()) {
            $_SESSION['sukses'] = "Fakultas baru berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambah fakultas.";
        }
    }
    header("Location: lokasi.php");
    exit();
}

// 2. PROSES TAMBAH RUANGAN (DI HALAMAN DETAIL)
if (isset($_POST['tambah_ruangan'])) {
    $fakultas = $_POST['fakultas_hidden'];
    $lantai = trim($_POST['lantai']);
    $ruangan = trim($_POST['ruangan']);
    
    $stmt = $conn->prepare("INSERT INTO master_ruangan (fakultas, lantai, ruangan) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fakultas, $lantai, $ruangan);
    if ($stmt->execute()) {
        $_SESSION['sukses'] = "Ruangan baru berhasil ditambahkan di " . htmlspecialchars($fakultas) . "!";
    } else {
        $_SESSION['error'] = "Gagal menambah ruangan.";
    }
    header("Location: detail_lokasi.php?fakultas=" . urlencode($fakultas));
    exit();
}

// 3. PROSES HAPUS FAKULTAS (BESERTA ISINYA)
if (isset($_GET['hapus_fakultas'])) {
    $fak = $_GET['hapus_fakultas'];

    // Cek apakah ada laporan yang belum selesai di seluruh ruangan fakultas ini
    $cek_laporan = $conn->prepare("
        SELECT COUNT(*) as pending 
        FROM laporan l
        JOIN master_ruangan mr ON l.id_ruangan = mr.id
        WHERE mr.fakultas = ? AND l.status != 'Selesai'
    ");
    $cek_laporan->bind_param("s", $fak);
    $cek_laporan->execute();
    $result_cek = $cek_laporan->get_result()->fetch_assoc();

    if ($result_cek['pending'] > 0) {
        // Kalau masih ada tugas gantung, lempar ke SweetAlert Error
        $_SESSION['error'] = "Tidak bisa dihapus! Masih ada " . $result_cek['pending'] . " laporan perbaikan yang belum 'Selesai' di fakultas ini.";
    } else {
        // Kalau aman (udah Selesai semua atau kosong), baru boleh dihapus
        $stmt = $conn->prepare("DELETE FROM master_ruangan WHERE fakultas = ?");
        $stmt->bind_param("s", $fak);
        if ($stmt->execute()) {
            $_SESSION['sukses'] = "Fakultas dan seluruh ruangannya berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menghapus fakultas.";
        }
    }

    header("Location: lokasi.php");
    exit();
}

// 4. PROSES HAPUS RUANGAN SPESIFIK
if (isset($_GET['hapus_ruangan'])) {
    $id_hapus = $_GET['hapus_ruangan'];
    $fak_redirect = isset($_GET['fak']) ? $_GET['fak'] : '';

    // Cek apakah ada laporan yang belum selesai khusus di ruangan ini
    $cek_laporan = $conn->prepare("
        SELECT COUNT(*) as pending 
        FROM laporan 
        WHERE id_ruangan = ? AND status != 'Selesai'
    ");
    $cek_laporan->bind_param("i", $id_hapus);
    $cek_laporan->execute();
    $result_cek = $cek_laporan->get_result()->fetch_assoc();

    if ($result_cek['pending'] > 0) {
        // Kalau masih ada tugas gantung, lempar ke SweetAlert Error
        $_SESSION['error'] = "Ditolak! Masih ada " . $result_cek['pending'] . " laporan perbaikan yang sedang berjalan di ruangan ini.";
    } else {
        // Kalau aman, hapus ruangan
        $stmt = $conn->prepare("DELETE FROM master_ruangan WHERE id = ?");
        $stmt->bind_param("i", $id_hapus);
        if ($stmt->execute()) {
            $_SESSION['sukses'] = "Ruangan berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat menghapus ruangan.";
        }
    }

    header("Location: detail_lokasi.php?fakultas=" . urlencode($fak_redirect));
    exit();
}

// DATA UNTUK TABEL UTAMA (GROUP BY FAKULTAS)
// NULLIF dipakai supaya fakultas yg belum ada ruangannya (ruangan = '') tetap tampil tapi ruangannya dihitung 0
$query_fakultas = "SELECT fakultas, COUNT(NULLIF(ruangan, '')) as total_ruangan 
                   FROM master_ruangan 
                   GROUP BY fakultas 
                   ORDER BY fakultas ASC";
$res_fakultas = $conn->query($query_fakultas);

// DATA UNTUK TABEL DETAIL (BERDASARKAN GET FAKULTAS)
$res_detail = null;
if (isset($_GET['fakultas'])) {
    $fak_pilih = $_GET['fakultas'];
    // Filter out data dummy pendaftaran awal (ruangan != '')
    $stmt_det = $conn->prepare("SELECT * FROM master_ruangan WHERE fakultas = ? AND ruangan != '' ORDER BY lantai ASC, ruangan ASC");
    $stmt_det->bind_param("s", $fak_pilih);
    $stmt_det->execute();
    $res_detail = $stmt_det->get_result();
}
?>
