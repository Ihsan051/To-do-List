<?php
session_start();
require 'database/function.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = query("SELECT * FROM users WHERE id = $user_id")[0];

// Proses form submit
if (isset($_POST['submit'])) {
    $result = updateUserProfile($user_id, $_POST, $_FILES);
    $message = $result['message'];
    $error = $result['error'];

    // Ambil data terbaru
    $user = query("SELECT * FROM users WHERE id = $user_id")[0];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Profil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #eef4f7;
    }
    .card {
      background: #fff;
      border-radius: 16px;
      padding: 30px;
      margin-top: 40px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-control, .form-label {
      font-size: 0.95rem;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card">
    <h4 class="mb-4">Edit Profil</h4>

    <?php if (isset($message)): ?>
      <div class="alert alert-success"><?= $message ?></div>
    <?php elseif (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="profile_image" class="form-label">Foto Profil</label><br>
        <img src="<?= $user['profil'] ? 'uploads/'.$user['profil'] : 'asset/profil.svg' ?>" width="100" height="100" class="rounded-circle mb-2 mx-auto d-block mb-3 border border-secondary" alt="Profile Image">
        <input class="form-control" type="file" id="profil" name="profil">
      </div>

      <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>

      <hr>
      <h6 class="mt-4">Ubah Password (opsional)</h6>

      <div class="mb-3">
        <label for="old_password" class="form-label">Password Lama</label>
        <input type="password" name="old_password" class="form-control" id="old_password">
      </div>

      <div class="mb-3">
        <label for="new_password" class="form-label">Password Baru</label>
        <input type="password" name="new_password" class="form-control" id="new_password">
      </div>

      <div class="mb-3">
        <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="confirm_password" class="form-control" id="confirm_password">
      </div>

      <button type="submit" name="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
      <a href="profil.php" class="btn btn-secondary mt-2">Batal</a>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>