<?php
$host     = "127.0.0.1";     
$username = "Baihaqy";          
$password = "1023";              
$database = "db_latihan_pbo_trpl1a_tatagwildanbaihaqy"; 

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
?>