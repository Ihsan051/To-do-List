<?php
// Membuat koneksi ke databse
$host = "localhost";
$username = "root";
$password = "";
$dbName = "todolist";

$conn = mysqli_connect($host, $username, $password, $dbName);

// mengecek apakah koneksi ke database berhasil
if(!$conn){
    echo "koneksi gagal" . mysqli_connect_error($conn);
}

// membuat fungsi untuk melakukan query 
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row; 
    }
    return $rows;
}

// membuat fungsi tambah tugas
function tambahTugas($data){
    global $conn;

    $user_id = $_SESSION['user_id'];
    $kategori_id = isset($data['kategori_id']) ? (int)$data['kategori_id'] : 'NULL';
    $judul = htmlspecialchars($data['judul']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $tengatWaktu = htmlspecialchars($data['tengatWaktu']);
    $prioritas = isset($data['prioritas']) ? htmlspecialchars($data['prioritas']) : 'Biasa'; // Default 'Biasa'

    // Query insert dengan menentukan kolom yang akan diisi
    $query = "INSERT INTO tugas (user_id, kategori_id, judul, deskripsi, tengat_waktu, prioritas) 
              VALUES ('$user_id', $kategori_id, '$judul', '$deskripsi', '$tengatWaktu', '$prioritas')";

    // Jalankan query
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// membuat fungsi edit tugas
function editTugas($data){
    global $conn;

    $id = (int)$data['id'];
    $kategori_id = isset($data['kategori_id']) ? (int)$data['kategori_id'] : 'NULL';
    $judul = htmlspecialchars($data['judul']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $tengatWaktu = htmlspecialchars($data['tengatWaktu']);
    $prioritas = isset($data['prioritas']) ? htmlspecialchars($data['prioritas']) : 'Biasa'; // Default 'Biasa'

    // Query update dengan menentukan kolom yang akan diubah
    $query = "UPDATE tugas SET 
              kategori_id = $kategori_id, 
              judul = '$judul', 
              deskripsi = '$deskripsi', 
              tengat_waktu = '$tengatWaktu', 
              prioritas = '$prioritas' 
              WHERE id = $id";

    // Jalankan query
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

// membuat fungsi hapus tugas
function hapus($id){
    global $conn;
    mysqli_query($conn," DELETE FROM tugas WHERE id = '$id' ");
    return mysqli_affected_rows($conn);
}

// membuat fungsi selesaikan tugas
function selesai($id){
    global $conn;
    mysqli_query($conn," update tugas set status = 'Selesai' where id = '$id' ");
    return mysqli_affected_rows($conn);
}

// membuat fungsi tambah kategori
function tambahKategori($data){
    global $conn;

    $user_id = $_SESSION['user_id'];
    $nama = htmlspecialchars($data['inputKategori']);

    // Perbaiki query insert dengan menambahkan koma yang hilang
    $query = "INSERT INTO kategori (user_id, nama) VALUES ('$user_id', '$nama')";

    // Jalankan query
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}



// membuat fungsi register
function register($data){
    global $conn;
    $nama = $data['name'];
    $email = $data['email'];
    $password = $data['password'];
    $password2 = $data['confirm_password'];

    // cek apakah password dan konfirmasi password sama
    if ($password !== $password2) {
        return "Password tidak sama";
    }

    // cek apakah email sudah ada dalam database
    $result = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
    if (mysqli_fetch_assoc($result)) {
        return "Email sudah terdaftar";
    }

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // tambah data ke database
    $query = "INSERT INTO users (name, email, password, created_at) VALUES ('$nama', '$email', '$password', NOW())";
    mysqli_query($conn, $query);

    if (mysqli_affected_rows($conn) > 0) {
        return "Registrasi berhasil silahkan <a href='login.php'>Login</a>";
    } else {
        return "Registrasi gagal";
    }
}

