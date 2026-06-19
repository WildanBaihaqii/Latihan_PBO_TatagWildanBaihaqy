<?php 
require_once __DIR__ . '/proses_data.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Cinema Dashboard - Soft Clean Edition</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

    <aside class="sidebar">
        <div>
            <div class="sidebar-logo">
                <span style="background: linear-gradient(135deg, #b39ddb, #4fc3f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Bioskop</span>
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
                <p style="font-size:11px; color:#92929d;">Administrator</p>
            </div>
        </div>
    </aside>

    <main class="main-content">

        <div id="dashboard-view" class="app-view active-view">
            <header class="main-header">
                <h1>Dashboard Bioskop</h1>
                <p>Ikhtisar performa penjualan tiket studio</p>
            </header>

            <section class="stats-grid">
                <div class="stat-card card-omset">
                    <h4>Total Omset Bruto</h4>
                    <div class="value">Rp <?php echo number_format($totalPendapatan, 0, ',', '.'); ?></div>
                </div>
                <div class="stat-card card-velvet">
                    <h4>Surcharge Velvet</h4>
                    <div class="value" style="color:#b39ddb;">Rp <?php echo number_format($totalSurchargeVelvet, 0, ',', '.'); ?></div>
                </div>
                <div class="stat-card card-imax">
                    <h4>Surcharge IMAX</h4>
                    <div class="value" style="color:#4fc3f7;">Rp <?php echo number_format($totalBiayaImax, 0, ',', '.'); ?></div>
                </div>
                <div class="stat-card card-volume">
                    <h4>Volume Penjualan</h4>
                    <div class="value"><?php echo $totalTiketTerjual; ?> Pax</div>
                </div>
            </section>

            <div class="chart-box">
                <h4 style="font-size:12px; color:#92929d; text-transform:uppercase; letter-spacing:0.5px;">Rasio Distribusi Objek Studio</h4>
                <div class="bar-container">
                    <div class="bar-segment" style="width: <?php echo $pctRegular; ?>%; background: #4caf50;"><?php echo $pctRegular; ?>% Reg</div>
                    <div class="bar-segment" style="width: <?php echo $pctImax; ?>%; background: #03a9f4;"><?php echo $pctImax; ?>% IMAX</div>
                    <div class="bar-segment" style="width: <?php echo $pctVelvet; ?>%; background: #9c27b0;"><?php echo $pctVelvet; ?>% Velvet</div>
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
                                <tr><th>Token ID</th><th>Film</th><th>Kapasitas</th><th>Harga Dasar</th><th>Fasilitas Khusus</th><th>Total Tagihan</th></tr>
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
                <p>Lini masa distribusi pertunjukan teater berdasarkan jam operasional</p>
            </header>

            <div class="timeline-container">
                <?php foreach ($semuaTransaksi as $t): ?>
                    <div class="timeline-card card-type-<?php echo $t->getJenisStudio(); ?>">
                        <div>
                            <div class="time-header">
                                <span>⏰</span> <?php echo date('H:i', strtotime($t->getJadwalTayang())); ?> WIB
                            </div>
                            <h3 style="font-size:16px; font-weight:600; margin-bottom:6px; color:#1c1c1e;"><?php echo $t->getNamaFilm(); ?></h3>
                            <p style="font-size:13px; color:#8e8e93; margin-bottom:12px;">Tanggal: <?php echo date('d M Y', strtotime($t->getJadwalTayang())); ?></p>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f2f2f7; padding-top:12px; margin-top:12px;">
                            <span class="counter-badge" style="font-size:11px;">Studio <?php echo $t->getJenisStudio(); ?></span>
                            <span style="font-size:12px; color:#48484a; font-weight:500;"><?php echo $t->getJumlahKursi(); ?> Kursi</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="transaksi-view" class="app-view">
            <header class="main-header">
                <h1>Log Transaksi Terpadu</h1>
                <p>Kompilasi seluruh basis data pembelian tiket dari entitas database</p>
            </header>

            <div class="milk-panel" style="margin-top:25px;">
                <div class="panel-header">
                    <div class="panel-title"><span>📂 Complete Data Stream Audit</span></div>
                    <span class="counter-badge" style="background:#1c1c1e; color:#fff;"><?php echo count($semuaTransaksi); ?> Total Item</span>
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
                                <th>Fasilitas</th>
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
                                    <td style="font-weight:600; color:#1c1c1e;">Rp <?php echo number_format($t->hitungTotalHarga(), 0, ',', '.'); ?></td>
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
            const views = document.querySelectorAll('.app-view');
            views.forEach(v => v.classList.remove('active-view'));

            const links = document.querySelectorAll('.sidebar-link');
            links.forEach(l => l.classList.remove('active'));

            document.getElementById(viewId).classList.add('active-view');
            document.getElementById('btn-' + viewId).classList.add('active');
        }
    </script>
</body>
</html>