<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Todolist";

$conn = new mysqli($servername, $username, $password);

if (!$conn) {
    echo "Koneksi error";
}
$sql = "DROP DATABASE IF EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database dropped successfully<br>";
} else {
    echo "Error dropping database: " . $conn->error . "<br>";
}

$sql = "CREATE DATABASE $dbname";
if (!$conn->query($sql) === TRUE) {
    echo "Database gagal dibuat";
}


$conn->select_db($dbname);

$user = "CREATE TABLE users (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) UNIQUE NOT NULL,
                    profil varchar(100),
                    password VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

if ($conn->query($user) === TRUE) {
    echo "table user berhasil dibuat";
} else {
    echo "table user gagal dibuat" . $conn->error;
}
echo "<br>";

$tugas = "CREATE TABLE tugas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    kategori_id INT NULL,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    tengat_waktu DATE NOT NULL,
    prioritas ENUM('Penting', 'Biasa', 'SangatPenting') DEFAULT 'Biasa',
    status ENUM('Belum Selesai', 'Selesai') DEFAULT 'Belum Selesai',
    dilihat BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";


if ($conn->query($tugas) === TRUE) {
    echo "table user berhasil dibuat";
} else {
    echo "table user gagal dibuat" . $conn->error;
}
echo "<br>";

$kategori = "CREATE TABLE kategori (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($kategori) === TRUE) {
    echo "table kategori berhasil dibuat";
} else {
    echo "table kategori gagal dibuat" . $conn->error;
}
echo "<br>";

$relasi = "ALTER TABLE tugas ADD FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE";

if ($conn->query($relasi) === TRUE) {
    echo "relasi berhasil dibuat";
} else {
    echo "relasi gagal dibuat";
}
