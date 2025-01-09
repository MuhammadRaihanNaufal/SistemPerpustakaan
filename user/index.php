<?php
session_start();

require 'Database.php';

$database = new Database();

$query = <<<QUERY
        select b.cover_buku, b.id_buku
        from buku b
        order by id_buku desc 
        limit 30
    QUERY;

$buku_terbaru = $database->koneksi
    ->query($query)
    ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HOME</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/9043/9043296.png" type="image/x-icon">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">PERPUSTAKAAN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['username'])) : ?>
                        <?php if ($_SESSION['role'] === 'Admin') : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../admin/tableBuku.php">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="listpeminjaman.php">Buku-ku</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container text-center my-5">
        <h1 class="display-4 fw-bold"><strong>Selamat Datang di Perpustakaan Online!</strong></h1>
        <form action="caribuku.php" class="d-flex justify-content-center my-4">
            <select onchange="this.form.submit()" name="kategori_buku" class="form-select ms-2" style="width: 300px;">
                <option disabled selected>Kategori</option>
                <option value="Agama">Agama</option>
                <option value="Komik">Komik</option>
                <option value="Sejarah">Sejarah</option>
                <option value="Horror">Horror</option>
            </select>
            <input type="text" name="cari" class="form-control ms-5" style="width: 500px;" placeholder="Cari buku..." />
            <button class="btn btn-primary ms-3" type="submit">Cari</button>
        </form>
    </div>

    <div class="container py-5 bg-primary">
        <h3 class="text-center mb-4"><strong>BUKU - BUKU</strong></h3>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4">
            <?php foreach ($buku_terbaru as $bt) : ?>
                <div class="col">
                    <div class="card h-100">
                        <a href="detail.php?id_buku=<?= $bt['id_buku'] ?>">
                            <img src="<?= $bt['cover_buku'] ?>" class="card-img-top" alt="Cover Buku" />
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>