<?php
session_start();
require 'database/function.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
$user_id = $_SESSION['user_id'];

$message = '';

$sql = "SELECT * FROM tugas WHERE user_id = $user_id AND status != 'Selesai' ORDER BY tengat_waktu ASC";
$tugas = query($sql);

$sqlKategori = "SELECT * FROM kategori WHERE user_id = $user_id";
$kategori = query($sqlKategori);

$sqlUser = "SELECT name FROM users WHERE id = $user_id";
$user = query($sqlUser)[0];

$notifikasi = getNotifikasi($user_id);

if (isset($_POST['tambahTugas'])) {
  if (tambahTugas($_POST) > 0) {
    echo "<script>alert('Tugas berhasil ditambahkan');</script>";
    header("location: index.php");
    exit;
  } else {
    echo "<script>alert('Tugas gagal ditambahkan');</script>";
  }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
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
    #hide-desktop{
      display: none;
    }

    @media (max-width: 768px) {
      #sidebar {
        display: none;
      }
      #hide-mobile{
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

    <?php include 'sidebar.php'; ?>

    <!-- Konten Utama -->
    <div id="content">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Tugas</h3>
        <div class="d-flex align-items-center gap-3">

          <!-- button tambah tugas (desktop)  -->
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTugas" id="hide-mobile">
            <i class="bi bi-plus-circle"></i> Tambah
          </button>

          <!-- button notifikasi  -->
          <a href="notifikasi.php" class="position-relative text-dark">
            <i class="bi bi-bell" style="font-size: 1.8rem;"></i>
            <?php if (!empty($notifikasi)): ?>
              <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"></span>
            <?php endif; ?>
          </a>
        </div>

      </div>

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

    <!-- button tambah tugas (mobile)  -->
    <button class="btn btn-primary rounded-circle shadow mb-5" id="hide-desktop"
      style="position: fixed; bottom: 70px; right: 20px; z-index: 999;"
      data-bs-toggle="modal" data-bs-target="#modalTambahTugas">
      <i class="bi bi-plus-lg" style="font-size: 1.5rem;"></i>
    </button>

    <!-- Modal Tambah Tugas -->
    <div class="modal fade" id="modalTambahTugas" tabindex="-1" aria-labelledby="modalTambahTugasLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post">
            <div class="modal-header">
              <h5 class="modal-title" id="modalTambahTugasLabel">Tambah Tugas</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" id="judul" name="judul" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" maxlength="25" required></textarea>
              </div>
              <div class="mb-3">
                <label for="kategori_id" class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select">
                  <option value="">-- Pilih Kategori --</option>
                  <?php foreach ($kategori as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nama']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="tengatWaktu" class="form-label">Deadline</label>
                  <input type="date" name="tengatWaktu" class="form-control" min="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="prioritas" class="form-label">Prioritas</label>
                  <select name="prioritas" class="form-select" required>
                    <option value="Biasa">Biasa</option>
                    <option value="Penting">Penting</option>
                    <option value="SangatPenting">Sangat Penting</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="tambahTugas" class="btn btn-primary w-100">Tambah</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- navigasi bawah (mobile only)  -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


