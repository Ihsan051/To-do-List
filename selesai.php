<?php
session_start();
require 'database/function.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pastikan task_id dikirim via GET
if (!isset($_GET['task_id'])) {
    header("Location: index.php");
    exit;
}

$tugas_id = $_GET['task_id'];

// update status tugas
if( selesai($tugas_id) > 0 ){
    header("location: index.php");
}else{
    echo "<script>
            alert(' Tugas gagal diselesaikan ')      
        </script>";  
}
?>
