<?php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

// PROSES PENYIMPANAN TASK (jika form disubmit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi: judul harus diisi
    if (empty($_POST['title'])) {
        $message = "Judul harus diisi.";
    } else {
        $title       = trim($_POST['title']);
        $description = isset($_POST['description']) ? trim($_POST['description']) : "";
        $due_date    = (!empty($_POST['due_date'])) ? $_POST['due_date'] : null;
        $priority    = (!empty($_POST['priority'])) ? $_POST['priority'] : "Biasa"; // default "Biasa"

        // Insert data tugas ke tabel tasks
        // Pastikan tabel tasks memiliki kolom status dengan default 'Pending'
        $sql = "INSERT INTO tasks (user_id, title, description, due_date, priority, status) 
                VALUES (:user_id, :title, :description, :due_date, :priority, 'Pending')";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([
                'user_id'     => $_SESSION['user_id'],
                'title'       => $title,
                'description' => $description,
                'due_date'    => $due_date,
                'priority'    => $priority
            ]);
            $message = "Task berhasil dibuat.";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

// AMBIL DATA TASK untuk pengguna yang sedang login
// Hanya ambil tugas yang statusnya belum selesai
$sql = "SELECT * FROM tasks WHERE user_id = :user_id AND status != 'Selesai' ORDER BY due_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard</title>
  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <!-- Google Fonts (Poppins) -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- FontAwesome (untuk ikon toggle) -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    /* Gaya dasar */
    body {
      font-family: 'Poppins', sans-serif;
      background: #5faeb6;
      color: #2d2d2d;
      margin: 0;
      padding: 0;
    }
    /* Container utama */
    .wrapper {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }
    /* Sidebar untuk tampilan desktop */
    #sidebar {
      background: #f6f7f9;
      color: #2d2d2d;
      width: 250px;
      min-width: 250px;
      transition: all 0.3s ease;
    }
    #sidebar .sidebar-header {
      padding: 20px;
      background: #f6f7f9;
    }
    #sidebar ul.components {
      padding: 20px 0;
      list-style: none;
    }
    #sidebar ul li a {
      padding: 10px;
      font-size: 1.1em;
      display: block;
      color: #2d2d2d;
      text-decoration: none;
    }
    #sidebar ul li a:hover {
      background: #293c90;
      color: #fff;
    }
    /* Konten halaman */
    #content {
      width: 100%;
      padding: 20px;
      transition: margin-left 0.3s ease;
    }
    /* Navbar untuk tombol toggle */
    .navbar {
      background: #fff;
      padding: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    /* Form tambah tugas */
    .tambah_tugas {
      display: none;
      margin-top: 20px;
    }
    .show {
      display: block;
    }
    .card-form {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    /* Tombol dan layout card */
    .d-flex.flex-row-reverse a {
      text-decoration: none;
    }
    .inputProject{
      display: none;
    }
    .tambahProject{
      display: block;
    }
    /* Responsive: Sidebar pada layar kecil */
    @media (max-width: 768px) {
      #sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 40%;
        height: 100%;
        z-index: 999;
      }
      #sidebar.active {
        left: 0;
      }
      .hr-color {
        background-color: #2d2d2d !important;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
      <div class="sidebar-header d-flex justify-content">
        <img src="profil.svg" class="rounded-5" alt="..." width="55px">
        <h4 class="ms-3 my-auto">M Ihsan</h4>
      </div>
      <hr class="border opacity-85 m-auto hr-color" width="75%">
      <ul class="components">
        <li class="active"><a href="#"><i class="bi bi-calendar"></i> Hari Ini</a></li>
        <li><a href="tugas_selesai.php"><i class="bi bi-calendar-check"></i> Tugas yang selesai</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
      <div class="d-flex justify-content-between">
        <h6 class="ms-3">Projects</h6>
        <button class="border-0 bg-transparent" id="tambahProject"><i class="bi bi-plus-lg"></i></button>
      </div>
      <div class="inputProject mx-2" id="inputProject">
        <div class="input-group mb-3 mt-2">
          <input type="text" name="inputProject" class="form-control" placeholder="Masukan Project" aria-label="Project Name">
          <button class="btn btn-primary" type="submit">OK</button>
        </div>
      </div>
      <hr class="border opacity-85 m-auto hr-color" width="75%">
      <ul class="projects components ms-3">
        <li><i class="bi bi-forward"></i> Workout</li>
      </ul>
    </nav>

    <!-- Konten Halaman -->
    <div id="content" class="overflow-auto">
      <!-- Navbar untuk tombol toggle (hanya muncul di layar kecil) -->
      <nav class="navbar">
        <div class="container-fluid">
          <button type="button" id="sidebarToggle" class="btn d-lg-none">
            <i class="fas fa-bars"></i>
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </nav>
      <h2 class="text-center mt-4">Daftar Tugas</h2>

      <!-- Tampilkan pesan (jika ada) -->
      <?php if (!empty($message)) : ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <div class="d-grid mt-4">
        <button class="btn btn-primary" type="button" id="tambahTugas"><i class="bi bi-plus-lg"></i> Tambah Tugas</button>
      </div>

      <!-- Form tambah tugas -->
      <div class="tambah_tugas" id="inputTugas">
        <div class="card card-form">
          <!-- Form submit ke halaman yang sama -->
          <form action="" method="post">
            <div class="mb-3">
              <label for="title" class="form-label">Judul Tugas</label>
              <input type="text" id="title" name="title" class="form-control" placeholder="Masukkan judul tugas" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi</label>
              <textarea id="description" name="description" class="form-control" placeholder="Masukkan deskripsi tugas (opsional)"></textarea>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="due_date" class="form-label">Tanggal Jatuh Tempo</label>
                <input type="date" id="due_date" name="due_date" class="form-control">
              </div>
              <div class="col-md-6 mb-3">
                <label for="priority" class="form-label">Prioritas</label>
                <select id="priority" name="priority" class="form-select">
                  <option value="Biasa">Biasa</option>
                  <option value="Penting">Penting</option>
                  <option value="SangatPenting">Sangat Penting</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Tambah tugas</button>
          </form>
        </div>
      </div>

      <!-- Tampilkan daftar tugas sebagai card -->
      <div id="taskList" class="mt-4">
        <?php if ($tasks): ?>
          <?php foreach ($tasks as $task): ?>
            <div class="card mt-4" style="width: 100%;">
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
                <!-- Tombol aksi: Selesai, Edit, Hapus -->
                <div class="d-flex flex-row-reverse">
                  <!-- Ketika tombol Selesai ditekan, status tugas diubah dan card tidak akan tampil di index -->
                  <a href="selesai.php?task_id=<?php echo $task['id']; ?>" class="btn btn-success btn-sm">Selesai</a>
                  <a href="edit_tugas.php?task_id=<?php echo $task['id']; ?>" class="btn btn-warning mx-2 btn-sm">Edit</a>
                  <a href="hapus_tugas.php?task_id=<?php echo $task['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">Hapus</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="mt-4 text-center">Belum ada tugas.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap 5 JS Bundle (termasuk Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <!-- Script untuk toggle tampilan form tambah tugas -->
  <script src="script.js"></script>
</body>
</html>
