<?php
session_start();
require 'database/function.php';

$message = ''; // Pastikan $message diinisialisasi agar bisa ditampilkan nanti

// cek apakah user sudah menekan login
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Jalankan query untuk memanggil email yang sama 
  $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

  // cek apakah ada email yang sama
  if (mysqli_num_rows($result) === 1) {
    // ambil semua data dari database
    $row = mysqli_fetch_assoc($result);

    // cek apakah password yang diinput sama dengan password database
    if (password_verify($password, $row['password'])) {
      // buat session
      $_SESSION['user_id'] = $row['id'];
      header("location: index.php");
      exit;
    } else {
      $message = "Username atau password salah"; // Pesan error jika password salah
    }
  } else {
    $message = "Username atau password salah"; // Pesan error jika email tidak ditemukan
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - To-Do App</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #eef4f7;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .login-box {
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 420px;
    }

    .logo {
      display: block;
      margin: 0 auto 20px;
      width: 150px;
    }

    .form-label {
      font-weight: 600;
    }

    .btn-primary-custom {
      background-color: #223c56;
      border: none;
    }

    .btn-primary-custom:hover {
      background-color: #2c7bc9;
    }

    .text-link {
      color: #223c56;
      text-decoration: none;
    }

    .text-link:hover {
      color: #2c7bc9;
      text-decoration: underline;
    }

    .form-control {
      border-radius: 8px;
      padding: 10px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <img src="asset/To-do.png" alt="To-Do Logo" class="logo">

  <?php if (!empty($message)) : ?>
    <div class="alert alert-danger text-center">
      <?= $message ?>
    </div>
  <?php endif; ?>

  <form action="login.php" method="post">
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
    </div>

    <button type="submit" name="login" class="btn btn-primary-custom w-100 text-white mb-3">Login</button>

    <p class="text-center">
      Belum punya akun?
      <a href="register.php" class="text-link">Daftar di sini</a>
    </p>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>