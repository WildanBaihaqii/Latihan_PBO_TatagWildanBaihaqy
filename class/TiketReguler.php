<?php
require_once __DIR__ . '/../Tiket.php';

class TiketRegular extends Tiket {
    protected $tipeAudio;
    protected $lokasiBaris;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $tipeAudio, $lokasiBaris) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->tipeAudio = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    public function getTipeAudio() { return $this->tipeAudio; }
    public function getLokasiBaris() { return $this->lokasiBaris; }

    public function getJenisStudio() { return 'Regular'; }

    // Tahap 5: Overriding Hitung Harga
    public function hitungTotalHarga() {
        return $this->jumlah_kursi * $this->harga_dasar_tiket;
    }
}
?>