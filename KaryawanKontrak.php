<?php
// =============================================
// FILE: KaryawanKontrak.php
// CLASS: KaryawanKontrak (Child dari Karyawan)
// IMPLEMENTASI POLIMORFISME - OVERRIDING
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
    
    /**
     * METHOD OVERRIDING - hitungGajiBersih()
     * POLIMORFISME: Karyawan Kontrak
     * Logika: Gaji Bersih = hariKerjaMasuk * gajiDasarPerHari
     * (Sistem penggajian murni berdasarkan jumlah hari kehadiran)
     */
    public function hitungGajiBersih() {
        // Menggunakan method hitungMasaKerja() dari parent
        $hariKerja = $this->hitungMasaKerja();
        $gajiBersih = $hariKerja * $this->getGajiDasarPerHari();
        return $gajiBersih;
    }
    
    // Implementasi method abstrak tampilkanProfilKaryawan()
    public function tampilkanProfilKaryawan() {
        echo "<div style='border:1px solid #3498db; padding:15px; margin:10px; border-radius:5px; background:#f0f8ff;'>";
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
        echo "<tr><td><strong>Hari Kerja</strong></td><td>: " . $this->hitungMasaKerja() . " hari</td></tr>";
        echo "<tr><td style='font-weight:bold;color:#3498db;'>💵 Gaji Bersih</strong></td><td style='font-weight:bold;color:#3498db;'>: Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Keterangan</strong></td><td>: <em>Murni berdasarkan jumlah hari kehadiran</em></td></tr>";
        echo "</table>";
        echo "</div>";
    }
}

?>