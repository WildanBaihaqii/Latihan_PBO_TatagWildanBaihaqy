<?php
require_once __DIR__ . '/../Tiket.php';

class TiketMax extends Tiket {
    protected $kacamata3dId;
    protected $efekGerakFitur;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $kacamata3dId, $efekGerakFitur) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->kacamata3dId = $kacamata3dId;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    public function getKacamata3dId() { return $this->kacamata3dId; }
    public function getEfekGerakFitur() { return $this->efekGerakFitur; }

    public function getJenisStudio() { return 'IMAX'; }

    // Tahap 5: Overriding Hitung Harga (+35000)
    public function hitungTotalHarga() {
        return ($this->jumlah_kursi * $this->harga_dasar_tiket) + 35000;
    }
}
?>