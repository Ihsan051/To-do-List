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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Custom CSS tambahan */
    body {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">To-Do List App</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item d-flex align-items-center me-2">
            <span class="navbar-text">Selamat datang, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong></span>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Konten Dashboard -->
  <div class="container my-4">
    <div class="row mb-3">
      <div class="col">
        <h2 class="mb-0">Dashboard</h2>
      </div>
    </div>
    <div class="row">
      <!-- Card Daftar Tugas -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tugas</h5>
            <a href="buat_tugas.php" class="btn btn-sm btn-success">Buat Tugas Baru</a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                  <tr class="text-center align-middle">
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
                        <td>
                          <div class="btn-group" role="group" aria-label="Action Buttons">
                            <a href="selesai_tugas.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-success">Selesai</a>
                            <a href="update_tugas.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-warning mx-2">Edit</a>
                            <a href="hapus_tugas.php?id=<?= $task['id'] ?>" onclick="return confirm('Yakin ingin menghapus tugas ini?');" class="btn btn-sm btn-danger">Hapus</a>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center">Tidak ada tugas yang ditemukan.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Daftar Proyek -->
    
    </div>
  </div>

  <!-- Bootstrap JS Bundle (termasuk Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>