<?php
// =============================================
// FILE: koneksi.php
// UAS PBO - TRPL1B - KRISTIANA SETYANINGSIH
// IMPLEMENTASI ABSTRAKSI
// =============================================

// 1. KONEKSI DATABASE
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "DB_UAS_PBO_TRPL1B_KRISTIANA_SETYANINGSIH";
    public $connection;

    public function __construct() {
        try {
            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );
            
            if ($this->connection->connect_error) {
                throw new Exception("Koneksi gagal: " . $this->connection->connect_error);
            }
            
            // Set charset ke UTF-8
            $this->connection->set_charset("utf8");
            
        } catch (Exception $e) {
            die("Error koneksi database: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}

// 2. ABSTRACT CLASS KARYAWAN
abstract class Karyawan {
    // Properti terenkapsulasi (protected)
    protected $id_karyawan;
    protected $nama_karyawan;
    protected $departemen;
    protected $hariKerjaMasuk;
    protected $gajiDasarPerHari;
    
    // Constructor
    public function __construct($id_karyawan, $nama_karyawan, $departemen, $hariKerjaMasuk, $gajiDasarPerHari) {
        $this->id_karyawan = $id_karyawan;
        $this->nama_karyawan = $nama_karyawan;
        $this->departemen = $departemen;
        $this->hariKerjaMasuk = $hariKerjaMasuk;
        $this->gajiDasarPerHari = $gajiDasarPerHari;
    }
    
    // Getter methods (untuk mengakses properti protected)
    public function getIdKaryawan() {
        return $this->id_karyawan;
    }
    
    public function getNamaKaryawan() {
        return $this->nama_karyawan;
    }
    
    public function getDepartemen() {
        return $this->departemen;
    }
    
    public function getHariKerjaMasuk() {
        return $this->hariKerjaMasuk;
    }
    
    public function getGajiDasarPerHari() {
        return $this->gajiDasarPerHari;
    }
    
    // Setter methods
    public function setIdKaryawan($id_karyawan) {
        $this->id_karyawan = $id_karyawan;
    }
    
    public function setNamaKaryawan($nama_karyawan) {
        $this->nama_karyawan = $nama_karyawan;
    }
    
    public function setDepartemen($departemen) {
        $this->departemen = $departemen;
    }
    
    public function setHariKerjaMasuk($hariKerjaMasuk) {
        $this->hariKerjaMasuk = $hariKerjaMasuk;
    }
    
    public function setGajiDasarPerHari($gajiDasarPerHari) {
        $this->gajiDasarPerHari = $gajiDasarPerHari;
    }
    
    // Method untuk menghitung masa kerja (dalam hari)
    public function hitungMasaKerja() {
        $tanggalMasuk = new DateTime($this->hariKerjaMasuk);
        $tanggalSekarang = new DateTime('now');
        $selisih = $tanggalMasuk->diff($tanggalSekarang);
        return $selisih->days; // Mengembalikan selisih dalam hari
    }
    
    // Method untuk menghitung gaji kotor (tanpa tunjangan)
    public function hitungGajiKotor() {
        $masaKerja = $this->hitungMasaKerja();
        // Asumsikan 1 bulan = 22 hari kerja
        $hariKerja = 22;
        return $this->gajiDasarPerHari * $hariKerja;
    }
    
    // ABSTRACT METHODS (wajib diimplementasikan oleh child class)
    abstract public function hitungGajiBersih();
    abstract public function tampilkanProfilKaryawan();
}
