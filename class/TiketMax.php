<?php
require_once 'Tiket.php';

class TiketMax extends Tiket {
    // Properti spesifik kelas IMAX
    protected $kacamata3dId;
    protected $efekGerakFitur;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $kacamata3dId, $efekGerakFitur) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->kacamata3dId = $kacamata3dId;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    // Getter & Setter Spesifik
    public function getKacamata3dId() { return $this->kacamata3dId; }
    public function setKacamata3dId($kacamata3dId) { $this->kacamata3dId = $kacamata3dId; }

    public function getEfekGerakFitur() { return $this->efekGerakFitur; }
    public function setEfekGerakFitur($efekGerakFitur) { $this->efekGerakFitur = $efekGerakFitur; }

    public function getJenisStudio() {
        return 'IMAX';
    }
}