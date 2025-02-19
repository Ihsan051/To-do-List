<?php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pastikan task_id dikirim via GET
if (!isset($_GET['task_id'])) {
    header("Location: index.php");
    exit;
}

$task_id = $_GET['task_id'];

// Update status tugas menjadi 'Selesai'
$sql = "UPDATE tasks SET status = 'Selesai' WHERE id = :task_id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'task_id' => $task_id,
    'user_id' => $_SESSION['user_id']
]);

// Setelah update, redirect ke halaman tugas selesai
header("Location: tugas_selesai.php");
exit;
?>
