<?php
session_start();
require 'database/function.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$user = query("SELECT * FROM users WHERE id = $user_id")[0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profil Pengguna</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #eef4f7;
      color: #333;
    }
    .wrapper {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }
    .card {
      background: #ffffff;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 30px;
      margin-top: 50px;
    }
    .profile-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #5faeb6;
    }
    #sidebar {
      background: #ffffff;
      border-right: 1px solid #ddd;
      width: 250px;
      display: flex;
      flex-direction: column;
      padding: 20px;
    }
    #sidebar a {
      color: #333;
      text-decoration: none;
      padding: 10px;
      display: block;
      border-radius: 6px;
      transition: background 0.3s;
    }
    #sidebar a:hover {
      background: #007bff;
      color: #fff;
    }
    #content {
      flex-grow: 1;
      padding: 30px;
      overflow-y: auto;
    }
    .navbar-bottom {
      display: none;
    }
    @media (max-width: 768px) {
      #sidebar {
        display: none;
      }
      .navbar-bottom {
        display: flex;
        justify-content: space-around;
        position: fixed;
        bottom: 0;
        width: 100%;
        background: #fff;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        padding: 10px 0;
        z-index: 999;
      }
      .navbar-bottom a {
        text-align: center;
        color: #333;
        text-decoration: none;
        font-size: 0.9rem;
      }
      .navbar-bottom i {
        font-size: 1.2rem;
        display: block;
      }
    }
  </style>
</head>
<body>
<div class="wrapper">
  
    <!-- Sidebar (Desktop Only) -->
    <?php include 'sidebar.php'; ?>

  <!-- Konten Utama -->
    <div class="container">
      <div class="card text-center">
        <img src="asset/profil.svg" alt="Foto Profil" class="profile-img mx-auto mb-3">
        <h4><?php echo $user['name']; ?></h4>
        <p class="text-muted mb-1">Email: <?php echo $user['email']; ?></p>
        <p class="text-muted">Bergabung sejak: <?php echo date('d M Y', strtotime($user['created_at'])); ?></p>
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
      </div>
    </div>
  </div>

   <!-- Bottom Navigation (Mobile Only) -->
   <div class="navbar-bottom">
    <a href="profil.php" class="nav-link text-center <?php echo basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : ''; ?>">
      <i class="bi bi-person-circle nav-icon"></i><br><small>Profil</small>
    </a>
    <a href="index.php" class="nav-link text-center <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
      <i class="bi bi-list-task nav-icon"></i><br><small>Tugas</small>
    </a>
    <a href="tugas_selesai.php" class="nav-link text-center <?php echo basename($_SERVER['PHP_SELF']) == 'tugas_selesai.php' ? 'active' : ''; ?>">
      <i class="bi bi-check2-square nav-icon"></i><br><small>Selesai</small>
    </a>
    <a href="kategori.php" class="nav-link text-center <?php echo basename($_SERVER['PHP_SELF']) == 'kategori.php' ? 'active' : ''; ?>">
      <i class="bi bi-folder nav-icon"></i><br><small>Kategori</small>
    </a>
  </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
