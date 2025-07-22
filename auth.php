<?php
require 'config.php';
require 'functions.php';

// Mulai session hanya jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect jika belum login
if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'index.php') {
    redirect('login.php');
}

// Redirect berdasarkan role
if (isLoggedIn()) {
    $current_page = basename($_SERVER['PHP_SELF']);
    
    if (isAdmin() && strpos($current_page, 'user_') !== false) {
        redirect('admin_dashboard.php');
    }
    
    if (isUser() && strpos($current_page, 'admin_') !== false) {
        redirect('user_dashboard.php');
    }
}
?>