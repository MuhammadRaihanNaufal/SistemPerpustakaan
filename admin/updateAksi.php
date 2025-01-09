<?php
if (!empty($_FILES['cover']['name'])) {
    $namafile = $_FILES['cover']['name'];
    $namaSementara = $_FILES['cover']['tmp_name'];

    $penyimpananCover = '../Storage/';
    $suksesUploadFile = move_uploaded_file($namaSementara, $penyimpananCover . $namafile);

    if (!$suksesUploadFile) {
        echo '<script>
        alert("Gagal upload file");
        window.location.href = "InputBuku.php";
        </script>';
    }
    $cover = '../Storage/' . $namafile;
}

$kodebuku = $_POST['kode_buku'];
$kategori = $_POST['kategori_buku'];
$judul = $_POST['judul_buku'];
$pengarang = $_POST['pengarang'];
$penerbit = $_POST['penerbit'];
$tanggalmasuk = $_POST['tanggal_masuk'];
$tahunterbit = $_POST['tahun_terbit'];
$jumlah = $_POST['jumlah'];
$statusbuku = $_POST['status_buku'];
$sinopsis = $_POST['sinopsis_buku'];

include 'koneksi.php';

if (!empty($_FILES['cover']['name'])) {
    $sql = "UPDATE buku SET cover_buku = '$cover', kode_buku = '$kodebuku', kategori_buku = '$kategori', judul_buku = '$judul', pengarang = '$pengarang', penerbit = '$penerbit', tanggal_masuk = '$tanggalmasuk', tahun_terbit = '$tahunterbit', jumlah = '$jumlah', status_buku = '$statusbuku', sinopsis_buku = '$sinopsis' WHERE kode_buku = '$kodebuku'";
} else {
    $sql = "UPDATE buku SET kode_buku = '$kodebuku', kategori_buku = '$kategori', judul_buku = '$judul', pengarang = '$pengarang', penerbit = '$penerbit', tanggal_masuk = '$tanggalmasuk', tahun_terbit = '$tahunterbit', jumlah = '$jumlah', status_buku = '$statusbuku', sinopsis_buku = '$sinopsis' WHERE kode_buku = '$kodebuku'";
}

if (mysqli_query($koneksi, $sql)) {
    echo '<script>
    alert("Berhasil update buku!");
    window.location.href = "tableBuku.php";
    </script>';
} else {
    echo '<script>
    alert("Gagal update buku!");
    window.location.href = "InputBuku.php";
    </script>';
}
?>
