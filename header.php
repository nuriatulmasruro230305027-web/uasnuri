<?php
require_once 'functions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SISWA CRUD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <style>
    body {
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
    }
    .sidebar {
      width: 250px;
      min-height: 100vh;
      background: #343a40;
      color: white;
      position: fixed;
      transition: all 0.3s;
      z-index: 1000;
    }
    .sidebar-header {
      padding: 20px;
      background: #2c3136;
    }
    .sidebar ul li a {
      padding: 10px 20px;
      display: block;
      color: #adb5bd;
      text-decoration: none;
    }
    .sidebar ul li a:hover,
    .sidebar ul li.active > a {
      color: #fff;
      background: #495057;
    }
    .main-content {
      margin-left: 250px;
      width: calc(100% - 250px);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .content-wrapper {
      flex: 1;
      padding: 20px;
    }
    .footer {
      background: #f8f9fa;
      padding: 15px;
      text-align: center;
    }
    .profile-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }
    @media (max-width: 768px) {
      .sidebar {
        margin-left: -250px;
      }
      .sidebar.active {
        margin-left: 0;
      }
      .main-content {
        margin-left: 0;
        width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="sidebar-header">
    <h3>SISWA CRUD</h3>
  </div>
  <ul class="nav flex-column">
    <li class="nav-item"><a href="admin_dashboard.php" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
    <li class="nav-item"><a href="admin_siswa.php" class="nav-link"><i class="bi bi-people me-2"></i> Data Siswa</a></li>
    <li class="nav-item"><a href="admin_profile.php" class="nav-link"><i class="bi bi-person me-2"></i> Profile</a></li>
  </ul>

  <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-dark">
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="<?= getFotoProfile($_SESSION['siswa_id'] ?? 0) ?>" class="profile-img me-2">
        <strong>admin</strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
        <li><a class="dropdown-item" href="admin_profile.php">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="main-content">
  <div class="content-wrapper">
