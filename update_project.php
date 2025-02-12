<?php
// edit_project.php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

// Proses ketika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan field nama dan id proyek sudah diisi
    if (empty($_POST['name']) || empty($_POST['id'])) {
        $message = "Nama proyek harus diisi.";
    } else {
        $project_id = $_POST['id'];
        $name       = trim($_POST['name']);

        // Pastikan proyek yang akan diedit milik pengguna yang sedang login
        $sql = "SELECT * FROM projects WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id'      => $project_id,
            'user_id' => $_SESSION['user_id']
        ]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            die("Proyek tidak ditemukan atau Anda tidak memiliki izin untuk mengedit proyek ini.");
        }

        // Update data proyek
        $sql = "UPDATE projects SET name = :name WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([
                'name' => $name,
                'id'   => $project_id
            ]);
            $message = "Proyek berhasil diperbarui.";

            // Mengambil data proyek terbaru
            $sql = "SELECT * FROM projects WHERE id = :id AND user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'id'      => $project_id,
                'user_id' => $_SESSION['user_id']
            ]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
} 
// Proses untuk menampilkan data proyek yang akan diedit (GET request)
else {
    // Pastikan parameter id proyek tersedia di URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $project_id = $_GET['id'];

        // Ambil data proyek dari database sesuai id dan pastikan proyek tersebut milik user yang login
        $sql = "SELECT * FROM projects WHERE id = :id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id'      => $project_id,
            'user_id' => $_SESSION['user_id']
        ]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            die("Proyek tidak ditemukan atau Anda tidak memiliki izin untuk mengedit proyek ini.");
        }
    } else {
        die("ID proyek diperlukan!");
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Proyek - To-Do List App</title>
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
    <h2>Edit Proyek</h2>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="update_project.php" method="post">
        <label for="name">Nama Proyek:</label>
        <input type="text" id="name" name="name" placeholder="Masukkan nama proyek" value="<?= htmlspecialchars($project['name']) ?>" required>

        <!-- Hidden input untuk menyimpan ID proyek -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($project['id']) ?>">

        <input type="submit" value="Perbarui Proyek">
    </form>
    <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
</body>
</html>
