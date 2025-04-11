<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
  header("Location: notifikasi.php");
  exit;
}

$id = (int)$_GET['id'];

if (!isset($_SESSION['dismissed_notifikasi'])) {
  $_SESSION['dismissed_notifikasi'] = [];
}

// tambahkan ID tugas ke daftar yang diabaikan
$_SESSION['dismissed_notifikasi'][] = $id;

header("Location: notifikasi.php");
exit;
