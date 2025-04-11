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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
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

    .text-primary:hover {
      color: #2c7bc9 !important;
    }

    .form-control-sm {
      width: 100%;
      border: none;
      outline: none;
    }

    .form-label {
      font-size: 15px;
    }
  </style>
</head>

<body>
  <div class="login-container second-color">
    <div class="text-center mb-0">
      <img src="asset/To-do.png" alt="Logo" width="150" class="img-fluid d-block mx-auto">
    </div>

    <?php if (!empty($message)) : ?>
      <div class="alert alert-warning text-center">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <form class="mt-n1" action="login.php" method="post">
      <div class="mb-3">
        <label for="email" class="form-label text-light">Email</label>
        <input type="email" class="form-control-sm" name="email" id="email" placeholder="Masukkan email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label text-light">Password</label>
        <input type="password" class="form-control-sm" name="password" id="password" placeholder="Masukkan password" required>
      </div>
      <button type="submit" name="login" class="btn primary-color w-100 text-light">Login</button>
      <p class="text-center mt-3 mb-1 text-light">
        Tidak punya akun? <a href="register.php" class="text-decoration-none text-primary">Daftar</a>
      </p>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>