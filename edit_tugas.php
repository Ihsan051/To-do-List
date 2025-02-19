<?php
session_start();
require 'database/koneksi.php';

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

$task_id = $_GET['task_id'];
$message = '';

// Jika form telah disubmit, proses pembaruan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = isset($_POST['description']) ? trim($_POST['description']) : "";
    $due_date = (!empty($_POST['due_date'])) ? $_POST['due_date'] : null;
    $priority = (!empty($_POST['priority'])) ? $_POST['priority'] : "Biasa";

    if (empty($title)) {
        $message = "Judul harus diisi.";
    } else {
        $sql = "UPDATE tasks 
                SET title = :title, description = :description, due_date = :due_date, priority = :priority 
                WHERE id = :task_id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'title'       => $title,
            'description' => $description,
            'due_date'    => $due_date,
            'priority'    => $priority,
            'task_id'     => $task_id,
            'user_id'     => $_SESSION['user_id']
        ]);
        // Setelah update, arahkan kembali ke index.php
        header("Location: index.php");
        exit;
    }
} else {
    // Jika metode GET, ambil data tugas untuk ditampilkan di form
    $sql = "SELECT * FROM tasks WHERE id = :task_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'task_id' => $task_id,
        'user_id' => $_SESSION['user_id']
    ]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$task) {
        header("Location: index.php");
        exit;
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
        <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($task['title']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea id="description" name="description" class="form-control"><?php echo htmlspecialchars($task['description']); ?></textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="due_date" class="form-label">Tanggal Jatuh Tempo</label>
          <input type="date" id="due_date" name="due_date" class="form-control" value="<?php echo htmlspecialchars($task['due_date']); ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label for="priority" class="form-label">Prioritas</label>
          <select id="priority" name="priority" class="form-select">
            <option value="Biasa" <?php if ($task['priority'] == 'Biasa') echo 'selected'; ?>>Biasa</option>
            <option value="Penting" <?php if ($task['priority'] == 'Penting') echo 'selected'; ?>>Penting</option>
            <option value="SangatPenting" <?php if ($task['priority'] == 'SangatPenting') echo 'selected'; ?>>Sangat Penting</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Perbarui Tugas</button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
