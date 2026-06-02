<?php
// Load file proses mahasiswa
require_once '../src/src_admin/proses_mahasiswa.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mahasiswa - FasilkomCare</title>
    
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
            <a href="mahasiswa.php" class="sidebar-link active text-white bg-maroon mb-2"><i class="bi bi-people me-2"></i>Kelola Mahasiswa</a>
            <a href="lokasi.php" class="sidebar-link text-white mb-2"><i class="bi bi-geo-alt me-2"></i>Kelola Lokasi</a>
            <hr>
            <a href="../logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
        </div>

        <div class="flex-grow-1 p-4 bg-light w-100">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 d-flex align-items-center">
                    <button class="btn btn-maroon me-3 d-md-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
                    <span class="fs-4 fs-md-3">Manajemen Data Mahasiswa</span>
                </h3>
                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 d-none d-md-inline-block">
                    <i class="bi bi-calendar3"></i> <?= date('d M Y') ?>
                </span>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0">
                    <h5 class="m-0 fw-bold">Daftar Mahasiswa Aktif</h5>
                    
                    <a href="tambah_mahasiswa.php" class="btn btn-sm btn-maroon shadow-sm">
                        <i class="bi bi-plus-lg"></i> Tambah Mahasiswa
                    </a>
                </div>

                <div class="card-body table-responsive pt-0">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Email NPM</th>
                                <th>Kelas</th>
                                <th width="100px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;

                            // Looping data mahasiswa
                            if ($res_mhs && $res_mhs->num_rows > 0):
                                while($row = $res_mhs->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                                <td><code><?= htmlspecialchars($row['email']) ?></code></td>
                                <td><span class="badge bg-secondary px-3"><?= htmlspecialchars($row['kelas']) ?></span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteMahasiswa(<?= $row['id'] ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data mahasiswa terdaftar.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/script.js?v=1.4"></script>

    <?php if(isset($_SESSION['sukses'])): ?>
        <script>
            Swal.fire({ title: 'Berhasil!', text: '<?= addslashes($_SESSION['sukses']) ?>', icon: 'success', confirmButtonColor: '#800000' });
        </script>
        <?php unset($_SESSION['sukses']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({ title: 'Gagal Hapus!', text: '<?= addslashes($_SESSION['error']) ?>', icon: 'error', confirmButtonColor: '#d33' });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

</body>
</html>
