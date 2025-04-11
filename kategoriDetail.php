<?php
session_start();
require 'database/function.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Pastikan kategori_id tersedia
if (!isset($_GET['kategori_id']) || empty($_GET['kategori_id'])) {
  header("Location: index.php");
  exit;
}

$kategori_id = $_GET['kategori_id'];
$namaKategori = $_GET['namaKategori'];

// Ambil data kategori
$sql = "SELECT * FROM kategori WHERE id = $kategori_id AND user_id = $user_id ";
$kategori = query($sql);

// ambil data user 
$sqlUser = "SELECT name FROM users WHERE id = $user_id";
$user = query($sqlUser)[0];

if (!$kategori) {
  echo "<script>alert('Kategori tidak ditemukan!'); window.location='index.php';</script>";
  exit;
}

// Ambil tugas berdasarkan kategori
$sql = "SELECT * FROM tugas WHERE kategori_id = $kategori_id AND user_id = $user_id AND status != 'Selesai' ORDER BY tengat_waktu ASC";
$tugas = query($sql);
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
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    .card {
      background: #fff;
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
      margin-bottom: 20px;
    }

    .card-body {
      padding: 20px;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #eef4f7;
      color: #333;
    }

    .wrapper {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    #sidebar {
      background: #ffffff;
      border-right: 1px solid #ddd;
      width: 250px;
      display: flex;
      flex-direction: column;
      padding: 20px;
    }

    #sidebar a {
      color: #333;
      text-decoration: none;
      padding: 10px;
      display: block;
      border-radius: 6px;
      transition: background 0.3s;
    }

    #sidebar a:hover {
      background: #007bff;
      color: #fff;
    }

    #content {
      flex-grow: 1;
      padding: 30px;
      overflow-y: auto;
      padding-bottom: 100px;
    }

    .navbar-bottom {
      display: none;
    }

    @media (max-width: 768px) {
      #sidebar {
        display: none;
      }

      .navbar-bottom {
        display: flex;
        justify-content: space-around;
        position: fixed;
        bottom: 0;
        width: 100%;
        background: #fff;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
        z-index: 999;
      }

      .navbar-bottom a {
        text-align: center;
        color: #333;
        text-decoration: none;
        font-size: 0.9rem;
      }

      .navbar-bottom i {
        font-size: 1.2rem;
        display: block;
      }
    }
  </style>
</head>

<body>
  <div class="wrapper">

    <!-- Sidebar (Desktop Only) -->
    <?php include 'sidebar.php'; ?>

    <div id="content">
      <h2 class="text-center mt-4"> <?php echo $namaKategori ?></h2>
      <?php
      $prioritasUrut = ['SangatPenting' => 'Sangat Penting', 'Penting' => 'Penting', 'Biasa' => 'Biasa'];
      $warnaPrioritas = [
        'SangatPenting' => 'danger',   // merah
        'Penting' => 'warning',        // kuning
        'Biasa' => 'info'              // biru
      ];
      foreach ($prioritasUrut as $kode => $label):
        $filtered = array_filter($tugas, fn($t) => $t['prioritas'] === $kode);
        if (count($filtered) > 0):
      ?>
          <?php foreach ($filtered as $task): ?>
            <div class="card mb-3 shadow-sm">
              <div class="card-body bg-light">
                <div class="d-flex justify-content-between text-muted mb-2">
                  <small><?= htmlspecialchars($task['tengat_waktu']) ?></small>
                  <small class="text-<?= $warnaPrioritas[$kode] ?> "><?= $label ?></small>
                </div>
                <h5 class="card-title"><?= htmlspecialchars($task['judul']) ?></h5>
                <?php if (!empty($task['deskripsi'])): ?>
                  <p class="card-text"><?= htmlspecialchars($task['deskripsi']) ?></p>
                <?php endif; ?>
                <div class="d-flex justify-content-end">
                  <a href="selesai.php?task_id=<?= $task['id'] ?>" class="btn btn-success btn-sm me-2">Selesai</a>
                  <a href="edit_tugas.php?task_id=<?= $task['id'] ?>" class="btn btn-warning btn-sm me-2">Edit</a>
                  <a href="hapus_tugas.php?task_id=<?= $task['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus tugas ini?')">Hapus</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
      <?php endif;
      endforeach; ?>

      <?php if (empty($tugas)): ?>
        <div class="alert alert-info text-center">Belum ada tugas.</div>
      <?php endif; ?>
    </div>

    <!-- navigasi bawah (mobile only)  -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>