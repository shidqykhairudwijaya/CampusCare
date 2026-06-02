<?php
// Load proses dashboard mahasiswa
require_once '../src/src_mahasiswa/proses_dashboard_mhs.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - CampusCare</title>
    
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css?v=1.8">
</head>

<body class="bg-light">
    <div class="d-flex">
        
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="sidebar p-3" id="sidebar" style="width: 250px; background-color: white;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-maroon fw-bold m-0"><i class="bi bi-building"></i> CampusCare</h4>
                <button class="btn btn-sm text-dark d-md-none" id="closeSidebar"><i class="bi bi-x-lg"></i></button>
            </div>
            
            <a href="dashboard.php" class="sidebar-link active"><i class="bi bi-house-door me-2"></i> Dashboard</a>
            <a href="tambah_laporan.php" class="sidebar-link"><i class="bi bi-plus-circle me-2"></i> Buat Laporan</a>
            <hr>
            <a href="../logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-left me-2"></i> Logout</a>
        </div>

        <div class="flex-grow-1 p-4 bg-light w-100">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <button class="btn btn-maroon me-3 d-md-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
                    <h3 class="m-0 fs-4 fs-md-3">Selamat datang, <?= htmlspecialchars($_SESSION['nama'] ?? 'Mahasiswa') ?></h3>
                </div>
                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 d-none d-md-inline-block">
                    <?= date('l, d M Y') ?>
                </span>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="card stat-card h-100 shadow-sm p-3 border-start border-secondary border-4" onclick="filterTable('all')">
                        <h6 class="text-muted">Total Laporan</h6>
                        <h3 class="text-dark fw-bold"><?= $stat['total'] ?? 0 ?></h3>
                        <small class="text-muted">Klik untuk lihat semua</small>
                    </div>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="card stat-card h-100 shadow-sm p-3 border-start border-danger border-4" onclick="filterTable('Menunggu')">
                        <h6 class="text-muted">Menunggu</h6>
                        <h3 class="text-danger fw-bold"><?= $stat['menunggu'] ?? 0 ?></h3>
                        <small class="text-muted">Klik untuk filter</small>
                    </div>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="card stat-card h-100 shadow-sm p-3 border-start border-warning border-4" onclick="filterTable('Sedang Dikerjakan')">
                        <h6 class="text-muted">Sedang Dikerjakan</h6>
                        <h3 class="text-warning fw-bold"><?= $stat['proses'] ?? 0 ?></h3>
                        <small class="text-muted">Klik untuk filter</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card h-100 shadow-sm p-3 border-start border-success border-4" onclick="filterTable('Selesai')">
                        <h6 class="text-muted">Selesai</h6>
                        <h3 class="text-success fw-bold"><?= $stat['selesai'] ?? 0 ?></h3>
                        <small class="text-muted">Klik untuk filter</small>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 w-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="m-0 fw-bold">Riwayat Laporan Saya <span id="filterLabel" class="badge bg-secondary ms-2" style="display:none;"></span></h5>
                    <a href="tambah_laporan.php" class="btn btn-sm btn-maroon">
                        <i class="bi bi-plus-lg"></i> Lapor Baru
                    </a>
                </div>

                <div class="card-body table-responsive pt-0">
                    <table class="table align-middle" id="mhsTableLaporan">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Fasilitas</th>
                                <th>Lokasi</th>
                                <th>Foto</th> 
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Catatan Teknisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($laporan_data) && count($laporan_data) > 0):
                                $no = 1;
                                foreach($laporan_data as $row): 
                                    $raw_status = $row['status'];
                                    
                                    // Penyesuaian warna badge
                                    if ($raw_status == 'Menunggu' || $raw_status == 'Menunggu Respon') {
                                        $display_status = 'Menunggu';
                                        $badge_color = 'danger';
                                    } elseif ($raw_status == 'Diproses' || $raw_status == 'Sedang Dikerjakan') {
                                        $display_status = 'Sedang Dikerjakan';
                                        $badge_color = 'warning text-dark';
                                    } else {
                                        $display_status = 'Selesai';
                                        $badge_color = 'success';
                                    }
                            ?>
                            <tr class="report-row border-bottom" data-status="<?= $display_status ?>" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id'] ?>">
                                <td><?= $no++ ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($row['nama_fasilitas']) ?></span><br>
                                    <small class="text-muted"><?= htmlspecialchars($row['kategori']) ?></small>
                                </td>
                                <td>
                                    <?php if(!empty($row['fakultas'])): ?>
                                        <i class="bi bi-building text-muted"></i> <span class="fw-bold text-dark"><?= htmlspecialchars($row['fakultas']) ?></span>
                                    <?php else: ?>
                                        <i class="bi bi-geo-alt text-muted"></i> <?= htmlspecialchars($row['lokasi'] ?? 'Lokasi lama') ?>
                                    <?php endif; ?>
                                </td>
                                <td><img src="../uploads/<?= htmlspecialchars($row['foto']) ?>" class="img-table shadow-sm border object-fit-cover" alt="Foto"></td>
                                <td><?= date('d M Y', strtotime($row['tanggal_lapor'])) ?></td>
                                <td><span class="badge bg-<?= $badge_color ?>"><?= $display_status ?></span></td>
                                <td>
                                    <small class="text-muted fst-italic text-truncate d-inline-block" style="max-width: 150px;">
                                        <?= htmlspecialchars($row['catatan_teknisi'] ?: 'Belum ada catatan') ?>
                                    </small>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr id="noDataDefault">
                                <td colspan="7" class="text-center py-4 text-muted">Anda belum pernah membuat laporan.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php 
    if(isset($laporan_data)): foreach($laporan_data as $row): 
        $raw_status = $row['status'];
        if ($raw_status == 'Menunggu' || $raw_status == 'Menunggu Respon') {
            $display_status = 'Menunggu';
            $badge_color = 'danger';
        } elseif ($raw_status == 'Diproses' || $raw_status == 'Sedang Dikerjakan') {
            $display_status = 'Sedang Dikerjakan';
            $badge_color = 'warning text-dark';
        } else {
            $display_status = 'Selesai';
            $badge_color = 'success';
        }
    ?>
    <div class="modal fade" id="detailModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-maroon"><i class="bi bi-info-circle-fill me-2"></i> Detail Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-5 text-center mb-3 mb-md-0">
                            <img src="../uploads/<?= htmlspecialchars($row['foto']) ?>" class="img-fluid rounded shadow-sm border mb-3 img-clickable" style="max-height: 250px; object-fit: cover; width: 100%; cursor: pointer;" onclick="openImageNewTab('../uploads/<?= htmlspecialchars($row['foto']) ?>')">
                            <div><span class="badge bg-<?= $badge_color ?> px-4 py-2 fs-6 w-100 shadow-sm"><?= $display_status ?></span></div>
                        </div>

                        <div class="col-md-7">
                            <table class="table table-borderless table-sm mb-3">
                                <tr>
                                    <td width="35%" class="text-muted fw-bold">Fasilitas</td>
                                    <td>: <span class="fw-bold text-dark"><?= htmlspecialchars($row['nama_fasilitas']) ?></span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Kategori</td>
                                    <td>: <span class="badge border border-secondary text-secondary"><?= htmlspecialchars($row['kategori']) ?></span></td>
                                </tr>
                                <?php if(!empty($row['fakultas'])): ?>
                                    <tr>
                                        <td class="text-muted fw-bold">Fakultas</td>
                                        <td>: <span class="fw-bold text-dark"><?= htmlspecialchars($row['fakultas']) ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-bold">Lantai</td>
                                        <td>: <span class="fw-bold text-dark"><?= htmlspecialchars($row['lantai']) ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted fw-bold">Ruangan/Kelas</td>
                                        <td>: <span class="fw-bold text-dark"><?= htmlspecialchars($row['nama_ruangan']) ?></span></td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td class="text-muted fw-bold">Lokasi</td>
                                        <td>: <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($row['lokasi'] ?? '-') ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-muted fw-bold">Waktu Laporan</td>
                                    <td>: <i class="bi bi-clock"></i> <?= date('d F Y, H:i', strtotime($row['tanggal_lapor'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-bold">Tenggat Waktu</td>
                                    <td>: <i class="bi bi-calendar-event"></i>
                                        <?= !empty($row['tenggat_waktu']) ? htmlspecialchars(date('d F Y', strtotime($row['tenggat_waktu']))) : 'Belum ditetapkan' ?>
                                    </td>
                                </tr>
                            </table>

                            <div class="mb-3">
                                <h6 class="fw-bold text-muted mb-2 small text-uppercase">Deskripsi:</h6>
                                <div class="p-3 bg-light rounded border text-dark" style="font-size: 0.9rem;">
                                    <?= nl2br(htmlspecialchars($row['deskripsi'])) ?>
                                </div>
                            </div>

                            <?php if(!empty($row['catatan_teknisi'])): ?>
                                <div class="mb-2">
                                    <h6 class="fw-bold text-success mb-2 small text-uppercase"><i class="bi bi-tools me-1"></i> Catatan Teknisi:</h6>
                                    <div class="p-3 bg-success bg-opacity-10 rounded border border-success text-dark" style="font-size: 0.9rem;">
                                        <?= nl2br(htmlspecialchars($row['catatan_teknisi'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; endif; ?>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/script.js?v=1.8"></script>

    <?php if(isset($_SESSION['sukses'])): ?>
    <script>
        Swal.fire({ title: 'Mantap!', text: '<?= addslashes($_SESSION['sukses']) ?>', icon: 'success', confirmButtonColor: '#800000' });
    </script>
    <?php unset($_SESSION['sukses']); endif; ?>

</body>
</html>
