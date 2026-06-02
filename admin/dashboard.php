<?php 
require_once '../src/src_admin/proses_dashboard_admin.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CampusCare</title>
    
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="../assets/style.css?v=1.1">
    
</head>

<body class="bg-light">

    <div class="d-flex">
        
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="sidebar p-3 bg-dark text-white" id="sidebar" style="width: 250px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-white fw-bold m-0">
                    <i class="bi bi-building"></i> AdminCare
                </h4>

                <button class="btn btn-sm text-white d-md-none" id="closeSidebar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <a href="dashboard.php" class="sidebar-link active text-white bg-maroon mb-2">
                <i class="bi bi-list-task me-2"></i>Semua Laporan
            </a>

            <a href="teknisi.php" class="sidebar-link text-white mb-2">
                <i class="bi bi-tools me-2"></i>Kelola Teknisi
            </a>

            <a href="mahasiswa.php" class="sidebar-link text-white mb-2">
                <i class="bi bi-people me-2"></i>Kelola Mahasiswa
            </a>

            <a href="lokasi.php" class="sidebar-link text-white mb-2">
                <i class="bi bi-geo-alt me-2"></i>Kelola Lokasi
            </a>

            <hr>

            <a href="../logout.php" class="sidebar-link text-danger">
                <i class="bi bi-box-arrow-left me-2"></i>Logout
            </a>
        </div>

        <div class="flex-grow-1 p-4 bg-light w-100">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="m-0 d-flex align-items-center">

                    <button class="btn btn-maroon me-3" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <span class="fs-4 fs-md-3">Dashboard Administrator</span>
                </h3>

                <span class="badge bg-white text-dark border shadow-sm px-3 py-2 d-none d-md-inline-block">
                    <i class="bi bi-calendar3"></i> <?= date('d M Y') ?>
                </span>
            </div>

            <div class="card shadow-sm border-0 mb-5 w-100">

                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                        <h5 class="m-0 fw-bold mb-3 mb-md-0">
                            <i class="bi bi-megaphone me-2"></i> Daftar Laporan Kerusakan
                        </h5>
                        
                        <div class="d-flex flex-nowrap flex-md-wrap overflow-auto pb-2 pb-md-0" 
                             id="pillFilterContainer" 
                             style="white-space: nowrap;">

                            <a class="filter-pill active" onclick="filterTable('all', this)">Semua</a>
                            <a class="filter-pill" onclick="filterTable('Menunggu', this)">Baru Masuk</a>
                            <a class="filter-pill" onclick="filterTable('Menunggu Respon', this)">Menunggu Respon</a>
                            <a class="filter-pill" onclick="filterTable('Diproses', this)">Diproses</a>
                            <a class="filter-pill" onclick="filterTable('Selesai', this)">Selesai</a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body table-responsive pt-0">

                    <table class="table align-middle" id="tableLaporan">

                        <thead class="table-light">
                            <tr>
                                <th>Pelapor</th>
                                <th>Kategori</th>
                                <th>Fasilitas</th>
                                <th>Lokasi</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Teknisi</th>
                                <th width="120px">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            // Mengecek apakah ada data laporan
                            if (count($laporan_data) > 0):

                                // Loop semua data laporan
                                foreach($laporan_data as $row): 

                                    $raw_status = $row['status'];
                                    $badge = 'bg-secondary';
                                    $filter_status = $raw_status; 

                                    // Menentukan warna badge status
                                    if($raw_status == 'Menunggu') {
                                        $badge = 'bg-danger';

                                    } elseif($raw_status == 'Menunggu Respon') {
                                        $badge = 'bg-info text-dark shadow-sm'; 

                                    } elseif($raw_status == 'Diproses' || $raw_status == 'Sedang Dikerjakan') {
                                        $badge = 'bg-warning text-dark';
                                        $filter_status = 'Diproses'; 

                                    } elseif($raw_status == 'Selesai') {
                                        $badge = 'bg-success';
                                    }
                            ?>

                            <tr class="report-row border-bottom" 
                                data-status="<?= $filter_status ?>" 
                                onclick="showDetail(event, <?= $row['id'] ?>)">

                                <td>
                                    <strong><?= htmlspecialchars($row['nama_pelapor']) ?></strong><br>
                                    <small class="text-muted">
                                        <?= date('d/m/y H:i', strtotime($row['tanggal_lapor'])) ?>
                                    </small>
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= $row['kategori'] ?>
                                    </span>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($row['nama_fasilitas']) ?></strong>
                                </td>

                                <td>

                                    <?php if(!empty($row['fakultas'])): ?>

                                        <small class="text-muted">
                                            <i class="bi bi-building"></i>
                                        </small>

                                        <span class="fw-bold text-dark">
                                            <?= htmlspecialchars($row['fakultas']) ?>
                                        </span><br>

                                        <small class="text-muted">
                                            Lt. <?= htmlspecialchars($row['lantai']) ?> | 
                                            <?= htmlspecialchars($row['nama_ruangan']) ?>
                                        </small>

                                    <?php else: ?>

                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt"></i> 
                                            <?= htmlspecialchars($row['lokasi'] ?? 'Lokasi lama') ?>
                                        </small>

                                    <?php endif; ?>
                                </td>

                                <td onclick="event.stopPropagation();">
                                    <img src="../uploads/<?= $row['foto'] ?>" 
                                         width="60" 
                                         height="60" 
                                         class="rounded border shadow-sm object-fit-cover">
                                </td>

                                <td>
                                    <span class="badge <?= $badge ?> shadow-sm">
                                        <?= $raw_status ?>
                                    </span>
                                </td>

                                <td>
                                    <?= $row['nama_teknisi'] 
                                        ? '<span class="text-primary fw-bold small"><i class="bi bi-person-gear"></i> '.$row['nama_teknisi'].'</span>' 
                                        : '<span class="text-muted small fst-italic">Belum ada</span>' ?>
                                </td>
                                
                                <td onclick="event.stopPropagation();">

                                    <?php if($row['status'] == 'Menunggu'): ?>

                                        <button class="btn btn-sm btn-maroon w-100" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#assignModal<?= $row['id'] ?>">

                                            <i class="bi bi-person-plus"></i> Assign
                                        </button>

                                    <?php elseif($row['status'] == 'Menunggu Respon' || 
                                                  $row['status'] == 'Sedang Dikerjakan' || 
                                                  $row['status'] == 'Diproses'): ?>

                                        <button class="btn btn-sm btn-outline-dark w-100" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#assignModal<?= $row['id'] ?>">

                                            <i class="bi bi-arrow-repeat"></i> Reassign
                                        </button>

                                    <?php elseif($row['status'] == 'Selesai'): ?>

                                        <button class="btn btn-sm btn-danger w-100" 
                                                onclick="confirmDelete(<?= $row['id'] ?>)">

                                            <i class="bi bi-trash"></i> Hapus
                                        </button>

                                    <?php endif; ?>
                                </td>
                            </tr>

                            <?php endforeach; else: ?>

                            <tr id="noDataDefault">
                                <td colspan="8" class="text-center py-5 text-muted">
                                    Belum ada laporan kerusakan yang masuk.
                                </td>
                            </tr>

                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php 
    // Looping ulang data laporan khusus untuk merender form Modal HTML-nya per ID laporan
    if (count($laporan_data) > 0):
        foreach($laporan_data as $row): 
            $raw_status = $row['status'];
            $badge = ($raw_status == 'Menunggu') ? 'bg-danger' : (($raw_status == 'Menunggu Respon') ? 'bg-info text-dark shadow-sm' : (($raw_status == 'Selesai') ? 'bg-success' : 'bg-warning text-dark'));
    ?>
    
    <div class="modal fade" id="detailModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-maroon"><i class="bi bi-info-circle-fill me-2"></i> Detail Lengkap Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-5 text-center mb-3 mb-md-0">
                            <a href="../uploads/<?= htmlspecialchars($row['foto']) ?>" target="_blank">
                                <img src="../uploads/<?= htmlspecialchars($row['foto']) ?>" 
                                     class="img-fluid rounded shadow-sm border mb-2 img-zoomable" 
                                     style="max-height: 250px; object-fit: cover; width: 100%;" 
                                     title="Klik untuk melihat ukuran penuh">
                            </a>
                            <small class="text-muted d-block mb-3"><i class="bi bi-zoom-in"></i> Klik gambar untuk ukuran penuh</small>
                            <div><span class="badge <?= $badge ?> px-4 py-2 fs-6 w-100"><?= $raw_status ?></span></div>
                        </div>
                        <div class="col-md-7">
                            <table class="table table-borderless table-sm mb-3">
                                <tr><td width="35%" class="text-muted fw-bold">Pelapor</td><td>: <span class="fw-bold text-dark"><?= htmlspecialchars($row['nama_pelapor']) ?></span></td></tr>
                                <tr><td class="text-muted fw-bold">Waktu Lapor</td><td>: <i class="bi bi-clock"></i> <?= date('d F Y, H:i', strtotime($row['tanggal_lapor'])) ?></td></tr>
                                <tr><td class="text-muted fw-bold">Fasilitas</td><td>: <span class="fw-bold text-dark"><?= htmlspecialchars($row['nama_fasilitas']) ?></span></td></tr>
                                <tr><td class="text-muted fw-bold">Kategori</td><td>: <span class="badge border border-secondary text-secondary"><?= htmlspecialchars($row['kategori']) ?></span></td></tr>
                                
                                <?php if(!empty($row['fakultas'])): ?>
                                    <tr><td class="text-muted fw-bold">Fakultas</td><td>: <span class="fw-bold text-dark"><?= htmlspecialchars($row['fakultas']) ?></span></td></tr>
                                    <tr><td class="text-muted fw-bold">Lantai</td><td>: <span class="fw-bold text-dark"> <?= htmlspecialchars($row['lantai']) ?></span></td></tr>
                                    <tr><td class="text-muted fw-bold">Ruangan</td><td>: <span class="fw-bold text-dark"><?= htmlspecialchars($row['nama_ruangan']) ?></span></td></tr>
                                <?php else: ?>
                                    <tr><td class="text-muted fw-bold">Lokasi</td><td>: <span class="fw-bold text-dark"><?= htmlspecialchars($row['lokasi'] ?? '-') ?></span></td></tr>
                                <?php endif; ?>
                            </table>
                            <div class="mb-3">
                                <h6 class="fw-bold text-muted mb-2 small text-uppercase">Deskripsi Kerusakan:</h6>
                                <div class="p-3 bg-light rounded border text-dark" style="font-size: 0.9rem; text-align: justify;"><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignModal<?= $row['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form method="POST">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-maroon">Tugaskan Teknisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="id_laporan" value="<?= $row['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Pilih Teknisi:</label>
                            <select name="id_teknisi" class="form-select" required>
                                <option value="">-- Pilih Teknisi --</option>
                                <?php foreach($teknisi_data as $t): ?>
                                    <option value="<?= $t['id'] ?>" <?= ($row['id_teknisi'] == $t['id']) ? 'selected' : '' ?>><?= $t['nama'] ?> (<?= $t['keahlian'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold small">Tenggat Waktu:</label>
                            <input type="date" name="tenggat_waktu" class="form-control" required value="<?= $row['tenggat_waktu'] ?>">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" name="assign_teknisi" class="btn btn-maroon px-4 fw-bold shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; endif; ?>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/script.js?v=2.0"></script>

    <?php if(isset($_SESSION['sukses'])): ?>
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: '<?= htmlspecialchars($_SESSION['sukses']) ?>',
            icon: 'success',
            confirmButtonColor: '#800000'
        });
    </script>

    <?php unset($_SESSION['sukses']); endif; ?>

</body>
</html>
