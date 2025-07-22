<?php 
require 'auth.php';
if (!isAdmin()) redirect('user_dashboard.php');
?>

<?php include 'header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Data Siswa</h1>
        <a href="admin_siswa_tambah.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Siswa
        </a>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Data berhasil <?= $_GET['success'] ?>!</div>
    <?php endif; ?>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM siswa ORDER BY id DESC");
                        $no = 1;
                        while ($row = $stmt->fetch()):
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nis'] ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['telepon'] ?></td>
                            <td>
                                <a href="admin_siswa_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="admin_siswa_hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>