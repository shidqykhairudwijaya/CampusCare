<?php 
// Load file proses
require_once '../src/src_admin/proses_lokasi.php'; 

// Cek parameter fakultas di URL
if (!isset($_GET['fakultas'])) {
    header("Location: lokasi.php");
    exit();
}
$nama_fakultas = $_GET['fakultas'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lokasi - CampusCare</title>

    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css?v=1.3">
</head>

<body class="bg-light">

    <div class="d-flex">
        
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="sidebar p-3 bg-dark text-white" id="sidebar" style="width: 250px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-white fw-bold m-0"><i class="bi bi-building"></i> AdminCare</h4>
                <button class="btn btn-sm text-white d-md-none" id="closeSidebar"><i class="bi bi-x-lg"></i></button>
            </div>

            <a href="dashboard.php" class="sidebar-link text-white mb-2"><i class="bi bi-list-task me-2"></i>Semua Laporan</a>
            <a href="teknisi.php" class="sidebar-link text-white mb-2"><i class="bi bi-tools me-2"></i>Kelola Teknisi</a>
            <a href="mahasiswa.php" class="sidebar-link text-white mb-2"><i class="bi bi-people me-2"></i>Kelola Mahasiswa</a>
            <a href="lokasi.php" class="sidebar-link active text-white bg-maroon mb-2"><i class="bi bi-geo-alt me-2"></i>Kelola Lokasi</a>
            <hr>
            <a href="../logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
        </div>

        <div class="flex-grow-1 p-4 bg-light w-100">
            
            <div class="d-flex align-items-center mb-4">
                <button class="btn btn-maroon me-3 d-md-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
                <a href="lokasi.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Fakultas</a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0">
                    <h5 class="m-0 fw-bold">Ruangan di <span class="text-maroon"><?= htmlspecialchars($nama_fakultas) ?></span></h5>
                    
                    <button type="button" class="btn btn-sm btn-maroon" data-bs-toggle="modal" data-bs-target="#modalRuangan">
                        <i class="bi bi-plus-lg"></i> Tambah Ruangan
                    </button>
                </div>

                <div class="card-body table-responsive pt-0">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Lantai</th>
                                <th>Daftar Ruangan / Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Kelompokkan data per lantai
                            $grouped_data = [];
                            if ($res_detail && $res_detail->num_rows > 0) {
                                while($row = $res_detail->fetch_assoc()) {
                                    $grouped_data[$row['lantai']][] = $row;
                                }
                            }
                            
                            // Urutkan lantai dari terkecil
                            ksort($grouped_data);

                            $no = 1;
                            
                            // Tampilkan data per lantai
                            if (!empty($grouped_data)):
                                foreach($grouped_data as $lantai => $ruangan_list): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span class="badge border border-secondary text-secondary px-3 py-2">Lantai <?= htmlspecialchars($lantai) ?></span></td>
                                
                                <td>
                                    <?php foreach($ruangan_list as $r): ?>
                                        <span class="badge bg-light text-dark border p-2 me-1 mb-1 shadow-sm fs-6 fw-normal">
                                            <?= htmlspecialchars($r['ruangan']) ?>
                                            
                                            <i class="bi bi-x-circle-fill text-danger ms-2 btn-delete-badge" 
                                               title="Hapus Ruangan Ini"
                                               onclick="confirmDeleteRuangan(<?= $r['id'] ?>, '<?= htmlspecialchars($nama_fakultas, ENT_QUOTES) ?>')"></i>
                                        </span>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Fakultas ini belum memiliki data ruangan.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRuangan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                
                <form action="" method="POST">
                    
                    <input type="hidden" name="fakultas_hidden" value="<?= htmlspecialchars($nama_fakultas) ?>">
                    
                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title fw-bold text-maroon">Tambah Ruangan Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Pilih Lantai</label>
                            <select class="form-select" name="lantai" required>
                                <option value="">-- Pilih Lantai Berapa --</option>
                                <option value="1">Lantai 1</option>
                                <option value="2">Lantai 2</option>
                                <option value="3">Lantai 3</option>
                                <option value="4">Lantai 4</option>
                                <option value="5">Lantai 5</option>
                                <option value="6">Lantai 6</option>
                                <option value="7">Lantai 7</option>
                                <option value="8">Lantai 8</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold text-muted small">Angka/Nama Ruangan</label>
                            <input type="text" class="form-control" name="ruangan" required placeholder="Contoh: 4.76 atau Lab Komputer">
                        </div>
                    </div>

                    <div class="modal-footer border-top-0 bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_ruangan" class="btn btn-maroon fw-bold px-4">Simpan Ruangan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/script.js?v=1.2"></script>

    <?php if(isset($_SESSION['sukses'])): ?>
        <script>
            Swal.fire({ title: 'Berhasil!', text: '<?= addslashes($_SESSION['sukses']) ?>', icon: 'success', confirmButtonColor: '#800000' });
        </script>
        <?php unset($_SESSION['sukses']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({ title: 'Gagal!', text: '<?= addslashes($_SESSION['error']) ?>', icon: 'error', confirmButtonColor: '#d33' });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

</body>
</html>
