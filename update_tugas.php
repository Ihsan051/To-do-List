<?php
// update_task.php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Pastikan parameter task id tersedia
if (!isset($_GET['id'])) {
    die("Task ID tidak disediakan.");
}

$task_id = $_GET['id'];

// Ambil data tugas dan verifikasi kepemilikan
$sql = "SELECT * FROM tasks WHERE id = :task_id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'task_id' => $task_id,
    'user_id' => $user_id
]);
$task = $stmt->fetch();

if (!$task) {
    die("Task tidak ditemukan atau Anda tidak memiliki akses.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date    = (!empty($_POST['due_date'])) ? $_POST['due_date'] : null;
    $priority    = (!empty($_POST['priority'])) ? $_POST['priority'] : "Biasa";
    $status      = (!empty($_POST['status'])) ? $_POST['status'] : $task['status'];
    
    // Validasi: judul harus diisi
    if (empty($title)) {
        $message = "Judul harus diisi.";
    } else {
        // Update data tugas
        $updateSql = "UPDATE tasks 
                      SET title = :title, description = :description, due_date = :due_date, priority = :priority, status = :status 
                      WHERE id = :task_id AND user_id = :user_id";
        $stmtUpdate = $pdo->prepare($updateSql);
        try {
            $stmtUpdate->execute([
                'title'    => $title,
                'description' => $description,
                'due_date' => $due_date,
                'priority' => $priority,
                'status'   => $status,
                'task_id'  => $task_id,
                'user_id'  => $user_id
            ]);
            
            // Catat aksi update ke tabel task_logs
            $sqlLog = "INSERT INTO task_logs (task_id, user_id, action) 
                       VALUES (:task_id, :user_id, 'Diupdate')";
            $stmtLog = $pdo->prepare($sqlLog);
            $stmtLog->execute([
                'task_id' => $task_id,
                'user_id' => $user_id
            ]);
            
            $message = "Task berhasil diperbarui.";
            
            // Re-fetch data tugas yang sudah diupdate
            $sql = "SELECT * FROM tasks WHERE id = :task_id AND user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'task_id' => $task_id,
                'user_id' => $user_id
            ]);
            $task = $stmt->fetch();
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
    <title>Edit Task - To-Do List App</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 0 10px; }
        h2 { border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"],
        input[type="date"],
        select,
        textarea { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        textarea { height: 100px; }
        input[type="submit"] { margin-top: 15px; padding: 10px 20px; }
        .message { margin-top: 15px; padding: 10px; background: #f0f0f0; border-left: 4px solid #007BFF; }
    </style>
</head>
<body>
    <h2>Edit Task</h2>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <form action="update_tugas.php?id=<?= htmlspecialchars($task_id) ?>" method="post">
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
        
        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description"><?= htmlspecialchars($task['description']) ?></textarea>
        
        <label for="due_date">Tanggal Jatuh Tempo:</label>
        <input type="date" id="due_date" name="due_date" value="<?= htmlspecialchars($task['due_date']) ?>">
        
        <label for="priority">Prioritas:</label>
        <select id="priority" name="priority">
            <option value="Biasa" <?= $task['priority'] == 'Biasa' ? 'selected' : '' ?>>Biasa</option>
            <option value="Penting" <?= $task['priority'] == 'Penting' ? 'selected' : '' ?>>Penting</option>
        </select>
        
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="Belum Selesai" <?= $task['status'] == 'Belum Selesai' ? 'selected' : '' ?>>Belum Selesai</option>
            <option value="Selesai" <?= $task['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>
        
        <input type="submit" value="Update Tugas">
    </form>
    <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
</body>
</html>
