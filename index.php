<?php
require_once 'functions.php';
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Beranda | CRUD Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #74ebd5, #acb6e5);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
    }

    .hero-box {
      background: white;
      border-radius: 20px;
      padding: 50px 30px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
      transition: all 0.3s;
    }

    .hero-box:hover {
      transform: translateY(-5px);
    }

    .btn-custom {
      border-radius: 50px;
      font-weight: bold;
      padding: 12px 30px;
      font-size: 1.1rem;
    }

    .illustration {
      max-width: 100%;
      height: auto;
      margin-top: 30px;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-10 text-center">
      <div class="hero-box">
        <h1 class="display-5 fw-bold mb-3">Selamat Datang di <span class="text-primary">CRUD Siswa</span></h1>
        <p class="lead mb-4">Sistem ini dirancang untuk mengelola data siswa secara efisien dengan fitur login untuk Admin dan User.</p>
        
        <?php if (!isLoggedIn()): ?>
          <div class="d-flex justify-content-center gap-3">
            <a href="login.php" class="btn btn-primary btn-custom"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
          </div>
        <?php else: ?>
          <div class="d-flex justify-content-center gap-3">
            <a href="<?= isAdmin() ? 'admin_dashboard.php' : 'user_dashboard.php' ?>" class="btn btn-success btn-custom"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="logout.php" class="btn btn-outline-secondary btn-custom"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
          </div>
        <?php endif; ?>

        <img src="assets/img/welcome.svg" alt="Ilustrasi Selamat Datang" class="illustration mt-4">
      </div>
    </div>
  </div>
</div>

</body>
</html>
