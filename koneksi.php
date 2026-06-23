<?php
// =============================================
// FILE: koneksi.php
// UAS PBO - TRPL1B - KRISTIANA SETYANINGSIH
// KONEKSI DATABASE OOP DENGAN SINGLETON PATTERN
// =============================================

/**
 * Class Database - Mengelola koneksi database dengan Singleton Pattern
 * Mencegah multiple koneksi dan memastikan hanya satu koneksi yang aktif
 */
class Database {
    // Singleton instance
    private static $instance = null;
    
    // Koneksi database
    private $connection;
    
    // Konfigurasi database
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "DB_UAS_PBO_TRPL1B_KRISTIANA_SETYANINGSIH";
    
    /**
     * Constructor - Private untuk Singleton Pattern
     * Hanya bisa dipanggil dari dalam class
     */
    private function __construct() {
        $this->connect();
    }
    
    /**
     * Method untuk mendapatkan instance tunggal (Singleton)
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Melakukan koneksi ke database
     * @throws Exception Jika koneksi gagal
     */
    private function connect() {
        try {
            // Buat koneksi dengan MySQLi
            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );
            
            // Cek koneksi
            if ($this->connection->connect_error) {
                throw new Exception(
                    "Koneksi database gagal: " . $this->connection->connect_error
                );
            }
            
            // Set charset ke UTF-8 untuk mendukung karakter Indonesia
            $this->connection->set_charset("utf8mb4");
            
            // Set mode error untuk exception
            $this->connection->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
            
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
    /**
     * Mendapatkan objek koneksi
     * @return mysqli
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Menjalankan query dengan prepared statement (keamanan)
     * @param string $sql Query SQL dengan placeholder
     * @param string $types Tipe data (s: string, i: integer, d: double, b: blob)
     * @param array $params Parameter untuk prepared statement
     * @return mysqli_result|bool
     */
    public function executeQuery($sql, $types = "", $params = []) {
        try {
            if (empty($types) && empty($params)) {
                // Query tanpa parameter
                return $this->connection->query($sql);
            } else {
                // Query dengan prepared statement
                $stmt = $this->connection->prepare($sql);
                if ($types && $params) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                
                // Jika SELECT, return result
                if (strpos(strtoupper($sql), 'SELECT') === 0) {
                    return $stmt->get_result();
                }
                
                // Jika INSERT/UPDATE/DELETE, return affected rows
                return $stmt->affected_rows;
            }
        } catch (Exception $e) {
            die("Error query: " . $e->getMessage());
        }
    }
    
    /**
     * Mendapatkan ID terakhir yang di-insert
     * @return int
     */
    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
    
    /**
     * Menghitung jumlah baris yang terpengaruh
     * @return int
     */
    public function getAffectedRows() {
        return $this->connection->affected_rows;
    }
    
    /**
     * Memulai transaksi
     */
    public function beginTransaction() {
        $this->connection->begin_transaction();
    }
    
    /**
     * Commit transaksi
     */
    public function commit() {
        $this->connection->commit();
    }
    
    /**
     * Rollback transaksi
     */
    public function rollback() {
        $this->connection->rollback();
    }
    
    /**
     * Menutup koneksi database
     */
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
            self::$instance = null; // Reset instance untuk singleton
        }
    }
    
    /**
     * Cek apakah koneksi aktif
     * @return bool
     */
    public function isConnected() {
        return $this->connection !== null && $this->connection->ping();
    }
    
    /**
     * Mencegah cloning (Singleton)
     */
    private function __clone() {}
    
    /**
     * Mencegah unserialize (Singleton)
     */
    private function __wakeup() {}
}

// =============================================
// ABSTRACT CLASS KARYAWAN
// =============================================

abstract class Karyawan {
    // Properti terenkapsulasi (protected)
    protected $id_karyawan;
    protected $nama_karyawan;
    protected $departemen;
    protected $hariKerjaMasuk;
    protected $gajiDasarPerHari;
    
    /**
     * Constructor
     * @param int $id_karyawan
     * @param string $nama_karyawan
     * @param string $departemen
     * @param string $hariKerjaMasuk
     * @param float $gajiDasarPerHari
     */
    public function __construct($id_karyawan, $nama_karyawan, $departemen, $hariKerjaMasuk, $gajiDasarPerHari) {
        $this->id_karyawan = $id_karyawan;
        $this->nama_karyawan = $nama_karyawan;
        $this->departemen = $departemen;
        $this->hariKerjaMasuk = $hariKerjaMasuk;
        $this->gajiDasarPerHari = $gajiDasarPerHari;
    }
    
    // ========== GETTER METHODS ==========
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
    
    // ========== SETTER METHODS ==========
    public function setIdKaryawan($id_karyawan) {
        $this->id_karyawan = $id_karyawan;
        return $this; // Method chaining
    }
    
    public function setNamaKaryawan($nama_karyawan) {
        $this->nama_karyawan = $nama_karyawan;
        return $this;
    }
    
    public function setDepartemen($departemen) {
        $this->departemen = $departemen;
        return $this;
    }
    
    public function setHariKerjaMasuk($hariKerjaMasuk) {
        $this->hariKerjaMasuk = $hariKerjaMasuk;
        return $this;
    }
    
    public function setGajiDasarPerHari($gajiDasarPerHari) {
        $this->gajiDasarPerHari = $gajiDasarPerHari;
        return $this;
    }
    
    /**
     * Menghitung masa kerja dalam hari
     * @return int
     */
    public function hitungMasaKerja() {
        $tanggalMasuk = new DateTime($this->hariKerjaMasuk);
        $tanggalSekarang = new DateTime('now');
        $selisih = $tanggalMasuk->diff($tanggalSekarang);
        return $selisih->days;
    }
    
    /**
     * Menghitung gaji kotor (22 hari kerja)
     * @return float
     */
    public function hitungGajiKotor() {
        $hariKerja = 22; // Standar 22 hari kerja per bulan
        return $this->gajiDasarPerHari * $hariKerja;
    }
    
    /**
     * Mendapatkan format tanggal Indonesia
     * @param string $format
     * @return string
     */
    public function getFormattedTanggalMasuk($format = 'd F Y') {
        return date($format, strtotime($this->hariKerjaMasuk));
    }
    
    /**
     * Mendapatkan status masa kerja
     * @return string
     */
    public function getStatusMasaKerja() {
        $hari = $this->hitungMasaKerja();
        if ($hari < 30) {
            return "Baru (< 1 bulan)";
        } elseif ($hari < 365) {
            $bulan = floor($hari / 30);
            return "{$bulan} bulan";
        } else {
            $tahun = floor($hari / 365);
            $bulan = floor(($hari % 365) / 30);
            if ($bulan > 0) {
                return "{$tahun} tahun {$bulan} bulan";
            }
            return "{$tahun} tahun";
        }
    }
    
    // ========== ABSTRACT METHODS ==========
    abstract public function hitungGajiBersih();
    abstract public function tampilkanProfilKaryawan();
}

// =============================================
// FUNGSI BANTUAN (Helper Functions)
// =============================================

/**
 * Fungsi untuk mendapatkan data karyawan dari database
 * @param int $id Karyawan ID (opsional)
 * @param string $jenis Jenis karyawan (opsional)
 * @return array
 */
function getKaryawanData($id = null, $jenis = null) {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $sql = "SELECT * FROM tabel_karyawan";
    $conditions = [];
    
    if ($id) {
        $conditions[] = "id_karyawan = " . intval($id);
    }
    
    if ($jenis) {
        $conditions[] = "jenis_karyawan = '" . $conn->real_escape_string($jenis) . "'";
    }
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY jenis_karyawan, id_karyawan";
    
    $result = $db->executeQuery($sql);
    
    if ($result && $result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    
    return [];
}

/**
 * Fungsi untuk membuat objek karyawan dari data
 * @param array $data Data karyawan dari database
 * @return Karyawan|null
 */
function createKaryawanObject($data) {
    if (empty($data)) {
        return null;
    }
    
    switch ($data['jenis_karyawan']) {
        case 'kontrak':
            return new KaryawanKontrak(
                $data['id_karyawan'],
                $data['nama_karyawan'],
                $data['departemen'],
                $data['hari_kerja_masuk'],
                $data['gaji_dasar_per_hari'],
                $data['durasi_kontrak_bulan'],
                $data['agensi_penyalur']
            );
            
        case 'tetap':
            return new KaryawanTetap(
                $data['id_karyawan'],
                $data['nama_karyawan'],
                $data['departemen'],
                $data['hari_kerja_masuk'],
                $data['gaji_dasar_per_hari'],
                $data['tunjangan_kesehatan'],
                $data['opsi_saham_id']
            );
            
        case 'magang':
            return new KaryawanMagang(
                $data['id_karyawan'],
                $data['nama_karyawan'],
                $data['departemen'],
                $data['hari_kerja_masuk'],
                $data['gaji_dasar_per_hari'],
                $data['uang_saku_bulanan'],
                $data['sertifikat_kampus_merdeka']
            );
            
        default:
            return null;
    }
}

/**
 * Fungsi untuk format Rupiah
 * @param float $number
 * @return string
 */
function formatRupiah($number) {
    return "Rp " . number_format($number, 0, ',', '.');
}

/**
 * Fungsi untuk debug (print_r yang rapi)
 * @param mixed $data
 */
function debug($data) {
    echo "<pre style='background:#f4f4f4; padding:15px; border-radius:5px;'>";
    print_r($data);
    echo "</pre>";
}

?>