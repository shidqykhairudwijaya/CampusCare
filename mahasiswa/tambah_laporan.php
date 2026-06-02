<?php
// Load file master lokasi
require_once '../src/src_mahasiswa/proses_tambah_laporan_data.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laporan - CampusCare</title>
    
    <link href="../assets/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/style.css?v=1.1">
</head>
<body>
    <div class="d-flex">

        <div class="sidebar p-3 sidebar-light" style="width: 250px;">
            <h4 class="text-maroon fw-bold mb-4"><i class="bi bi-building"></i> CampusCare</h4>

            <a href="dashboard.php" class="sidebar-link"><i class="bi bi-house-door me-2"></i> Dashboard</a>
            <a href="tambah_laporan.php" class="sidebar-link active"><i class="bi bi-plus-circle me-2"></i> Buat Laporan</a>
            <hr>
            <a href="../logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-left me-2"></i> Logout</a>
        </div>

        <div class="flex-grow-1 p-4 bg-light">

            <div class="mb-4">
                <a href="dashboard.php" class="btn btn-outline-secondary btn-sm shadow-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white pt-3">
                    <h5 class="m-0 fw-bold text-maroon">Form Pelaporan Kerusakan</h5>
                </div>

                <div class="card-body">
                    
                    <form id="formLaporan" action="../src/src_mahasiswa/proses_tambah_laporan.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-muted">Nama Fasilitas</label>
                                <input type="text" class="form-control" name="nama_fasilitas" required placeholder="Contoh: AC, Proyektor, Kursi">
                                <div class="invalid-feedback">Nama fasilitas tidak boleh kosong.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-muted">Kategori Kerusakan</label>
                                <select class="form-select" name="kategori" required>
                                    <option value="">Pilih Kategori...</option>
                                    <option value="Elektronik">Elektronik (AC, Lampu, dll)</option>
                                    <option value="Furniture">Furniture (Meja, Kursi, dll)</option>
                                    <option value="Internet">Internet/Jaringan</option>
                                    <option value="Kebersihan">Kebersihan/Bangunan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div class="invalid-feedback">Silakan pilih kategori fasilitas.</div>
                            </div>

                            <div class="col-12 mt-3">
                                <h6 class="fw-bold text-maroon">Titik Lokasi Kerusakan</h6>
                                <hr>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small text-muted">Fakultas</label>
                                <select class="form-select" name="fakultas" id="fakultas" required>
                                    <option value="">Pilih Fakultas...</option>
                                </select>
                                <div class="invalid-feedback">Pilih Fakultas terlebih dahulu.</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small text-muted">Lantai</label>
                                <select class="form-select" name="lantai" id="lantai" required disabled>
                                    <option value="">Pilih Lantai...</option>
                                </select>
                                <div class="invalid-feedback">Pilih Lantai terlebih dahulu.</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small text-muted">Ruangan / Kelas</label>
                                <select class="form-select" name="ruangan" id="ruangan" required disabled>
                                    <option value="">Pilih Ruangan...</option>
                                </select>
                                <div class="invalid-feedback">Pilih Ruangan terlebih dahulu.</div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold small text-muted">Deskripsi Kerusakan</label>
                                <textarea class="form-control" name="deskripsi" rows="3" required placeholder="Jelaskan secara singkat detail kerusakan..."></textarea>
                                <div class="invalid-feedback">Mohon berikan deskripsi kerusakan.</div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold small text-muted">Upload Foto Bukti</label>
                                <div class="mb-2">
                                    <img class="img-preview img-fluid rounded shadow-sm" style="display:none; max-height: 200px; object-fit: cover;">
                                </div>
                                <input class="form-control" type="file" id="foto" name="foto" required onchange="previewImage()" accept="image/*">
                                <div class="invalid-feedback">Bukti foto wajib diunggah.</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold shadow-sm">
                            <i class="bi bi-send"></i> Kirim Laporan
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    
    <script>window.dataMasterLokasi = <?= json_encode($data_lokasi) ?>;</script>
    <script src="../assets/script.js?v=2.0"></script>
    
    <?php if(isset($_SESSION['sukses'])): ?>
        <script>
            Swal.fire({ title: 'Mantap!', text: '<?= addslashes($_SESSION['sukses']) ?>', icon: 'success', confirmButtonColor: '#800000' });
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
