<?php
session_start();

// Cek session admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Teknisi - CampusCare</title>
    
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

            <div class="d-flex align-items-center mb-4">
                <button class="btn btn-maroon me-3 d-md-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
                <a href="teknisi.php" class="btn btn-outline-secondary btn-sm shadow-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <div class="card shadow-sm border-0 mb-4 w-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="m-0 fw-bold text-maroon"><i class="bi bi-person-plus"></i> Form Tambah Teknisi Baru</h5>
                </div>

                <div class="card-body p-md-4">
                    
                    <?php if(isset($_SESSION['error_form'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= htmlspecialchars($_SESSION['error_form']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error_form']); ?>
                    <?php endif; ?>

                    <form action="../src/src_admin/proses_tambah_teknisi.php" method="POST" class="needs-validation" novalidate>
                        
                        <h6 class="fw-bold text-muted border-bottom pb-2 mb-3">Informasi Profil</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-lg fs-6" name="nama" required placeholder="Contoh: Budi Santoso">
                                <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-muted">Nomor WhatsApp</label>
                                <input type="text" class="form-control form-control-lg fs-6" name="no_wa" required placeholder="Contoh: 081234567890">
                                <div class="invalid-feedback">Nomor WhatsApp wajib diisi.</div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold small text-muted">Keahlian Utama</label>
                                <select class="form-select form-select-lg fs-6" name="keahlian" required>
                                    <option value="">Pilih Keahlian...</option>
                                    <option value="Elektronik">Elektronik (AC, Lampu, Kelistrikan)</option>
                                    <option value="Furniture">Furniture (Meja, Kursi, Lemari)</option>
                                    <option value="Internet">Internet/Jaringan (WiFi, Kabel LAN)</option>
                                    <option value="Kebersihan">Kebersihan/Bangunan (Bocor, Pipa, dll)</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div class="invalid-feedback">Silakan pilih salah satu keahlian.</div>
                            </div>
                            
                            <div class="col-12"><hr class="mb-4"></div>

                            <h6 class="fw-bold text-muted border-bottom pb-2 mb-3 mt-2">Kredensial Login Akun</h6>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small text-muted">Email (Username)</label>
                                <input type="email" class="form-control form-control-lg fs-6" name="email" required placeholder="teknisi@campuscare.com">
                                <div class="invalid-feedback">Masukkan alamat email yang valid.</div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small text-muted">Password</label>
                                <input type="password" class="form-control form-control-lg fs-6" name="password" required placeholder="Buat password login">
                                <div class="invalid-feedback">Password wajib diisi untuk keamanan akun.</div>
                            </div>
                        </div>
                        
                        <div class="mt-2">
                            <button type="submit" class="btn btn-maroon w-100 py-3 fw-bold shadow-sm fs-6">
                                <i class="bi bi-save me-2"></i> Daftarkan Teknisi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/script.js?v=1.6"></script>

</body>
</html>
