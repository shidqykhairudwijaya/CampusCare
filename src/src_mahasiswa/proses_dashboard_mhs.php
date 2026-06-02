<?php
session_start();

// Panggil file koneksi database
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

// Cek apakah user adalah mahasiswa
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../index.php");
    exit();
}

// Ambil id user dari session
$id_user = $_SESSION['user_id'];

// Query statistik laporan mahasiswa
$query_stat = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status IN ('Menunggu', 'Menunggu Respon') THEN 1 ELSE 0 END) as menunggu,
    SUM(CASE WHEN status = 'Diproses' OR status = 'Sedang Dikerjakan' THEN 1 ELSE 0 END) as proses,
    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai
    FROM laporan WHERE id_mahasiswa = ?";

$stmt_stat = $conn->prepare($query_stat);
$stmt_stat->bind_param("i", $id_user);
$stmt_stat->execute();

// Simpan hasil statistik
$stat = $stmt_stat->get_result()->fetch_assoc();

// Array untuk menampung data laporan
$laporan_data = [];

// Query data laporan mahasiswa
$stmt = $conn->prepare("SELECT l.*, t.nama as nama_teknisi, mr.fakultas, mr.lantai, mr.ruangan as nama_ruangan, l.tenggat_waktu
                        FROM laporan l 
                        LEFT JOIN users t ON l.id_teknisi = t.id 
                        LEFT JOIN master_ruangan mr ON l.id_ruangan = mr.id
                        WHERE l.id_mahasiswa = ? 
                        ORDER BY l.tanggal_lapor DESC");
                        
$stmt->bind_param("i", $id_user);
$stmt->execute();

$result = $stmt->get_result();

// Simpan semua data laporan ke array
while($row = $result->fetch_assoc()) {
    $laporan_data[] = $row;
}
?>