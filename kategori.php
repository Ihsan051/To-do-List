<?php
session_start();
require 'database/function.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
$user_id = $_SESSION['user_id'];

$message = '';

// ambil data user 
$sqlUser = "SELECT name FROM users WHERE id = $user_id";
$user = query($sqlUser)[0];

$sqlKategori = "SELECT * FROM kategori WHERE user_id = $user_id";
$kategori = query($sqlKategori);

if (isset($_POST['tambahKategori'])) {
  if (tambahKategori($_POST) > 0) {
    echo "<script>alert('Kategori berhasil ditambahkan');</script>";
    header("location: kategori.php");
    exit;
  } else {
    echo "<script>alert('Kategori gagal ditambahkan');</script>";
  }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kategori Tugas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #eef4f7;
      color: #333;
    }

    .wrapper {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .kategori-card {
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .kategori-card:hover {
      transform: scale(1.02);
      background-color: #f8f9fa;
    }

    #sidebar {
      background: #ffffff;
      border-right: 1px solid #ddd;
      width: 250px;
      display: flex;
      flex-direction: column;
      padding: 20px;
    }

    #sidebar a {
      color: #333;
      text-decoration: none;
      padding: 10px;
      display: block;
      border-radius: 6px;
      transition: background 0.3s;
    }

    #sidebar a:hover {
      background: #007bff;
      color: #fff;
    }

    #content {
      flex-grow: 1;
      padding: 30px;
      overflow-y: auto;
      padding-bottom: 100px;
    }

    .navbar-bottom {
      display: none;
    }

    @media (max-width: 768px) {
      #sidebar {
        display: none;
      }

      .navbar-bottom {
        display: flex;
        justify-content: space-around;
        position: fixed;
        bottom: 0;
        width: 100%;
        background: #fff;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
        z-index: 999;
      }

      .navbar-bottom a {
        text-align: center;
        color: #333;
        text-decoration: none;
        font-size: 0.9rem;
      }

      .navbar-bottom i {
        font-size: 1.2rem;
        display: block;
      }
    }
  </style>
</head>

<body>
  <div class="wrapper">

    <!-- Sidebar (Desktop Only) -->
    <?php include 'sidebar.php'; ?>

    <div class="container py-5">
      <h2 class="text-center mb-4">Kategori Tugas</h2>
      <div class="row g-4">
        <!-- tambah kategori -->
        <div class="col-md-4">
          <!-- Card default -->
          <div id="addKategoriCard" class="card kategori-card text-center p-4">
            <p class="m-0"><i class="bi bi-plus-circle me-2"></i>Tambah Kategori</p>
          </div>

          <!-- Form tambah kategori -->
          <div id="addKategoriForm" class="card p-3 d-none">
            <form method="post" class="d-flex flex-column gap-2">
              <input type="text" name="inputKategori" class="form-control" placeholder="Nama kategori..." required>
              <div class="d-flex justify-content-between">
                <button type="submit" name="tambahKategori" class="btn btn-primary btn-sm w-100 me-2">Tambah</button>
                <button type="button" id="cancelForm" class="btn btn-outline-secondary btn-sm w-100">Batal</button>
              </div>
            </form>
          </div>
        </div>
        <!--Kategori -->
        <?php if (!empty($kategori)): ?>
          <?php foreach ($kategori as $category): ?>
            <div class="col-md-4">
              <div class="card kategori-card text-center p-4">
                <div class="d-flex justify-content-between align-items-center">
                  <a href="kategoriDetail.php?kategori_id=<?= $category['id'] ?>&namaKategori=<?= $category['nama'] ?>" class="text-decoration-none text-dark flex-grow-1">
                    <i class="bi bi-folder-fill me-2"></i><?= htmlspecialchars($category['nama']) ?>
                  </a>
                  <form method="get" action="hapusKategori.php" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Tugas yang ada di dalam kategori akan terhapus')" class="ms-2">
                    <input type="hidden" name="kategori_id" value="<?= $category['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Kategori">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- navigasi bawah (mobile only)  -->
  <?php include 'footer.php'; ?>

</body>
<script>
  const card = document.getElementById('addKategoriCard');
  const form = document.getElementById('addKategoriForm');
  const cancel = document.getElementById('cancelForm');

  card.addEventListener('click', () => {
    card.classList.add('d-none');
    form.classList.remove('d-none');
  });

  cancel.addEventListener('click', () => {
    form.classList.add('d-none');
    card.classList.remove('d-none');
  });
</script>

</html>