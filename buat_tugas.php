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
