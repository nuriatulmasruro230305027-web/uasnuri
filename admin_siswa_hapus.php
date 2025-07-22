<?php
require 'auth.php';
if (!isAdmin()) redirect('user_dashboard.php');

if (!isset($_GET['id'])) {
    redirect('admin_siswa.php');
}

$id = $_GET['id'];

try {
    $pdo->beginTransaction();
    
    // Hapus foto terkait
    $stmt = $pdo->prepare("SELECT nama_file FROM tbl_foto WHERE siswa_id = ?");
    $stmt->execute([$id]);
    $fotos = $stmt->fetchAll();
    
    foreach ($fotos as $foto) {
        $file_path = "uploads/" . $foto['nama_file'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Hapus dari tbl_foto
    $stmt = $pdo->prepare("DELETE FROM tbl_foto WHERE siswa_id = ?");
    $stmt->execute([$id]);
    
    // Hapus user terkait
    $stmt = $pdo->prepare("DELETE FROM users WHERE siswa_id = ?");
    $stmt->execute([$id]);
    
    // Hapus siswa
    $stmt = $pdo->prepare("DELETE FROM siswa WHERE id = ?");
    $stmt->execute([$id]);
    
    $pdo->commit();
    $success = 'dihapus';
    redirect("admin_siswa.php?success=$success");
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Gagal menghapus data: " . $e->getMessage());
}
?>