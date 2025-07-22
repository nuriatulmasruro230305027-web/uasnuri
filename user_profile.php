<?php 
require 'auth.php';
if (isAdmin()) redirect('admin_profile.php');

$error = '';
$success = '';

// Ambil data user
$stmt = $pdo->prepare("SELECT u.*, s.nama FROM users u JOIN siswa s ON u.siswa_id = s.id WHERE u.id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $current_password = sanitize($_POST['current_password']);
    $new_password = sanitize($_POST['new_password']);
    $telepon = sanitize($_POST['telepon']);
    $alamat = sanitize($_POST['alamat']);
    
    // Handle foto upload
    $foto_uploaded = false;
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFoto($_FILES['foto'], $_SESSION['siswa_id']);
        if ($upload['success']) {
            $foto_uploaded = true;
        } else {
            $error = $upload['message'];
        }
    }
    
    if (!$error) {
        try {
            $pdo->beginTransaction();
            
            // Verifikasi password saat ini
            if (!password_verify($current_password, $user['password'])) {
                $error = 'Password saat ini salah!';
            } else {
                // Update user
                $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt->execute([$username, $_SESSION['user_id']]);
                
                // Update password jika diisi
                if (!empty($new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$hashed_password, $_SESSION['user_id']]);
                }
                
                // Update data siswa
                $stmt = $pdo->prepare("UPDATE siswa SET telepon = ?, alamat = ? WHERE id = ?");
                $stmt->execute([$telepon, $alamat, $_SESSION['siswa_id']]);
                
                $pdo->commit();
                $_SESSION['username'] = $username;
                $success = 'Profil berhasil diupdate!';
                
                if ($foto_uploaded) {
                    $success .= ' Foto profil juga berhasil diupdate!';
                }
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Gagal mengupdate profil: ' . $e->getMessage();
        }
    }
}

// Ambil data terbaru
$stmt = $pdo->prepare("SELECT s.* FROM siswa s WHERE s.id = ?");
$stmt->execute([$_SESSION['siswa_id']]);
$siswa = $stmt->fetch();
?>

<?php include 'header.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4">Profil User</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img src="<?= getFotoProfile($_SESSION['siswa_id']) ?>" alt="Profile" class="rounded-circle mb-3" width="150" height="150">
                    <h4 class="card-title"><?= $siswa['nama'] ?></h4>
                    <p class="card-text text-muted">Siswa</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Profil</h6>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" value="<?= $user['username'] ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Password Saat Ini</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" class="form-control" name="new_password">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" name="telepon" value="<?= $siswa['telepon'] ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3" required><?= $siswa['alamat'] ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control" name="foto" accept="image/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>