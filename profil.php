<?php
session_start();
require 'database/function.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = query("SELECT * FROM users WHERE id = $user_id")[0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profil Pengguna</title>
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
    }

    .card {
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 30px;
    }

    .profile-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #5faeb6;
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
    
    <?php include 'sidebar.php'; ?>

    <div id="content">
      <h3 class="mb-4">Profil Pengguna</h3>

      <div class="card mx-auto" style="max-width: 500px;">
        <div class="text-center">
          <img src="<?= $user['profil'] ? 'uploads/'.$user['profil'] : 'asset/profil.svg' ?>" class="profile-img mb-3" alt="Foto Profil">
          <h5 class="mb-1"><?= htmlspecialchars($user['name']) ?></h5>
          <p class="text-muted small mb-2"><?= htmlspecialchars($user['email']) ?></p>
          <p class="text-muted small">Bergabung sejak: <?= date('d M Y', strtotime($user['created_at'])) ?></p>
        </div>
        <div class="d-flex justify-content-center gap-3 mt-4">
          <a href="editProfil.php?id=<?= $user['id'] ?>" class="btn btn-warning">
            <i class="bi bi-pencil-square me-1"></i> Edit Profil
          </a>
          <a href="logout.php" class="btn btn-danger">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
