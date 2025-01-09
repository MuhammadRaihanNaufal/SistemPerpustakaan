<?php

require "Konfigurasi.php";

class Database
{
    public $koneksi = null;

    public function __construct()
    {
        try {
            $url = "mysql:host=" . Konfigurasi::DB_HOST . ";port=" . Konfigurasi::DB_PORT . ";dbname=" . Konfigurasi::DB_NAMA;

            $this->koneksi = new PDO($url, Konfigurasi::DB_USER);
            $this->koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error koneksi" . $e->getMessage());
        }
    }

    public function close()
    {
        $this->koneksi = null;
    }
}
?> 
