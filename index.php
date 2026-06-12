<?php
require_once 'koneksi.php';
require_once 'Tiket.php';
require_once 'class/TiketRegular.php';
require_once 'class/TiketMax.php';
require_once 'class/TiketVelvet.php';

// Ambil data tiket dari database
$query = "SELECT * FROM tiket_bioskop";
$result = $koneksi->query($query);

// Array penampung kelompok kategori studio
$daftarTiket = ['Regular' => [], 'IMAX' => [], 'Velvet' => []];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $studio = $row['jenis_studio'];

        // Instansiasi polimorfik & mapping properti dari kolom tabel DB
        if ($studio == 'Regular') {
            $daftarTiket['Regular'][] = new TiketRegular(
                $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], $row['jumlah_kursi'], $row['harga_dasar_tiket'],
                $row['tipe_audio'] ?? 'Dolby Atmos', $row['lokasi_baris'] ?? 'Row C'
            );
        } elseif ($studio == 'IMAX' || $studio == 'Max') {
            $daftarTiket['IMAX'][] = new TiketMax(
                $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], $row['jumlah_kursi'], $row['harga_dasar_tiket'],
                $row['kacamata_3d_id'] ?? 'G-IMAX01', $row['efek_gerak_fitur'] ?? 'Aktif'
            );
        } elseif ($studio == 'Velvet') {
            $daftarTiket['Velvet'][] = new TiketVelvet(
                $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], $row['jumlah_kursi'], $row['harga_dasar_tiket'],
                $row['bantal_selimut_pack'] ?? 'Lengkap', $row['layanan_butler'] ?? 'Tersedia'
            );
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Manajer Bioskop - Data Tiket Dinamis</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; margin: 30px; color: #333; }
        h1 { text-align: center; margin-bottom: 30px; font-weight: 600; }
        .box-studio { background: #fff; padding: 22px; border-radius: 10px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .studio-Regular { border-left: 5px solid #2ecc71; }
        .studio-IMAX { border-left: 5px solid #3498db; }
        .studio-Velvet { border-left: 5px solid #e74c3c; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e9ecef; }
        th { background: #f1f3f5; font-size: 14px; }
        .fitur-badge { background: #e9ecef; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
    </style>
</head>
<body>

    <h1>Sistem Informasi Manajemen Tiket Penonton</h1>

    <?php foreach ($daftarTiket as $namaStudio => $kumpulanTiket): ?>
        <div class="box-studio studio-<?php echo $namaStudio; ?>">
            <h2>Kategori: Studio <?php echo $namaStudio; ?></h2>
            <?php if (empty($kumpulanTiket)): ?>
                <p style="color: #6c757d;">Belum ada antrean pesanan pada kategori studio ini.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID Tiket</th>
                            <th>Film</th>
                            <th>Jadwal Tayang</th>
                            <th>Pax</th>
                            <th>Harga Satuan</th>
                            <th>Spesifikasi Fasilitas Unik (Polimorfik)</th>
                            <th>Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kumpulanTiket as $t): ?>
                            <tr>
                                <td><strong><?php echo $t->getIdTiket(); ?></strong></td>
                                <td><?php echo $t->getNamaFilm(); ?></td>
                                <td><?php echo $t->getJadwalTayang(); ?></td>
                                <td><?php echo $t->getJumlahKursi(); ?> Kursi</td>
                                <td>Rp <?php echo number_format($t->getHargaDasarTiket(), 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    // Pemanggilan metode polimorfik cetak atribut unik masing-masing studio
                                    if ($t instanceof TiketRegular) {
                                        echo "<span class='fitur-badge'>Audio: " . $t->getTipeAudio() . "</span> ";
                                        echo "<span class='fitur-badge'>Baris: " . $t->getLokasiBaris() . "</span>";
                                    } elseif ($t instanceof TiketMax) {
                                        echo "<span class='fitur-badge'>3D Kacamata: " . $t->getKacamata3dId() . "</span> ";
                                        echo "<span class='fitur-badge'>Motion System: " . $t->getEfekGerakFitur() . "</span>";
                                    } elseif ($t instanceof TiketVelvet) {
                                        echo "<span class='fitur-badge'>Snooze Pack: " . $t->getBantalSelimutPack() . "</span> ";
                                        echo "<span class='fitur-badge'>Service: " . $t->getLayananButler() . "</span>";
                                    }
                                    ?>
                                </td>
                                <td><span style="font-weight:bold; color:#2c3e50;">Rp <?php echo number_format($t->hitungTotalHarga(), 0, ',', '.'); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</body>
</html>