<?php 
require 'auth.php';
if (!isAdmin()) redirect('user_dashboard.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = sanitize($_POST['nis']);
    $nama = sanitize($_POST['nama']);
    $jenis_kelamin = sanitize($_POST['jenis_kelamin']);
    $tanggal_lahir = sanitize($_POST['tanggal_lahir']);
    $alamat = sanitize($_POST['alamat']);
    $email = sanitize($_POST['email']);
    $telepon = sanitize($_POST['telepon']);
    $username = sanitize($_POST['username']);
    $password = password_hash(sanitize($_POST['password']), PASSWORD_DEFAULT);
    
    try {
        $pdo->beginTransaction();
        
        // Insert siswa
        $stmt = $pdo->prepare("INSERT INTO siswa (nis, nama, jenis_kelamin, tanggal_lahir, alamat, email, telepon) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nis, $nama, $jenis_kelamin, $tanggal_lahir, $alamat, $email, $telepon]);
        $siswa_id = $pdo->lastInsertId();
        
        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, siswa_id) VALUES (?, ?, 'user', ?)");
        $stmt->execute([$username, $password, $siswa_id]);
        
        // Upload foto jika ada
        if (!empty($_FILES['foto']['name'])) {
            $upload = uploadFoto($_FILES['foto'], $siswa_id);
            if (!$upload['success']) {
                $error = $upload['message'];
                $pdo->rollBack();
            }
        }
        
        $pdo->commit();
        $success = 'ditambahkan';
        redirect("admin_siswa.php?success=$success");
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = 'Gagal menambahkan data: ' . $e->getMessage();
    }
}
?>

<?php include 'header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Tambah Siswa</h1>
        <a href="admin_siswa.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Data Siswa</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" class="form-control" name="nis" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="mb-3">Kontak & Akun</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" name="telepon" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" name="foto" accept="image/*">
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>