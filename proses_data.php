<?php
require_once __DIR__ . '/src/koneksi.php';
require_once __DIR__ . '/src/Tiket.php'; 

require_once __DIR__ . '/class/TiketRegular.php';
require_once __DIR__ . '/class/TiketMax.php';
require_once __DIR__ . '/class/TiketVelvet.php';

// Mengambil data dari database
$query = "SELECT * FROM table_tiket ORDER BY jadwal_tayang ASC";
$result = $koneksi->query($query);

$daftarTiket = ['Regular' => [], 'IMAX' => [], 'Velvet' => []];
$semuaTransaksi = []; 

$totalPendapatan = 0;
$totalTiketTerjual = 0;
$totalSurchargeVelvet = 0;
$totalBiayaImax = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $studio = $row['jenis_studio'];
        $tiket = null;

        if ($studio == 'Regular') {
            $tiket = new TiketRegular(
                $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], $row['jumlah_kursi'], $row['harga_dasar_tiket'],
                $row['tipe_audio'] ?? '-', $row['lokasi_baris'] ?? '-'
            );
            $daftarTiket['Regular'][] = $tiket;
        } elseif ($studio == 'IMAX') {
            $tiket = new TiketMax(
                $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], $row['jumlah_kursi'], $row['harga_dasar_tiket'],
                $row['kacamata_3d_id'] ?? '-', $row['efek_gerak_fitur'] ?? '-'
            );
            $daftarTiket['IMAX'][] = $tiket;
            $totalBiayaImax += 35000 * $row['jumlah_kursi'];
        } elseif ($studio == 'Velvet') {
            $tiket = new TiketVelvet(
                $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], $row['jumlah_kursi'], $row['harga_dasar_tiket'],
                $row['bantal_selimut_pack'] ?? '-', $row['layanan_butler'] ?? '-'
            );
            $daftarTiket['Velvet'][] = $tiket;
            $totalSurchargeVelvet += ($row['jumlah_kursi'] * $row['harga_dasar_tiket']) * 0.50;
        }

        if ($tiket) {
            $totalPendapatan += $tiket->hitungTotalHarga();
            $totalTiketTerjual += $tiket->getJumlahKursi();
            $semuaTransaksi[] = $tiket; 
        }
    }
}

// Hitung persentase kapasitas untuk diagram mini dashboard
$countRegular = count($daftarTiket['Regular']);
$countImax = count($daftarTiket['IMAX']);
$countVelvet = count($daftarTiket['Velvet']);
$totalData = max($countRegular + $countImax + $countVelvet, 1);

$pctRegular = round(($countRegular / $totalData) * 100);
$pctImax = round(($countImax / $totalData) * 100);
$pctVelvet = round(($countVelvet / $totalData) * 100);
?>