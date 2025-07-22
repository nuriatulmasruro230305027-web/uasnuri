<?php
// Tambahkan pengecekan jika fungsi sudah ada
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    function isUser() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
    }

    function redirect($url) {
        header("Location: $url");
        exit();
    }

    function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    function uploadFoto($file, $siswa_id) {
        global $pdo;
        
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $new_filename = 'profile_' . $siswa_id . '_' . time() . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;
        
        $check = getimagesize($file["tmp_name"]);
        if($check === false) {
            return ['success' => false, 'message' => 'File is not an image.'];
        }
        
        if ($file["size"] > 2000000) {
            return ['success' => false, 'message' => 'File is too large (max 2MB).'];
        }
        
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            return ['success' => false, 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.'];
        }
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO tbl_foto (siswa_id, nama_file) VALUES (?, ?)");
            $stmt->execute([$siswa_id, $new_filename]);
            return ['success' => true, 'filename' => $new_filename];
        } else {
            return ['success' => false, 'message' => 'Error uploading file.'];
        }
    }

    function getFotoProfile($siswa_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT nama_file FROM tbl_foto WHERE siswa_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$siswa_id]);
        $foto = $stmt->fetch();
        
        if ($foto) {
            return 'uploads/' . $foto['nama_file'];
        } else {
            return 'assets/img/default-profile.jpg';
        }
    }
}
?>