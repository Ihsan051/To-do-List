<!-- Sidebar (Desktop Only) -->
<nav id="sidebar">
    <a href="profil.php">
        <div class="d-flex align-items-center mb-4">
            <img src="asset/profil.svg" alt="Profil" width="50" class="me-3 rounded-circle">
            <div><strong><?php echo $user['name'] ?></strong></div>
        </div>
    </a>
    <a href="#"><i class="bi bi-calendar"></i> Hari Ini</a>
    <a href="tugas_selesai.php"><i class="bi bi-calendar-check"></i> Tugas Selesai</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>

    <hr>
    <form action="kategori.php" method="post" class="mb-3 d-flex">
        <input type="text" name="inputKategori" class="form-control me-2" placeholder="Kategori..." required>
        <button class="btn btn-outline-primary" name="tambahKategori">+</button>
    </form>

    <?php if (!empty($kategori)): ?>
        <?php foreach ($kategori as $category): ?>
            <a href="kategori.php?kategori_id=<?= $category['id'] ?>">
                <i class="bi bi-folder-fill me-1"></i><?= htmlspecialchars($category['nama']) ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</nav>