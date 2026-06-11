<?php
require_once 'Tiket.php';

class TiketRegular extends Tiket {
    // Properti spesifik kelas Regular
    protected $tipeAudio;
    protected $lokasiBaris;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket, $tipeAudio, $lokasiBaris) {
        // Mengirim data ke constructor abstract class Tiket
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket);
        $this->tipeAudio = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    // Getter & Setter Spesifik
    public function getTipeAudio() { return $this->tipeAudio; }
    public function setTipeAudio($tipeAudio) { $this->tipeAudio = $tipeAudio; }

    public function getLokasiBaris() { return $this->lokasiBaris; }
    public function setLokasiBaris($lokasiBaris) { $this->lokasiBaris = $lokasiBaris; }

    // Implementasi dari method abstract induk
    public function getJenisStudio() {
        return 'Regular';
    }
}