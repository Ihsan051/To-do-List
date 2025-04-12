<?php
session_start();
require 'database/function.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// menghapus notifikasi 
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
  $id_tugas = $_GET['hapus'];
  mysqli_query($conn, "UPDATE tugas SET dilihat = 1 WHERE id = $id_tugas AND user_id = $user_id");
}

$notifikasi = getNotifikasi($user_id);

// Ambil data pengguna dari database
$user = query("SELECT * FROM users WHERE id = $user_id")[0];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Notifikasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
      padding-bottom: 100px;
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
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
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
    <!-- sidebar Desktop -->
    <?php include 'sidebar.php'; ?>
    <div id="content">
      <h3 class="mb-4">Notifikasi</h3>
      <?php if (!empty($notifikasi)): ?>
        <?php foreach ($notifikasi as $notif): ?>
          <div class="alert alert-<?= $notif['type'] ?> d-flex justify-content-between align-items-center">
            <div><?= $notif['message'] ?></div>
            <a href="?hapus=<?=$notif['id']?>" class="btn btn-sm btn-outline-danger ms-3" onclick="return confirm('Hapus notifikasi ini?')">
              <i class="bi bi-x-lg"></i>
            </a>
          </div>
        <?php endforeach; ?>

      <?php else: ?>
        <div class="alert alert-info">Tidak ada notifikasi.</div>
      <?php endif; ?>
    </div>
    <!-- footer Mobile  -->
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>