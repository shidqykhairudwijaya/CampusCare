<?php
session_start();

// Cek akses via email
if (!isset($_SESSION['reset_email'])) {
    header("Location: lupa_password.php");
    exit();
}

// Tangkap dan hapus session error
$error_otp = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - CampusCare</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/style.css?v=1.4">
</head>

<body>

    <div class="container">
        <div class="card card-otp shadow-lg p-4 text-center">

            <div class="mb-3">
                <i class="bi bi-lock-fill text-maroon" style="font-size: 3.5rem;"></i>
            </div>

            <h4 class="fw-bold mb-2 text-maroon">Verifikasi OTP</h4>
            <p class="text-muted small mb-1">Masukkan 6 digit kode yang telah dikirim ke:</p>
            <p class="fw-bold text-dark mb-4"><?= htmlspecialchars($_SESSION['reset_email']) ?></p>
            
            <form action="../src/src_lupa_pass/proses_verifikasi_otp.php" method="POST">
                
                <div class="mb-4">
                    <input type="text" name="otp_input" class="form-control form-control-lg text-center fw-bold otp-input <?= $error_otp ? 'is-invalid' : '' ?>" maxlength="6" placeholder="••••••" pattern="\d{6}" title="Masukkan 6 digit angka" required autofocus autocomplete="off">
                           
                    <?php if($error_otp): ?>
                        <div class="invalid-feedback fw-bold mt-2 text-center" style="letter-spacing: normal; font-size: 0.875rem;">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>
                            <?= htmlspecialchars($error_otp) ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" name="verifikasi" class="btn btn-maroon w-100 mb-4 fw-bold py-2">
                    <i class="bi bi-patch-check-fill me-2"></i> Verifikasi Kode
                </button>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="lupa_password.php" class="text-secondary small text-decoration-none">
                        <i class="bi bi-envelope-exclamation me-1"></i> Ganti Email
                    </a>
                    
                    <a href="../src/src_lupa_pass/proses_otp_resend.php" class="text-maroon small text-decoration-none fw-bold">
                        <i class="bi bi-arrow-clockwise"></i> Kirim Ulang
                    </a>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php if(isset($_SESSION['sukses'])): ?>
        <script>
            Swal.fire({ title: 'Berhasil!', text: '<?= addslashes($_SESSION['sukses']) ?>', icon: 'success', confirmButtonColor: '#800000' });
        </script>
        <?php unset($_SESSION['sukses']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_resend'])): ?>
        <script>
            Swal.fire({ title: 'Gagal!', text: '<?= addslashes($_SESSION['error_resend']) ?>', icon: 'error', confirmButtonColor: '#800000' });
        </script>
        <?php unset($_SESSION['error_resend']); ?>
    <?php endif; ?>

</body>
</html>
