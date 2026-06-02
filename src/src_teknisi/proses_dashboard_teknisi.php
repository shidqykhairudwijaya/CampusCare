<?php
require_once dirname(__DIR__, 2) . '/config/koneksi.php';

// Proteksi halaman teknisi
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teknisi') {
    header("Location: ../index.php");
    exit();
}

$id_teknisi = $_SESSION['user_id'];

// Proses terima tugas
if(isset($_POST['terima_tugas'])) {

    $id_laporan = $_POST['id_laporan'];
    $status = 'Sedang Dikerjakan';

    // Update status laporan
    $stmt = $conn->prepare("UPDATE laporan SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id_laporan);
    
    if($stmt->execute()) {
        $_SESSION['sukses'] = "Tugas diterima! Segera selesaikan perbaikan.";
    }

    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

// Proses update status laporan
if(isset($_POST['update_status'])) {

    $id_laporan = $_POST['id_laporan'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan_teknisi'] ?? '';

    // Update status dan catatan teknisi
    $stmt = $conn->prepare("UPDATE laporan SET status = ?, catatan_teknisi = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $catatan, $id_laporan);
    
    if($stmt->execute()) {
        $_SESSION['sukses'] = "Status perbaikan berhasil diperbarui!";
    }

    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

// Ambil data laporan untuk teknisi
$laporan_data = [];

$stmt_data = $conn->prepare("SELECT l.*, mr.fakultas, mr.lantai, mr.ruangan as nama_ruangan 
                        FROM laporan l 
                        LEFT JOIN master_ruangan mr ON l.id_ruangan = mr.id 
                        WHERE l.id_teknisi = ? 
                        ORDER BY FIELD(l.status, 'Menunggu Respon', 'Sedang Dikerjakan', 'Selesai'), l.tanggal_lapor DESC");

$stmt_data->bind_param("i", $id_teknisi);
$stmt_data->execute();

$result_data = $stmt_data->get_result();

// Simpan hasil query ke array
while($row = $result_data->fetch_assoc()) {
    $laporan_data[] = $row;
}

$stmt_data->close();
?>