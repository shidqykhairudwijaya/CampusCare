<?php
session_start();

// Cek akses via OTP
if (!isset($_SESSION['reset_id']) || !isset($_SESSION['reset_email'])) {
    header("Location: lupa_password.php");
    exit();
}

// Tangkap dan hapus session error
$error_pass = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - CampusCare</title>
    
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/style.css?v=1.2">
</head>

<body>

    <div class="container">
        <div class="card card-reset shadow-lg p-4">
            
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock text-maroon" style="font-size: 3rem; color: #800000;"></i>
                <h4 class="fw-bold mt-2 text-maroon">Password Baru</h4>
                <p class="text-muted small">
                    Buat password baru yang kuat untuk akun <br>
                    <b><?= htmlspecialchars($_SESSION['reset_email']) ?></b>
                </p>
            </div>

            <form action="../src/src_lupa_pass/proses_ganti_password.php" method="POST">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold">Password Baru</label>
                    <div class="password-wrapper <?= $error_pass ? 'is-invalid' : '' ?>">
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                        <div class="toggle-icon" data-target="new_password">
                            <i class="bi bi-eye-slash text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Konfirmasi Password</label>
                    <div class="password-wrapper <?= $error_pass ? 'is-invalid' : '' ?>">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Ulangi password baru" required minlength="6">
                        <div class="toggle-icon" data-target="confirm_password">
                            <i class="bi bi-eye-slash text-muted"></i>
                        </div>
                    </div>

                    <?php if($error_pass): ?>
                        <div class="text-danger fw-bold mt-2 text-center" style="font-size: 0.875rem;">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>
                            <?= htmlspecialchars($error_pass) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" name="submit_password" class="btn btn-maroon w-100 fw-bold py-2">
                    SIMPAN PASSWORD
                </button>

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Logika Show/Hide Password
        document.querySelectorAll('.toggle-icon').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const inputField = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (inputField.type === 'password') {
                    inputField.type = 'text';
                    icon.classList.remove('bi-eye-slash', 'text-muted');
                    icon.classList.add('bi-eye');
                    icon.style.color = '#800000';
                } else {
                    inputField.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash', 'text-muted');
                    icon.style.color = '';
                }
            });
        });
    </script>
</body>
</html>
