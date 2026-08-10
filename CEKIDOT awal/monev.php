<?php
// monev.php - Halaman Monitoring & Evaluasi untuk Tamu/Guest
session_start();

if (!isset($_SESSION['access_granted']) || $_SESSION['access_granted'] !== true) {
    header('Location: index.php');
    exit;
}

include 'config/database.php';
include 'includes/header.php';

function getPredikat($capaian) {
    if ($capaian === null || $capaian === '') {
        return ['label' => 'BELUM ADA', 'class' => 'predikat-belum'];
    }
    $capaian = (float) str_replace(',', '.', str_replace('.', '', $capaian));
    if ($capaian > 100) {
        return ['label' => 'ISTIMEWA', 'class' => 'predikat-istimewa'];
    } elseif ($capaian >= 80) {
        return ['label' => 'BAIK', 'class' => 'predikat-baik'];
    } elseif ($capaian >= 60) {
        return ['label' => 'BUTUH PERBAIKAN', 'class' => 'predikat-butuh'];
    } elseif ($capaian >= 20) {
        return ['label' => 'KURANG', 'class' => 'predikat-kurang'];
    } elseif ($capaian > 0) {
        return ['label' => 'SANGAT KURANG', 'class' => 'predikat-sangat'];
    } else {
        return ['label' => 'BELUM ADA', 'class' => 'predikat-belum'];
    }
}

$tahun_list = ['2025', '2026', '2027', '2028', '2029', '2030'];
$tahun_aktif = isset($_GET['tahun']) ? $_GET['tahun'] : '2025';
if (!in_array($tahun_aktif, $tahun_list)) {
    $tahun_aktif = $tahun_list[0];
}

$bulan_list = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$bulan_singkat = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
$bulan_aktif = isset($_GET['bulan']) ? $_GET['bulan'] : 'Januari';
if (!in_array($bulan_aktif, $bulan_list)) {
    $bulan_aktif = $bulan_list[0];
}

$tab_aktif = isset($_GET['tab']) ? $_GET['tab'] : 'bulanan';

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'monev_bulanan'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("CREATE TABLE `monev_bulanan` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `tahun` varchar(4) NOT NULL,
            `bulan` varchar(20) NOT NULL,
            `sub_kegiatan` text NOT NULL,
            `indikator` text NOT NULL,
            `target_ik` decimal(15,2) DEFAULT 0,
            `target_keu` decimal(15,2) DEFAULT 0,
            `realisasi_ik` decimal(15,2) DEFAULT 0,
            `realisasi_keu` decimal(15,2) DEFAULT 0,
            `capaian_ik` decimal(10,2) DEFAULT 0,
            `capaian_keu` decimal(10,2) DEFAULT 0,
            `sumber_data` text,
            `faktor_penghambat` text,
            `faktor_pendukung` text,
            `created_at` datetime DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `tahun` (`tahun`,`bulan`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'monev_akumulasi'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("CREATE TABLE `monev_akumulasi` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `tahun` varchar(4) NOT NULL,
            `sub_kegiatan` text NOT NULL,
            `indikator` text NOT NULL,
            `target_ik` decimal(15,2) DEFAULT 0,
            `target_keu` decimal(15,2) DEFAULT 0,
            `realisasi_ik` decimal(15,2) DEFAULT 0,
            `realisasi_keu` decimal(15,2) DEFAULT 0,
            `capaian_ik` decimal(10,2) DEFAULT 0,
            `capaian_keu` decimal(10,2) DEFAULT 0,
            `predikat_ik` varchar(50) DEFAULT NULL,
            `predikat_keu` varchar(50) DEFAULT NULL,
            `status` varchar(50) DEFAULT NULL,
            `created_at` datetime DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `tahun` (`tahun`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM monev_akumulasi LIKE 'status'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE monev_akumulasi ADD COLUMN `status` varchar(50) DEFAULT NULL AFTER `predikat_keu`");
    }
} catch (PDOException $e) {
    // Tabel sudah ada atau error
}

$data_bulanan = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM monev_bulanan WHERE tahun = ? AND bulan = ? ORDER BY id");
    $stmt->execute([$tahun_aktif, $bulan_aktif]);
    $data_bulanan = $stmt->fetchAll();
} catch (PDOException $e) {
    // Tabel belum ada
}

$data_akumulasi = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM monev_akumulasi WHERE tahun = ? ORDER BY id");
    $stmt->execute([$tahun_aktif]);
    $data_akumulasi = $stmt->fetchAll();
} catch (PDOException $e) {
    // Tabel belum ada
}

$total_bulanan = count($data_bulanan);
$total_akumulasi = count($data_akumulasi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monev - CEKIDOT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .monev-page {
            background-image: url('assets/img/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            padding: 0;
        }

        .monev-hero {
            background: linear-gradient(135deg, rgba(15, 59, 94, 0.92) 0%, rgba(26, 90, 122, 0.88) 100%);
            padding: 45px 0 35px;
            color: #fff;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-bottom: 3px solid rgba(234, 179, 8, 0.3);
        }
        .monev-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -15%;
            width: 500px;
            height: 500px;
            background: rgba(234, 179, 8, 0.06);
            border-radius: 50%;
            pointer-events: none;
        }
        .monev-hero::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 50%;
            pointer-events: none;
        }
        .monev-hero .container {
            position: relative;
            z-index: 1;
        }
        .monev-hero .hero-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .monev-hero .hero-text {
            flex: 1;
            min-width: 280px;
        }
        .monev-hero .hero-text h1 {
            font-size: 36px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .monev-hero .hero-text h1 i {
            color: #eab308;
            filter: drop-shadow(0 2px 8px rgba(234, 179, 8, 0.3));
        }
        .monev-hero .hero-text p {
            font-size: 16px;
            opacity: 0.85;
            max-width: 550px;
            line-height: 1.7;
            font-weight: 300;
        }

        .monev-content {
            padding: 40px 0 60px;
            min-height: 60vh;
        }
        .monev-content .content-wrapper {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 32px 36px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .filter-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            background: #f1f5f9;
            border-radius: 14px;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            justify-content: center;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        .filter-wrapper .filter-group {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .filter-wrapper .filter-group .btn-filter {
            padding: 4px 14px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            background: transparent;
            color: #64748b;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }
        .filter-wrapper .filter-group .btn-filter:hover {
            background: #e2e8f0;
            color: #0f3b5e;
        }
        .filter-wrapper .filter-group .btn-filter.active {
            background: #0f3b5e;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(15,59,94,0.2);
        }

        .tab-nav {
            display: flex;
            gap: 0;
            margin-bottom: 24px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .tab-nav .tab-btn {
            padding: 10px 28px;
            border: none;
            background: transparent;
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            text-decoration: none;
            border-bottom: 3px solid transparent;
        }
        .tab-nav .tab-btn:hover {
            background: #f8fafc;
            color: #0f3b5e;
        }
        .tab-nav .tab-btn.active {
            color: #0f3b5e;
            background: #f8fafc;
            border-bottom-color: #0f3b5e;
        }
        .tab-nav .tab-btn i { font-size: 15px; margin-right: 6px; }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .table-wrapper .table-header {
            padding: 12px 20px;
            background: #fafbfc;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .table-wrapper .table-header .table-title {
            font-weight: 700;
            color: #0f3b5e;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .table-wrapper .table-header .table-title i { color: #eab308; }
        .table-wrapper .table-header .table-badge {
            font-size: 11px;
            color: #16a34a;
            background: #d1fae5;
            padding: 3px 14px;
            border-radius: 20px;
            font-weight: 500;
        }
        .table-wrapper .table-header .table-badge i {
            margin-right: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 1200px;
        }
        table th {
            text-align: center;
            padding: 10px 10px;
            background: #f8fafc;
            font-weight: 700;
            color: #0f3b5e;
            border-bottom: 2px solid #e2e8f0;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        table th:first-child { 
            text-align: center; 
            width: 40px;
            min-width: 40px;
            max-width: 40px;
        }
        table th:nth-child(2) { text-align: left; min-width: 140px; }
        table th:nth-child(3) { text-align: left; min-width: 140px; }
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        table tr:hover td {
            background: #f8fafc;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        table .text-center { text-align: center; }
        table .text-right { text-align: right; }
        table .text-left { text-align: left; }

        table .capaian-value {
            font-weight: 700;
        }
        table .capaian-value.istimewa { color: #1d4ed8; }
        table .capaian-value.baik { color: #16a34a; }
        table .capaian-value.butuh-perbaikan { color: #92400e; }
        table .capaian-value.kurang { color: #9a3412; }
        table .capaian-value.sangat-kurang { color: #dc2626; }
        table .capaian-value.belum-ada { color: #94a3b8; }

        .predikat-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 11px;
            text-align: center;
            min-width: 80px;
            letter-spacing: 0.3px;
        }
        .predikat-istimewa {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #93c5fd;
        }
        .predikat-baik {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .predikat-butuh {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        .predikat-kurang {
            background: #ffedd5;
            color: #9a3412;
            border: 1px solid #fdba74;
        }
        .predikat-sangat {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .predikat-belum {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            min-width: 80px;
            letter-spacing: 0.3px;
        }
        .status-badge.efisien {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .status-badge.tidak-efisien {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .sumber-link {
            color: #0f3b5e;
            text-decoration: none;
            font-size: 11px;
            word-break: break-all;
        }
        .sumber-link:hover {
            color: #eab308;
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }
        .empty-state i {
            font-size: 56px;
            opacity: 0.2;
            display: block;
            margin-bottom: 16px;
            color: #0f3b5e;
        }
        .empty-state h3 {
            font-size: 20px;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .empty-state p {
            font-size: 14px;
        }

        .legend-wrapper {
            margin-top: 16px;
            padding: 14px 20px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e8ecf1;
        }
        .legend-wrapper .legend-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 16px;
            align-items: center;
            padding: 4px 0;
        }
        .legend-wrapper .legend-row:first-child {
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 6px;
        }
        .legend-wrapper .legend-row .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #475569;
        }
        .legend-wrapper .legend-row .legend-item .predikat-badge,
        .legend-wrapper .legend-row .legend-item .status-badge {
            font-size: 10px;
            padding: 2px 10px;
            min-width: auto;
        }
        .legend-wrapper .legend-label {
            font-size: 11px;
            font-weight: 600;
            color: #0f3b5e;
            margin-right: 4px;
        }

        @media (max-width: 992px) {
            .monev-hero .hero-content {
                flex-direction: column;
                align-items: flex-start;
            }
            .monev-content .content-wrapper {
                padding: 24px 20px;
            }
            .filter-wrapper .filter-group .btn-filter {
                padding: 4px 10px;
                font-size: 11px;
            }
            table { min-width: 1000px; font-size: 11px; }
            .predikat-badge { font-size: 10px; min-width: 70px; padding: 2px 10px; }
            .status-badge { font-size: 10px; min-width: 70px; padding: 2px 10px; }
            .legend-wrapper .legend-row { gap: 6px 12px; }
        }

        @media (max-width: 768px) {
            .monev-hero {
                padding: 30px 0 24px;
            }
            .monev-hero .hero-text h1 {
                font-size: 26px;
            }
            .monev-hero .hero-text p {
                font-size: 14px;
            }
            
            .monev-content .content-wrapper {
                padding: 16px 12px;
            }
            
            .filter-wrapper {
                padding: 8px 10px;
                gap: 6px;
            }
            .filter-wrapper .filter-group .btn-filter {
                padding: 3px 8px;
                font-size: 10px;
            }
            
            .tab-nav .tab-btn {
                padding: 8px 16px;
                font-size: 13px;
            }
            
            table { font-size: 10px; min-width: 800px; }
            table th, table td { padding: 6px 8px; }
            table th { font-size: 10px; }
            table th:first-child { width: 30px; min-width: 30px; max-width: 30px; }
            .predikat-badge { font-size: 9px; min-width: 60px; padding: 2px 8px; }
            .status-badge { font-size: 9px; min-width: 60px; padding: 2px 8px; }
            
            .legend-wrapper { padding: 12px 14px; }
            .legend-wrapper .legend-row { gap: 4px 8px; }
            .legend-wrapper .legend-row .legend-item { font-size: 11px; }
            .legend-wrapper .legend-row .legend-item .predikat-badge,
            .legend-wrapper .legend-row .legend-item .status-badge { font-size: 9px; padding: 1px 8px; }
        }

        @media (max-width: 480px) {
            .monev-hero .hero-text h1 {
                font-size: 22px;
            }
            
            .filter-wrapper .filter-group .btn-filter {
                padding: 2px 6px;
                font-size: 9px;
            }
            
            .tab-nav .tab-btn {
                padding: 6px 12px;
                font-size: 12px;
            }
            .tab-nav .tab-btn i { font-size: 13px; margin-right: 4px; }
            
            table { font-size: 9px; min-width: 650px; }
            table th, table td { padding: 4px 6px; }
            table th { font-size: 9px; }
            table th:first-child { width: 25px; min-width: 25px; max-width: 25px; }
            .predikat-badge { font-size: 8px; min-width: 50px; padding: 1px 6px; }
            .status-badge { font-size: 8px; min-width: 50px; padding: 1px 6px; }
            .table-wrapper .table-header { flex-direction: column; align-items: stretch; text-align: center; }
            .table-wrapper .table-header .table-badge { text-align: center; }
            
            .legend-wrapper { padding: 10px 12px; }
            .legend-wrapper .legend-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .legend-wrapper .legend-row .legend-item { font-size: 10px; }
            .legend-wrapper .legend-row:first-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        }
    </style>
</head>
<body>

<section class="monev-page">
    <section class="monev-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>
                        <i class="fas fa-chart-pie"></i> 
                        Monitoring & Evaluasi
                    </h1>
                    <p>
                        Monitoring dan evaluasi capaian kinerja program Dinas Pariwisata 
                        Provinsi Sulawesi Tengah. Mengukur efektivitas pelaksanaan program 
                        dan pencapaian target yang telah ditetapkan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="monev-content">
        <div class="container">
            <div class="content-wrapper">

                <div class="filter-wrapper">
                    <div class="filter-group">
                        <?php foreach($tahun_list as $t): ?>
                        <a href="?tahun=<?= urlencode($t) ?>&bulan=<?= urlencode($bulan_aktif) ?>&tab=<?= urlencode($tab_aktif) ?>" class="btn-filter <?= $tahun_aktif == $t ? 'active' : '' ?>">
                            <?= $t ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if ($tab_aktif == 'bulanan'): ?>
                    <div class="filter-group">
                        <?php 
                        $bulan_index = 0;
                        foreach($bulan_list as $b): 
                        ?>
                        <a href="?tahun=<?= urlencode($tahun_aktif) ?>&bulan=<?= urlencode($b) ?>&tab=<?= urlencode($tab_aktif) ?>" class="btn-filter <?= $bulan_aktif == $b ? 'active' : '' ?>">
                            <?= $bulan_singkat[$bulan_index] ?>
                        </a>
                        <?php 
                            $bulan_index++;
                        endforeach; 
                        ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="tab-nav">
                    <a href="?tahun=<?= urlencode($tahun_aktif) ?>&bulan=<?= urlencode($bulan_aktif) ?>&tab=bulanan" class="tab-btn <?= $tab_aktif == 'bulanan' ? 'active' : '' ?>">
                        <i class="fas fa-list"></i> Data Bulanan
                    </a>
                    <a href="?tahun=<?= urlencode($tahun_aktif) ?>&bulan=<?= urlencode($bulan_aktif) ?>&tab=akumulasi" class="tab-btn <?= $tab_aktif == 'akumulasi' ? 'active' : '' ?>">
                        <i class="fas fa-calculator"></i> Akumulasi
                    </a>
                </div>

                <div class="tab-content <?= $tab_aktif == 'bulanan' ? 'active' : '' ?>">
                    <div class="table-wrapper">
                        <div class="table-header">
                            <div class="table-title">
                                <i class="fas fa-list"></i> <?= $bulan_aktif ?> <?= $tahun_aktif ?>
                            </div>
                            <span class="table-badge">
                                <i class="fas fa-database"></i> <?= $total_bulanan ?> data
                            </span>
                        </div>
                        <div class="table-scroll">
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th style="text-align:left;">Sub Kegiatan</th>
                                        <th style="text-align:left;">Indikator</th>
                                        <th>Target<br><small>IK</small></th>
                                        <th>Target<br><small>KEU</small></th>
                                        <th>Realisasi<br><small>IK</small></th>
                                        <th>Realisasi<br><small>KEU</small></th>
                                        <th>Capaian<br><small>IK</small></th>
                                        <th>Capaian<br><small>KEU</small></th>
                                        <th style="text-align:left;">Sumber Data</th>
                                        <th style="text-align:left;">Faktor Penghambat</th>
                                        <th style="text-align:left;">Faktor Pendukung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($data_bulanan)): ?>
                                    <tr>
                                        <td colspan="12">
                                            <div class="empty-state">
                                                <i class="fas fa-file-alt"></i>
                                                <h3>Belum Ada Data</h3>
                                                <p>Belum ada data untuk <?= $bulan_aktif ?> <?= $tahun_aktif ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php else: 
                                        $no = 1;
                                        foreach ($data_bulanan as $row):
                                            $pred_ik = getPredikat($row['capaian_ik']);
                                            $pred_keu = getPredikat($row['capaian_keu']);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-left"><?= htmlspecialchars($row['sub_kegiatan']) ?></td>
                                        <td class="text-left"><?= htmlspecialchars($row['indikator']) ?></td>
                                        <td class="text-right"><?= number_format($row['target_ik'], 0, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format($row['target_keu'], 0, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format($row['realisasi_ik'], 0, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format($row['realisasi_keu'], 0, ',', '.') ?></td>
                                        <td class="text-center"><span class="capaian-value <?= strtolower(str_replace(' ', '-', $pred_ik['label'])) ?>"><?= number_format($row['capaian_ik'], 2, ',', '.') ?>%</span></td>
                                        <td class="text-center"><span class="capaian-value <?= strtolower(str_replace(' ', '-', $pred_keu['label'])) ?>"><?= number_format($row['capaian_keu'], 2, ',', '.') ?>%</span></td>
                                        <td>
                                            <?php if (!empty($row['sumber_data'])): ?>
                                                <a href="<?= htmlspecialchars($row['sumber_data']) ?>" target="_blank" class="sumber-link">
                                                    <i class="fas fa-external-link-alt"></i> <?= htmlspecialchars(substr($row['sumber_data'], 0, 35)) ?><?= strlen($row['sumber_data']) > 35 ? '...' : '' ?>
                                                </a>
                                            <?php else: ?>
                                                <span style="color:#94a3b8; font-size:10px;">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-left"><?= htmlspecialchars($row['faktor_penghambat'] ?? '-') ?></td>
                                        <td class="text-left"><?= htmlspecialchars($row['faktor_pendukung'] ?? '-') ?></td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-content <?= $tab_aktif == 'akumulasi' ? 'active' : '' ?>">
                    <div class="table-wrapper">
                        <div class="table-header">
                            <div class="table-title">
                                <i class="fas fa-calculator"></i> Akumulasi Tahunan <?= $tahun_aktif ?>
                            </div>
                            <span class="table-badge">
                                <i class="fas fa-sync"></i> Otomatis diperbarui
                            </span>
                        </div>
                        <div class="table-scroll">
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th style="text-align:left;">Sub Kegiatan</th>
                                        <th style="text-align:left;">Indikator</th>
                                        <th>Target<br><small>IK</small></th>
                                        <th>Target<br><small>KEU</small></th>
                                        <th>Realisasi<br><small>IK</small></th>
                                        <th>Realisasi<br><small>KEU</small></th>
                                        <th>Capaian<br><small>IK</small></th>
                                        <th>Capaian<br><small>KEU</small></th>
                                        <th>Predikat<br><small>IK</small></th>
                                        <th>Predikat<br><small>KEU</small></th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($data_akumulasi)): ?>
                                    <tr>
                                        <td colspan="12">
                                            <div class="empty-state">
                                                <i class="fas fa-calculator"></i>
                                                <h3>Belum Ada Data Akumulasi</h3>
                                                <p>Belum ada data akumulasi untuk tahun <?= $tahun_aktif ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php else: 
                                        $no = 1;
                                        foreach ($data_akumulasi as $row):
                                            $pred_ik = getPredikat($row['capaian_ik']);
                                            $pred_keu = getPredikat($row['capaian_keu']);
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-left"><?= htmlspecialchars($row['sub_kegiatan']) ?></td>
                                        <td class="text-left"><?= htmlspecialchars($row['indikator']) ?></td>
                                        <td class="text-right"><?= number_format($row['target_ik'], 0, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format($row['target_keu'], 0, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format($row['realisasi_ik'], 0, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format($row['realisasi_keu'], 0, ',', '.') ?></td>
                                        <td class="text-center"><span class="capaian-value <?= strtolower(str_replace(' ', '-', $pred_ik['label'])) ?>"><?= number_format($row['capaian_ik'], 2, ',', '.') ?>%</span></td>
                                        <td class="text-center"><span class="capaian-value <?= strtolower(str_replace(' ', '-', $pred_keu['label'])) ?>"><?= number_format($row['capaian_keu'], 2, ',', '.') ?>%</span></td>
                                        <td class="text-center"><span class="predikat-badge <?= $pred_ik['class'] ?>"><?= $pred_ik['label'] ?></span></td>
                                        <td class="text-center"><span class="predikat-badge <?= $pred_keu['class'] ?>"><?= $pred_keu['label'] ?></span></td>
                                        <td class="text-center"><span class="status-badge <?= strtolower(str_replace(' ', '-', $row['status'])) ?>"><?= $row['status'] ?></span></td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php if (!empty($data_akumulasi)): ?>
                    <div class="legend-wrapper">
                        <div class="legend-row">
                            <span class="legend-label">Predikat</span>
                            <span class="legend-item">
                                <span class="predikat-badge predikat-istimewa">ISTIMEWA</span> &gt; 100%
                            </span>
                            <span class="legend-item">
                                <span class="predikat-badge predikat-baik">BAIK</span> 80-100%
                            </span>
                            <span class="legend-item">
                                <span class="predikat-badge predikat-butuh">BUTUH PERBAIKAN</span> 60-80%
                            </span>
                            <span class="legend-item">
                                <span class="predikat-badge predikat-kurang">KURANG</span> 20-60%
                            </span>
                            <span class="legend-item">
                                <span class="predikat-badge predikat-sangat">SANGAT KURANG</span> 0-20%
                            </span>
                        </div>
                        <div class="legend-row">
                            <span class="legend-label">Status</span>
                            <span class="legend-item">
                                <span class="status-badge efisien">EFISIEN</span> Capaian IK ≥ Capaian KEU
                            </span>
                            <span class="legend-item">
                                <span class="status-badge tidak-efisien">TIDAK EFISIEN</span> Capaian IK &lt; Capaian KEU
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.title = 'Monev Renaksi - CEKIDOT';
});
</script>

</body>
</html>