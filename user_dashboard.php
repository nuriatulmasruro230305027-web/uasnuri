<?php 
require 'auth.php';
if (isAdmin()) redirect('admin_dashboard.php');

// Ambil data siswa
$stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = ?");
$stmt->execute([$_SESSION['siswa_id']]);
$siswa = $stmt->fetch();
?>

<?php include 'header.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4">User Dashboard</h1>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">NIS</h5>
                    <h2 class="card-text"><?= $siswa['nis'] ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Nama</h5>
                    <h2 class="card-text"><?= $siswa['nama'] ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Email</h5>
                    <h2 class="card-text"><?= $siswa['email'] ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Lengkap</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Jenis Kelamin:</strong> <?= $siswa['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                    <p><strong>Tanggal Lahir:</strong> <?= date('d F Y', strtotime($siswa['tanggal_lahir'])) ?></p>
                    <p><strong>Telepon:</strong> <?= $siswa['telepon'] ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Alamat:</strong></p>
                    <p><?= nl2br($siswa['alamat']) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>