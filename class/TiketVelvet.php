<?php
require_once __DIR__ . '/../src/Tiket.php';

class TiketVelvet extends Tiket {
    protected $bantalSelimutPack;
    protected $layananButler;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $bantalSelimutPack, $layananButler) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->bantalSelimutPack = $bantalSelimutPack;
        $this->layananButler = $layananButler;
    }

    public function getBantalSelimutPack() { return $this->bantalSelimutPack; }
    public function getLayananButler() { return $this->layananButler; }

    public function getJenisStudio() { return 'Velvet'; }

    // Tahap 5: Overriding Hitung Harga (Surcharge 50% / * 1.50)
    public function hitungTotalHarga() {
        return ($this->jumlah_kursi * $this->harga_dasar_tiket) * 1.50;
    }
}
?>