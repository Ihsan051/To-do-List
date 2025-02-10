<?php
// create_project_task.php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pastikan parameter project_id ada
if (!isset($_GET['project_id'])) {
    die("Project ID tidak tersedia.");
}

$project_id = $_GET['project_id'];
$user_id = $_SESSION['user_id'];

// Verifikasi bahwa proyek tersebut dimiliki oleh pengguna
$sql = "SELECT * FROM projects WHERE id = :project_id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'project_id' => $project_id,
    'user_id'    => $user_id
]);
$project = $stmt->fetch();

if (!$project) {
    die("Proyek tidak ditemukan atau Anda tidak memiliki akses.");
}

$message = '';

// Proses penyimpanan tugas jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input: judul harus diisi
    if (empty($_POST['title'])) {
        $message = "Judul tugas harus diisi.";
    } else {
        $title       = trim($_POST['title']);
        $description = isset($_POST['description']) ? trim($_POST['description']) : "";
        $due_date    = (!empty($_POST['due_date'])) ? $_POST['due_date'] : null;
        $priority    = (!empty($_POST['priority'])) ? $_POST['priority'] : "Biasa";

        // Insert data tugas ke tabel tasks
        $sqlTask = "INSERT INTO tasks (user_id, title, description, due_date, priority) 
                    VALUES (:user_id, :title, :description, :due_date, :priority)";
        $stmtTask = $pdo->prepare($sqlTask);
        try {
            $stmtTask->execute([
                'user_id'     => $user_id,
                'title'       => $title,
                'description' => $description,
                'due_date'    => $due_date,
                'priority'    => $priority
            ]);

            // Ambil ID tugas yang baru dibuat
            $task_id = $pdo->lastInsertId();

            // Insert relasi tugas dengan proyek ke tabel task_project
            $sqlRelation = "INSERT INTO task_project (task_id, project_id)
                            VALUES (:task_id, :project_id)";
            $stmtRelation = $pdo->prepare($sqlRelation);
            $stmtRelation->execute([
                'task_id'    => $task_id,
                'project_id' => $project_id
            ]);

            // Catat aksi pembuatan tugas ke tabel task_logs
            $sqlLog = "INSERT INTO task_logs (task_id, user_id, action) 
                       VALUES (:task_id, :user_id, 'Dibuat dan diassign ke proyek')";
            $stmtLog = $pdo->prepare($sqlLog);
            $stmtLog->execute([
                'task_id' => $task_id,
                'user_id' => $user_id
            ]);

            $message = "Tugas berhasil dibuat dan diassign ke proyek.";
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
    <title>Buat Tugas untuk Proyek - To-Do List App</title>
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
    <h2>Buat Tugas untuk Proyek: <?= htmlspecialchars($project['name']) ?></h2>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form action="buat_tugas_project.php?project_id=<?= htmlspecialchars($project_id) ?>" method="post">
        <label for="title">Judul Tugas:</label>
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

        <input type="submit" value="Buat Tugas">
    </form>
    <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
</body>
</html>
