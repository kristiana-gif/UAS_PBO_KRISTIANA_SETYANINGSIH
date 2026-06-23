<?php
// =============================================
// FILE: KaryawanTetap.php
// CLASS: KaryawanTetap (Child dari Karyawan)
// =============================================

require_once 'koneksi.php';

class KaryawanTetap extends Karyawan {
    // Properti tambahan
    private $tunjanganKesehatan;
    private $opsiSahamId;
    
    // Constructor
    public function __construct($id_karyawan, $nama_karyawan, $departemen, $hariKerjaMasuk, $gajiDasarPerHari, $tunjanganKesehatan, $opsiSahamId) {
        // Panggil constructor parent
        parent::__construct($id_karyawan, $nama_karyawan, $departemen, $hariKerjaMasuk, $gajiDasarPerHari);
        $this->tunjanganKesehatan = $tunjanganKesehatan;
        $this->opsiSahamId = $opsiSahamId;
    }
    
    // Getter untuk properti tambahan
    public function getTunjanganKesehatan() {
        return $this->tunjanganKesehatan;
    }
    
    public function getOpsiSahamId() {
        return $this->opsiSahamId;
    }
    
    // Setter untuk properti tambahan
    public function setTunjanganKesehatan($tunjanganKesehatan) {
        $this->tunjanganKesehatan = $tunjanganKesehatan;
    }
    
    public function setOpsiSahamId($opsiSahamId) {
        $this->opsiSahamId = $opsiSahamId;
    }
    
    // Implementasi method abstrak hitungGajiBersih()
    public function hitungGajiBersih() {
        $gajiKotor = $this->hitungGajiKotor();
        // Karyawan tetap mendapat tunjangan kesehatan
        $gajiSetelahTunjangan = $gajiKotor + $this->tunjanganKesehatan;
        // Pajak 5% (lebih tinggi karena tetap)
        $pajak = $gajiKotor * 0.05;
        $gajiBersih = $gajiSetelahTunjangan - $pajak;
        return $gajiBersih;
    }
    
    // Implementasi method abstrak tampilkanProfilKaryawan()
    public function tampilkanProfilKaryawan() {
        echo "<div style='border:1px solid #2ecc71; padding:15px; margin:10px; border-radius:5px;'>";
        echo "<h3 style='color:#2ecc71;'>📋 PROFIL KARYAWAN TETAP</h3>";
        echo "<hr>";
        echo "<table style='width:100%;'>";
        echo "<tr><td><strong>ID Karyawan</strong></td><td>: " . $this->getIdKaryawan() . "</td></tr>";
        echo "<tr><td><strong>Nama Karyawan</strong></td><td>: " . $this->getNamaKaryawan() . "</td></tr>";
        echo "<tr><td><strong>Departemen</strong></td><td>: " . $this->getDepartemen() . "</td></tr>";
        echo "<tr><td><strong>Tanggal Masuk</strong></td><td>: " . $this->getHariKerjaMasuk() . "</td></tr>";
        echo "<tr><td><strong>Gaji Dasar/Hari</strong></td><td>: Rp " . number_format($this->getGajiDasarPerHari(), 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Tunjangan Kesehatan</strong></td><td>: Rp " . number_format($this->tunjanganKesehatan, 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Opsi Saham ID</strong></td><td>: " . $this->opsiSahamId . "</td></tr>";
        echo "<tr><td><strong>Gaji Kotor/Bulan</strong></td><td>: Rp " . number_format($this->hitungGajiKotor(), 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Gaji Bersih/Bulan</strong></td><td>: Rp " . number_format($this->hitungGajiBersih(), 0, ',', '.') . "</td></tr>";
        echo "<tr><td><strong>Masa Kerja</strong></td><td>: " . $this->hitungMasaKerja() . " hari</td></tr>";
        echo "</table>";
        echo "</div>";
    }
}

?>