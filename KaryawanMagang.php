<?php
// =============================================
// FILE: KaryawanMagang.php
// CLASS: KaryawanMagang (Child dari Karyawan)
// =============================================

require_once 'koneksi.php';

class KaryawanMagang extends Karyawan {
    // Properti tambahan
    private $uangSakuBulanan;
    private $sertifikatKampusMerdeka;
    
    // Constructor
    public function __construct($id_karyawan, $nama_karyawan, $departemen, $hariKerjaMasuk, $gajiDasarPerHari, $uangSakuBulanan, $sertifikatKampusMerdeka) {
        // Panggil constructor parent
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, $hariKerjaMasuk, $gajiDasarPerHari);
        $this->uangSakuBulanan = $uangSakuBulanan;
        $this->sertifikatKampusMerdeka = $sertifikatKampusMerdeka;
    }
    
    // Getter untuk properti tambahan
    public function getUangSakuBulanan() {
        return $this->uangSakuBulanan;
    }
    
    public function getSertifikatKampusMerdeka() {
        return $this->sertifikatKampusMerdeka;
    }
    
    // Setter untuk properti tambahan
    public function setUangSakuBulanan($uangSakuBulanan) {
        $this->uangSakuBulanan = $uangSakuBulanan;
    }
    
    public function setSertifikatKampusMerdeka($sertifikatKampusMerdeka) {
        $this->sertifikatKampusMerdeka = $sertifikatKampusMerdeka;
    }
    
    // Implementasi method abstrak hitungGajiBersih()
    public function hitungGajiBersih() {
        // Magang mendapat uang saku bulanan (tanpa pajak)
        return $this->uangSakuBulanan;
    }
    
    // Implementasi method abstrak tampilkanProfilKaryawan()
    public function tampilkanProfilKaryawan() {
        echo "<div style='border:1px solid #e67e22; padding:15px; margin:10px; border-radius:5px;'>";
        echo "<h3 style='color:#e67e22;'>📋 PROFIL KARYAWAN MAGANG</h3>";
        echo "<hr>";
        echo "<table style='width:100%;'>";
        echo "<tr><td><strong>ID Karyawan</strong></td><td>: " . $this->getIdKaryawan() . "</td></tr>";
        echo "<tr><td><strong>Nama Karyawan</strong></td><td>: " . $this->getNamaKaryawan() . "</td></tr>";
        echo "<tr><td><strong>Departemen</strong></td><td>: " . $this->getDepartemen() . "</td></tr>";
        echo "<tr><td><strong>Tanggal Masuk</strong></td><td>: " . $this->getHariKerjaMasuk() . "</td></tr>";
        echo "<tr><td><strong>Gaji Dasar/Hari</strong></td><td>: Rp " . number_format($this->getGajiDasarPerHari(), 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Uang Saku Bulanan</strong></td><td>: Rp " . number_format($this->uangSakuBulanan, 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Sertifikat Kampus Merdeka</strong></td><td>: " . $this->sertifikatKampusMerdeka . "</td></tr>";
        echo "<tr><td><strong>Gaji Bersih/Bulan</strong></td><td>: Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Masa Kerja</strong></td><td>: " . $this->hitungMasaKerja() . " hari</td></tr>";
        echo "</table>";
        echo "</div>";
    }
}

?>