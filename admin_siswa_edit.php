<?php 
require 'auth.php';
if (!isAdmin()) redirect('user_dashboard.php');

if (!isset($_GET['id'])) {
    redirect('admin_siswa.php');
}

$id = $_GET['id'];
$error = '';
$success = '';

// Ambil data siswa
$stmt = $pdo->prepare("SELECT s.*, u.username FROM siswa s LEFT JOIN users u ON s.id = u.siswa_id WHERE s.id = ?");
$stmt->execute([$id]);
$siswa = $stmt->fetch();

if (!$siswa) {
    redirect('admin_siswa.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nis = sanitize($_POST['nis']);
    $nama = sanitize($_POST['nama']);
    $jenis_kelamin = sanitize($_POST['jenis_kelamin']);
    $tanggal_lahir = sanitize($_POST['tanggal_lahir']);
    $alamat = sanitize($_POST['alamat']);
    $email = sanitize($_POST['email']);
    $telepon = sanitize($_POST['telepon']);
    $username = sanitize($_POST['username']);
    
    try {
        $pdo->beginTransaction();
        
        // Update siswa
        $stmt = $pdo->prepare("UPDATE siswa SET nis = ?, nama = ?, jenis_kelamin = ?, tanggal_lahir = ?, alamat = ?, email = ?, telepon = ? WHERE id = ?");
        $stmt->execute([$nis, $nama, $jenis_kelamin, $tanggal_lahir, $alamat, $email, $telepon, $id]);
        
        // Update user
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE siswa_id = ?");
        $stmt->execute([$username, $id]);
        
        // Update password jika diisi
        if (!empty($_POST['password'])) {
            $password = password_hash(sanitize($_POST['password']), PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE siswa_id = ?");
            $stmt->execute([$password, $id]);
        }
        
        // Upload foto jika ada
        if (!empty($_FILES['foto']['name'])) {
            $upload = uploadFoto($_FILES['foto'], $id);
            if (!$upload['success']) {
                $error = $upload['message'];
                $pdo->rollBack();
            }
        }
        
        $pdo->commit();
        $success = 'diupdate';
        redirect("admin_siswa.php?success=$success");
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = 'Gagal mengupdate data: ' . $e->getMessage();
    }
}
?>

<?php include 'header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Siswa</h1>
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
                            <input type="text" class="form-control" name="nis" value="<?= $siswa['nis'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" value="<?= $siswa['nama'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="L" <?= $siswa['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $siswa['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir" value="<?= $siswa['tanggal_lahir'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3" required><?= $siswa['alamat'] ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h5 class="mb-3">Kontak & Akun</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= $siswa['email'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" class="form-control" name="telepon" value="<?= $siswa['telepon'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" name="foto" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                            <?php if (getFotoProfile($id) !== 'assets/img/default-profile.jpg'): ?>
                                <div class="mt-2">
                                    <img src="<?= getFotoProfile($id) ?>" alt="Foto Profil" width="100" class="img-thumbnail">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="<?= $siswa['username'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>