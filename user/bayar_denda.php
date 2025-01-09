<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require 'Database.php';

$database = new Database();

// Ambil ID peminjaman dari URL
$id_peminjaman = $_GET['id_peminjaman'] ?? null;

if (!$id_peminjaman) {
    echo "ID peminjaman tidak valid.";
    exit;
}

// Query untuk mengambil data peminjaman
$query = <<<QUERY
    SELECT pm.id_peminjaman, b.id_buku, b.judul_buku, pm.tanggal_peminjaman, pm.durasi_pinjam
    FROM peminjaman pm
    LEFT JOIN buku b ON pm.id_buku = b.id_buku
    WHERE pm.id_peminjaman = :id_peminjaman
QUERY;

$stmt = $database->koneksi->prepare($query);
$stmt->execute(['id_peminjaman' => $id_peminjaman]);
$peminjaman = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$peminjaman) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}

// Hitung tanggal pengembalian
$tanggalPengembalian = date('Y-m-d', strtotime($peminjaman['tanggal_peminjaman'] . ' + ' . $peminjaman['durasi_pinjam'] . ' days'));
$today = date('Y-m-d');

// Hitung keterlambatan dan denda
$keterlambatan = (strtotime($today) - strtotime($tanggalPengembalian)) / (60 * 60 * 24);
$denda = max(0, floor($keterlambatan) * 5000); // Rp 5.000 per hari

// Proses pengembalian dan pembayaran denda
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_buku = $peminjaman['judul_buku'];
    $jumlah = 1; // Default jumlah buku
    $username = $_SESSION['username'];

    // Mulai transaksi database
    $database->koneksi->beginTransaction();

    try {
        // Insert data ke tabel pengembalian
        $insertPengembalianQuery = <<<SQL
            INSERT INTO pengembalian (id_buku, id_user, id_peminjaman, tanggal_pengembalian, denda, status_denda, judul_buku, jumlah, username) 
            VALUES (:id_buku, :id_user, :id_peminjaman, NOW(), :denda, :status_denda, :judul_buku, :jumlah, :username)
        SQL;
        $status_denda = $denda > 0 ? 'Lunas' : 'Tidak ada denda';

        $insertPengembalianStmt = $database->koneksi->prepare($insertPengembalianQuery);
        $insertPengembalianStmt->execute([
            'id_buku' => $peminjaman['id_buku'],
            'id_user' => $_SESSION['id_user'],
            'id_peminjaman' => $id_peminjaman,
            'denda' => $denda,
            'status_denda' => $status_denda,
            'judul_buku' => $judul_buku,
            'jumlah' => $jumlah,
            'username' => $username
        ]);

        // Update jumlah buku di tabel buku
        $updateBukuQuery = "UPDATE buku SET jumlah = jumlah + :jumlah WHERE id_buku = :id_buku";
        $updateBukuStmt = $database->koneksi->prepare($updateBukuQuery);
        $updateBukuStmt->execute(['jumlah' => $jumlah, 'id_buku' => $peminjaman['id_buku']]);

        // Update status peminjaman menjadi dikembalikan
        $updatePeminjamanQuery = "UPDATE peminjaman SET status = 'Dikembalikan' WHERE id_peminjaman = :id_peminjaman";
        $updatePeminjamanStmt = $database->koneksi->prepare($updatePeminjamanQuery);
        $updatePeminjamanStmt->execute(['id_peminjaman' => $id_peminjaman]);

        $database->koneksi->commit();

        // Redirect dengan notifikasi
        echo '<script>alert("Buku berhasil dikembalikan!"); window.location.href = "listpeminjaman.php";</script>';
        exit;
    } catch (Exception $e) {
        $database->koneksi->rollBack();
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bayar Denda</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <div class="container my-5">
        <h3 class="text-center mb-4">Bayar Denda</h3>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($peminjaman['judul_buku']) ?></h5>
                <p class="card-text">Tanggal Pengembalian: <?= htmlspecialchars($tanggalPengembalian) ?></p>
                <p class="card-text">Denda: Rp <?= number_format($denda, 0, ',', '.') ?></p>
                <?php if ($denda > 0): ?>
                    <form method="POST">
                        <button type="submit" class="btn btn-primary">Bayar Denda dan Kembalikan</button>
                    </form>
                <?php else: ?>
                    <form method="POST">
                        <button type="submit" class="btn btn-success">Kembalikan Buku</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
