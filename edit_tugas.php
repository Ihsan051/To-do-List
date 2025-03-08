<?php
session_start();
require_once 'database/function.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pastikan task_id ada di parameter GET
if (!isset($_GET['task_id'])) {
    header("Location: index.php");
    exit;
}

// Ambil data tugas berdasarkan task_id
$task_id = (int)$_GET['task_id'];
$task = query("SELECT * FROM tugas WHERE id = $task_id")[0];

// cek apakah tombol edit tugas sudah ditekan
if (isset($_POST['edit'])) {
    // tambahkan task_id ke dalam data POST
    $_POST['id'] = $task_id;

    // panggil fungsi editTugas dan cek hasilnya
    if ( editTugas($_POST ) > 0) {
        echo "<script>
            alert('Tugas berhasil diperbarui');
            window.location.href = 'index.php';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Tugas gagal diperbarui');
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Tugas</title>
  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
  <!-- Google Fonts (Poppins) -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
</head>
<body>
  <div class="container">
    <h2 class="mt-4">Edit Tugas</h2>
    <?php if (!empty($message)) : ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="" method="post">
      <div class="mb-3">
        <label for="title" class="form-label">Judul Tugas</label>
        <input type="text" id="title" name="judul" class="form-control" value="<?php echo htmlspecialchars($task['judul']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea id="description" name="deskripsi" class="form-control"><?php echo htmlspecialchars($task['deskripsi']); ?></textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="due_date" class="form-label">Tanggal Jatuh Tempo</label>
          <input type="date" id="due_date" name="tengatWaktu" class="form-control" value="<?php echo htmlspecialchars($task['tengat_waktu']); ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label for="priority" class="form-label">Prioritas</label>
          <select id="priority" name="prioritas" class="form-select">
            <option value="Biasa" <?php if ($task['prioritas'] == 'Biasa') echo 'selected'; ?>>Biasa</option>
            <option value="Penting" <?php if ($task['prioritas'] == 'Penting') echo 'selected'; ?>>Penting</option>
            <option value="SangatPenting" <?php if ($task['prioritas'] == 'SangatPenting') echo 'selected'; ?>>Sangat Penting</option>
          </select>
        </div>
      </div>
      <button type="submit" name="edit" class="btn btn-primary">Perbarui Tugas</button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>