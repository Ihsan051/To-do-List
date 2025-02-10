<?php
// delete_task.php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    die("Task ID tidak disediakan.");
}

$task_id = $_GET['id'];

// Verifikasi apakah tugas tersebut milik pengguna
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

try {
    // Pertama, catat log penghapusan (sebelum tugas dihapus)
    $sqlLog = "INSERT INTO task_logs (task_id, user_id, action) VALUES (:task_id, :user_id, 'Dihapus')";
    $stmtLog = $pdo->prepare($sqlLog);
    $stmtLog->execute([
        'task_id' => $task_id,
        'user_id' => $user_id
    ]);
    
    // Kemudian, hapus tugas dari tabel tasks
    $sqlDelete = "DELETE FROM tasks WHERE id = :task_id AND user_id = :user_id";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([
        'task_id' => $task_id,
        'user_id' => $user_id
    ]);
    
    // Jika constraint foreign key dengan ON DELETE CASCADE aktif, maka baris terkait di tabel task_logs akan dihapus
    // Namun, karena log penghapusan sudah tercatat, sebaiknya constraint tersebut diubah atau log disimpan di tabel lain
    header("Location: dashboard.php");
    exit;
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
