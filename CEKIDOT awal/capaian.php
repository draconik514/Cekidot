<?php
// capaian.php - Halaman Capaian Program untuk Tamu/Guest
session_start();
include 'config/database.php';
include 'includes/header.php';

// ===== FILTER TAHUN (2025 - 2030) =====
$tahun_aktif = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
$tahun_list = range(2025, 2030);

if (!in_array($tahun_aktif, $tahun_list)) {
    $tahun_aktif = $tahun_list[0];
}

// ===== CEK TABLE CAPAIAN PROGRAM =====
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'capaian_program'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `capaian_program` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `program` varchar(255) NOT NULL,
            `sasaran` varchar(255) NOT NULL,
            `indikator` varchar(255) NOT NULL,
            `target` decimal(20,6) DEFAULT 0,
            `realisasi` decimal(20,6) DEFAULT 0,
            `capaian` decimal(10,4) DEFAULT 0,
            `frekwensi` varchar(50) DEFAULT NULL,
            `sumber_data` varchar(500) DEFAULT NULL,
            `file_sumber` varchar(255) DEFAULT NULL,
            `penanggung_jawab` varchar(255) DEFAULT NULL,
            `tahun` varchar(4) NOT NULL,
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `tahun` (`tahun`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
} catch (PDOException $e) {
    // Table already exists or error
}

// ============================================================
// AMBIL DATA CAPAIAN PROGRAM PER TAHUN
// ============================================================
$stmt = $pdo->prepare("SELECT * FROM capaian_program WHERE tahun = ? ORDER BY id");
$stmt->execute([$tahun_aktif]);
$capaian_data = $stmt->fetchAll();

// ============================================================
// HITUNG STATISTIK
// ============================================================
$total_data = count($capaian_data);
$total_capaian = 0;
$rata_capaian = 0;

foreach ($capaian_data as $d) {
    $total_capaian += (float) $d['capaian'];
}
if ($total_data > 0) {
    $rata_capaian = $total_capaian / $total_data;
}

// ============================================================
// FUNGSI PREDIKAT
// ============================================================
function getPredikat($capaian) {
    if ($capaian > 100) {
        return ['label' => 'ISTIMEWA', 'class' => 'predikat-istimewa'];
    } elseif ($capaian > 80) {
        return ['label' => 'BAIK', 'class' => 'predikat-baik'];
    } elseif ($capaian > 60) {
        return ['label' => 'BUTUH PERBAIKAN', 'class' => 'predikat-butuh'];
    } elseif ($capaian > 20) {
        return ['label' => 'KURANG', 'class' => 'predikat-kurang'];
    } elseif ($capaian > 0) {
        return ['label' => 'SANGAT KURANG', 'class' => 'predikat-sangat'];
    } else {
        return ['label' => 'BELUM ADA', 'class' => 'predikat-belum'];
    }
}

// ============================================================
// FUNGSI FORMAT ANGKA FLEKSIBEL (HAPUS NOL DI BELAKANG)
// ============================================================
function formatAngkaFlexibel($angka) {
    // Format dengan 6 desimal
    $formatted = number_format($angka, 6, ',', '.');
    // Hapus nol di belakang koma
    $formatted = rtrim($formatted, '0');
    $formatted = rtrim($formatted, ',');
    return $formatted;
}
?>

<!-- ============================================================
   OVERRIDE TITLE - Capaian Program - CEKIDOT
   ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.title = 'Capaian Program - CEKIDOT';
});
</script>

<style>
    /* ============================================================
       HALAMAN CAPAIAN PROGRAM TAMU
       ============================================================ */
    
    /* ----- Background ----- */
    .capaian-page {
        background-image: url('assets/img/background.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        padding: 0;
    }

    /* ----- Hero Section ----- */
    .capaian-hero {
        background: linear-gradient(135deg, rgba(15, 59, 94, 0.92) 0%, rgba(26, 90, 122, 0.88) 100%);
        padding: 45px 0 35px;
        color: #fff;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border-bottom: 3px solid rgba(234, 179, 8, 0.3);
    }
    .capaian-hero::before {
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
    .capaian-hero::after {
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
    .capaian-hero .container {
        position: relative;
        z-index: 1;
    }
    .capaian-hero .hero-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .capaian-hero .hero-text {
        flex: 1;
        min-width: 280px;
    }
    .capaian-hero .hero-text h1 {
        font-size: 36px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .capaian-hero .hero-text h1 i {
        color: #eab308;
        filter: drop-shadow(0 2px 8px rgba(234, 179, 8, 0.3));
    }
    .capaian-hero .hero-text p {
        font-size: 16px;
        opacity: 0.85;
        max-width: 550px;
        line-height: 1.7;
        font-weight: 300;
    }
    
    /* Stats di sebelah kanan hero - HANYA 2 STAT */
    .capaian-hero .hero-stats {
        display: flex;
        gap: 24px;
        background: rgba(255,255,255,0.08);
        padding: 14px 28px;
        border-radius: 16px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    .capaian-hero .hero-stats .stat {
        text-align: center;
        padding: 0 6px;
    }
    .capaian-hero .hero-stats .stat .number {
        font-size: 28px;
        font-weight: 800;
        color: #eab308;
        display: block;
        line-height: 1.2;
    }
    .capaian-hero .hero-stats .stat .number.high {
        color: #4ade80;
    }
    .capaian-hero .hero-stats .stat .number.low {
        color: #f87171;
    }
    .capaian-hero .hero-stats .stat .label {
        font-size: 10px;
        opacity: 0.6;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 400;
    }
    .capaian-hero .hero-stats .stat-divider {
        width: 1px;
        background: rgba(255,255,255,0.1);
    }

    /* ----- Main Content ----- */
    .capaian-content {
        padding: 40px 0 60px;
        min-height: 60vh;
    }
    .capaian-content .content-wrapper {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 32px 36px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.08);
        border: 1px solid rgba(255,255,255,0.3);
    }

    /* ----- Filter Tahun - TANPA BADGE COUNT ----- */
    .filter-tahun {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 24px;
        background: #f1f5f9;
        border-radius: 14px;
        padding: 4px;
        border: 1px solid #e2e8f0;
        justify-content: center;
        flex-wrap: nowrap;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .filter-tahun .btn-tahun {
        width: 44px;
        height: 44px;
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 10px;
        margin: 2px;
    }
    .filter-tahun .btn-tahun:hover:not(:disabled) {
        background: #0f3b5e;
        color: #fff;
        transform: scale(1.05);
    }
    .filter-tahun .btn-tahun:disabled {
        opacity: 0.25;
        cursor: not-allowed;
    }
    .filter-tahun .tahun-items {
        display: flex;
        align-items: center;
        gap: 2px;
        flex: 1;
        justify-content: center;
        padding: 0 4px;
    }
    .filter-tahun .tahun-items .tahun-item {
        padding: 6px 16px;
        border: none;
        background: transparent;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'Inter', sans-serif;
        position: relative;
        min-width: 48px;
        text-align: center;
        text-decoration: none;
    }
    .filter-tahun .tahun-items .tahun-item:hover {
        color: #0f3b5e;
        background: rgba(15, 59, 94, 0.06);
    }
    .filter-tahun .tahun-items .tahun-item.active {
        background: #0f3b5e;
        color: #fff;
        box-shadow: 0 4px 12px rgba(15, 59, 94, 0.25);
        font-weight: 600;
    }
    .filter-tahun .tahun-range-label {
        font-size: 11px;
        color: #94a3b8;
        padding: 0 12px;
        font-weight: 400;
        letter-spacing: 0.5px;
        flex-shrink: 0;
        border-left: 1px solid #e2e8f0;
        margin-left: 4px;
        padding-left: 16px;
    }
    .filter-tahun .tahun-range-label i {
        margin-right: 4px;
        font-size: 10px;
    }

    /* ----- Table ----- */
    .table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
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
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    table th:first-child { text-align: left; min-width: 160px; }
    table th:nth-child(2) { min-width: 140px; }
    table th:nth-child(3) { min-width: 160px; }
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

    table .program-cell {
        font-weight: 600;
        color: #0f3b5e;
        font-size: 11px;
    }
    table .sasaran-cell {
        font-size: 11px;
        color: #1e293b;
    }
    table .indikator-cell {
        font-size: 11px;
        color: #1e293b;
    }
    table .text-center {
        text-align: center;
    }
    table .text-right {
        text-align: right;
    }
    table .text-left {
        text-align: left;
    }

    table .capaian-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 13px;
        min-width: 50px;
        text-align: center;
    }
    table .capaian-badge.high {
        background: #d1fae5;
        color: #16a34a;
    }
    table .capaian-badge.medium {
        background: #fef3c7;
        color: #eab308;
    }
    table .capaian-badge.low {
        background: #fef2f2;
        color: #dc2626;
    }
    table .capaian-badge.zero {
        background: #f1f5f9;
        color: #94a3b8;
    }

    /* PREDIKAT STYLES */
    .predikat-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 10px;
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

    table .frekwensi-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
        background: #f1f5f9;
        color: #475569;
    }
    table .sumber-cell {
        font-size: 11px;
        color: #64748b;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    table .sumber-cell i {
        color: #0f3b5e;
        margin-right: 4px;
    }
    table .sumber-cell a {
        color: #0f3b5e;
        text-decoration: none;
    }
    table .sumber-cell a:hover {
        color: #eab308;
        text-decoration: underline;
    }
    table .pj-cell {
        font-size: 11px;
        color: #64748b;
    }

    /* ----- Empty State ----- */
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

    /* ----- Responsive ----- */
    @media (max-width: 992px) {
        .capaian-hero .hero-content {
            flex-direction: column;
            align-items: flex-start;
        }
        .capaian-hero .hero-stats {
            width: 100%;
            justify-content: center;
        }
        .capaian-content .content-wrapper {
            padding: 24px 20px;
        }
        .filter-tahun .tahun-items .tahun-item {
            padding: 4px 10px;
            font-size: 12px;
            min-width: 36px;
        }
        .filter-tahun .tahun-range-label {
            font-size: 10px;
            padding: 0 8px;
            padding-left: 12px;
        }
        table { min-width: 1000px; font-size: 11px; }
        .predikat-badge { font-size: 9px; min-width: 60px; padding: 2px 8px; }
    }

    @media (max-width: 768px) {
        .capaian-hero {
            padding: 30px 0 24px;
        }
        .capaian-hero .hero-text h1 {
            font-size: 26px;
        }
        .capaian-hero .hero-text p {
            font-size: 14px;
        }
        .capaian-hero .hero-stats {
            padding: 10px 16px;
            gap: 16px;
        }
        .capaian-hero .hero-stats .stat .number {
            font-size: 20px;
        }
        .capaian-hero .hero-stats .stat .label {
            font-size: 9px;
        }
        .capaian-hero .hero-stats .stat-divider {
            display: none;
        }
        
        .capaian-content .content-wrapper {
            padding: 16px 12px;
        }
        
        .filter-tahun {
            flex-wrap: wrap;
            padding: 4px;
            gap: 2px;
        }
        .filter-tahun .btn-tahun {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
        .filter-tahun .tahun-items {
            flex-wrap: wrap;
            gap: 2px;
            padding: 2px;
        }
        .filter-tahun .tahun-items .tahun-item {
            padding: 4px 8px;
            font-size: 12px;
            min-width: 32px;
        }
        .filter-tahun .tahun-range-label {
            display: none;
        }
        
        table { font-size: 10px; min-width: 800px; }
        table th, table td { padding: 6px 8px; }
        table .capaian-badge { font-size: 11px; padding: 1px 8px; min-width: 40px; }
        .predikat-badge { font-size: 8px; min-width: 50px; padding: 2px 6px; }
    }

    @media (max-width: 480px) {
        .capaian-hero .hero-text h1 {
            font-size: 22px;
        }
        .capaian-hero .hero-stats {
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }
        .capaian-hero .hero-stats .stat {
            flex: 1;
            min-width: 60px;
        }
        .capaian-hero .hero-stats .stat .number {
            font-size: 18px;
        }
        
        .filter-tahun .btn-tahun {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }
        .filter-tahun .tahun-items .tahun-item {
            padding: 3px 6px;
            font-size: 11px;
            min-width: 28px;
        }
        
        table { font-size: 9px; min-width: 650px; }
        table th, table td { padding: 4px 6px; }
        .predikat-badge { font-size: 7px; min-width: 40px; padding: 1px 4px; }
    }
</style>

<!-- ============================================================
   HERO SECTION
   ============================================================ -->
<section class="capaian-page">
    <section class="capaian-hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>
                        <i class="fas fa-flag-checkered"></i> 
                        Capaian Program
                    </h1>
                    <p>
                        Laporan capaian kinerja program dan kegiatan Dinas Pariwisata 
                        Provinsi Sulawesi Tengah. Mengukur efektivitas pelaksanaan program 
                        dan pencapaian target yang telah ditetapkan.
                    </p>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="number"><?= $total_data ?></span>
                        <span class="label">Total Indikator</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <span class="number <?= $rata_capaian >= 80 ? 'high' : ($rata_capaian >= 50 ? '' : 'low') ?>">
                            <?= number_format($rata_capaian, 2, ',', '.') ?>%
                        </span>
                        <span class="label">Rata-rata Capaian</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
       MAIN CONTENT
       ============================================================ -->
    <section class="capaian-content">
        <div class="container">
            <div class="content-wrapper">

                <!-- Filter Tahun - TANPA BADGE COUNT -->
                <div class="filter-tahun">
                    <button class="btn-tahun" onclick="window.location.href='?tahun=<?= $tahun_aktif - 1 ?>'" <?= $tahun_aktif <= 2025 ? 'disabled' : '' ?>>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="tahun-items">
                        <?php foreach(range(2025, 2030) as $t): ?>
                        <a href="?tahun=<?= $t ?>" class="tahun-item <?= $t == $tahun_aktif ? 'active' : '' ?>">
                            <?= $t ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <span class="tahun-range-label">
                        <i class="fas fa-calendar-alt"></i> 2025 - 2030
                    </span>
                    
                    <button class="btn-tahun" onclick="window.location.href='?tahun=<?= $tahun_aktif + 1 ?>'" <?= $tahun_aktif >= 2030 ? 'disabled' : '' ?>>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Table -->
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th style="min-width:160px;">Program</th>
                                <th style="min-width:140px;">Sasaran</th>
                                <th style="min-width:160px;">Indikator</th>
                                <th style="width:80px;">Target</th>
                                <th style="width:80px;">Realisasi</th>
                                <th style="width:80px;">Capaian</th>
                                <th style="width:90px;">Predikat</th>
                                <th style="width:80px;">Frekwensi</th>
                                <th style="min-width:140px;">Sumber Data</th>
                                <th style="min-width:130px;">Penanggung Jawab</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($capaian_data)): ?>
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <i class="fas fa-file-alt"></i>
                                        <h3>Belum Ada Data</h3>
                                        <p>Belum ada data capaian program untuk tahun <?= $tahun_aktif ?></p>
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($capaian_data as $d): 
                                $capaian = (float)$d['capaian'];
                                $class = 'zero';
                                if ($capaian >= 80) $class = 'high';
                                elseif ($capaian >= 50) $class = 'medium';
                                elseif ($capaian > 0) $class = 'low';
                                
                                $predikat = getPredikat($capaian);
                                
                                // Format angka fleksibel (hapus nol di belakang)
                                $target_display = formatAngkaFlexibel($d['target']);
                                $realisasi_display = formatAngkaFlexibel($d['realisasi']);
                            ?>
                            <tr>
                                <td class="program-cell"><?= htmlspecialchars($d['program']) ?></td>
                                <td class="sasaran-cell"><?= htmlspecialchars($d['sasaran']) ?></td>
                                <td class="indikator-cell"><?= htmlspecialchars($d['indikator']) ?></td>
                                <td class="text-center"><?= $target_display ?></td>
                                <td class="text-center"><?= $realisasi_display ?></td>
                                <td class="text-center">
                                    <span class="capaian-badge <?= $class ?>">
                                        <?= number_format($capaian, 2, ',', '.') ?>%
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="predikat-badge <?= $predikat['class'] ?>">
                                        <?= $predikat['label'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="frekwensi-badge"><?= htmlspecialchars($d['frekwensi']) ?></span>
                                </td>
                                <td class="sumber-cell">
                                    <?php if (!empty($d['sumber_data'])): ?>
                                        <?php if (filter_var($d['sumber_data'], FILTER_VALIDATE_URL)): ?>
                                            <a href="<?= htmlspecialchars($d['sumber_data']) ?>" target="_blank" title="<?= htmlspecialchars($d['sumber_data']) ?>">
                                                <i class="fas fa-external-link-alt"></i> Link
                                            </a>
                                        <?php else: ?>
                                            <i class="fas fa-info-circle"></i> <?= htmlspecialchars(substr($d['sumber_data'], 0, 30)) . (strlen($d['sumber_data']) > 30 ? '...' : '') ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="color:#94a3b8;">-</span>
                                    <?php endif; ?>
                                    <?php if (!empty($d['file_sumber'])): ?>
                                    <span style="font-size:10px; color:#16a34a; margin-left:4px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="pj-cell"><?= htmlspecialchars($d['penanggung_jawab']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Keterangan Predikat -->
                <?php if (!empty($capaian_data)): ?>
                <div style="margin-top:12px; padding:10px 16px; background:#f8fafc; border-radius:8px; border:1px solid #e8ecf1; display:flex; flex-wrap:wrap; gap:8px 16px; align-items:center; justify-content:center; font-size:11px; color:#64748b;">
                    <span><i class="fas fa-tag"></i> Keterangan Predikat:</span>
                    <span><span class="predikat-badge predikat-istimewa" style="font-size:9px; padding:1px 8px; min-width:auto;">ISTIMEWA</span> &gt; 100%</span>
                    <span><span class="predikat-badge predikat-baik" style="font-size:9px; padding:1px 8px; min-width:auto;">BAIK</span> 80-100%</span>
                    <span><span class="predikat-badge predikat-butuh" style="font-size:9px; padding:1px 8px; min-width:auto;">BUTUH PERBAIKAN</span> 60-80%</span>
                    <span><span class="predikat-badge predikat-kurang" style="font-size:9px; padding:1px 8px; min-width:auto;">KURANG</span> 20-60%</span>
                    <span><span class="predikat-badge predikat-sangat" style="font-size:9px; padding:1px 8px; min-width:auto;">SANGAT KURANG</span> 0-20%</span>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </section>
</section>

<?php include 'includes/footer.php'; ?>