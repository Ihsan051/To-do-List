<?php
// dashboard.php
session_start();
require 'database/koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data tugas berdasarkan user_id
$sql = "SELECT * FROM tasks WHERE user_id = :user_id ORDER BY due_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$tasks = $stmt->fetchAll();

// Ambil data proyek berdasarkan user_id
$sql = "SELECT * FROM projects WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - To-Do List App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .logout {
            float: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .action a {
            margin-right: 10px;
        }
        h2, h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <h2>Dashboard</h2>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong>! <a href="logout.php" class="logout">Logout</a></p>

    <!-- Tampilan Daftar Tugas -->
    <h3>Daftar Tugas</h3>
    <p><a href="buat_tugas.php">Buat Tugas Baru</a></p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($tasks) > 0): ?>
                <?php foreach($tasks as $task): ?>
                    <tr>
                        <td><?= $task['id'] ?></td>
                        <td><?= htmlspecialchars($task['title']) ?></td>
                        <td><?= htmlspecialchars($task['description']) ?></td>
                        <td><?= $task['due_date'] ?></td>
                        <td><?= $task['status'] ?></td>
                        <td class="action">
                            <a href="update_tugas.php?id=<?= $task['id'] ?>">Edit</a>
                            <a href="hapus_tugas.php?id=<?= $task['id'] ?>" onclick="return confirm('Yakin ingin menghapus tugas ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada tugas yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tampilan Daftar Proyek -->
    <h3>Daftar Proyek</h3>
    <p><a href="buat_project.php">Buat Proyek Baru</a></p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Proyek</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($projects) > 0): ?>
                <?php foreach($projects as $project): ?>
                    <tr>
                        <td><?= $project['id'] ?></td>
                        <td><?= htmlspecialchars($project['name']) ?></td>
                        <td class="action">
                            <!-- Link ke halaman detail proyek -->
                            <a href="project_detail.php?project_id=<?= $project['id'] ?>">Lihat Tugas</a>
                            <a href="update_project.php?id=<?= $project['id'] ?>">Edit</a>
                            <a href="delete_project.php?id=<?= $project['id'] ?>" onclick="return confirm('Yakin ingin menghapus proyek ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Tidak ada proyek yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
