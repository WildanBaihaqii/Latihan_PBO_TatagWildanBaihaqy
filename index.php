<?php
require_once __DIR__ . '/src/koneksi.php';
require_once __DIR__ . '/src/Tiket.php'; 

require_once __DIR__ . '/class/TiketRegular.php';
require_once __DIR__ . '/class/TiketMax.php';
require_once __DIR__ . '/class/TiketVelvet.php';

// Mengambil data dari tabel asli database: table_tiket
$query = "SELECT * FROM table_tiket ORDER BY jadwal_tayang ASC";
$result = $koneksi->query($query);

$daftarTiket = ['Regular' => [], 'IMAX' => [], 'Velvet' => []];
$semuaTransaksi = []; // Untuk menu Transaksi Tiket

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
            $semuaTransaksi[] = $tiket; // Simpan ke array linear
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Cinema Dashboard - Interactive Edition</title>
    <style>
        /* Apple Pro Typography & Custom Palette */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "SF Pro Display", "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background: linear-gradient(135deg, #2b1055 0%, #172b4d 50%, #0c1033 100%);
            background-attachment: fixed;
            color: #ffffff;
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        /* 1. Sidebar Layout */
        .sidebar {
            width: 270px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            padding: 35px 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 10;
        }

        .sidebar-logo {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-menu {
            list-style: none;
            margin-top: 40px;
        }

        .sidebar-item {
            margin-bottom: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            color: #b0b3b8;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(175, 82, 222, 0.25), rgba(0, 122, 255, 0.25));
            color: #ffffff;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .sidebar-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        .sidebar-footer {
            background: rgba(255, 255, 255, 0.05);
            padding: 14px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .avatar-circle {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #af52de, #007aff);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* 2. Main Content Viewport */
        .main-content {
            flex: 1;
            padding: 40px 45px;
            overflow-y: auto;
            height: 100vh;
        }

        /* Navigasi HalamanSPA */
        .app-view {
            display: none; /* Disembunyikan secara default, diatur via JS */
        }

        .app-view.active-view {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .main-header h1 {
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -0.6px;
            background: linear-gradient(135deg, #ffffff 0%, #b0b3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .main-header p {
            font-size: 14px;
            color: #b0b3b8;
            margin-top: 4px;
        }

        /* Widgets Card */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 22px;
            margin: 35px 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 18px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.07);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .stat-card h4 {
            font-size: 11px;
            font-weight: 600;
            color: #b0b3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
        }

        .stat-card .value {
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
        }

        /* Panel Putih Susu (Milk White) */
        .milk-panel {
            background: #fdfdfd;
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 35px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            color: #1d1d1f;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f0f0f4;
        }

        .panel-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 19px;
            font-weight: 700;
            color: #1c1c1e;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .dot-regular { background-color: #34c759; box-shadow: 0 0 8px #34c759; }
        .dot-imax { background-color: #007aff; box-shadow: 0 0 8px #007aff; }
        .dot-velvet { background-color: #af52de; box-shadow: 0 0 8px #af52de; }

        .counter-badge {
            font-size: 12px;
            background: #f2f2f7;
            padding: 6px 12px;
            border-radius: 20px;
            color: #545456;
            font-weight: 600;
        }

        /* Table Styling */
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { font-size: 11px; font-weight: 600; color: #8e8e93; text-transform: uppercase; padding: 14px 16px; border-bottom: 2px solid #f2f2f7; }
        td { font-size: 14px; padding: 18px 16px; border-bottom: 1px solid #f2f2f7; color: #3a3a3c; }
        tr:hover td { background-color: #fbfbfe; }
        .ticket-token { font-family: monospace; background: #f2f2f7; padding: 5px 9px; border-radius: 8px; font-size: 12px; font-weight: 700; color: #1c1c1e; }
        
        /* Badges */
        .pill-box { display: flex; gap: 6px; flex-wrap: wrap; }
        .pill-ios { font-size: 12px; font-weight: 500; padding: 6px 14px; border-radius: 30px; background: #f2f2f7; color: #1c1c1e; }
        .live-badge { font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 8px; background-color: #e3fbeb; color: #34c759; }

        /* UI STRUKTUR JADWAL TAYANG (Timeline Style) */
        .timeline-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .timeline-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            color: #1d1d1f;
            border-left: 6px solid #007aff;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-type-Regular { border-left-color: #34c759; }
        .card-type-IMAX { border-left-color: #007aff; }
        .card-type-Velvet { border-left-color: #af52de; }

        .time-header {
            font-size: 18px;
            font-weight: 700;
            color: #000;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Diagram Kontribusi Visual Mini */
        .chart-box {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 35px;
        }
        .bar-container {
            display: flex;
            height: 24px;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 15px;
            background: rgba(255,255,255,0.1);
        }
        .bar-segment { height: 100%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #fff; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div>
            <div class="sidebar-logo">
                <span style="background: linear-gradient(135deg, #af52de, #007aff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Bioskop Ji</span>
            </div>
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <div onclick="switchView('dashboard-view')" id="btn-dashboard-view" class="sidebar-link active">📊 Dashboard Utama</div>
                </li>
                <li class="sidebar-item">
                    <div onclick="switchView('jadwal-view')" id="btn-jadwal-view" class="sidebar-link">🎬 Jadwal Tayang</div>
                </li>
                <li class="sidebar-item">
                    <div onclick="switchView('transaksi-view')" id="btn-transaksi-view" class="sidebar-link">🎫 Transaksi Tiket</div>
                </li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <div class="avatar-circle">TW</div>
            <div>
                <p style="font-size:13px; color:#ffffff; font-weight:600;">Tatag Wildan</p>
                <p style="font-size:11px; color:#b0b3b8;">Administrator</p>
            </div>
        </div>
    </aside>

    <main class="main-content">

        <div id="dashboard-view" class="app-view active-view">
            <header class="main-header">
                <h1>Dashboard Bioskop </h1>
                <p>Table Tiket Bioskop</p>
            </header>

            <section class="stats-grid">
                <div class="stat-card" style="border-left: 4px solid #34c759;">
                    <h4>Total Omset Bruto</h4>
                    <div class="value">Rp <?php echo number_format($totalPendapatan, 0, ',', '.'); ?></div>
                </div>
                <div class="stat-card" style="border-left: 4px solid #af52de;">
                    <h4>Surcharge Velvet</h4>
                    <div class="value" style="color:#af52de;">Rp <?php echo number_format($totalSurchargeVelvet, 0, ',', '.'); ?></div>
                </div>
                <div class="stat-card" style="border-left: 4px solid #007aff;">
                    <h4>Surcharge IMAX</h4>
                    <div class="value" style="color:#007aff;">Rp <?php echo number_format($totalBiayaImax, 0, ',', '.'); ?></div>
                </div>
                <div class="stat-card" style="border-left: 4px solid #ffffff;">
                    <h4>Volume Penjualan</h4>
                    <div class="value"><?php echo $totalTiketTerjual; ?> Pax</div>
                </div>
            </section>

            <div class="chart-box">
                <h4 style="font-size:12px; color:#b0b3b8; text-transform:uppercase; letter-spacing:0.5px;">Rasio Distribusi Objek Studio</h4>
                <div class="bar-container">
                    <div class="bar-segment" style="width: <?php echo $pctRegular; ?>%; background: #34c759;"><?php echo $pctRegular; ?>% Reg</div>
                    <div class="bar-segment" style="width: <?php echo $pctImax; ?>%; background: #007aff;"><?php echo $pctImax; ?>% IMAX</div>
                    <div class="bar-segment" style="width: <?php echo $pctVelvet; ?>%; background: #af52de;"><?php echo $pctVelvet; ?>% Velvet</div>
                </div>
            </div>

            <?php foreach ($daftarTiket as $kategoriStudio => $listTiket): ?>
                <div class="milk-panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <span class="status-dot <?php echo 'dot-'.strtolower($kategoriStudio); ?>"></span>
                            <span>Log Antrean Studio: <?php echo $kategoriStudio; ?></span>
                        </div>
                        <span class="counter-badge"><?php echo count($listTiket); ?> Transaksi</span>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr><th>Token ID</th><th>Film</th><th>Kapasitas</th><th>Harga Dasar</th><th>Fasilitas Khusus Polimorfik</th><th>Total Tagihan</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($listTiket, 0, 3) as $t): ?>
                                    <tr>
                                        <td><span class="ticket-token"><?php echo $t->getIdTiket(); ?></span></td>
                                        <td style="font-weight:600;"><?php echo $t->getNamaFilm(); ?></td>
                                        <td><?php echo $t->getJumlahKursi(); ?> Kursi</td>
                                        <td>Rp <?php echo number_format($t->getHargaDasarTiket(), 0, ',', '.'); ?></td>
                                        <td>
                                            <div class="pill-box">
                                                <?php 
                                                if ($t instanceof TiketRegular) echo "<span class='pill-ios'>🔊 ".$t->getTipeAudio()."</span>";
                                                elseif ($t instanceof TiketMax) echo "<span class='pill-ios'>👓 ID: ".$t->getKacamata3dId()."</span>";
                                                elseif ($t instanceof TiketVelvet) echo "<span class='pill-ios'>🛌 Pack: ".$t->getBantalSelimutPack()."</span>";
                                                ?>
                                            </div>
                                        </td>
                                        <td style="font-weight:600;">Rp <?php echo number_format($t->hitungTotalHarga(), 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="jadwal-view" class="app-view">
            <header class="main-header">
                <h1>Jadwal Penayangan Teater</h1>
                <p>Urutan waktu dan lini masa distribusi pertunjukan film berdasarkan jam tayang operasional teater</p>
            </header>

            <div class="timeline-container">
                <?php foreach ($semuaTransaksi as $t): ?>
                    <div class="timeline-card card-type-<?php echo $t->getJenisStudio(); ?>">
                        <div>
                            <div class="time-header">
                                <span>⏰</span> <?php echo date('H:i', strtotime($t->getJadwalTayang())); ?> WIB
                            </div>
                            <h3 style="font-size:16px; font-weight:700; margin-bottom:6px;"><?php echo $t->getNamaFilm(); ?></h3>
                            <p style="font-size:13px; color:#636366; margin-bottom:12px;">Tanggal: <?php echo date('d M Y', strtotime($t->getJadwalTayang())); ?></p>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f2f2f7; padding-top:12px; margin-top:12px;">
                            <span class="counter-badge" style="font-size:11px;">Studio <?php echo $t->getJenisStudio(); ?></span>
                            <span style="font-size:12px; color:#3a3a3c; font-weight:500;"><?php echo $t->getJumlahKursi(); ?> Kursi Diorder</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="transaksi-view" class="app-view">
            <header class="main-header">
                <h1>Log Transaksi Terpadu</h1>
                <p>Kompilasi seluruh basis data pembelian tiket polimorfik dari relasi entitas MariaDB</p>
            </header>

            <div class="milk-panel" style="margin-top:25px;">
                <div class="panel-header">
                    <div class="panel-title"><span>📂 Complete Data Stream Audit</span></div>
                    <span class="counter-badge" style="background:#007aff; color:#fff;"><?php echo count($semuaTransaksi); ?> Total Item</span>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Token ID</th>
                                <th>Film</th>
                                <th>Studio</th>
                                <th>Waktu Siar</th>
                                <th>Volume</th>
                                <th>Fasilitas Polimorfik</th>
                                <th>Total Netto</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semuaTransaksi as $t): ?>
                                <tr>
                                    <td><span class="ticket-token"><?php echo $t->getIdTiket(); ?></span></td>
                                    <td style="font-weight:600;"><?php echo $t->getNamaFilm(); ?></td>
                                    <td><span class="counter-badge"><?php echo $t->getJenisStudio(); ?></span></td>
                                    <td><?php echo date('d M - H:i', strtotime($t->getJadwalTayang())); ?> WIB</td>
                                    <td><?php echo $t->getJumlahKursi(); ?> Pax</td>
                                    <td>
                                        <div class="pill-box">
                                            <?php 
                                            if ($t instanceof TiketRegular) {
                                                echo "<span class='pill-ios'>🔊 " . $t->getTipeAudio() . "</span>";
                                                echo "<span class='pill-ios'>🪑 Row: " . $t->getLokasiBaris() . "</span>";
                                            } elseif ($t instanceof TiketMax) {
                                                echo "<span class='pill-ios'>👓 ID: " . $t->getKacamata3dId() . "</span>";
                                                echo "<span class='pill-ios'>🎬 Motion: " . $t->getEfekGerakFitur() . "</span>";
                                            } elseif ($t instanceof TiketVelvet) {
                                                echo "<span class='pill-ios'>🛌 Pack: " . $t->getBantalSelimutPack() . "</span>";
                                                echo "<span class='pill-ios'>🤵 Butler: " . $t->getLayananButler() . "</span>";
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td style="font-weight:700; color:#000;">Rp <?php echo number_format($t->hitungTotalHarga(), 0, ',', '.'); ?></td>
                                    <td><span class="live-badge">Success</span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <script>
        function switchView(viewId) {
            // 1. Sembunyikan semua kontainer view
            const views = document.querySelectorAll('.app-view');
            views.forEach(v => v.classList.remove('active-view'));

            // 2. Matikan semua status active tombol di sidebar
            const links = document.querySelectorAll('.sidebar-link');
            links.forEach(l => l.classList.remove('active'));

            // 3. Aktifkan view yang dituju beserta tombol sidebarnya
            document.getElementById(viewId).classList.add('active-view');
            document.getElementById('btn-' + viewId).classList.add('active');
        }
    </script>
</body>
</html>