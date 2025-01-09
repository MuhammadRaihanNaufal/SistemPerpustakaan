<?php
include 'koneksi.php';

if(!isset($_GET['kode_buku'])) {
    echo 'Silahkan masukkan buku!!!';
    exit;
}

$kodebuku = $_GET['kode_buku'];
$cek = "SELECT * FROM buku WHERE kode_buku = '$kodebuku'";

if(mysqli_query($koneksi, $cek) -> num_rows <= 0) {
    echo 'Buku tidak ditemukan!';
    exit;
}

$sql = "DELETE FROM buku WHERE kode_buku = '$kodebuku'";
if(mysqli_query($koneksi, $sql)) {
    echo '<script>
    alert("Berhasil hapus buku!");
    window.location.href = "tableBuku.php";
    </script>';
} else {
    echo '<script>
    alert("Gagal hapus buku!");
    </script>';
}
?>
