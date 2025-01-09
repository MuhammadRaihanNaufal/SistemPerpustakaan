<?php
include '../admin/koneksi.php';
session_start();

$pencarian = htmlspecialchars($_GET['cari'] ?? '');
$kategori_buku = htmlspecialchars($_GET['kategori_buku'] ?? '');

$sql = <<<SQL
  select * from buku 
  where judul_buku like '%{$pencarian}%' and kategori_buku like '%{$kategori_buku}%'
  order by id_buku desc
SQL;

$buku_dicari = $koneksi->query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CARI BUKU</title>
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
              <a class="nav-link" href="index.php">Home</a>
            </li>
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

  <div class="container mt-5">
    <h1 class="text-center"><strong>Hasil Pencarian</strong></h1>

    <form action="caribuku.php" method="GET" class="search text-center mb-4">
      <input type="text" name="cari" class="search__input" placeholder="Cari buku..." required />
      <button type="submit" class="search__button">
        <svg class="search__icon" aria-hidden="true" viewBox="0 0 24 24">
          <g>
            <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
          </g>
        </svg>
      </button>
    </form>

    <section class="section-rekomendasi">
      <div class="row row-cols-5 align-items-start">
        <?php if (count($buku_dicari) > 0): ?>
          <?php foreach ($buku_dicari as $buku) : ?>
            <div class="col mb-4">
              <div class="card card-caribuku">
                <a href="detail.php?id_buku=<?= $buku['id_buku'] ?>">
                  <img class="card-img-top" src="<?= $buku['cover_buku'] ?>" alt="<?= $buku['judul_buku'] ?>" />
                </a>
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($buku['judul_buku']) ?></h5>
                  <p class="card-text"><?= htmlspecialchars($buku['kategori_buku']) ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <p class="text-center">Tidak ada buku yang ditemukan!</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>