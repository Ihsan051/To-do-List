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
        project_id int ,
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
    task_id int not null ,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE
)";

if($conn->query($project) === TRUE){
echo "table project berhasil dibuat";
}else{
echo "table project gagal dibuat" . $conn->error;
}
echo "<br>";

$relasi = "ALTER TABLE tasks ADD FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE";

if($conn->query($relasi) === TRUE){
    echo "relasi berhasil dibuat";
}else{
    echo "relasi gagal dibuat";
}


