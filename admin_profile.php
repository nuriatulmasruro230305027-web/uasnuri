<?php 
require 'auth.php';
if (!isAdmin()) redirect('user_profile.php');

$error = '';
$success = '';

// Ambil data admin
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$admin = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $current_password = sanitize($_POST['current_password']);
    $new_password = sanitize($_POST['new_password']);
    
    try {
        // Verifikasi password saat ini
        if (!password_verify($current_password, $admin['password'])) {
            $error = 'Password saat ini salah!';
        } else {
            // Update username
            $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->execute([$username, $_SESSION['user_id']]);
            
            // Update password jika diisi
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $_SESSION['user_id']]);
            }
            
            $_SESSION['username'] = $username;
            $success = 'Profil berhasil diupdate!';
        }
    } catch (PDOException $e) {
        $error = 'Gagal mengupdate profil: ' . $e->getMessage();
    }
}
?>

<?php include 'header.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4">Profil Admin</h1>
    
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
                    <img src="<?= getFotoProfile($_SESSION['siswa_id'] ?? 0) ?>" alt="Profile" class="rounded-circle mb-3" width="150" height="150">
                    <h4 class="card-title"><?= $_SESSION['username'] ?></h4>
                    <p class="card-text text-muted">Administrator</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Profil</h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="<?= $admin['username'] ?>" required>
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