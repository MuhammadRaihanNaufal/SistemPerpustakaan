<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['id_user'];

if (!empty($_FILES['foto']['name'])) {
    $filename = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $folder = "img/";

    // Ekstensi yang diizinkan
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Cek validitas ekstensi
    if (in_array($ext, $allowed_ext)) {
        $nama_baru = time() . "_" . preg_replace('/[^a-zA-Z0-9_.]/', '', $filename); // Filter nama
        $path = $folder . $nama_baru;

        // Pindahkan file
        if (move_uploaded_file($tmp, $path)) {
            // Simpan nama file saja ke database
            $query = "UPDATE user SET foto = '$nama_baru' WHERE id_user = '$id_user'";
            mysqli_query($koneksi, $query);

            echo "<script>alert('Foto berhasil diperbarui'); window.location='tableBuku.php';</script>";
        } else {
            echo "<script>alert('Gagal mengunggah file'); window.location='tableBuku.php';</script>";
        }
    } else {
        echo "<script>alert('Hanya file gambar yang diperbolehkan'); window.location='tableBuku.php';</script>";
    }
} else {
    echo "<script>alert('Pilih file terlebih dahulu!'); window.location='tableBuku.php';</script>";
}
