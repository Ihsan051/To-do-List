<?php
// login.php
session_start();
require 'database/koneksi.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Query untuk mengambil data pengguna berdasarkan email
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        // Jika pengguna ditemukan dan password cocok
        if ($user && password_verify($password, $user['password'])) {
            // Set session untuk menandai bahwa pengguna sudah login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            // Redirect ke halaman dashboard atau halaman utama aplikasi
            header("Location: index.php");
            exit;
        } else {
            $message = 'Email atau password salah.';
        }
    } else {
        $message = 'Harap isi email dan password.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
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
    .text-primary {
        color: #223c56 !important;
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
  <div class="login-container second-color">
    <!-- Logo yang ditengah dengan gap lebih kecil antara logo dan form -->
    <div class="text-center mb-0">
      <img src="To-do.png" alt="Logo" width="150" class="img-fluid d-block mx-auto">
    </div>
    <!-- Mengurangi jarak antara logo dan form dengan memberikan margin-top negatif pada form -->
    <form class="mt-n1" action="login.php" method="post">
    <?php if ($message): ?>
                    <p><?= $message ?></p>
                     <?php endif; ?>    
      <div class="mb-3">
        <label for="email" class="form-label text-light">Email</label>
        <input type="email" class="form-control-sm" name="email" id="email" placeholder="Masukkan email">
      </div>
      <div class="mb-3 ">
        <label for="password" class="form-label text-light">Password</label>
        <input type="password" class="form-control-sm" name="password" id="password" placeholder="Masukkan password">
      </div>
      <button type="submit" class="btn primary-color w-100 text-light">Login</button>
      <p class="text-center mt-3 mb-1 text-light">
        Tidak punya akun? <a href="register.php" class="text-decoration-none  text-primary">Daftar</a>
      </p>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
