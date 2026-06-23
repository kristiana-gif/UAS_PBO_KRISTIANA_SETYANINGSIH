<?php
// =============================================
// FILE: KaryawanKontrak.php
// CLASS: KaryawanKontrak (Child dari Karyawan)
// =============================================

require_once 'koneksi.php';

class KaryawanKontrak extends Karyawan {
    // Properti tambahan
    private $durasiKontrakBulan;
    private $agensiPenyalur;
    
    // Constructor
    public function __construct($id_karyawan, $nama_karyawan, $departemen, $hariKerjaMasuk, $gajiDasarPerHari, $durasiKontrakBulan, $agensiPenyalur) {
        // Panggil constructor parent
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, $hariKerjaMasuk, $gajiDasarPerHari);
        $this->durasiKontrakBulan = $durasiKontrakBulan;
        $this->agensiPenyalur = $agensiPenyalur;
    }
    
    // Getter untuk properti tambahan
    public function getDurasiKontrakBulan() {
        return $this->durasiKontrakBulan;
    }
    
    public function getAgensiPenyalur() {
        return $this->agensiPenyalur;
    }
    
    // Setter untuk properti tambahan
    public function setDurasiKontrakBulan($durasiKontrakBulan) {
        $this->durasiKontrakBulan = $durasiKontrakBulan;
    }
    
    public function setAgensiPenyalur($agensiPenyalur) {
        $this->agensiPenyalur = $agensiPenyalur;
    }
    
    // Implementasi method abstrak hitungGajiBersih()
    public function hitungGajiBersih() {
        $gajiKotor = $this->hitungGajiKotor();
        // Kontrak mendapat tunjangan 5% dari gaji kotor
        $tunjangan = $gajiKotor * 0.05;
        // Pajak 2.5%
        $pajak = $gajiKotor * 0.025;
        $gajiBersih = $gajiKotor + $tunjangan - $pajak;
        return $gajiBersih;
    }
    
    // Implementasi method abstrak tampilkanProfilKaryawan()
    public function tampilkanProfilKaryawan() {
        echo "<div style='border:1px solid #3498db; padding:15px; margin:10px; border-radius:5px;'>";
        echo "<h3 style='color:#3498db;'>📋 PROFIL KARYAWAN KONTRAK</h3>";
        echo "<hr>";
        echo "<table style='width:100%;'>";
        echo "<tr><td><strong>ID Karyawan</strong></td><td>: " . $this->getIdKaryawan() . "</td></tr>";
        echo "<tr><td><strong>Nama Karyawan</strong></td><td>: " . $this->getNamaKaryawan() . "</td></tr>";
        echo "<tr><td><strong>Departemen</strong></td><td>: " . $this->getDepartemen() . "</td></tr>";
        echo "<tr><td><strong>Tanggal Masuk</strong></td><td>: " . $this->getHariKerjaMasuk() . "</td></tr>";
        echo "<tr><td><strong>Gaji Dasar/Hari</strong></td><td>: Rp " . number_format($this->getGajiDasarPerHari(), 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Durasi Kontrak</strong></td><td>: " . $this->durasiKontrakBulan . " bulan</td></tr>";
        echo "<tr><td><strong>Agensi Penyalur</strong></td><td>: " . $this->agensiPenyalur . "</td></tr>";
        echo "<tr><td><strong>Gaji Kotor/Bulan</strong></td><td>: Rp " . number_format($this->hitungGajiKotor(), 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Gaji Bersih/Bulan</strong></td><td>: Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Masa Kerja</strong></td><td>: " . $this->hitungMasaKerja() . " hari</td></tr>";
        echo "</table>";
        echo "</div>";
    }
}

?>