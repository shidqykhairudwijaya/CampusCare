<?php
session_start();

// Tangkap dan hapus session error
$error_email = isset($_SESSION['error_email']) ? $_SESSION['error_email'] : '';
unset($_SESSION['error_email']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - CampusCare</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/style.css?v=1.3">
</head>

<body>

    <div class="container">
        <div class="card card-lupa shadow-lg p-4 text-center">

            <div class="mb-3">
                <i class="bi bi-shield-check text-maroon" style="font-size: 3.5rem;"></i>
            </div>
            
            <h4 class="fw-bold mb-2 text-maroon">Verifikasi Akun</h4>
            <p class="text-muted small mb-4">
                Masukkan email Anda untuk menerima <br>kode OTP reset password.
            </p>
            
            <form action="../src/src_lupa_pass/proses_lupa_pass.php" method="POST">
                
                <div class="mb-4 text-start">
                    <label class="form-label small fw-bold text-muted">Email Terdaftar</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light border-end-0 text-muted <?= $error_email ? 'error-border' : '' ?>">
                            <i class="bi bi-envelope"></i>
                        </span>
                        
                        <input type="email" name="email" class="form-control border-start-0 <?= $error_email ? 'is-invalid' : '' ?>" placeholder="nama@student.unsika.ac.id" required autofocus autocomplete="off">
                               
                        <?php if($error_email): ?>
                            <div class="invalid-feedback fw-bold mt-2">
                                <i class="bi bi-exclamation-circle-fill me-1"></i>
                                <?= htmlspecialchars($error_email) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <button type="submit" name="cek_email" class="btn btn-maroon w-100 mb-3 fw-bold">
                    <i class="bi bi-send-fill me-2"></i> Kirim Kode OTP
                </button>
                
                <a href="../index.php" class="text-decoration-none small text-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Login
                </a>
            </form>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
