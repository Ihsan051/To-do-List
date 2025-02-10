<?php
// create_task.php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

// Proses penyimpanan tugas jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input minimal: judul harus diisi
    if (empty($_POST['title'])) {
        $message = "Judul harus diisi.";
    } else {
        $title       = trim($_POST['title']);
        $description = isset($_POST['description']) ? trim($_POST['description']) : "";
        $due_date    = (!empty($_POST['due_date'])) ? $_POST['due_date'] : null;
        $priority    = (!empty($_POST['priority'])) ? $_POST['priority'] : "Biasa"; // default "Biasa"

        // Insert data tugas ke tabel tasks
        $sql = "INSERT INTO tasks (user_id, title, description, due_date, priority) 
                VALUES (:user_id, :title, :description, :due_date, :priority)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([
                'user_id'     => $_SESSION['user_id'],
                'title'       => $title,
                'description' => $description,
                'due_date'    => $due_date,
                'priority'    => $priority
            ]);

            // Ambil ID tugas yang baru dibuat
            $task_id = $pdo->lastInsertId();

            // Catat aksi pembuatan tugas ke tabel task_logs
            $sqlLog = "INSERT INTO task_logs (task_id, user_id, action) 
                       VALUES (:task_id, :user_id, 'Dibuat')";
            $stmtLog = $pdo->prepare($sqlLog);
            $stmtLog->execute([
                'task_id' => $task_id,
                'user_id' => $_SESSION['user_id']
            ]);

            $message = "Task berhasil dibuat.";
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
    <title>Buat Task Baru - To-Do List App</title>
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
        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
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
    <h2>Buat Task Baru</h2>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="buat_tugas.php" method="post">
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" placeholder="Masukkan judul tugas" required>

        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description" placeholder="Masukkan deskripsi tugas (opsional)"></textarea>

        <label for="due_date">Tanggal Jatuh Tempo:</label>
        <input type="date" id="due_date" name="due_date">

        <label for="priority">Prioritas:</label>
        <select id="priority" name="priority">
            <option value="Biasa">Biasa</option>
            <option value="Penting">Penting</option>
        </select>

        <input type="submit" value="Buat Task">
    </form>
    <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
</body>
</html>
