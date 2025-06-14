<?php
session_start();
require 'Database.php';

$database = new Database();

if (empty($_GET['id_buku'])) {
    echo 'Buku tidak ditemukan!';
    exit;
}

$id_buku = htmlspecialchars($_GET['id_buku']);

// Fetch book details safely using prepared statements
$query = "SELECT * FROM buku WHERE id_buku = :id_buku LIMIT 1";
$stmt = $database->koneksi->prepare($query);
$stmt->execute(['id_buku' => $id_buku]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    echo 'Buku tidak ditemukan!';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        echo '<script>
            alert("Anda belum login!");
            window.location.href = "index.php";
        </script>';
        exit;
    }

    $id_buku = $_POST['id_buku'];
    $id_user = $_SESSION['id_user'];
    $judul_buku = $buku['judul_buku'];
    $username = $_SESSION['username'];
    $tanggal_peminjaman = date("Y-m-d");
    $durasi_pinjam = htmlspecialchars($_POST['durasi_pinjam']);
    $jumlah_buku = htmlspecialchars($_POST['jumlah_buku']);

    // Update book quantity safely using prepared statements
    $update = "UPDATE buku SET jumlah = jumlah - :jumlah_buku WHERE id_buku = :id_buku";
    $stmtUpdate = $database->koneksi->prepare($update);
    $stmtUpdate->execute(['jumlah_buku' => $jumlah_buku, 'id_buku' => $id_buku]);

    // Insert into peminjaman table safely using prepared statements
    $insertPeminjaman = "INSERT INTO peminjaman (id_user, id_buku, judul_buku, username, tanggal_peminjaman, durasi_pinjam, jumlah_buku, status)
                         VALUES (:id_user, :id_buku, :judul_buku, :username, :tanggal_peminjaman, :durasi_pinjam, :jumlah_buku, 'Dipinjam')";
    $stmtInsert = $database->koneksi->prepare($insertPeminjaman);

    if ($stmtInsert->execute([
        'id_user' => $id_user,
        'id_buku' => $id_buku,
        'judul_buku' => $judul_buku,
        'username' => $username,
        'tanggal_peminjaman' => $tanggal_peminjaman,
        'durasi_pinjam' => $durasi_pinjam,
        'jumlah_buku' => $jumlah_buku
    ])) {
        // Hitung tanggal pengembalian
        $tanggal_pengembalian = date('Y-m-d', strtotime($tanggal_peminjaman . ' + ' . $durasi_pinjam . ' days'));

        // Tampilkan alert dengan tanggal pengembalian
        echo '<script>
            alert("Berhasil meminjam buku! Harap kembalikan pada tanggal ' . $tanggal_pengembalian . '");
            window.location.href = "index.php"; // Redirect to the main page
        </script>';
        exit;
    } else {
        echo '<script>
            alert("Gagal meminjam buku!");
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DETAIL BUKU</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/9043/9043296.png" type="image/x-icon">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">PERPUSTAKAAN ONLINE</a>
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

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <a href="../user/index.php" class="btn btn-primary btn-back mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4 text-center">
                    <img src="<?= htmlspecialchars($buku['cover_buku']); ?>" class="img-fluid rounded-start p-5" alt="Cover Buku" style="width: 500px; height: 500px;">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($buku['judul_buku']); ?></h5>
                        <p class="card-text"><strong>Pengarang:</strong> <?= htmlspecialchars($buku['pengarang']); ?></p>
                        <p class="card-text"><strong>Penerbit:</strong> <?= htmlspecialchars($buku['penerbit']); ?></p>
                        <p class="card-text"><strong>Tahun Terbit:</strong> <?= htmlspecialchars($buku['tahun_terbit']); ?></p>
                        <p class="card-text"><strong>Tanggal Masuk:</strong> <?= htmlspecialchars($buku['tanggal_masuk']); ?></p>
                        <p class="card-text"><strong>Jumlah:</strong> <?= htmlspecialchars($buku['jumlah']); ?></p>
                        <p class="card-text"><strong>Sinopsis:</strong> <?= htmlspecialchars($buku['sinopsis_buku']); ?></p>
                        <form method="POST" class="mt-3">
                            <input type="hidden" name="id_buku" value="<?= htmlspecialchars($buku['id_buku']) ?>">
                            <div class="input-group mb-2">
                                <input class="form-control" type="number" name="durasi_pinjam" placeholder="Durasi (Hari)" required>
                            </div>
                            <div class="input-group mb-2">
                                <input class="form-control" type="number" name="jumlah_buku" placeholder="Jumlah Buku" required>
                            </div>
                            <button type="submit" class="btn btn-success">Pinjam Buku</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
    <script src="script.js"></script>
</body>

</html>