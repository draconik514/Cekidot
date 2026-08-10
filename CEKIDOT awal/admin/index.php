<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

include '../config/database.php';

$total_baru = $pdo->query("SELECT COUNT(*) FROM surat_masuk WHERE status='baru'")->fetchColumn();

$admin_nama = $_SESSION['admin_nama'] ?? 'Admin';

$total_surat = $pdo->query("SELECT COUNT(*) FROM surat_masuk")->fetchColumn();
$total_surat_baru = $pdo->query("SELECT COUNT(*) FROM surat_masuk WHERE status='baru'")->fetchColumn();
$total_slider = $pdo->query("SELECT COUNT(*) FROM slider")->fetchColumn();
$total_akip = $pdo->query("SELECT COUNT(*) FROM dokumen_akip")->fetchColumn();
$total_iki = $pdo->query("SELECT COUNT(*) FROM dokumen_iki")->fetchColumn();

$total_monev_bulanan = 0;
$total_monev_akumulasi = 0;

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'monev_bulanan'");
    if ($stmt->rowCount() > 0) {
        $total_monev_bulanan = $pdo->query("SELECT COUNT(*) FROM monev_bulanan")->fetchColumn();
    }
} catch (PDOException $e) {}

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'monev_akumulasi'");
    if ($stmt->rowCount() > 0) {
        $total_monev_akumulasi = $pdo->query("SELECT COUNT(*) FROM monev_akumulasi")->fetchColumn();
    }
} catch (PDOException $e) {}

$surat_terbaru = $pdo->query("SELECT * FROM surat_masuk ORDER BY id DESC LIMIT 5")->fetchAll();

$aktivitas = [];

$stmt = $pdo->query("SELECT 'surat' as type, id, CONCAT('Surat baru dari ', asal_instansi) as deskripsi, tanggal_masuk as waktu FROM surat_masuk ORDER BY id DESC LIMIT 3");
$aktivitas = array_merge($aktivitas, $stmt->fetchAll());

$stmt = $pdo->query("SELECT 'akip' as type, id, CONCAT('Dokumen AKIP: ', judul) as deskripsi, created_at as waktu FROM dokumen_akip ORDER BY id DESC LIMIT 2");
$aktivitas = array_merge($aktivitas, $stmt->fetchAll());

$stmt = $pdo->query("SELECT 'iki' as type, id, CONCAT('Dokumen IKI: ', judul) as deskripsi, created_at as waktu FROM dokumen_iki ORDER BY id DESC LIMIT 2");
$aktivitas = array_merge($aktivitas, $stmt->fetchAll());

$stmt = $pdo->query("SELECT 'slider' as type, id, CONCAT('Slide: ', judul) as deskripsi, created_at as waktu FROM slider ORDER BY id DESC LIMIT 2");
$aktivitas = array_merge($aktivitas, $stmt->fetchAll());

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'monev_bulanan'");
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->query("SELECT 'monev' as type, id, CONCAT('Data Monev: ', sub_kegiatan) as deskripsi, created_at as waktu FROM monev_bulanan ORDER BY id DESC LIMIT 2");
        $aktivitas = array_merge($aktivitas, $stmt->fetchAll());
    }
} catch (PDOException $e) {}

usort($aktivitas, function($a, $b) {
    return strtotime($b['waktu']) - strtotime($a['waktu']);
});
$aktivitas = array_slice($aktivitas, 0, 10);

$icon_colors = [
    'surat' => '#3b82f6',
    'akip' => '#8b5cf6',
    'iki' => '#06b6d4',
    'slider' => '#f59e0b',
    'monev' => '#10b981'
];

$icon_icons = [
    'surat' => 'fa-envelope',
    'akip' => 'fa-file-pdf',
    'iki' => 'fa-file-alt',
    'slider' => 'fa-image',
    'monev' => 'fa-chart-pie'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CEKIDOT</title>
    <link rel="icon" href="admin/img/logo-sulteng.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            color: #0f172a;
        }

        .sidebar {
            width: 240px;
            background: #0f3b5e;
            color: rgba(255,255,255,0.85);
            min-height: 100vh;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255,255,255,0.04);
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 20px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 16px;
            text-align: center;
        }
        .sidebar-brand .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.08);
            background: #ffffff;
            padding: 4px;
        }
        .sidebar-brand .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .sidebar-brand .brand-text {
            text-align: left;
        }
        .sidebar-brand .brand-text h2 {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }
        .sidebar-brand .brand-text h2 span { color: #eab308; }
        .sidebar-brand .brand-text small {
            font-size: 9px;
            opacity: 0.5;
            letter-spacing: 1.2px;
            display: block;
            text-transform: uppercase;
            margin-top: -1px;
        }

        .sidebar-nav { flex: 1; padding: 0 12px 12px; }
        .nav-list { list-style: none; }
        .nav-list li { margin-bottom: 2px; }
        .nav-list li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 14px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 500;
            text-align: left;
            white-space: nowrap;
        }
        .nav-list li a i {
            width: 18px;
            text-align: center;
            font-size: 15px;
            flex-shrink: 0;
            color: rgba(255,255,255,0.4);
        }
        .nav-list li a span { 
            flex: 1; 
            text-align: left; 
            white-space: nowrap;
        }
        .nav-list li a:hover {
            background: rgba(255,255,255,0.06);
            color: #ffffff;
        }
        .nav-list li a:hover i { color: #eab308; }
        .nav-list li a.active {
            background: rgba(234,179,8,0.10);
            color: #ffffff;
            border: 1px solid rgba(234,179,8,0.08);
        }
        .nav-list li a.active i { color: #eab308; }

        .nav-list li a .badge {
            background: #dc2626;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 10px;
            min-width: 16px;
            text-align: center;
            line-height: 1.5;
            flex-shrink: 0;
            margin-left: 0;
        }
        .nav-list li a .badge.zero {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.4);
            font-size: 8px;
            padding: 1px 6px;
            min-width: 14px;
        }
        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 10px 14px;
        }
        .nav-logout a {
            color: rgba(255,255,255,0.5) !important;
            border: 1px solid rgba(220,38,38,0.06);
        }
        .nav-logout a:hover {
            background: rgba(220,38,38,0.12) !important;
            color: #fca5a5 !important;
        }
        .nav-logout a i { color: rgba(220,38,38,0.5) !important; }

        .sidebar-footer {
            padding: 14px 20px 18px;
            border-top: 1px solid rgba(255,255,255,0.05);
            margin-top: auto;
        }
        .sidebar-footer .datetime {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            font-size: 10px;
            color: rgba(255,255,255,0.4);
        }
        .sidebar-footer .datetime .date,
        .sidebar-footer .datetime .time {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .sidebar-footer .datetime i { font-size: 10px; color: rgba(255,255,255,0.25); }
        .sidebar-footer .datetime .time {
            font-weight: 500;
            color: rgba(255,255,255,0.6);
        }
        .sidebar-footer .datetime .time i { color: #eab308; }
        .sidebar-footer .footer-version {
            text-align: center;
            margin-top: 8px;
            font-size: 8px;
            color: rgba(255,255,255,0.15);
        }

        .main-content {
            flex: 1;
            padding: 28px 32px;
            background: #f1f5f9;
            min-height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .header-left h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .header-left h1 i { color: #eab308; margin-right: 8px; }
        .header-left .greeting {
            font-size: 14px;
            color: #64748b;
            margin-top: 2px;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #fff;
            padding: 6px 16px 6px 12px;
            border-radius: 40px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border: 1px solid #e8ecf1;
        }
        .header-right .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #0f3b5e;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }
        .header-right .name {
            font-weight: 600;
            font-size: 14px;
            color: #0f172a;
        }
        .header-right .role {
            font-size: 11px;
            color: #94a3b8;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: #fff;
            padding: 18px 20px;
            border-radius: 14px;
            border: 1px solid #e8ecf1;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .stat-icon.blue { background: #dbeafe; color: #1d4ed8; }
        .stat-icon.red { background: #fef2f2; color: #dc2626; }
        .stat-icon.orange { background: #fef3c7; color: #b45309; }
        .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
        .stat-icon.cyan { background: #cffafe; color: #0891b2; }
        .stat-icon.green { background: #d1fae5; color: #065f46; }

        .stat-info .number {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        .stat-info .number .new-badge {
            font-size: 12px;
            font-weight: 600;
            color: #dc2626;
            background: #fef2f2;
            padding: 0 10px;
            border-radius: 12px;
            margin-left: 4px;
        }
        .stat-info .label {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 2px;
        }
        .stat-info .trend {
            font-size: 11px;
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 1px 10px;
            border-radius: 12px;
            background: #f1f5f9;
            color: #64748b;
        }
        .stat-info .trend.up { background: #d1fae5; color: #065f46; }
        .stat-info .trend.down { background: #fef2f2; color: #991b1b; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e8ecf1;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            overflow: hidden;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
        }
        .card-header h3 {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-header h3 i { color: #eab308; }
        .card-header .count {
            background: #f1f5f9;
            color: #64748b;
            font-size: 11px;
            padding: 2px 10px;
            border-radius: 20px;
        }
        .card-body { padding: 16px 20px; }
        .card-body .empty {
            text-align: center;
            padding: 32px 0;
            color: #94a3b8;
        }
        .card-body .empty i { font-size: 32px; opacity: 0.3; display: block; margin-bottom: 8px; }

        /* Surat item */
        .surat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .surat-item:last-child { border-bottom: none; }
        .surat-item .icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .surat-item .icon.baru { background: #fef2f2; color: #dc2626; }
        .surat-item .icon.sudah { background: #f1f5f9; color: #94a3b8; }
        .surat-item .info { flex: 1; min-width: 0; }
        .surat-item .info .judul {
            font-weight: 600;
            font-size: 14px;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .surat-item .info .meta {
            font-size: 12px;
            color: #94a3b8;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .surat-item .info .meta i { width: 14px; }
        .surat-item .status {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 20px;
            flex-shrink: 0;
        }
        .surat-item .status.baru { background: #fef2f2; color: #dc2626; }
        .surat-item .status.sudah { background: #f1f5f9; color: #94a3b8; }

        .view-all {
            display: block;
            text-align: center;
            padding: 10px 0 0;
            margin-top: 8px;
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
            color: #0f3b5e;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: 0.2s;
        }
        .view-all:hover { color: #eab308; }

        /* Aktivitas */
        .aktivitas-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .aktivitas-item:last-child { border-bottom: none; }
        .aktivitas-item .icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #fff;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .aktivitas-item .text {
            flex: 1;
            font-size: 13px;
            color: #1e293b;
        }
        .aktivitas-item .text strong { color: #0f3b5e; }
        .aktivitas-item .time {
            font-size: 11px;
            color: #94a3b8;
            flex-shrink: 0;
            margin-top: 2px;
        }

        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); }
            .dashboard-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 992px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar {
                width: 100%;
                min-height: auto;
                height: auto;
                position: relative;
                flex-direction: row;
                flex-wrap: wrap;
                padding: 8px 12px;
                border-right: none;
                border-bottom: 1px solid rgba(255,255,255,0.06);
            }
            .sidebar-brand {
                padding: 4px 0;
                border-bottom: none;
                margin-bottom: 0;
                flex: 1;
                gap: 10px;
            }
            .sidebar-brand .brand-logo { width: 34px; height: 34px; padding: 3px; }
            .sidebar-brand .brand-text h2 { font-size: 15px; }
            .sidebar-brand .brand-text small { font-size: 7px; }
            .sidebar-nav { flex: none; width: 100%; padding: 6px 0 4px; }
            .nav-list { display: flex; flex-wrap: wrap; gap: 2px; }
            .nav-list li { margin-bottom: 0; }
            .nav-list li a { padding: 5px 10px; font-size: 12px; border-radius: 6px; }
            .nav-list li a i { font-size: 13px; width: 16px; }
            .nav-divider { display: none; }
            .nav-logout a { border: none !important; }
            .sidebar-footer { display: none; }

            .main-content { padding: 16px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .header { flex-direction: column; align-items: flex-start; }
            .header-right { width: 100%; justify-content: space-between; }
        }
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .sidebar-brand .brand-logo { width: 28px; height: 28px; padding: 2px; }
            .sidebar-brand .brand-text h2 { font-size: 13px; }
            .nav-list li a { padding: 4px 8px; font-size: 11px; }
            .nav-list li a i { font-size: 12px; }
            .header-left h1 { font-size: 20px; }
            .main-content { padding: 12px; }
            .surat-item { flex-wrap: wrap; }
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">

    <div class="header">
        <div class="header-left">
            <h1><i class="fas fa-home"></i> Dashboard</h1>
            <div class="greeting">
                Selamat datang kembali, <strong><?= htmlspecialchars($admin_nama) ?></strong> 
                <span style="color:#94a3b8; margin-left:6px;">• <?= date('d F Y') ?></span>
            </div>
        </div>
        <div class="header-right">
            <div class="avatar"><?= strtoupper(substr($admin_nama, 0, 1)) ?></div>
            <div>
                <div class="name"><?= htmlspecialchars($admin_nama) ?></div>
                <div class="role">Administrator</div>
            </div>
        </div>
    </div>

    <!-- STATISTIK - 6 KOLOM (TAMBAH MONEV) -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-inbox"></i></div>
            <div class="stat-info">
                <div class="number"><?= $total_surat ?></div>
                <div class="label">Total Surat</div>
                <div class="trend up"><i class="fas fa-arrow-up"></i> <?= $total_surat > 0 ? 'Aktif' : 'Belum ada' ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <div class="number">
                    <?= $total_surat_baru ?>
                    <?php if ($total_surat_baru > 0): ?>
                    <span class="new-badge">Baru!</span>
                    <?php endif; ?>
                </div>
                <div class="label">Belum Dibaca</div>
                <div class="trend <?= $total_surat_baru > 0 ? 'up' : '' ?>">
                    <?= $total_surat_baru > 0 ? 'Perlu perhatian' : 'Semua terbaca ✅' ?>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-images"></i></div>
            <div class="stat-info">
                <div class="number"><?= $total_slider ?>/6</div>
                <div class="label">Slide Aktif</div>
                <div class="trend"><?= $total_slider >= 6 ? 'Penuh' : (6 - $total_slider) . ' slot tersisa' ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-clipboard-check"></i></div>
            <div class="stat-info">
                <div class="number"><?= $total_akip ?></div>
                <div class="label">Dokumen AKIP</div>
                <div class="trend"><?= $total_akip > 0 ? 'Tersedia' : 'Belum ada' ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon cyan"><i class="fas fa-user-check"></i></div>
            <div class="stat-info">
                <div class="number"><?= $total_iki ?></div>
                <div class="label">Dokumen IKI</div>
                <div class="trend"><?= $total_iki > 0 ? 'Tersedia' : 'Belum ada' ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-chart-pie"></i></div>
            <div class="stat-info">
                <div class="number"><?= $total_monev_bulanan + $total_monev_akumulasi ?></div>
                <div class="label">Data Monev</div>
                <div class="trend up">
                    <i class="fas fa-arrow-up"></i> 
                    <?= $total_monev_bulanan > 0 ? $total_monev_bulanan . ' bulanan' : '' ?>
                    <?= $total_monev_akumulasi > 0 ? ($total_monev_bulanan > 0 ? ' & ' : '') . $total_monev_akumulasi . ' akumulasi' : '' ?>
                    <?= ($total_monev_bulanan + $total_monev_akumulasi) == 0 ? 'Belum ada' : '' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- DASHBOARD GRID -->
    <div class="dashboard-grid">

        <!-- SURAT TERBARU -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-envelope"></i> Surat Masuk Terbaru</h3>
                <span class="count"><?= count($surat_terbaru) ?></span>
            </div>
            <div class="card-body">
                <?php if (empty($surat_terbaru)): ?>
                <div class="empty">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada surat masuk</p>
                </div>
                <?php else: ?>
                <?php foreach($surat_terbaru as $s): ?>
                <div class="surat-item">
                    <div class="icon <?= $s['status'] == 'baru' ? 'baru' : 'sudah' ?>">
                        <i class="fas <?= $s['status'] == 'baru' ? 'fa-circle' : 'fa-envelope' ?>"></i>
                    </div>
                    <div class="info">
                        <div class="judul"><?= htmlspecialchars($s['perihal'] ?? 'Tanpa Judul') ?></div>
                        <div class="meta">
                            <span><i class="fas fa-building"></i> <?= htmlspecialchars($s['asal_instansi'] ?? '-') ?></span>
                            <span><i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($s['tanggal_masuk'])) ?></span>
                        </div>
                    </div>
                    <span class="status <?= $s['status'] == 'baru' ? 'baru' : 'sudah' ?>">
                        <?= $s['status'] == 'baru' ? 'Baru' : 'Dibaca' ?>
                    </span>
                </div>
                <?php endforeach; ?>
                <a href="surat-masuk.php" class="view-all">
                    Lihat Semua Surat <i class="fas fa-arrow-right"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- AKTIVITAS TERBARU -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-bolt"></i> Aktivitas Terbaru</h3>
                <span class="count"><?= count($aktivitas) ?></span>
            </div>
            <div class="card-body">
                <?php if (empty($aktivitas)): ?>
                <div class="empty">
                    <i class="fas fa-clock"></i>
                    <p>Belum ada aktivitas</p>
                </div>
                <?php else: ?>
                <?php foreach($aktivitas as $act): ?>
                <div class="aktivitas-item">
                    <div class="icon" style="background: <?= $icon_colors[$act['type']] ?? '#94a3b8' ?>;">
                        <i class="fas <?= $icon_icons[$act['type']] ?? 'fa-file' ?>"></i>
                    </div>
                    <div class="text"><?= htmlspecialchars($act['deskripsi']) ?></div>
                    <div class="time">
                        <?php 
                        $waktu = strtotime($act['waktu']);
                        $diff = time() - $waktu;
                        if ($diff < 60) echo 'Baru saja';
                        elseif ($diff < 3600) echo floor($diff/60) . ' menit lalu';
                        elseif ($diff < 86400) echo floor($diff/3600) . ' jam lalu';
                        else echo date('d/m/Y', $waktu);
                        ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</main>

<script>
function updateSidebarClock() {
    var now = new Date();
    var hours = String(now.getHours()).padStart(2, '0');
    var minutes = String(now.getMinutes()).padStart(2, '0');
    var seconds = String(now.getSeconds()).padStart(2, '0');
    var clockEl = document.getElementById('sidebarClock');
    if (clockEl) clockEl.textContent = hours + ':' + minutes + ':' + seconds;
}
updateSidebarClock();
setInterval(updateSidebarClock, 1000);
</script>

</body>
</html>