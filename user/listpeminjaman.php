<?php
session_start();

// Pastikan id_user ada di session
if (!isset($_SESSION['username']) || !isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

require 'Database.php';

$database = new Database();

// Query untuk mengambil data peminjaman yang sedang berlangsung
$queryPeminjaman = <<<QUERY
    SELECT pm.id_peminjaman, b.cover_buku, b.judul_buku, pm.tanggal_peminjaman, pm.durasi_pinjam, pm.status, b.id_buku, b.pengarang
    FROM peminjaman pm 
    LEFT JOIN buku b ON pm.id_buku = b.id_buku
    LEFT JOIN user u ON pm.id_user = u.id_user
    WHERE u.id_user = :id_user AND pm.status = 'Dipinjam'
    ORDER BY pm.id_peminjaman DESC
QUERY;

$stmtPeminjaman = $database->koneksi->prepare($queryPeminjaman);
$stmtPeminjaman->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
$stmtPeminjaman->execute();
$semuapeminjaman = $stmtPeminjaman->fetchAll(PDO::FETCH_ASSOC);

// Query untuk mengambil data pengembalian
$queryPengembalian = <<<QUERY
    SELECT pg.id_pengembalian, b.cover_buku, b.judul_buku, pg.tanggal_pengembalian, pg.denda, pg.username, pg.id_peminjaman, b.id_buku, b.pengarang, pg.status_denda
    FROM pengembalian pg
    LEFT JOIN buku b ON pg.id_buku = b.id_buku
    LEFT JOIN user u ON pg.id_user = u.id_user
    WHERE u.id_user = :id_user
    ORDER BY pg.id_pengembalian DESC
QUERY;

$stmtPengembalian = $database->koneksi->prepare($queryPengembalian);
$stmtPengembalian->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
$stmtPengembalian->execute();
$semuapengembalian = $stmtPengembalian->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HISTORY</title>
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

    <!-- Peminjaman yang Sedang Berlangsung -->
    <div class="container my-5 py-4 bg-primary">
        <h3 class="text-center mb-4"><strong>Sedang Dipinjam</strong></h3>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($semuapeminjaman as $bt) : ?>
                <?php
                $tanggalPengembalian = date('Y-m-d', strtotime($bt['tanggal_peminjaman'] . ' + ' . $bt['durasi_pinjam'] . ' days'));
                ?>
                <div class="col">
                    <div class="card custom-card h-100 shadow-sm mb-4">
                        <a href="detail.php?id_buku=<?= htmlspecialchars($bt['id_buku']) ?>">
                            <img class="card-img-top custom-cover" src="<?= htmlspecialchars($bt['cover_buku']) ?>" alt="" />
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($bt['judul_buku']); ?></h5>
                            <p class="card-text">Tanggal Peminjaman: <?= htmlspecialchars($bt['tanggal_peminjaman']); ?></p>
                            <p class="card-text">Tanggal Pengembalian: <?= htmlspecialchars($tanggalPengembalian); ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <?php if (strtotime($tanggalPengembalian) < time()): ?>
                                <a href="bayar_denda.php?id_peminjaman=<?= htmlspecialchars($bt['id_peminjaman']) ?>" class="btn btn-danger w-100">Kembalikan</a>
                            <?php else: ?>
                                <button class="btn btn-warning w-100">Sedang Dipinjam</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Data Pengembalian -->
    <div class="container my-5 py-4 bg-primary">
        <h3 class="text-center mb-4"><strong>History Peminjaman</strong></h3>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($semuapengembalian as $bt) : ?>
                <div class="col">
                    <div class="card custom-card h-100 shadow-sm mb-4">
                        <a href="detail.php?id_buku=<?= htmlspecialchars($bt['id_buku']) ?>">
                            <img class="card-img-top custom-cover" src="<?= htmlspecialchars($bt['cover_buku']) ?>" alt="" />
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($bt['judul_buku']); ?></h5>
                            <p class="card-text">Denda: Rp <?= number_format($bt['denda'], 0, ',', '.'); ?></p>
                            <p class="card-text">Tanggal Kembali: <?= htmlspecialchars($bt['tanggal_pengembalian']); ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-success w-100">Sudah Dikembalikan</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>