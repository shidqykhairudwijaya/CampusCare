<?php
session_start();
require_once '../src/src_teknisi/proses_dashboard_teknisi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknisi Dashboard - CampusCare</title>
    
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css?v=1.7">
</head>

<body class="bg-light">

    <div class="d-flex">
        
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="sidebar p-3 bg-dark text-white" id="sidebar" style="width: 250px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-white fw-bold m-0"><i class="bi bi-wrench-adjustable"></i> Area Teknisi</h4>
                <button class="btn btn-sm text-white d-md-none" id="closeSidebar"><i class="bi bi-x-lg"></i></button>
            </div>
            
            <a href="dashboard.php" class="sidebar-link active bg-secondary text-white"><i class="bi bi-wrench me-2"></i> Tugas Saya</a>
            <hr>
            <a href="../logout.php" class="sidebar-link text-warning"><i class="bi bi-box-arrow-left me-2"></i> Logout</a>
        </div>

        <div class="flex-grow-1 p-4 w-100">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <button class="btn btn-maroon me-3 d-md-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
                    <div>
                        <h3 class="fw-bold mb-0 text-dark fs-4 fs-md-3">Daftar Pekerjaan</h3>
                        <p class="text-muted small mb-0">Teknisi: <?= htmlspecialchars($_SESSION['nama']) ?></p>
                    </div>
                </div>
                <div class="text-end d-none d-md-block">
                    <span class="badge bg-white text-dark border shadow-sm px-3 py-2">
                        <i class="bi bi-calendar3"></i> <?= date('d M Y') ?>
                    </span>
                </div>
            </div>

            <div class="d-flex flex-nowrap flex-md-wrap overflow-auto pb-2 pb-md-0 mb-4" id="pillFilterContainer" style="white-space: nowrap;">
                <a class="filter-pill active" onclick="filterTable('all', this)">Semua Tugas</a>
                <a class="filter-pill" onclick="filterTable('Menunggu Respon', this)">Tugas Baru</a>
                <a class="filter-pill" onclick="filterTable('Sedang Dikerjakan', this)">Proses</a>
                <a class="filter-pill" onclick="filterTable('Selesai', this)">Selesai</a>
            </div>

            <div class="card shadow-sm border-0 w-100">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover align-middle mb-0" id="tablePekerjaan">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Fasilitas</th>
                                <th>Lokasi</th>
                                <th>Tenggat Waktu</th>
                                <th>Status</th>
                                <th class="pe-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $today = date('Y-m-d');

                            if(count($laporan_data) > 0):
                                foreach($laporan_data as $row):
                                    $is_late = (!empty($row['tenggat_waktu']) && $row['tenggat_waktu'] < $today && $row['status'] != 'Selesai');
                                    $badge_color = ($row['status'] == 'Menunggu Respon') ? 'danger' : (($row['status'] == 'Sedang Dikerjakan') ? 'warning text-dark' : 'success');
                            ?>
                            <tr class="report-row border-bottom" data-status="<?= $row['status'] ?>" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id'] ?>">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="../uploads/<?= htmlspecialchars($row['foto']) ?>" class="img-table shadow-sm border me-3">
                                        <div>
                                            <span class="fw-bold text-dark d-block"><?= htmlspecialchars($row['nama_fasilitas']) ?></span>
                                            <small class="text-muted text-truncate d-inline-block" style="max-width: 150px;"><?= htmlspecialchars($row['deskripsi']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if(!empty($row['fakultas'])): ?>
                                        <span class="fw-bold text-dark d-block"><i class="bi bi-building text-muted"></i> <?= htmlspecialchars($row['fakultas']) ?></span>
                                        <small class="text-muted">Lt. <?= htmlspecialchars($row['lantai']) ?> | <?= htmlspecialchars($row['nama_ruangan']) ?></small>
                                    <?php else: ?>
                                        <small class="text-dark"><i class="bi bi-geo-alt text-muted"></i> <?= htmlspecialchars($row['lokasi'] ?? '-') ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($row['tenggat_waktu'])): ?>
                                        <span class="badge <?= $is_late ? 'box-late' : 'bg-light text-dark border' ?> px-2 py-1">
                                            <?= $is_late ? '<i class="bi bi-exclamation-circle me-1"></i> TELAT' : date('d M Y', strtotime($row['tenggat_waktu'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-<?= $badge_color ?>"><?= $row['status'] ?></span></td>
                                <td class="pe-4 text-center">
                                    <button class="btn btn-sm btn-outline-dark rounded-pill px-3">Detail</button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr id="noDataDefault"><td colspan="5" class="text-center py-5 text-muted">Belum ada tugas yang ditugaskan kepada Anda.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php 
    if(count($laporan_data) > 0):
        foreach($laporan_data as $row): 
            $is_late = (!empty($row['tenggat_waktu']) && $row['tenggat_waktu'] < $today && $row['status'] != 'Selesai');
            $badge_color = ($row['status'] == 'Menunggu Respon') ? 'danger' : (($row['status'] == 'Sedang Dikerjakan') ? 'warning text-dark' : 'success');
    ?>
    <div class="modal fade" id="modalDetail<?= $row['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header <?= $is_late ? 'bg-danger text-white border-0' : 'bg-light' ?>">
                    <h5 class="modal-title fw-bold">Detail Pekerjaan</h5>
                    <button type="button" class="btn-close <?= $is_late ? 'btn-close-white' : '' ?>" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4">
                    <?php if($is_late): ?>
                        <div class="alert alert-danger py-2 mb-3 small fw-bold shadow-sm">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Peringatan: Tugas ini sudah melewati batas waktu perbaikan!
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-5 mb-3 mb-md-0 text-center">
                            <a href="../uploads/<?= htmlspecialchars($row['foto']) ?>" target="_blank">
                                <img src="../uploads/<?= htmlspecialchars($row['foto']) ?>" class="img-fluid rounded shadow-sm border mb-2 img-clickable" style="max-height: 250px; object-fit: cover; width: 100%;">
                            </a>
                            <small class="text-muted d-block mb-3"><i class="bi bi-zoom-in"></i> Klik gambar untuk ukuran penuh</small>
                            <span class="badge bg-<?= $badge_color ?> w-100 py-2 fs-6 shadow-sm"><?= $row['status'] ?></span>
                        </div>
                        <div class="col-md-7">
                            <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($row['nama_fasilitas']) ?></h5>
                            
                            <div class="mb-3">
                                <p class="text-muted small mb-1">
                                    <?php if(!empty($row['fakultas'])): ?>
                                        <i class="bi bi-building"></i> <?= htmlspecialchars($row['fakultas']) ?> (Lt. <?= $row['lantai'] ?> - <?= $row['nama_ruangan'] ?>)
                                    <?php else: ?>
                                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($row['lokasi'] ?? '-') ?>
                                    <?php endif; ?>
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar-event"></i> Tenggat Waktu: 
                                    <?php if(!empty($row['tenggat_waktu'])): ?>
                                        <span class="fw-bold <?= $is_late ? 'text-danger' : 'text-dark' ?>"><?= date('d F Y', strtotime($row['tenggat_waktu'])) ?></span>
                                    <?php else: ?>
                                        <span class="fw-bold text-dark">Belum ditentukan</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="mb-3 p-3 bg-light rounded border text-dark" style="font-size: 0.9rem;">
                                <small class="fw-bold d-block mb-1 text-muted">Deskripsi Kerusakan:</small>
                                <?= nl2br(htmlspecialchars($row['deskripsi'])) ?>
                            </div>
                            <hr>

                            <?php if($row['status'] == 'Menunggu Respon'): ?>
                                <form method="POST">
                                    <input type="hidden" name="id_laporan" value="<?= $row['id'] ?>">
                                    <button type="submit" name="terima_tugas" class="btn btn-maroon w-100 fw-bold shadow-sm">Terima Tugas Sekarang</button>
                                </form>
                            <?php elseif($row['status'] == 'Sedang Dikerjakan'): ?>
                                <form method="POST">
                                    <input type="hidden" name="id_laporan" value="<?= $row['id'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Status Perbaikan</label>
                                        <select name="status" class="form-select" required>
                                            <option value="Sedang Dikerjakan">Masih Dikerjakan</option>
                                            <option value="Selesai">Selesai (Tuntas)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Catatan Teknisi</label>
                                        <textarea name="catatan_teknisi" class="form-control" rows="3" required placeholder="Jelaskan perbaikan yang dilakukan..."></textarea>
                                    </div>
                                    <button type="submit" name="update_status" class="btn btn-maroon w-100 fw-bold shadow-sm">Simpan Progress</button>
                                </form>
                            <?php else: ?>
                                <div class="p-3 rounded border bg-success bg-opacity-10 text-success">
                                    <small class="fw-bold d-block">Catatan Perbaikan:</small>
                                    <?= nl2br(htmlspecialchars($row['catatan_teknisi'] ?? '-')) ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; endif; ?>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/script.js?v=1.7"></script>
    
    <?php if(isset($_SESSION['sukses'])): ?>
        <script>Swal.fire({ title: 'Berhasil!', text: '<?= addslashes($_SESSION['sukses']) ?>', icon: 'success', confirmButtonColor: '#800000' });</script>
        <?php unset($_SESSION['sukses']); ?>
    <?php endif; ?>
</body>
</html>
