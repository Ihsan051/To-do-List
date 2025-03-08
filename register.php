<?php
// register.php
require 'database/function.php';

// mengecek apakah tombol register sudah di tekan
if(isset($_POST['register'])){

  // cek data yang dikembalikan oleh function
  $message = register($_POST);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Page</title>
  <!-- Memuat font Poppins dari Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #d9d9d9;
    }
    .login-container {
      width: 400px;
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    .second-color {
      background-color: #5faeb6 !important;
    }
    .primary-color {
      background-color: #223c56 !important;
    }
    .primary-color:hover {
      background-color: #2c7bc9 !important;
    }
    .text-primary {
      color: #223c56 !important;
    }
    .text-primary:hover{
      color: #2c7bc9 !important;
    }
     .form-control-sm{
      width: 100%;
      border: none;
      outline: none;
    }
    .form-label{
      font-size: 15px;
    }
  </style>
</head>
<body>
  <div class="login-container second-color my-auto mt-3 mb-4">
    <!-- Logo di tengah -->
    <div class="text-center mb-0 mt-n1">
      <img src="asset/To-do.png" alt="Logo" width="150" class="img-fluid d-block mx-auto">
    </div>
    
    <!-- Tampilkan notifikasi jika ada pesan -->
    <?php if (!empty($message)): ?>
      <div class="alert alert-warning text-center">
        <?= $message ?>
      </div>
    <?php endif; ?>
    <form class="mt-n1" action="register.php" method="post">
      <div class="mb-3 mt-0">
        <label for="nama_lengkap" class="form-label text-light">Nama Lengkap</label>
        <input type="text" class="form-control-sm" name="name" id="nama_lengkap" placeholder="Masukkan Nama Lengkap" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label text-light">E-Mail</label>
        <input type="email" class="form-control-sm" name="email" id="email" placeholder="Masukkan E-Mail" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label text-light">Password</label>
        <input type="password" class="form-control-sm" name="password" id="password" placeholder="Masukkan password" required>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label text-light">Konfirmasi Password</label>
        <input type="password" class="form-control-sm" name="confirm_password" id="confirm_password" placeholder="Masukkan password" required>
      </div> 
      <button type="submit" name="register" class="btn primary-color w-100 text-light">Register</button>
      <p class="text-center mt-3 mb-0 text-light">
        Sudah punya akun? <a href="login.php" class="text-decoration-none text-primary">Login</a>
      </p>
    </form>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
