<?php
// register.php
require 'database/koneksi.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input (minimal)
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $name     = trim($_POST['name']);
        $email    = trim($_POST['email']);
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
                    <h2 class="text-center">Register</h2>
                    <?php if ($message): ?>
                    <p><?= $message ?></p>
                     <?php endif; ?>
                    <form action="register.php" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="Password" class="form-label">Password</label>
                            <input type="Password" class="form-control" name="password" id="password" required>
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

