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

// Ambil data kategori
$sql = "SELECT * FROM kategori WHERE id = $kategori_id AND user_id = $user_id ";
$kategori = query($sql);

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kategori: <?php echo htmlspecialchars($kategori[0]['nama']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Kategori: <?php echo htmlspecialchars($kategori[0]['nama']); ?></h2>
        <a href="index.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>

        <?php if ($tugas): ?>
            <div class="list-group">
                <?php foreach ($tugas as $task): ?>
                    <div class="list-group-item">
                        <h5><?php echo htmlspecialchars($task['judul']); ?></h5>
                        <p class="mb-1"><?php echo htmlspecialchars($task['deskripsi']); ?></p>
                        <small class="text-muted">Tenggat: <?php echo htmlspecialchars($task['tengat_waktu']); ?> | Prioritas: <?php echo htmlspecialchars($task['prioritas']); ?></small>
                        <div class="mt-2">
                            <a href="selesai.php?task_id=<?php echo $task['id']; ?>" class="btn btn-success btn-sm">Selesai</a>
                            <a href="edit_tugas.php?task_id=<?php echo $task['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus_tugas.php?task_id=<?php echo $task['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus tugas ini?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center mt-4">Tidak ada tugas dalam kategori ini.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
