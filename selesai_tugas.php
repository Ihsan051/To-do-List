<?php
// selesai_tugas.php
session_start();
require 'database/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil id tugas dari URL
$id = $_GET['id'];

// Update status tugas menjadi selesai
$sql = "UPDATE tasks SET status = 'Selesai' WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);

// Redirect ke dashboard
header("Location: dashboard.php");
exit;
?>