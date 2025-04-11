<?php
session_start();
require 'database/function.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// ambil id kategori
$kategori_id = $_GET['kategori_id'];

// hapus kategori
if (hapusKategori($kategori_id) > 0) {
    header("location: kategori.php");
} else {
    echo "<script>
            alert(' Kategori gagal dihapus ')      
        </script>";
}
