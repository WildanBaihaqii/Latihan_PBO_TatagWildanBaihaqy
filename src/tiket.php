<?php

abstract class Tiket {
    // Properti dasar (Protected) untuk semua jenis tiket
    protected $id_tiket;
    protected $nama_film;
    protected $jadwal_tayang;
    protected $jumlah_kursi;
    protected $harga_dasar_tiket;

    // Constructor Induk
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $harga_dasar_tiket) {
        $this->id_tiket = $id_tiket;
        $this->nama_film = $nama_film;
        $this->jadwal_tayang = $jadwal_tayang;
        $this->jumlah_kursi = $jumlah_kursi;
        $this->harga_dasar_tiket = $harga_dasar_tiket;
    }

    // ==================== GETTER & SETTER INDUK ====================
    public function getIdTiket() { return $this->id_tiket; }
    public function setIdTiket($id_tiket) { $this->id_tiket = $id_tiket; }

    public function getNamaFilm() { return $this->nama_film; }
    public function setNamaFilm($nama_film) { $this->nama_film = $nama_film; }

    public function getJadwalTayang() { return $this->jadwal_tayang; }
    public function setJadwalTayang($jadwal_tayang) { $this->jadwal_tayang = $jadwal_tayang; }

    public function getJumlahKursi() { return $this->jumlah_kursi; }
    public function setJumlahKursi($jumlah_kursi) { $this->jumlah_kursi = $jumlah_kursi; }

    public function getHargaDasarTiket() { return $this->harga_dasar_tiket; }
    public function setHargaDasarTiket($harga_dasar_tiket) { $this->harga_dasar_tiket = $harga_dasar_tiket; }
    
    // Method abstrak yang wajib diisi oleh setiap kelas anak
    abstract public function getJenisStudio();
}