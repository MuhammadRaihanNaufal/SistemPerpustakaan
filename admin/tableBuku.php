<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
include 'koneksi.php';

// Ambil data user saat ini
$id_user = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query);
$foto = !empty($user['foto']) ? "img/" . $user['foto'] : 'adminlogo.png';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>TABEL BUKU</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/9043/9043296.png" type="image/x-icon">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-text mx-3">ADMIN</div>
            </a>
            <hr class="sidebar-divider">
            <li class="nav-item active">
                <a class="nav-link" href="tableBuku.php">
                    <i class="fas fa-book"></i>
                    <span>Buku</span>
                </a>
            </li>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 large"><?= $_SESSION['username']; ?></span>
                                <img class="img-profile rounded-circle" src="img/<?= $foto ?>?v=<?= time(); ?>" width="32" height="32">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <form action="uploadfoto.php" method="post" enctype="multipart/form-data" class="px-4 py-2">
                                    <input type="file" name="foto" accept="image/*" class="form-control mb-2" required>
                                    <button class="btn btn-primary btn-sm w-100" type="submit">Ganti Foto</button>
                                </form>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../user/index.php">
                                    <i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i> Home
                                </a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h2 class="mb-2 text-dark" style="font-weight: bold;">DATA BUKU</h2>
                        </div>
                        <div class="card-body">
                            <a href="InputBuku.php" class="btn btn-primary shadow mb-4">+ Tambah</a>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Cover</th>
                                            <th>Kode Buku</th>
                                            <th>Kategori</th>
                                            <th>Judul Buku</th>
                                            <th>Pengarang</th>
                                            <th>Penerbit</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Tahun Terbit</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $tampil = mysqli_query($koneksi, "SELECT * FROM buku");
                                        while ($d = mysqli_fetch_array($tampil)) :
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><img src="<?= $d['cover_buku']; ?>" width="80" height="120"></td>
                                                <td><?= $d['kode_buku'] ?></td>
                                                <td><?= $d['kategori_buku'] ?></td>
                                                <td><?= $d['judul_buku'] ?></td>
                                                <td><?= $d['pengarang'] ?></td>
                                                <td><?= $d['penerbit'] ?></td>
                                                <td><?= $d['tanggal_masuk'] ?></td>
                                                <td><?= $d['tahun_terbit'] ?></td>
                                                <td><?= $d['jumlah'] ?></td>
                                                <td><?= $d['status_buku'] ?></td>
                                                <td>
                                                    <a href="formEditBuku.php?kode_buku=<?= $d['kode_buku']; ?>" class="mb-2 btn btn-warning text-xs">Edit</a>
                                                    <a href="deleteAksi.php?kode_buku=<?= $d['kode_buku']; ?>" class="btn btn-danger text-xs">Hapus</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="sticky-footer bg-white">
                    <div class="container my-auto text-center">
                        <span>Muhammad Raihan Naufal &copy; 2024</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Keluar dari akun?</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">Klik logout untuk keluar dari sesi.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="../user/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>