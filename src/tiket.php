<?php

class Tiket {
    // Properti Terenkapsulasi (Protected)
    // Dipetakan sesuai dengan kolom pada tabel database
    protected $id_tiket;
    protected $nama_film;
    protected $jadwal_tayang;
    protected $jumlah_kursi;
    protected $harga_dasar_tiket;

    // Constructor untuk menginisialisasi data saat objek dibuat
    public function __construct($id_tiket = null, $nama_film = null, $jadwal_tayang = null, $jumlah_kursi = null, $harga_dasar_tiket = null) {
        $this->id_tiket = $id_tiket;
        $this->nama_film = $nama_film;
        $this->jadwal_tayang = $jadwal_tayang;
        $this->jumlah_kursi = $jumlah_kursi;
        $this->harga_dasar_tiket = $harga_dasar_tiket;
    }

    // ==================== GETTER & SETTER ====================

    // 1. id_tiket
    public function getIdTiket() {
        return $this->id_tiket;
    }
    public function setIdTiket($id_tiket) {
        $this->id_tiket = $id_tiket;
    }

    // 2. nama_film
    public function getNamaFilm() {
        return $this->nama_film;
    }
    public function setNamaFilm($nama_film) {
        $this->nama_film = $nama_film;
    }

    // 3. jadwal_tayang
    public function getJadwalTayang() {
        return $this->jadwal_tayang;
    }
    public function setJadwalTayang($jadwal_tayang) {
        $this->jadwal_tayang = $jadwal_tayang;
    }

    // 4. jumlah_kursi
    public function getJumlahKursi() {
        return $this->jumlah_kursi;
    }
    public function setJumlahKursi($jumlah_kursi) {
        // Validasi dasar: jumlah kursi tidak boleh minus
        $this->jumlah_kursi = ($jumlah_kursi > 0) ? $jumlah_kursi : 0;
    }

    // 5. harga_dasar_tiket
    public function getHargaDasarTiket() {
        return $this->harga_dasar_tiket;
    }
    public function setHargaDasarTiket($harga_dasar_tiket) {
        $this->harga_dasar_tiket = $harga_dasar_tiket;
    }
}