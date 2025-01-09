<?php
// file penghubung antara database dengan koneksi.php
$koneksi = mysqli_connect("localhost", "root", "", "sistemperpustakaan");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
