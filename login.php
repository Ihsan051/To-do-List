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
            header("Location: dashboard.php");
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
    <title>Login & Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff7eb3, #ff758c);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background-color: #ff4081;
            border: none;
        }
        .btn-primary:hover {
            background-color: #e6005c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h2 class="text-center">Login</h2>
                    <?php if ($message): ?>
                    <p><?= $message ?></p>
                     <?php endif; ?>
                    <form action="login.php" method="post">
                        <div class="mb-3">
                            <label for="Email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="Password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <p class="text-center mt-3">Belum punya akun? <a href="register.php" >Daftar</a></p>
                </div>
            </div>
        </div>
    </div>


</body>
</html>

