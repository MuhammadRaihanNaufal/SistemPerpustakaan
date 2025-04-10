<?php
session_start();
include 'koneksi.php';

$id_user = $_SESSION['id_user'];

if (!empty($_FILES['foto']['name'])) {
    $filename = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $folder = "img/";
    $nama_baru = time() . "_" . $filename; // Buat nama unik
    $path = $folder . $nama_baru;

    // Pindahkan file ke folder admin/img/
    if (move_uploaded_file($tmp, "admin/" . $path)) {

        // Simpan nama file saja ke database
        mysqli_query($koneksi, "UPDATE user SET foto = '$nama_baru' WHERE id_user = '$id_user'");

        echo "<script>alert('Foto berhasil diperbarui'); window.location='tableBuku.php';</script>";
    } else {
        echo "<script>alert('Gagal mengunggah file'); window.location='tableBuku.php';</script>";
    }
} else {
    echo "<script>alert('Pilih file terlebih dahulu!'); window.location='tableBuku.php';</script>";
}
