<?php 
// Load file proses
require_once '../src/src_admin/proses_lokasi.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lokasi - FasilkomCare</title>
    
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css?v=1.4">
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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 d-flex align-items-center">
                    <button class="btn btn-maroon me-3 d-md-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
                    <span class="fs-4 fs-md-3">Manajemen Lokasi</span>
                </h3>
                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 d-none d-md-inline-block">
                    <i class="bi bi-calendar3"></i> <?= date('d M Y') ?>
                </span>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0">
                    <h5 class="m-0 fw-bold">Daftar Fakultas Terdaftar</h5>
                    
                    <button type="button" class="btn btn-sm btn-maroon shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFakultas">
                        <i class="bi bi-plus-lg"></i> Tambah Fakultas
                    </button>
                </div>

                <div class="card-body table-responsive pt-0">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Fakultas</th>
                                <th>Total Ruangan</th>
                                <th class="text-center" width="15%">Aksi</th>
                                <th class="text-center" width="10%">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;

                            // Looping data fakultas
                            if ($res_fakultas && $res_fakultas->num_rows > 0):
                                while($row = $res_fakultas->fetch_assoc()): 
                            ?>
                            <tr class="report-row border-bottom" onclick="window.location.href='detail_lokasi.php?fakultas=<?= urlencode($row['fakultas']) ?>'">
                                <td><?= $no++ ?></td>
                                <td><strong class="text-maroon"><?= htmlspecialchars($row['fakultas']) ?></strong></td>
                                <td><span class="badge bg-info text-dark shadow-sm px-3"><?= $row['total_ruangan'] ?> Ruangan</span></td>
                                
                                <td class="text-center" onclick="event.stopPropagation();">
                                    <a href="detail_lokasi.php?fakultas=<?= urlencode($row['fakultas']) ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-gear-fill"></i> Kelola
                                    </a>
                                </td>
                                
                                <td class="text-center" onclick="event.stopPropagation();">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteFakultas('<?= addslashes($row['fakultas']) ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted fst-italic">Belum ada data fakultas.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFakultas" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                
                <form action="" method="POST">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-maroon">Tambah Fakultas Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nama Fakultas</label>
                            <input type="text" class="form-control" name="nama_fakultas" required placeholder="Contoh: Fakultas Ilmu Komputer">
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_fakultas" class="btn btn-maroon px-4 fw-bold">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/script.js?v=1.3"></script>

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
