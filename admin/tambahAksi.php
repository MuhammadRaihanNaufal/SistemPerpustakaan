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
    $sql = "INSERT INTO buku (cover_buku, kode_buku, kategori_buku, judul_buku, pengarang, penerbit, tanggal_masuk, tahun_terbit, jumlah, status_buku, sinopsis_buku)
            VALUES ('$cover','$kodebuku','$kategori','$judul','$pengarang','$penerbit', '$tanggalmasuk', '$tahunterbit','$jumlah','$statusbuku','$sinopsis')";
} else {
    $sql = "INSERT INTO buku (kode_buku, kategori_buku, judul_buku, pengarang, penerbit, tanggal_masuk, tahun_terbit, jumlah, status_buku, sinopsis_buku)
    VALUES ('$kodebuku','$kategori','$judul','$pengarang','$penerbit','$tanggalmasuk', '$tahunterbit','$jumlah','$statusbuku','$sinopsis')";
}

if (mysqli_query($koneksi, $sql)) {
    echo '<script>
    alert("Berhasil input buku!");
    window.location.href = "tableBuku.php";
    </script>';
} else {
    echo '<script>
    alert("Gagal input buku!");
    window.location.href = "InputBuku.php";
    </script>';
}
