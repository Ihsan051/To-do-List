<?php
require 'database/function.php';

if (isset($_POST['register'])) {
  $message = register($_POST);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar Akun</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #eef4f7;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .register-container {
      background-color: #fff;
      padding: 30px;
      border-radius: 15px;
      max-width: 450px;
      width: 100%;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .form-label {
      font-weight: 500;
    }

    .btn-primary-custom {
      background-color: #223c56;
      border: none;
    }

    .btn-primary-custom:hover {
      background-color: #2c7bc9;
    }
    .text-small {
      font-size: 0.9rem;
    }

    @media (max-width: 576px) {
      .register-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="register-container">
    <div class="text-center mb-4">
      <img src="asset/To-do.png" alt="Logo" width="120">
      <h4 class="mt-2 mb-1">Buat Akun</h4>
      <p class="text-muted mb-0">Mulai kelola tugasmu hari ini</p>
    </div>

    <?php if (!empty($message)): ?>
      <div class="alert alert-warning text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
      <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">E-Mail</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
      </div>
      <button type="submit" name="register" class="btn btn-primary-custom w-100 text-white mb-3">Daftar</button>
    </form>

    <p class="text-center text-small mt-3 mb-0">
      Sudah punya akun?
      <a href="login.php" class="text-decoration-none text-primary">Login</a>
    </p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
