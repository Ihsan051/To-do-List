<?php
// Membuat koneksi ke databse
$host = "localhost";
$username = "root";
$password = "";
$dbName = "todolist";

$conn = mysqli_connect($host, $username, $password, $dbName);

// mengecek apakah koneksi ke database berhasil
if (!$conn) {
    echo "koneksi gagal" . mysqli_connect_error($conn);
}

// membuat fungsi untuk melakukan query 
function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambahTugas($data)
{
    global $conn;
    $user_id = $_SESSION['user_id'];
    
    $judul = htmlspecialchars($data['judul']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $tengatWaktu = htmlspecialchars($data['tengatWaktu']);
    $prioritas = isset($data['prioritas']) ? htmlspecialchars($data['prioritas']) : 'Biasa';

    // Cek jika kategori kosong
    if (empty($data['kategori_id'])) {
        // Ambil id kategori "Tanpa Kategori" milik user
        $queryKategori = "SELECT id FROM kategori WHERE user_id = '$user_id' AND nama = 'Tanpa Kategori' LIMIT 1";
        $resultKategori = mysqli_query($conn, $queryKategori);
        if ($row = mysqli_fetch_assoc($resultKategori)) {
            $kategori_id = $row['id'];
        } else {
            // Jika belum ada, buat dulu
            mysqli_query($conn, "INSERT INTO kategori (user_id, nama) VALUES ('$user_id', 'Tanpa Kategori')");
            $kategori_id = mysqli_insert_id($conn);
        }
    } else {
        $kategori_id = (int)$data['kategori_id'];
    }

    // Cek duplikasi tugas
    $cekQuery = "SELECT * FROM tugas WHERE user_id = '$user_id' 
                 AND judul = '$judul' 
                 AND deskripsi = '$deskripsi' 
                 AND tengat_waktu = '$tengatWaktu' 
                 AND prioritas = '$prioritas'";

    $result = mysqli_query($conn, $cekQuery);

    if (mysqli_num_rows($result) > 0) {
        return 0; // Duplikat
    }

    $query = "INSERT INTO tugas (user_id, kategori_id, judul, deskripsi, tengat_waktu, prioritas) 
              VALUES ('$user_id', $kategori_id, '$judul', '$deskripsi', '$tengatWaktu', '$prioritas')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}




// membuat fungsi edit tugas
function editTugas($data)
{
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

function updateUserProfile($user_id, $data, $files) {
    global $conn;

    $name = htmlspecialchars($data['name']);
    $email = htmlspecialchars($data['email']);
    $oldPassword = $data['old_password'];
    $newPassword = $data['new_password'];
    $confirmPassword = $data['confirm_password'];

    $message = "";
    $error = "";

    // Update foto jika ada upload
    if (isset($files['profil']) && $files['profil']['error'] === 0) {
        $imgName = $files['profil']['name'];
        $imgTmp = $files['profil']['tmp_name'];
        $imgExt = pathinfo($imgName, PATHINFO_EXTENSION);
        $imgNew = "profile_" . time() . ".$imgExt";
        move_uploaded_file($imgTmp, "uploads/" . $imgNew);
        mysqli_query($conn, "UPDATE users SET profil = '$imgNew' WHERE id = $user_id");
    }

    // Update nama dan email
    mysqli_query($conn, "UPDATE users SET name = '$name', email = '$email' WHERE id = $user_id");

    // Cek password jika diisi
    if ($oldPassword && $newPassword && $newPassword === $confirmPassword) {
        $user = query("SELECT * FROM users WHERE id = $user_id")[0];
        if (password_verify($oldPassword, $user['password'])) {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password = '$hashed' WHERE id = $user_id");
            $message = "Profil dan password berhasil diperbarui.";
        } else {
            $error = "Password lama salah.";
        }
    } else {
        $message = "Profil berhasil diperbarui.";
        header("Location: profil.php"); 
        exit;
    }

    return [
        "message" => $message,
        "error" => $error
    ];
}

// membuat fungsi hapus tugas
function hapusTugas($id)
{
    global $conn;
    mysqli_query($conn, " DELETE FROM tugas WHERE id = '$id' ");
    return mysqli_affected_rows($conn);
}
// membuat fungsi hapus kategori
function hapusKategori($id)
{
    global $conn;
    mysqli_query($conn, " DELETE FROM kategori WHERE id = '$id' ");
    return mysqli_affected_rows($conn);
}

// membuat fungsi selesaikan tugas
function selesai($id)
{
    global $conn;
    mysqli_query($conn, " update tugas set status = 'Selesai' where id = '$id' ");
    return mysqli_affected_rows($conn);
}

// membuat fungsi tambah kategori
function tambahKategori($data)
{
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
function register($data)
{
    global $conn;
    $nama = htmlspecialchars($data['name']);
    $email = htmlspecialchars($data['email']);
    $password = htmlspecialchars($data['password']);
    $password2 = htmlspecialchars($data['confirm_password']);

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

    // notifikasi 
    function getNotifikasi($user_id)
    {
        $todayDate = date('Y-m-d');
        $sql = "SELECT * FROM tugas WHERE user_id = $user_id AND status != 'Selesai' AND dilihat = 0";
        $tugas = query($sql);
        $notifikasi = [];

        foreach ($tugas as $task) {
            $deadline = new DateTime($task['tengat_waktu']);
            $today = new DateTime();
            $interval = $today->diff($deadline)->days;
            $id = $task['id'];

            if ($interval > 0) {
                $notifikasi[] = [
                    'type' => 'danger',
                    'message' => "Tugas <strong>" . htmlspecialchars($task['judul']) . "</strong> telah melewati tenggat waktu!",
                    'id' => $id
                ];
            } elseif ($interval === 0) {
                $notifikasi[] = [
                    'type' => 'warning',
                    'message' => "Hari ini Deadline dari tugas <strong> " . htmlspecialchars($task['judul']) . "</strong> " . $task['tengat_waktu'],
                    'id' => $id
                ];
            }
        }

        return $notifikasi;
    }
