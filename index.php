<?php
session_start();
if(isset($_SESSION['role'])) {
    header("Location: " . $_SESSION['role'] . "/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CampusCare</title>
    
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="assets/style.css?v=1.1">

</head>
<body class="bg-login">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4 login-card" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <i class="bi bi-building-check text-maroon" style="font-size: 3rem;"></i>
            <h3 class="text-maroon fw-bold mt-2">CampusCare</h3>
            <p class="text-muted small">Silakan login dengan Email Anda</p>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Gagal!</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="src/src_login/proses_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="NPM@student.unsika.ac.id" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-maroon w-100 fw-bold shadow-sm">MASUK</button>
            
            <div class="text-center mt-3">
                <small class="text-muted">Lupa Password? <a href="lupa_pass/lupa_password.php" class="text-maroon text-decoration-none fw-bold">Klik di sini</a></small>
            </div>
        </form>
    </div>
</div>

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(isset($_SESSION['reset_sukses'])): ?>
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: '<?= addslashes($_SESSION['reset_sukses']) ?>',
            icon: 'success',
            confirmButtonColor: '#800000'
        });
    </script>
    <?php unset($_SESSION['reset_sukses']); ?>
<?php endif; ?>

</body>
</html>
