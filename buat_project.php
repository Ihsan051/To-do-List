<?php
// create_project.php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

// Proses penyimpanan proyek jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input: nama proyek harus diisi
    if (empty($_POST['name'])) {
        $message = "Nama proyek harus diisi.";
    } else {
        $name = trim($_POST['name']);

        // Insert data proyek ke tabel projects
        $sql = "INSERT INTO projects (user_id, name) VALUES (:user_id, :name)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([
                'user_id' => $_SESSION['user_id'],
                'name'    => $name
            ]);
            $message = "Proyek berhasil dibuat.";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Proyek Baru - To-Do List App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 20px auto;
            padding: 0 10px;
        }
        h2 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 15px;
            padding: 10px 20px;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            background: #f0f0f0;
            border-left: 4px solid #007BFF;
        }
    </style>
</head>
<body>
    <h2>Buat Proyek Baru</h2>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="buat_project.php" method="post">
        <label for="name">Nama Proyek:</label>
        <input type="text" id="name" name="name" placeholder="Masukkan nama proyek" required>

        <input type="submit" value="Buat Proyek">
    </form>
    <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
</body>
</html>
