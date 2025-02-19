<?php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data tugas yang sudah selesai
$sql = "SELECT * FROM tasks WHERE user_id = :user_id AND status = 'Selesai' ORDER BY due_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$completed_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <div class="mt-4">
      <?php if ($completed_tasks): ?>
        <?php foreach ($completed_tasks as $task): ?>
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between mb-3">
                <p class="mb-0">
                  <?php 
                    if (!empty($task['due_date'])) {
                      setlocale(LC_TIME, 'id_ID.UTF-8');
                      echo strftime("%e %B %Y", strtotime($task['due_date']));
                    } else {
                      echo "Tidak ada tanggal";
                    }
                  ?>
                </p>
                <p class="mb-0"><?php echo htmlspecialchars($task['priority']); ?></p>
              </div>
              <h5 class="card-title"><?php echo htmlspecialchars($task['title']); ?></h5>
              <?php if (!empty($task['description'])): ?>
                <h6 class="card-subtitle mb-2 text-body-secondary"><?php echo htmlspecialchars($task['description']); ?></h6>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center mt-4">Belum ada tugas selesai.</p>
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
