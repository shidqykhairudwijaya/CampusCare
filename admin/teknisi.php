<?php
// Memanggil proses backend teknisi
require_once '../src/src_admin/proses_teknisi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Teknisi - CampusCare</title>

    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css?v=1.6">
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
            <a href="teknisi.php" class="sidebar-link active text-white bg-maroon mb-2"><i class="bi bi-tools me-2"></i>Kelola Teknisi</a>
            <a href="mahasiswa.php" class="sidebar-link text-white mb-2"><i class="bi bi-people me-2"></i>Kelola Mahasiswa</a>
            <a href="lokasi.php" class="sidebar-link text-white mb-2"><i class="bi bi-geo-alt me-2"></i>Kelola Lokasi</a>
            <hr>
            <a href="../logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
        </div>

        <div class="flex-grow-1 p-4 bg-light w-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 d-flex align-items-center">
                    <button class="btn btn-maroon me-3 d-md-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
                    <span class="fs-4 fs-md-3">Manajemen Data Teknisi</span>
                </h3>
                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 d-none d-md-inline-block">
                    <i class="bi bi-calendar3"></i> <?= date('d M Y') ?>
                </span>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="m-0 fw-bold">Daftar Teknisi Terdaftar</h5>
                    <a href="tambah_teknisi.php" class="btn btn-sm btn-maroon">
                        <i class="bi bi-plus-lg"></i> Tambah Teknisi
                    </a>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Teknisi</th>
                                <th>Keahlian</th> 
                                <th>No. WhatsApp</th>
                                <th>Email</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            
                            // Looping data teknisi dari database
                            if ($res_teknisi && $res_teknisi->num_rows > 0):
                                while($row = $res_teknisi->fetch_assoc()): 
                            ?>
                            <tr >
                                <td><?= $no++ ?></td>
                                <td><span class="fw-bold"><?= htmlspecialchars($row['nama']) ?></span></td>
                                <td><span class="badge-keahlian shadow-sm"><?= htmlspecialchars($row['keahlian'] ?: '-') ?></span></td>
                                <td>
                                    <?php if($row['no_wa']): ?>
                                        <a href="https://wa.me/<?= preg_replace('/^0/', '62', $row['no_wa']) ?>" target="_blank" class="text-decoration-none text-success">
                                            <i class="bi bi-whatsapp"></i> <?= htmlspecialchars($row['no_wa']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><code><?= htmlspecialchars($row['email']) ?></code></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteTeknisi(<?= $row['id'] ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data teknisi.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/script.js?v=1.6"></script>

    <?php if(isset($_SESSION['sukses'])): ?>
        <script>
            Swal.fire({ title: 'Berhasil!', text: '<?= $_SESSION['sukses'] ?>', icon: 'success', confirmButtonColor: '#800000' });
        </script>
        <?php unset($_SESSION['sukses']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({ title: 'Gagal!', text: '<?= $_SESSION['error'] ?>', icon: 'error', confirmButtonColor: '#d33' });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

</body>
</html>
