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
    <title>Register - To-Do List App</title>
</head>
<body>
    <h2>Register</h2>
    <?php if ($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>
    <form action="register.php" method="post">
        <label for="name">Nama:</label><br>
        <input type="text" id="name" name="name" placeholder="Nama lengkap" required><br><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" placeholder="Email" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Password" required><br><br>
        
        <input type="submit" value="Register">
    </form>
    <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
</body>
</html>
