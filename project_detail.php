<?php
// project_detail.php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pastikan parameter project_id tersedia
if (!isset($_GET['project_id'])) {
    die("Project ID tidak tersedia.");
}

$project_id = $_GET['project_id'];
$user_id = $_SESSION['user_id'];

// Ambil detail proyek dan pastikan proyek tersebut milik pengguna
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

// Ambil tugas-tugas yang terkait dengan proyek melalui tabel task_project
$sql = "SELECT t.* FROM tasks t 
        INNER JOIN task_project tp ON t.id = tp.task_id
        WHERE tp.project_id = :project_id AND t.user_id = :user_id
        ORDER BY t.due_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'project_id' => $project_id,
    'user_id'    => $user_id
]);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Proyek - <?= htmlspecialchars($project['name']) ?> - To-Do List App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 0 10px;
        }
        h2, h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
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
    </style>
</head>
<body>
    <h2>Detail Proyek: <?= htmlspecialchars($project['name']) ?></h2>
    <p><a href="dashboard.php">Kembali ke Dashboard</a></p>

    <h3>Daftar Tugas untuk Proyek Ini</h3>
    <!-- Link untuk menambahkan tugas baru ke proyek -->
    <p><a href="buat_tugas_project.php?project_id=<?= htmlspecialchars($project_id) ?>">Tambah Tugas ke Proyek</a></p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul Tugas</th>
                <th>Deskripsi</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>Status</th>
                <th>Prioritas</th>
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
                        <td><?= htmlspecialchars($task['due_date']) ?></td>
                        <td><?= htmlspecialchars($task['status']) ?></td>
                        <td><?= htmlspecialchars($task['priority']) ?></td>
                        <td class="action">
                            <a href="update_tugas.php?id=<?= $task['id'] ?>">Edit</a>
                            <a href="hapus_tugas.php?id=<?= $task['id'] ?>" onclick="return confirm('Yakin ingin menghapus tugas ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Tidak ada tugas untuk proyek ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
