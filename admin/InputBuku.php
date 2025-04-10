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
$foto = !empty($user['foto']) ? $user['foto'] : 'adminlogo.png';


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>INPUT BUKU</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/128/9043/9043296.png" type="image/x-icon">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-text mx-3">ADMIN<sup></sup></div>
            </a>

            <hr class="sidebar-divider my-0">

            <hr class="sidebar-divider">

            <div class="sidebar-heading text-info">
                Interface
            </div>

            <li class="nav-item active">
                <a class="nav-link" href="tableBuku.php">
                    <i class="fas fa-solid fa-book"></i>
                    <span>Buku</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 large"><?= $_SESSION['username']; ?></span>
                                <img class="img-profile rounded-circle" src="img/<?= $foto ?>?v=<?= time(); ?>" width="32" height="32">
                            </a>
                        </li>

                    </ul>

                </nav>

                <div class="container-fluid">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h2 class="mb-2 text-dark" style="font-weight: bold;">TAMBAH BUKU</h2>
                        </div>
                        <form action="tambahAksi.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <a href="tableBuku.php" type="submit" class="btn btn-secondary rounded shadow">Kembali</a>
                                <div class="form-row mt-4">
                                    <div class="m-2 col">
                                        <label for="">Kode Buku</label>
                                        <input type="text" name="kode_buku" class="form-control">
                                    </div>
                                    <div class="m-2 col">
                                        <label for="">Kategori</label>
                                        <input type="text" name="kategori_buku" class="form-control">
                                    </div>
                                </div>
                                <div class="m-2">
                                    <label for="">Judul</label>
                                    <input type="text" name="judul_buku" class="form-control">
                                </div>
                                <div class="m-2">
                                    <label for="">Pengarang</label>
                                    <input type="text" name="pengarang" class="form-control">
                                </div>
                                <div class="form-row">
                                    <div class="m-2 col">
                                        <label for="">Penerbit</label>
                                        <input type="text" name="penerbit" class="form-control">
                                    </div>
                                    <div class="m-2 col">
                                        <label for="">Tanggal Masuk</label>
                                        <input type="date" name="tanggal_masuk" class="form-control">
                                    </div>
                                    <div class="m-2 col">
                                        <label for="">Tahun Terbit</label>
                                        <input type="number" name="tahun_terbit" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="m-2 col">
                                        <label for="">Jumlah</label>
                                        <input type="number" name="jumlah" class="form-control">
                                    </div>
                                    <div class="m-2 col">
                                        <label class="form-label">Status</label>
                                        <select name="status_buku" id="" class="form-control">
                                            <option disabled selected>-Pilih-</option>
                                            <option value="Tersedia">Tersedia</option>
                                            <option value="Tak Tersedia">Tak Tersedia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="m-2">
                                    <label for="">Pilih Cover</label>
                                    <input type="file" name="cover" class="form-control">
                                </div>
                                <div class="m-2">
                                    <label for="">Sinopsis</label>
                                    <textarea name="sinopsis_buku" id="" cols="30" rows="10" class="form-control"></textarea>
                                </div>
                                <div class="d-flex mt-5 m-4">
                                    <input type="reset" value="Kosongkan" class="btn btn-danger" style="margin-right: 15px;">
                                    <input type="submit" value="Simpan" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Muhammad Raihan Naufal &copy; 2024</span>
                    </div>
                </div>
            </footer>

        </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>