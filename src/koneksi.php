<?php
// 1. Konfigurasi Database
$host     = "localhost";     // Server database (biasanya localhost)
$username = "root";          // Username default MySQL XAMPP
$password = "";              // Password default MySQL XAMPP (kosong)
$database = "db_latihan_pbo_trpl1a_tatagwildanbaihaqy.sql"; // ⚠️ GANTI dengan nama database tempat Anda mengimport tiket_bioskop.sql

// 2. Membuat Koneksi ke Database
$koneksi = new mysqli($host, $username, $password, $database);

// 3. Memeriksa Apakah Koneksi Berhasil
if ($koneksi->connect_error) {
    // Jika koneksi gagal, hentikan program dan tampilkan pesan error
    die("Koneksi database gagal: " . $koneksi->connect_error);
} 

// Catatan: Pesan sukses sengaja dikomentari agar tidak mengganggu output saat dipanggil di file lain.
// echo "Koneksi database berhasil!"; 
?>