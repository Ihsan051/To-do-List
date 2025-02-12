<?php
// register.php
require 'database/koneksi.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan semua field terisi, termasuk konfirmasi password
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        // Validasi kecocokan password dan konfirmasi password
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $message = 'Password dan konfirmasi password tidak cocok!';
        } else {
            $name     = trim($_POST['name']);
            $email    = trim($_POST['email']);
            // Hash password menggunakan bcrypt
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            // Query untuk memasukkan data pengguna baru
            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $pdo->prepare($sql);
            try {
                $stmt->execute([
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $password
                ]);
                $message = 'Pendaftaran berhasil. Silakan <a href="login.php">Login</a>';
            } catch (PDOException $e) {
                // Jika terjadi error, misalnya email sudah terdaftar (error kode 23000)
                if ($e->getCode() == 23000) {
                    $message = 'Email sudah terdaftar, gunakan email lain.';
                } else {
                    $message = 'Terjadi kesalahan: ' . $e->getMessage();
                }
            }
        }
    } else {
        $message = 'Semua field harus diisi!';
    }
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
  </style>
</head>
<body>
  <div class="login-container second-color my-auto mt-3 mb-4">
    <!-- Logo di tengah -->
    <div class="text-center mb-0 mt-n1">
      <img src="To-do.png" alt="Logo" width="150" class="img-fluid d-block mx-auto">
    </div>
    
    <!-- Tampilkan notifikasi jika ada pesan -->
    <?php if (!empty($message)): ?>
      <div class="alert alert-warning text-center">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <!-- Form registrasi dengan validasi JavaScript -->
    <form class="mt-n1" action="register.php" method="post" onsubmit="return validatePassword()">
      <div class="mb-2 mt-0">
        <label for="nama_lengkap" class="form-label text-light">Nama Lengkap</label>
        <input type="text" class="form-control" name="name" id="nama_lengkap" placeholder="Masukkan Nama Lengkap">
      </div>
      <div class="mb-2">
        <label for="email" class="form-label text-light">E-Mail</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan E-Mail">
      </div>
      <div class="mb-2">
        <label for="password" class="form-label text-light">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password">
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label text-light">Konfirmasi Password</label>
        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Masukkan password">
      </div> 
      <button type="submit" class="btn primary-color w-100 text-light">Register</button>
      <p class="text-center mt-3 mb-0 text-light">
        Sudah punya akun? <a href="login.html" class="text-decoration-none text-primary">Login</a>
      </p>
    </form>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Fungsi validasi password dan konfirmasi password
    function validatePassword() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        if (password !== confirmPassword) {
            alert("Password tidak cocok!");
            return false; // Mencegah formulir dikirim
        }
        return true; // Melanjutkan pengiriman formulir
    }
  </script>
</body>
</html>
