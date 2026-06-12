<?php
$host     = "localhost";     
$username = "root";          
$password = "";              
// Disamakan dengan nama database di screenshot Anda
$database = "db_latihan_pbo_trpl1a_tatagwildanbaihaqy"; 

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
?>