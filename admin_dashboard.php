<?php
require 'auth.php';
if (!isAdmin()) redirect('user_dashboard.php');

include 'header.php';
require 'config.php'; // pastikan koneksi $pdo sudah dibuat
?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-speedometer2 me-2"></i> Dashboard</h1>
    <span class="badge bg-primary p-2">
      <i class="bi bi-calendar me-1"></i> <?= date('l, d F Y') ?>
    </span>
  </div>

  <div class="row">
    <!-- Admin Count -->
    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card border-start border-primary border-3">
        <div class="card-body d-flex justify-content-between">
          <div>
            <h6 class="text-muted">ADMINISTRATOR</h6>
            <?php
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
            $adminTotal = $stmt->fetch()['total'];
            ?>
            <h3><?= $adminTotal ?></h3>
          </div>
          <div class="bg-primary bg-opacity-10 p-3 rounded">
            <i class="bi bi-shield-lock text-primary fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- User Count -->
    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card border-start border-success border-3">
        <div class="card-body d-flex justify-content-between">
          <div>
            <h6 class="text-muted">PENGGUNA SISWA</h6>
            <?php
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
            $userTotal = $stmt->fetch()['total'];
            ?>
            <h3><?= $userTotal ?></h3>
          </div>
          <div class="bg-success bg-opacity-10 p-3 rounded">
            <i class="bi bi-people text-success fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Waktu -->
    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card border-start border-info border-3">
        <div class="card-body d-flex justify-content-between">
          <div>
            <h6 class="text-muted">AKTIVITAS TERAKHIR</h6>
            <h3><?= date('H:i') ?></h3>
          </div>
          <div class="bg-info bg-opacity-10 p-3 rounded">
            <i class="bi bi-clock-history text-info fs-4"></i>
          </div>
        </div>
        <small class="text-muted d-block mt-2">Pembaruan real-time</small>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card h-100">
        <div class="card-body text-center d-flex flex-column justify-content-center">
          <h6 class="text-muted mb-3">Quick Actions</h6>
          <div class="d-flex justify-content-center gap-2">
            <a href="admin_siswa.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-people"></i></a>
            <a href="admin_profile.php" class="btn btn-outline-success btn-sm"><i class="bi bi-person"></i></a>
            <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Info Sistem -->
  <div class="row mt-4">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header bg-white border-bottom-0">
          <h5><i class="bi bi-info-circle me-2"></i> Informasi Sistem</h5>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between"><span>Versi Sistem</span><span class="badge bg-primary">1.0.0</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>Terakhir Diperbarui</span><span><?= date('d F Y') ?></span></li>
            <li class="list-group-item d-flex justify-content-between"><span>Pengembang</span><span>Tim IT Sekolah</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>Status</span><span class="badge bg-success">Aktif</span></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
