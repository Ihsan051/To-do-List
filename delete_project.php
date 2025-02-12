<?php
// delete_project.php
session_start();
require 'database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Pastikan parameter project id tersedia
if (!isset($_GET['id'])) {
    die("Project ID tidak disediakan.");
}

$project_id = $_GET['id'];

// Verifikasi apakah proyek tersebut milik pengguna
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

try {
    // Hapus proyek dari tabel projects
    $sqlDelete = "DELETE FROM projects WHERE id = :project_id AND user_id = :user_id";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([
        'project_id' => $project_id,
        'user_id'    => $user_id
    ]);
    
    // Karena pada tabel task_project relasi diatur dengan ON DELETE CASCADE,
    // relasi antara proyek dan tugas akan terhapus otomatis.
    
    // Redirect ke dashboard setelah penghapusan proyek
    header("Location: dashboard.php");
    exit;
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
