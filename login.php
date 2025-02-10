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
    <title>Login - To-Do List App</title>
</head>
<body>
    <h2>Login</h2>
    <?php if ($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" placeholder="Email" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Password" required><br><br>
        
        <input type="submit" value="Login">
    </form>
    <p>Belum punya akun? <a href="register.php">Register disini</a></p>
</body>
</html>
