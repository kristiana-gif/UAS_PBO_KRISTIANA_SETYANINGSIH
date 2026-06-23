<?php
// =============================================
// FILE: index.php
// UAS PBO - TRPL1B - KRISTIANA SETYANINGSIH
// IMPLEMENTASI VIEW - SLIP GAJI KARYAWAN
// =============================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'koneksi.php';
require_once 'KaryawanKontrak.php';
require_once 'KaryawanTetap.php';
require_once 'KaryawanMagang.php';

// Buat koneksi database
$db = new Database();
$conn = $db->getConnection();

// Ambil parameter filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';
$detailId = isset($_GET['detail']) ? $_GET['detail'] : null;

// Fungsi untuk membuat objek karyawan dari data database
function createKaryawanObject($data) {
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

// Query data berdasarkan filter
if ($filter == 'kontrak') {
    $query = "SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'kontrak' ORDER BY id_karyawan";
} elseif ($filter == 'tetap') {
    $query = "SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'tetap' ORDER BY id_karyawan";
} elseif ($filter == 'magang') {
    $query = "SELECT * FROM tabel_karyawan WHERE jenis_karyawan = 'magang' ORDER BY id_karyawan";
} else {
    $query = "SELECT * FROM tabel_karyawan ORDER BY jenis_karyawan, id_karyawan";
}

$result = $conn->query($query);

// Ambil data untuk detail
$detailData = null;
$detailObject = null;
if ($detailId) {
    $queryDetail = "SELECT * FROM tabel_karyawan WHERE id_karyawan = " . intval($detailId);
    $detailResult = $conn->query($queryDetail);
    $detailData = $detailResult->fetch_assoc();
    if ($detailData) {
        $detailObject = createKaryawanObject($detailData);
    }
}

// Ambil statistik (dengan koneksi yang sama, belum ditutup)
$statsQuery = "SELECT 
    jenis_karyawan, 
    COUNT(*) as jumlah,
    AVG(gaji_dasar_per_hari) as rata_gaji
    FROM tabel_karyawan 
    GROUP BY jenis_karyawan";
$statsResult = $conn->query($statsQuery);

// Ambil total karyawan
$totalQuery = "SELECT COUNT(*) as total FROM tabel_karyawan";
$totalResult = $conn->query($totalQuery);
$total = $totalResult->fetch_assoc()['total'];

// =============================================
// CATATAN: KONEKSI BELUM DITUTUP DI SINI
// KONEKSI AKAN DITUTUP DI PALING AKHIR FILE
// =============================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji Karyawan - UAS PBO</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1300px;
            margin: 0 auto;
        }
        
        /* HEADER */
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .header-left h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .header-left .subtitle {
            color: #a8b2d1;
            font-size: 14px;
        }
        
        .header-right {
            text-align: right;
        }
        
        .header-right .badge {
            background: rgba(255,255,255,0.1);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .header-right .date {
            font-size: 14px;
            color: #a8b2d1;
            margin-top: 5px;
        }
        
        /* STATS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
        }
        
        .stat-card .number {
            font-size: 28px;
            font-weight: bold;
            color: #1a1a2e;
        }
        
        .stat-card .label {
            color: #7f8c8d;
            font-size: 13px;
            margin-top: 5px;
        }
        
        .stat-card .sub-info {
            font-size: 12px;
            color: #95a5a6;
            margin-top: 3px;
        }
        
        .stat-card.kontrak { border-color: #3498db; }
        .stat-card.tetap { border-color: #2ecc71; }
        .stat-card.magang { border-color: #e67e22; }
        .stat-card.total { border-color: #9b59b6; }
        
        /* FILTER */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            background: white;
            color: #555;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .filter-btn:hover {
            border-color: #1a1a2e;
            color: #1a1a2e;
        }
        
        .filter-btn.active {
            background: #1a1a2e;
            color: white;
            border-color: #1a1a2e;
        }
        
        .filter-btn.active-kontrak { background: #3498db; border-color: #3498db; color: white; }
        .filter-btn.active-tetap { background: #2ecc71; border-color: #2ecc71; color: white; }
        .filter-btn.active-magang { background: #e67e22; border-color: #e67e22; color: white; }
        
        .btn-add {
            padding: 8px 20px;
            background: #1a1a2e;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-add:hover {
            background: #16213e;
            transform: translateY(-2px);
        }
        
        /* SLIP GAJI GRID */
        .slip-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .slip-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .slip-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
        }
        
        .slip-header {
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .slip-header.kontrak { background: linear-gradient(135deg, #3498db, #2980b9); }
        .slip-header.tetap { background: linear-gradient(135deg, #2ecc71, #27ae60); }
        .slip-header.magang { background: linear-gradient(135deg, #e67e22, #d35400); }
        
        .slip-header .type-badge {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .slip-header .slip-id {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .slip-body {
            padding: 20px;
        }
        
        .slip-body .karyawan-name {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 5px;
        }
        
        .slip-body .karyawan-dept {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .slip-body .divider {
            border: none;
            border-top: 2px dashed #ecf0f1;
            margin: 15px 0;
        }
        
        .slip-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 20px;
        }
        
        .slip-detail .item {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 4px 0;
        }
        
        .slip-detail .item .label {
            color: #7f8c8d;
        }
        
        .slip-detail .item .value {
            font-weight: 500;
            color: #1a1a2e;
        }
        
        .slip-detail .item .value.highlight {
            color: #e74c3c;
            font-weight: 600;
        }
        
        .slip-total {
            background: #f8f9fa;
            padding: 12px 20px;
            border-radius: 10px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .slip-total .label {
            font-weight: 600;
            color: #1a1a2e;
        }
        
        .slip-total .amount {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
        }
        
        .slip-total .amount.kontrak { color: #3498db; }
        .slip-total .amount.tetap { color: #2ecc71; }
        .slip-total .amount.magang { color: #e67e22; }
        
        .slip-footer {
            padding: 15px 20px;
            background: #fafafa;
            border-top: 1px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .slip-footer .detail-link {
            color: #3498db;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }
        
        .slip-footer .detail-link:hover {
            text-decoration: underline;
        }
        
        .slip-footer .status {
            font-size: 11px;
            color: #95a5a6;
        }
        
        /* DETAIL MODAL */
        .detail-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            display: <?= $detailData ? 'block' : 'none' ?>;
        }
        
        .detail-section .close-detail {
            float: right;
            color: #95a5a6;
            text-decoration: none;
            font-size: 20px;
            transition: color 0.3s;
        }
        
        .detail-section .close-detail:hover {
            color: #e74c3c;
        }
        
        .detail-section h2 {
            color: #1a1a2e;
            margin-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }
        
        /* FULL SLIP GAJI */
        .full-slip {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .full-slip .slip-header {
            padding: 25px 30px;
        }
        
        .full-slip .slip-body {
            padding: 30px;
        }
        
        .full-slip .slip-body .company-info {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .full-slip .slip-body .company-info h3 {
            color: #1a1a2e;
            font-size: 22px;
        }
        
        .full-slip .slip-body .company-info p {
            color: #7f8c8d;
            font-size: 13px;
        }
        
        .full-slip .slip-body .slip-title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }
        
        .full-slip .slip-body .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .full-slip .slip-body .info-table td {
            padding: 8px 12px;
            font-size: 14px;
        }
        
        .full-slip .slip-body .info-table .label {
            color: #7f8c8d;
            width: 40%;
        }
        
        .full-slip .slip-body .info-table .value {
            font-weight: 500;
            color: #1a1a2e;
        }
        
        .full-slip .slip-body .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .full-slip .slip-body .salary-table th {
            background: #f8f9fa;
            padding: 10px 15px;
            text-align: left;
            font-size: 13px;
            text-transform: uppercase;
            color: #7f8c8d;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .full-slip .slip-body .salary-table td {
            padding: 10px 15px;
            font-size: 14px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .full-slip .slip-body .salary-table .total-row td {
            font-weight: 700;
            font-size: 16px;
            border-top: 2px solid #1a1a2e;
            border-bottom: none;
        }
        
        .full-slip .slip-body .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
        }
        
        .full-slip .slip-body .signature .sign-box {
            text-align: center;
        }
        
        .full-slip .slip-body .signature .sign-box .line {
            width: 150px;
            border-top: 1px solid #1a1a2e;
            margin: 30px auto 5px;
        }
        
        .full-slip .slip-body .signature .sign-box .name {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .header-right {
                text-align: center;
                margin-top: 10px;
            }
            
            .slip-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-section {
                flex-direction: column;
            }
            
            .filter-buttons {
                justify-content: center;
            }
            
            .slip-detail {
                grid-template-columns: 1fr;
            }
            
            .full-slip .slip-body .signature {
                flex-direction: column;
                gap: 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media print {
            .filter-section, .stats-grid, .header-right .badge, .slip-footer .detail-link {
                display: none !important;
            }
            .slip-card {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .detail-section {
                display: block !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <div class="header-content">
                <div class="header-left">
                    <h1>🏢 SLIP GAJI KARYAWAN</h1>
                    <div class="subtitle">PT. KRISTIANA SETYANINGSIH - Sistem Penggajian Terintegrasi</div>
                </div>
                <div class="header-right">
                    <div class="badge">📋 UAS PBO - TRPL1B</div>
                    <div class="date">Periode: <?= date('F Y') ?></div>
                </div>
            </div>
        </div>
        
        <!-- STATISTIK -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="number"><?= $total ?></div>
                <div class="label">Total Karyawan</div>
            </div>
            <?php 
            // Reset pointer hasil query statistik
            $statsResult->data_seek(0);
            while($stat = $statsResult->fetch_assoc()): 
            ?>
            <div class="stat-card <?= $stat['jenis_karyawan'] ?>">
                <div class="number"><?= $stat['jumlah'] ?></div>
                <div class="label"><?= ucfirst($stat['jenis_karyawan']) ?></div>
                <div class="sub-info">Rata-rata: Rp <?= number_format($stat['rata_gaji'], 0, ',', '.') ?>/hari</div>
            </div>
            <?php endwhile; ?>
        </div>
        
        <!-- FILTER -->
        <div class="filter-section">
            <div class="filter-buttons">
                <a href="?filter=semua" class="filter-btn <?= $filter == 'semua' ? 'active' : '' ?>">📊 Semua</a>
                <a href="?filter=kontrak" class="filter-btn <?= $filter == 'kontrak' ? 'active-kontrak' : '' ?>">📄 Kontrak</a>
                <a href="?filter=tetap" class="filter-btn <?= $filter == 'tetap' ? 'active-tetap' : '' ?>">📄 Tetap</a>
                <a href="?filter=magang" class="filter-btn <?= $filter == 'magang' ? 'active-magang' : '' ?>">📄 Magang</a>
            </div>
            <div>
                <span style="font-size:13px; color:#7f8c8d; margin-right:10px;">
                    <?= $result->num_rows ?> karyawan
                </span>
                <a href="#" onclick="window.print()" class="btn-add">🖨️ Cetak</a>
            </div>
        </div>
        
        <!-- DETAIL SLIP GAJI (Jika ada yang dipilih) -->
        <?php if ($detailData && $detailObject): ?>
        <div class="detail-section" id="detailSection">
            <a href="?filter=<?= $filter ?>" class="close-detail">✕</a>
            <h2>📋 DETAIL SLIP GAJI</h2>
            
            <div class="full-slip">
                <div class="slip-header <?= $detailData['jenis_karyawan'] ?>">
                    <div>
                        <strong style="font-size:18px;">SLIP GAJI</strong>
                        <div style="font-size:12px; opacity:0.8;">No. <?= str_pad($detailData['id_karyawan'], 4, '0', STR_PAD_LEFT) ?>/SG/<?= date('Y') ?></div>
                    </div>
                    <div class="type-badge"><?= strtoupper($detailData['jenis_karyawan']) ?></div>
                </div>
                
                <div class="slip-body">
                    <div class="company-info">
                        <h3>PT. KRISTIANA SETYANINGSIH</h3>
                        <p>Jl. Pendidikan No. 123, Yogyakarta | Telp: (0274) 123456</p>
                        <p style="font-size:12px; color:#95a5a6;">Slip Gaji Periode: <?= date('F Y') ?></p>
                    </div>
                    
                    <div class="slip-title">SLIP GAJI KARYAWAN</div>
                    
                    <table class="info-table">
                        <tr>
                            <td class="label">ID Karyawan</td>
                            <td class="value">: <?= $detailData['id_karyawan'] ?></td>
                            <td class="label">Jenis Karyawan</td>
                            <td class="value">: <?= ucfirst($detailData['jenis_karyawan']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Nama Karyawan</td>
                            <td class="value">: <?= $detailData['nama_karyawan'] ?></td>
                            <td class="label">Departemen</td>
                            <td class="value">: <?= $detailData['departemen'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Tanggal Masuk</td>
                            <td class="value">: <?= date('d F Y', strtotime($detailData['hari_kerja_masuk'])) ?></td>
                            <td class="label">Masa Kerja</td>
                            <td class="value">: <?= $detailObject->hitungMasaKerja() ?> hari</td>
                        </tr>
                        <tr>
                            <td class="label">Gaji Dasar / Hari</td>
                            <td class="value">: Rp <?= number_format($detailData['gaji_dasar_per_hari'], 0, ',', '.') ?></td>
                            <td class="label">Total Hari Kerja</td>
                            <td class="value">: <?= $detailObject->hitungMasaKerja() ?> hari</td>
                        </tr>
                        <?php if ($detailData['jenis_karyawan'] == 'kontrak'): ?>
                        <tr>
                            <td class="label">Durasi Kontrak</td>
                            <td class="value" colspan="3">: <?= $detailData['durasi_kontrak_bulan'] ?> bulan</td>
                        </tr>
                        <tr>
                            <td class="label">Agensi Penyalur</td>
                            <td class="value" colspan="3">: <?= $detailData['agensi_penyalur'] ?></td>
                        </tr>
                        <?php elseif ($detailData['jenis_karyawan'] == 'tetap'): ?>
                        <tr>
                            <td class="label">Tunjangan Kesehatan</td>
                            <td class="value" colspan="3">: Rp <?= number_format($detailData['tunjangan_kesehatan'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td class="label">Opsi Saham ID</td>
                            <td class="value" colspan="3">: <?= $detailData['opsi_saham_id'] ?></td>
                        </tr>
                        <?php elseif ($detailData['jenis_karyawan'] == 'magang'): ?>
                        <tr>
                            <td class="label">Uang Saku Bulanan</td>
                            <td class="value" colspan="3">: Rp <?= number_format($detailData['uang_saku_bulanan'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td class="label">Sertifikat Kampus Merdeka</td>
                            <td class="value" colspan="3">: <?= $detailData['sertifikat_kampus_merdeka'] ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    
                    <h4 style="margin:20px 0 10px; color:#1a1a2e;">Rincian Gaji</h4>
                    <table class="salary-table">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th style="text-align:right;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Gaji Pokok (<?= $detailObject->hitungMasaKerja() ?> hari × Rp <?= number_format($detailData['gaji_dasar_per_hari'], 0, ',', '.') ?>)</td>
                                <td style="text-align:right;">Rp <?= number_format($detailObject->hitungMasaKerja() * $detailData['gaji_dasar_per_hari'], 0, ',', '.') ?></td>
                            </tr>
                            <?php if ($detailData['jenis_karyawan'] == 'tetap'): ?>
                            <tr>
                                <td>Tunjangan Kesehatan</td>
                                <td style="text-align:right;">Rp <?= number_format($detailData['tunjangan_kesehatan'], 0, ',', '.') ?></td>
                            </tr>
                            <?php elseif ($detailData['jenis_karyawan'] == 'magang'): ?>
                            <tr>
                                <td>Potongan Orientasi & Pelatihan (20%)</td>
                                <td style="text-align:right; color:#e74c3c;">- Rp <?= number_format(($detailObject->hitungMasaKerja() * $detailData['gaji_dasar_per_hari']) * 0.20, 0, ',', '.') ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr class="total-row">
                                <td><strong>Total Gaji Bersih</strong></td>
                                <td style="text-align:right; font-size:20px; color:<?= 
                                    $detailData['jenis_karyawan'] == 'kontrak' ? '#3498db' : 
                                    ($detailData['jenis_karyawan'] == 'tetap' ? '#2ecc71' : '#e67e22') 
                                ?>;">
                                    Rp <?= number_format($detailObject->hitungGajiBersih(), 0, ',', '.') ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div style="margin:20px 0; padding:15px; background:#f8f9fa; border-radius:8px; border-left:4px solid <?= 
                        $detailData['jenis_karyawan'] == 'kontrak' ? '#3498db' : 
                        ($detailData['jenis_karyawan'] == 'tetap' ? '#2ecc71' : '#e67e22') 
                    ?>;">
                        <strong style="color:#1a1a2e;">📝 Keterangan:</strong>
                        <?php if ($detailData['jenis_karyawan'] == 'kontrak'): ?>
                        <p style="margin-top:5px; font-size:13px; color:#555;">Gaji dihitung berdasarkan jumlah hari kerja (sistem penggajian murni berdasarkan kehadiran).</p>
                        <?php elseif ($detailData['jenis_karyawan'] == 'tetap'): ?>
                        <p style="margin-top:5px; font-size:13px; color:#555;">Gaji pokok ditambah tunjangan kesehatan sebagai karyawan tetap.</p>
                        <?php elseif ($detailData['jenis_karyawan'] == 'magang'): ?>
                        <p style="margin-top:5px; font-size:13px; color:#555;">Gaji dipotong 20% untuk biaya program orientasi, pelatihan, dan asuransi kerja intern.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="signature">
                        <div class="sign-box">
                            <div class="line"></div>
                            <div class="name">Hormat Kami,</div>
                            <div style="font-weight:600; margin-top:5px;">HRD Manager</div>
                        </div>
                        <div class="sign-box">
                            <div class="line"></div>
                            <div class="name">Mengetahui,</div>
                            <div style="font-weight:600; margin-top:5px;"><?= $detailData['nama_karyawan'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- DAFTAR SLIP GAJI -->
        <div class="slip-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): 
                    $obj = createKaryawanObject($row);
                    if (!$obj) continue;
                    $gajiBersih = $obj->hitungGajiBersih();
                    $masaKerja = $obj->hitungMasaKerja();
                ?>
                <div class="slip-card">
                    <div class="slip-header <?= $row['jenis_karyawan'] ?>">
                        <div>
                            <div style="font-weight:600;"><?= $row['nama_karyawan'] ?></div>
                            <div class="slip-id">ID: <?= str_pad($row['id_karyawan'], 4, '0', STR_PAD_LEFT) ?></div>
                        </div>
                        <div class="type-badge"><?= strtoupper($row['jenis_karyawan']) ?></div>
                    </div>
                    
                    <div class="slip-body">
                        <div class="karyawan-name"><?= $row['nama_karyawan'] ?></div>
                        <div class="karyawan-dept"><?= $row['departemen'] ?> • <?= $masaKerja ?> hari kerja</div>
                        
                        <hr class="divider">
                        
                        <div class="slip-detail">
                            <div class="item">
                                <span class="label">Gaji Dasar</span>
                                <span class="value">Rp <?= number_format($row['gaji_dasar_per_hari'], 0, ',', '.') ?>/hari</span>
                            </div>
                            <div class="item">
                                <span class="label">Total Hari</span>
                                <span class="value"><?= $masaKerja ?> hari</span>
                            </div>
                            <?php if ($row['jenis_karyawan'] == 'kontrak'): ?>
                            <div class="item">
                                <span class="label">Durasi Kontrak</span>
                                <span class="value"><?= $row['durasi_kontrak_bulan'] ?> bulan</span>
                            </div>
                            <div class="item">
                                <span class="label">Agensi</span>
                                <span class="value" style="font-size:11px;"><?= $row['agensi_penyalur'] ?></span>
                            </div>
                            <?php elseif ($row['jenis_karyawan'] == 'tetap'): ?>
                            <div class="item">
                                <span class="label">Tunjangan Kes.</span>
                                <span class="value">Rp <?= number_format($row['tunjangan_kesehatan'], 0, ',', '.') ?></span>
                            </div>
                            <div class="item">
                                <span class="label">Opsi Saham</span>
                                <span class="value" style="font-size:11px;"><?= $row['opsi_saham_id'] ?></span>
                            </div>
                            <?php elseif ($row['jenis_karyawan'] == 'magang'): ?>
                            <div class="item">
                                <span class="label">Uang Saku</span>
                                <span class="value">Rp <?= number_format($row['uang_saku_bulanan'], 0, ',', '.') ?></span>
                            </div>
                            <div class="item">
                                <span class="label">Sertifikat</span>
                                <span class="value" style="font-size:10px;"><?= $row['sertifikat_kampus_merdeka'] ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <hr class="divider">
                        
                        <div class="slip-total">
                            <span class="label">💵 Gaji Bersih</span>
                            <span class="amount <?= $row['jenis_karyawan'] ?>">
                                Rp <?= number_format($gajiBersih, 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="slip-footer">
                        <a href="?filter=<?= $filter ?>&detail=<?= $row['id_karyawan'] ?>" class="detail-link">
                            📄 Lihat Detail
                        </a>
                        <span class="status">Slip Gaji <?= date('M Y') ?></span>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align:center; padding:50px; background:white; border-radius:15px;">
                    <div style="font-size:50px; margin-bottom:15px;">📭</div>
                    <h3 style="color:#1a1a2e;">Belum Ada Data Karyawan</h3>
                    <p style="color:#7f8c8d;">Silakan tambahkan data karyawan terlebih dahulu.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- FOOTER -->
        <div style="text-align:center; padding:20px; color:#7f8c8d; font-size:13px; border-top:1px solid #e0e0e0; background:white; border-radius:12px;">
            <p>© 2026 - UAS PBO | TRPL1B | KRISTIANA SETYANINGSIH</p>
            <p style="font-size:11px; margin-top:5px;">Sistem Slip Gaji Berbasis OOP - Implementasi Abstract Class, Inheritance, & Polymorphism</p>
        </div>
    </div>
    
    <?php
    // =============================================
    // TUTUP KONEKSI DATABASE DI PALING AKHIR
    // =============================================
    $db->closeConnection();
    ?>
    
</body>
</html>