<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Todolist";

$conn = new mysqli($servername, $username, $password);

if(!$conn){
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
                    password VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

if($conn->query($user) === TRUE){
    echo "table user berhasil dibuat";
}else{
    echo "table user gagal dibuat" . $conn->error;
}
echo "<br>";

$tugas = "CREATE TABLE tasks (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT NULL,
        due_date DATE NULL,
        priority ENUM('Penting', 'Biasa') DEFAULT 'Biasa',
        status ENUM('Belum Selesai', 'Selesai') DEFAULT 'Belum Selesai',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE

)";

if($conn->query($tugas) === TRUE){
echo "table user berhasil dibuat";
}else{
echo "table user gagal dibuat" . $conn->error;
}
echo "<br>";

$project = "CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";


if($conn->query($project) === TRUE){
echo "table project berhasil dibuat";
}else{
echo "table project gagal dibuat" . $conn->error;
}
echo "<br>";

$task_project = "CREATE TABLE task_project (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    project_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
)";


if($conn->query($task_project) === TRUE){
echo "table task project berhasil dibuat";
}else{
echo "table task project gagal dibuat" . $conn->error;
}
echo "<br>";

$task_logs = "CREATE TABLE task_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    action enum ('Dibuat', 'Diedit', 'Diselesaikan') NOT NULL,  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE

)";


if($conn->query($task_logs) === TRUE){
echo "table task logs berhasil dibuat";
}else{
echo "table task logs gagal dibuat" . $conn->error;
}
echo "<br>";


$categories = "CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
)";


if($conn->query($categories) === TRUE){
echo "table kategori berhasil dibuat";
}else{
echo "table kategori gagal dibuat" . $conn->error;
}
echo "<br>";

$task_kategori = "CREATE TABLE task_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
)";


if($conn->query($task_kategori) === TRUE){
echo "table kategori berhasil dibuat";
}else{
echo "table kategori gagal dibuat" . $conn->error;
}
echo "<br>";


