<?php
session_start(); // WAJIB sebelum session_destroy()

require_once 'functions.php'; // Agar fungsi redirect() tersedia

session_destroy(); // Hapus semua session
redirect('login.php'); // Arahkan ke halaman login
?>
