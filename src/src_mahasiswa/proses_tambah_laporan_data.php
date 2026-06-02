<?php
session_start();
// Panggil koneksi ke database menggunakan path absolut agar aman
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

// Proteksi Halaman: Pastikan session sudah ada sebelum cek role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../index.php");
    exit();
}

// 1. AMBIL DATA MASTER RUANGAN UNTUK DROPDOWN
$query_lokasi = "SELECT id, fakultas, lantai, ruangan FROM master_ruangan WHERE ruangan != '' ORDER BY fakultas ASC, lantai ASC, ruangan ASC";
$result_lokasi = $conn->query($query_lokasi);

// 2. SUSUN DATA JADI ARRAY BERLAPIS
$data_lokasi = [];
if ($result_lokasi && $result_lokasi->num_rows > 0) {
    while ($row = $result_lokasi->fetch_assoc()) {
        $fak = $row['fakultas'];
        $lan = $row['lantai'];
        $rua_nama = $row['ruangan'];
        $rua_id = $row['id'];
        
        if (!isset($data_lokasi[$fak])) {
            $data_lokasi[$fak] = [];
        }
        if (!isset($data_lokasi[$fak][$lan])) {
            $data_lokasi[$fak][$lan] = [];
        }
        $data_lokasi[$fak][$lan][] = array("id" => $rua_id, "nama" => $rua_nama);
    }
}
?>
