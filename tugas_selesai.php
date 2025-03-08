<?php
session_start();
require 'database/function.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Ambil data tugas yang sudah selesai
$sql = "SELECT * FROM tugas WHERE user_id = $user_id AND status = 'Selesai' ORDER BY tengat_waktu ASC";
$tugasSelesai = query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tugas Selesai</title>
  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <!-- Google Fonts (Poppins) -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f6f7f9;
      color: #2d2d2d;
      margin: 0;
      padding: 20px;
    }
    .card {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="text-center mt-4">Tugas Selesai</h2>
    <div id="taskList" class="mt-4">
        <?php if ($tugasSelesai): ?>
          <?php foreach ($tugasSelesai as $task): ?>
            <div class="card mt-4" style="width: 100%;">
              <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                  <p class="mb-0"><?php echo htmlspecialchars($task['tengat_waktu']); ?></p>
                  <p class="mb-0"><?php echo htmlspecialchars($task['prioritas']); ?></p>
                </div>
                <h5 class="card-title"><?php echo htmlspecialchars($task['judul']); ?></h5>
                <?php if (!empty($task['deskripsi'])): ?>
                  <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo htmlspecialchars($task['deskripsi']); ?></h6>
                <?php endif; ?>
                <!-- Tombol aksi: Selesai, Edit, Hapus -->
                <div class="d-flex flex-row-reverse">
                  <!-- Ketika tombol Selesai ditekan, status tugas diubah dan card tidak akan tampil di index -->
                  <a href="edit_tugas.php?task_id=<?php echo $task['id']; ?>" class="btn btn-warning mx-2 btn-sm">Edit</a>
                  <a href="hapus_tugas.php?task_id=<?php echo $task['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">Hapus</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="mt-4 text-center">Belum ada tugas selesai</p>
        <?php endif; ?>
      </div>
    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-primary">Kembali ke Daftar Tugas</a>
    </div>
  </div>
  <!-- Bootstrap 5 JS Bundle (termasuk Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
