<?php
// admin/monev.php - Monitoring dan Evaluasi
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

include '../config/database.php';

function getPredikat($capaian) {
    if ($capaian === null || $capaian === '') {
        return ['label' => 'BELUM ADA', 'class' => 'belum-ada'];
    }
    $capaian = (float) str_replace(',', '.', str_replace('.', '', $capaian));
    if ($capaian > 100) {
        return ['label' => 'ISTIMEWA', 'class' => 'istimewa'];
    } elseif ($capaian >= 80) {
        return ['label' => 'BAIK', 'class' => 'baik'];
    } elseif ($capaian >= 60) {
        return ['label' => 'BUTUH PERBAIKAN', 'class' => 'butuh-perbaikan'];
    } elseif ($capaian >= 20) {
        return ['label' => 'KURANG', 'class' => 'kurang'];
    } elseif ($capaian > 0) {
        return ['label' => 'SANGAT KURANG', 'class' => 'sangat-kurang'];
    } else {
        return ['label' => 'BELUM ADA', 'class' => 'belum-ada'];
    }
}

// ============================================================
// TOTAL SURAT BARU
// ============================================================
$total_baru = $pdo->query("SELECT COUNT(*) FROM surat_masuk WHERE status='baru'")->fetchColumn();

// ============================================================
// TAHUN
// ============================================================
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

$success = '';
$error = '';

// ============================================================
// FUNGSI UPDATE AKUMULASI OTOMATIS - PER BARIS (TIDAK DIGABUNG)
// ============================================================
function updateAkumulasiOtomatis($pdo, $tahun_aktif) {
    try {
        $stmt = $pdo->prepare("DELETE FROM monev_akumulasi WHERE tahun = ?");
        $stmt->execute([$tahun_aktif]);
        
        $stmt = $pdo->prepare("SELECT * FROM monev_bulanan WHERE tahun = ?");
        $stmt->execute([$tahun_aktif]);
        $data_bulanan_all = $stmt->fetchAll();
        
        foreach ($data_bulanan_all as $row) {
            $capaian_ik = 0;
            if ($row['target_ik'] > 0) {
                $capaian_ik = ($row['realisasi_ik'] / $row['target_ik']) * 100;
            }
            
            $capaian_keu = 0;
            if ($row['target_keu'] > 0) {
                $capaian_keu = ($row['realisasi_keu'] / $row['target_keu']) * 100;
            }
            
            $predikat_ik = getPredikat($capaian_ik)['label'];
            $predikat_keu = getPredikat($capaian_keu)['label'];
            
            $status = 'Tidak Efisien';
            if ($capaian_ik >= $capaian_keu) {
                $status = 'Efisien';
            }
            
            $stmt = $pdo->prepare("INSERT INTO monev_akumulasi 
                (tahun, sub_kegiatan, indikator, target_ik, target_keu, realisasi_ik, realisasi_keu, capaian_ik, capaian_keu, predikat_ik, predikat_keu, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $tahun_aktif,
                $row['sub_kegiatan'],
                $row['indikator'],
                $row['target_ik'],
                $row['target_keu'],
                $row['realisasi_ik'],
                $row['realisasi_keu'],
                $capaian_ik,
                $capaian_keu,
                $predikat_ik,
                $predikat_keu,
                $status
            ]);
        }
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// ============================================================
// CEK DAN BUAT TABEL JIKA BELUM ADA
// ============================================================
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

// ============================================================
// AMBIL DATA BULANAN
// ============================================================
$data_bulanan = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM monev_bulanan WHERE tahun = ? AND bulan = ? ORDER BY id");
    $stmt->execute([$tahun_aktif, $bulan_aktif]);
    $data_bulanan = $stmt->fetchAll();
} catch (PDOException $e) {
    // Tabel belum ada
}

// ============================================================
// AMBIL DATA AKUMULASI
// ============================================================
$data_akumulasi = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM monev_akumulasi WHERE tahun = ? ORDER BY id");
    $stmt->execute([$tahun_aktif]);
    $data_akumulasi = $stmt->fetchAll();
} catch (PDOException $e) {
    // Tabel belum ada
}

// ============================================================
// PROSES SAVE - DELETE + INSERT + AKUMULASI OTOMATIS
// ============================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // ============================================================
    // SAVE BULANAN + UPDATE AKUMULASI OTOMATIS
    // ============================================================
    if (isset($_POST['save_bulanan']) && isset($_POST['data'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM monev_bulanan WHERE tahun = ? AND bulan = ?");
            $stmt->execute([$tahun_aktif, $bulan_aktif]);
            
            $inserted = 0;
            
            foreach ($_POST['data'] as $id => $row) {
                $target_ik = (float) str_replace('.', '', str_replace(',', '.', trim($row['target_ik'] ?? '0')));
                $target_keu = (float) str_replace('.', '', str_replace(',', '.', trim($row['target_keu'] ?? '0')));
                $realisasi_ik = (float) str_replace('.', '', str_replace(',', '.', trim($row['realisasi_ik'] ?? '0')));
                $realisasi_keu = (float) str_replace('.', '', str_replace(',', '.', trim($row['realisasi_keu'] ?? '0')));
                
                $sub_kegiatan = trim($row['sub_kegiatan'] ?? '');
                $indikator = trim($row['indikator'] ?? '');
                
                if ($target_ik == 0 && $target_keu == 0 && $realisasi_ik == 0 && $realisasi_keu == 0 && empty($sub_kegiatan) && empty($indikator)) {
                    continue;
                }
                
                if (empty($sub_kegiatan)) {
                    $sub_kegiatan = '-';
                }
                if (empty($indikator)) {
                    $indikator = '-';
                }
                
                $capaian_ik = 0;
                if ($target_ik > 0) {
                    $capaian_ik = ($realisasi_ik / $target_ik) * 100;
                }
                
                $capaian_keu = 0;
                if ($target_keu > 0) {
                    $capaian_keu = ($realisasi_keu / $target_keu) * 100;
                }
                
                $sumber_data = trim($row['sumber_data'] ?? '');
                $faktor_penghambat = trim($row['faktor_penghambat'] ?? '');
                $faktor_pendukung = trim($row['faktor_pendukung'] ?? '');
                
                $stmt = $pdo->prepare("INSERT INTO monev_bulanan 
                    (tahun, bulan, sub_kegiatan, indikator, target_ik, target_keu, realisasi_ik, realisasi_keu, capaian_ik, capaian_keu, sumber_data, faktor_penghambat, faktor_pendukung) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $stmt->execute([
                    $tahun_aktif,
                    $bulan_aktif,
                    $sub_kegiatan,
                    $indikator,
                    $target_ik,
                    $target_keu,
                    $realisasi_ik,
                    $realisasi_keu,
                    $capaian_ik,
                    $capaian_keu,
                    $sumber_data,
                    $faktor_penghambat,
                    $faktor_pendukung
                ]);
                $inserted++;
            }
            
            updateAkumulasiOtomatis($pdo, $tahun_aktif);
            
            $stmt = $pdo->prepare("SELECT * FROM monev_akumulasi WHERE tahun = ? ORDER BY id");
            $stmt->execute([$tahun_aktif]);
            $data_akumulasi = $stmt->fetchAll();
            
            $success = 'Data berhasil disimpan! (' . $inserted . ' data) - Akumulasi otomatis diperbarui';
            header("Location: ?tahun=" . urlencode($tahun_aktif) . "&bulan=" . urlencode($bulan_aktif) . "&tab=" . urlencode($tab_aktif) . "&success=1");
            exit;
            
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
    
    // ============================================================
    // HAPUS DATA
    // ============================================================
    if (isset($_POST['delete_id'])) {
        $id = (int) $_POST['delete_id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM monev_bulanan WHERE id = ? AND tahun = ? AND bulan = ?");
            $stmt->execute([$id, $tahun_aktif, $bulan_aktif]);
            
            updateAkumulasiOtomatis($pdo, $tahun_aktif);
            
            $success = 'Data berhasil dihapus! - Akumulasi otomatis diperbarui';
            header("Location: ?tahun=" . urlencode($tahun_aktif) . "&bulan=" . urlencode($bulan_aktif) . "&tab=" . urlencode($tab_aktif) . "&success=1");
            exit;
        } catch (PDOException $e) {
            $error = 'Gagal menghapus: ' . $e->getMessage();
        }
    }
}

// ============================================================
// REFRESH DATA SETELAH REDIRECT
// ============================================================
if (isset($_GET['success']) && $_GET['success'] == 1) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM monev_bulanan WHERE tahun = ? AND bulan = ? ORDER BY id");
        $stmt->execute([$tahun_aktif, $bulan_aktif]);
        $data_bulanan = $stmt->fetchAll();
    } catch (PDOException $e) {}
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM monev_akumulasi WHERE tahun = ? ORDER BY id");
        $stmt->execute([$tahun_aktif]);
        $data_akumulasi = $stmt->fetchAll();
    } catch (PDOException $e) {}
}

$admin_nama = $_SESSION['admin_nama'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monev - CEKIDOT</title>
    <link rel="icon" href="assets/img/logo-sulteng.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
        }
        
        /* ============================================================
           SIDEBAR
           ============================================================ */
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
            z-index: 1000;
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
            flex-shrink: 0;
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
        .sidebar-brand .brand-text { text-align: left; }
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

        .sidebar-nav { flex: 1; padding: 0 12px 12px; overflow-y: auto; }
        .nav-list { list-style: none; padding: 0; margin: 0; }
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
        .nav-list li a span { flex: 1; text-align: left; white-space: nowrap; }
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
            flex-shrink: 0;
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
        
        /* ============================================================
           MAIN CONTENT
           ============================================================ */
        .main-content {
            flex: 1;
            padding: 24px;
            background-image: url('../assets/img/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            overflow-x: auto;
        }
        .main-content .overlay {
            background: rgba(255,255,255,0.92);
            padding: 24px 30px;
            border-radius: 16px;
            min-height: calc(100vh - 48px);
            backdrop-filter: blur(4px);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .header h1 {
            font-size: 24px;
            color: #0f3b5e;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header h1 i { color: #eab308; }
        .header .info {
            color: #64748b;
            font-size: 14px;
        }
        .header .admin-welcome {
            font-size: 14px;
            color: #64748b;
        }
        .header .admin-welcome i { color: #eab308; margin-right: 4px; }
        
        .alert {
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-success i { color: #16a34a; }
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .alert-danger i { color: #dc2626; }
        
        /* ============================================================
           FILTER
           ============================================================ */
        .filter-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            padding: 12px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
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
        }
        .filter-wrapper .filter-group .btn-filter:hover {
            background: #f1f5f9;
            color: #0f3b5e;
        }
        .filter-wrapper .filter-group .btn-filter.active {
            background: #0f3b5e;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(15,59,94,0.2);
        }
        
        /* ============================================================
           TAB NAVIGATION
           ============================================================ */
        .tab-nav {
            display: flex;
            gap: 0;
            margin-bottom: 20px;
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
            font-family: inherit;
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
        
        /* ============================================================
           TABLE BULANAN
           ============================================================ */
        .table-section {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .table-section .table-header {
            padding: 14px 20px;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .table-section .table-header .table-title {
            font-weight: 700;
            color: #0f3b5e;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .table-section .table-header .table-title i { color: #eab308; }
        
        .table-scroll {
            overflow-x: auto;
            padding: 0 4px;
        }
        .table-scroll table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 1400px;
        }
        .table-scroll table th {
            text-align: center;
            padding: 8px 6px;
            background: #fafbfc;
            font-weight: 600;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .table-scroll table th:first-child { text-align: left; }
        .table-scroll table td {
            padding: 6px 4px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .table-scroll table tr:last-child td { border-bottom: none; }
        .table-scroll table .text-center { text-align: center; }
        .table-scroll table .text-right { text-align: right; }
        .table-scroll table .text-left { text-align: left; }
        
        .table-scroll table .form-input {
            width: 100%;
            padding: 4px 6px;
            border: 1.5px solid #e2e8f0;
            border-radius: 4px;
            font-size: 12px;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.3s;
            text-align: right;
        }
        .table-scroll table .form-input:focus {
            outline: none;
            border-color: #0f3b5e;
            box-shadow: 0 0 0 3px rgba(15,59,94,0.06);
        }
        .table-scroll table .form-input.text-left { text-align: left; }
        .table-scroll table .form-input.sumber-input { 
            text-align: left; 
            min-width: 120px;
        }
        
        /* ===== WARNA CAPAIAN - HANYA WARNA TEKS ===== */
        .table-scroll table .capaian-value {
            font-weight: 700;
        }
        .table-scroll table .capaian-value.istimewa { color: #1d4ed8; }
        .table-scroll table .capaian-value.baik { color: #16a34a; }
        .table-scroll table .capaian-value.butuh-perbaikan { color: #92400e; }
        .table-scroll table .capaian-value.kurang { color: #9a3412; }
        .table-scroll table .capaian-value.sangat-kurang { color: #dc2626; }
        .table-scroll table .capaian-value.belum-ada { color: #94a3b8; }
        
        .table-scroll table .btn-delete-row {
            background: none;
            border: none;
            color: #991b1b;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 4px;
            transition: 0.3s;
            font-size: 14px;
        }
        .table-scroll table .btn-delete-row:hover {
            background: #fef2f2;
            color: #dc2626;
        }
        
        /* ============================================================
           AKUMULASI TABLE
           ============================================================ */
        .akumulasi-section {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .akumulasi-section .akumulasi-header {
            padding: 14px 20px;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .akumulasi-section .akumulasi-header .akumulasi-title {
            font-weight: 700;
            color: #0f3b5e;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .akumulasi-section .akumulasi-header .akumulasi-title i { color: #eab308; }
        .akumulasi-section .akumulasi-header .akumulasi-auto {
            font-size: 12px;
            color: #16a34a;
            background: #d1fae5;
            padding: 4px 14px;
            border-radius: 20px;
            font-weight: 500;
        }
        .akumulasi-section .akumulasi-header .akumulasi-auto i {
            margin-right: 4px;
        }
        
        .akumulasi-section table {
            font-size: 12px;
            min-width: 1200px;
        }
        .akumulasi-section table th {
            text-align: center;
            padding: 8px 6px;
            background: #fafbfc;
            font-weight: 600;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .akumulasi-section table td {
            padding: 6px 4px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .akumulasi-section table .text-center { text-align: center; }
        .akumulasi-section table .text-right { text-align: right; }
        
        /* ===== WARNA CAPAIAN AKUMULASI - HANYA WARNA TEKS ===== */
        .akumulasi-section .capaian-value {
            font-weight: 700;
        }
        .akumulasi-section .capaian-value.istimewa { color: #1d4ed8; }
        .akumulasi-section .capaian-value.baik { color: #16a34a; }
        .akumulasi-section .capaian-value.butuh-perbaikan { color: #92400e; }
        .akumulasi-section .capaian-value.kurang { color: #9a3412; }
        .akumulasi-section .capaian-value.sangat-kurang { color: #dc2626; }
        .akumulasi-section .capaian-value.belum-ada { color: #94a3b8; }
        
        .akumulasi-section .predikat-badge {
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
            white-space: nowrap;
        }
        .akumulasi-section .predikat-badge.istimewa { background: #dbeafe; color: #1d4ed8; }
        .akumulasi-section .predikat-badge.baik { background: #d1fae5; color: #065f46; }
        .akumulasi-section .predikat-badge.butuh-perbaikan { background: #fef3c7; color: #92400e; }
        .akumulasi-section .predikat-badge.kurang { background: #ffedd5; color: #9a3412; }
        .akumulasi-section .predikat-badge.sangat-kurang { background: #fef2f2; color: #991b1b; }
        .akumulasi-section .predikat-badge.belum-ada { background: #f1f5f9; color: #64748b; }
        
        .akumulasi-section .status-badge {
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
            white-space: nowrap;
        }
        .akumulasi-section .status-badge.efisien { background: #d1fae5; color: #065f46; }
        .akumulasi-section .status-badge.tidak-efisien { background: #fef2f2; color: #991b1b; }
        
        /* ============================================================
           BUTTONS
           ============================================================ */
        .btn {
            padding: 6px 18px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
        }
        .btn-primary {
            background: #0f3b5e;
            color: #fff;
        }
        .btn-primary:hover {
            background: #0a2a44;
            transform: translateY(-1px);
        }
        .btn-success {
            background: #16a34a;
            color: #fff;
        }
        .btn-success:hover {
            background: #15803d;
            transform: translateY(-1px);
        }
        
        .table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .btn-save {
            padding: 10px 36px;
            background: #0f3b5e;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: inherit;
        }
        .btn-save:hover {
            background: #0a2a44;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15,59,94,0.3);
        }
        
        /* ============================================================
           CONFIRM POPUP - MEYAKINKAN
           ============================================================ */
        .confirm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .confirm-overlay.show {
            display: flex;
        }
        .confirm-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px 36px;
            max-width: 440px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(30px) scale(0.95); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }
        .confirm-box .confirm-icon {
            font-size: 56px;
            margin-bottom: 12px;
            color: #dc2626;
        }
        .confirm-box .confirm-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f3b5e;
            margin-bottom: 8px;
        }
        .confirm-box .confirm-text {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .confirm-box .confirm-text .highlight {
            color: #dc2626;
            font-weight: 600;
        }
        .confirm-box .confirm-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .confirm-box .confirm-actions .confirm-btn {
            padding: 10px 28px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .confirm-box .confirm-actions .confirm-btn-cancel {
            background: #f1f5f9;
            color: #475569;
        }
        .confirm-box .confirm-actions .confirm-btn-cancel:hover {
            background: #e2e8f0;
        }
        .confirm-box .confirm-actions .confirm-btn-confirm {
            background: #dc2626;
            color: #fff;
        }
        .confirm-box .confirm-actions .confirm-btn-confirm:hover {
            background: #b91c1c;
            transform: scale(1.02);
        }
        
        @media (max-width: 992px) {
            .sidebar { width: 200px; }
            .main-content .overlay { padding: 16px 18px; }
            .filter-wrapper { padding: 10px 16px; }
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
                z-index: 10;
            }
            .sidebar-brand { 
                padding: 4px 0; 
                border-bottom: none; 
                margin-bottom: 0; 
                flex: 1;
                flex-direction: row;
                gap: 10px;
                text-align: left;
            }
            .sidebar-brand .brand-logo { width: 36px; height: 36px; padding: 3px; }
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
            .main-content { padding: 12px; }
            .main-content .overlay { padding: 12px; }
            .header { flex-direction: column; align-items: flex-start; }
            .header h1 { font-size: 20px; }
            .filter-wrapper { padding: 8px 12px; }
            .filter-wrapper .filter-group .btn-filter { padding: 3px 10px; font-size: 11px; }
            .tab-nav .tab-btn { padding: 8px 16px; font-size: 13px; }
            .table-scroll table { font-size: 10px; min-width: 1000px; }
            .table-scroll table .form-input { font-size: 10px; padding: 2px 4px; }
            .akumulasi-section table { font-size: 10px; min-width: 900px; }
            .table-section .table-header { flex-direction: column; align-items: stretch; }
            .table-section .table-header .table-actions { justify-content: center; }
            .akumulasi-section .akumulasi-header { flex-direction: column; align-items: stretch; }
            .akumulasi-section .akumulasi-header .akumulasi-auto { text-align: center; }
            .confirm-box { padding: 24px 20px; max-width: 360px; }
            .confirm-box .confirm-icon { font-size: 44px; }
            .confirm-box .confirm-title { font-size: 18px; }
        }
        @media (max-width: 480px) {
            .sidebar-brand .brand-logo { width: 28px; height: 28px; padding: 2px; }
            .sidebar-brand .brand-text h2 { font-size: 13px; }
            .nav-list li a { padding: 4px 8px; font-size: 11px; }
            .nav-list li a i { font-size: 12px; }
            .header h1 { font-size: 18px; }
            .main-content .overlay { padding: 8px; }
            .filter-wrapper .filter-group .btn-filter { padding: 2px 8px; font-size: 10px; }
            .tab-nav .tab-btn { padding: 6px 12px; font-size: 12px; }
            .table-scroll table { font-size: 9px; min-width: 800px; }
            .akumulasi-section table { font-size: 9px; min-width: 700px; }
            .confirm-box { padding: 20px 16px; max-width: 320px; }
            .confirm-box .confirm-icon { font-size: 38px; }
            .confirm-box .confirm-title { font-size: 16px; }
            .confirm-box .confirm-text { font-size: 13px; }
            .confirm-box .confirm-actions .confirm-btn { padding: 8px 20px; font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- ============================================================
   SIDEBAR
   ============================================================ -->
<?php include 'includes/sidebar.php'; ?>

<!-- ============================================================
   CONFIRM POPUP
   ============================================================ -->
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="confirm-title">Hapus Data?</div>
        <div class="confirm-text">
            Apakah Anda yakin ingin menghapus baris data <span class="highlight" id="confirmRowInfo">ini</span>?
            <br><small style="color:#94a3b8;">Data yang dihapus tidak dapat dikembalikan.</small>
        </div>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" onclick="closeConfirm()">
                <i class="fas fa-times"></i> Batal
            </button>
            <button class="confirm-btn confirm-btn-confirm" id="confirmDeleteBtn">
                <i class="fas fa-trash"></i> Ya, Hapus!
            </button>
        </div>
    </div>
</div>

<!-- ============================================================
   MAIN CONTENT
   ============================================================ -->
<main class="main-content">
    <div class="overlay">
        
        <div class="header">
            <div>
                <h1><i class="fas fa-chart-pie"></i> Monitoring & Evaluasi</h1>
                <span class="info">Kelola data monitoring dan evaluasi kinerja</span>
            </div>
            <div class="admin-welcome">
                <i class="fas fa-user-circle"></i> <?= htmlspecialchars($admin_nama) ?>
            </div>
        </div>
        
        <?php if (isset($success) && !empty($success)): ?>
        <div class="alert alert-success" id="successAlert">
            <i class="fas fa-check-circle"></i> <?= $success ?>
        </div>
        <?php endif; ?>
        <?php if (isset($error) && !empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $error ?>
        </div>
        <?php endif; ?>
        
        <!-- ============================================================
           FILTER
           ============================================================ -->
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
        
        <!-- ============================================================
           TAB NAVIGATION
           ============================================================ -->
        <div class="tab-nav">
            <a href="?tahun=<?= urlencode($tahun_aktif) ?>&bulan=<?= urlencode($bulan_aktif) ?>&tab=bulanan" class="tab-btn <?= $tab_aktif == 'bulanan' ? 'active' : '' ?>">
                <i class="fas fa-list"></i> Input Data
            </a>
            <a href="?tahun=<?= urlencode($tahun_aktif) ?>&bulan=<?= urlencode($bulan_aktif) ?>&tab=akumulasi" class="tab-btn <?= $tab_aktif == 'akumulasi' ? 'active' : '' ?>">
                <i class="fas fa-calculator"></i> Akumulasi
            </a>
        </div>
        
        <!-- ============================================================
           TAB: INPUT DATA
           ============================================================ -->
        <div class="tab-content <?= $tab_aktif == 'bulanan' ? 'active' : '' ?>">
            <form method="post" enctype="multipart/form-data" autocomplete="off" id="mainForm">
                <div class="table-section">
                    <div class="table-header">
                        <div class="table-title">
                            <i class="fas fa-list"></i> <?= $bulan_aktif ?> <?= $tahun_aktif ?>
                        </div>
                        <div class="table-actions">
                            <button type="button" class="btn btn-primary" onclick="addRow()">
                                <i class="fas fa-plus"></i> Tambah Baris
                            </button>
                            <button type="submit" name="save_bulanan" value="1" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:30px;">No</th>
                                    <th style="min-width:150px; text-align:left;">Sub Kegiatan</th>
                                    <th style="min-width:150px; text-align:left;">Indikator</th>
                                    <th style="width:80px;">Target<br><small>IK</small></th>
                                    <th style="width:90px;">Target<br><small>KEU</small></th>
                                    <th style="width:80px;">Realisasi<br><small>IK</small></th>
                                    <th style="width:90px;">Realisasi<br><small>KEU</small></th>
                                    <th style="width:80px;">Capaian<br><small>IK</small></th>
                                    <th style="width:90px;">Capaian<br><small>KEU</small></th>
                                    <th style="min-width:180px; text-align:left;">Sumber Data</th>
                                    <th style="min-width:130px; text-align:left;">Faktor Penghambat</th>
                                    <th style="min-width:130px; text-align:left;">Faktor Pendukung</th>
                                    <th style="width:35px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php 
                                $no = 1;
                                if (empty($data_bulanan)): 
                                ?>
                                <tr id="row_new">
                                    <td class="text-center">1</td>
                                    <td><input type="text" name="data[new][sub_kegiatan]" class="form-input text-left" placeholder="Sub kegiatan" style="width:100%;"></td>
                                    <td><input type="text" name="data[new][indikator]" class="form-input text-left" placeholder="Indikator" style="width:100%;"></td>
                                    <td><input type="text" name="data[new][target_ik]" class="form-input" placeholder="0" oninput="formatNumber(this)" data-id="new" data-type="target_ik"></td>
                                    <td><input type="text" name="data[new][target_keu]" class="form-input" placeholder="0" oninput="formatNumber(this)" data-id="new" data-type="target_keu"></td>
                                    <td><input type="text" name="data[new][realisasi_ik]" class="form-input" placeholder="0" oninput="formatNumber(this)" data-id="new" data-type="realisasi_ik"></td>
                                    <td><input type="text" name="data[new][realisasi_keu]" class="form-input" placeholder="0" oninput="formatNumber(this)" data-id="new" data-type="realisasi_keu"></td>
                                    <td class="text-center"><span class="capaian-value belum-ada" data-capaian="new_ik">0%</span></td>
                                    <td class="text-center"><span class="capaian-value belum-ada" data-capaian="new_keu">0%</span></td>
                                    <td><input type="text" name="data[new][sumber_data]" class="form-input text-left sumber-input" placeholder="https://..." style="width:100%;"></td>
                                    <td><input type="text" name="data[new][faktor_penghambat]" class="form-input text-left" placeholder="Faktor penghambat" style="width:100%;"></td>
                                    <td><input type="text" name="data[new][faktor_pendukung]" class="form-input text-left" placeholder="Faktor pendukung" style="width:100%;"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn-delete-row" onclick="confirmDelete(this)" title="Hapus" data-row="baris baru">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($data_bulanan as $row): 
                                    $pred_ik = getPredikat($row['capaian_ik']);
                                    $pred_keu = getPredikat($row['capaian_keu']);
                                    $row_label = !empty($row['sub_kegiatan']) ? $row['sub_kegiatan'] : 'baris ke-' . $no;
                                ?>
                                <tr id="row_<?= $row['id'] ?>">
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><input type="text" name="data[<?= $row['id'] ?>][sub_kegiatan]" value="<?= htmlspecialchars($row['sub_kegiatan']) ?>" class="form-input text-left" style="width:100%;"></td>
                                    <td><input type="text" name="data[<?= $row['id'] ?>][indikator]" value="<?= htmlspecialchars($row['indikator']) ?>" class="form-input text-left" style="width:100%;"></td>
                                    <td><input type="text" name="data[<?= $row['id'] ?>][target_ik]" value="<?= number_format($row['target_ik'], 0, ',', '.') ?>" class="form-input" oninput="formatNumber(this)" data-id="<?= $row['id'] ?>" data-type="target_ik"></td>
                                    <td><input type="text" name="data[<?= $row['id'] ?>][target_keu]" value="<?= number_format($row['target_keu'], 0, ',', '.') ?>" class="form-input" oninput="formatNumber(this)" data-id="<?= $row['id'] ?>" data-type="target_keu"></td>
                                    <td><input type="text" name="data[<?= $row['id'] ?>][realisasi_ik]" value="<?= number_format($row['realisasi_ik'], 0, ',', '.') ?>" class="form-input" oninput="formatNumber(this)" data-id="<?= $row['id'] ?>" data-type="realisasi_ik"></td>
                                    <td><input type="text" name="data[<?= $row['id'] ?>][realisasi_keu]" value="<?= number_format($row['realisasi_keu'], 0, ',', '.') ?>" class="form-input" oninput="formatNumber(this)" data-id="<?= $row['id'] ?>" data-type="realisasi_keu"></td>
                                    <td class="text-center"><span class="capaian-value <?= $pred_ik['class'] ?>" data-capaian="<?= $row['id'] ?>_ik"><?= number_format($row['capaian_ik'], 2, ',', '.') ?>%</span></td>
                                    <td class="text-center"><span class="capaian-value <?= $pred_keu['class'] ?>" data-capaian="<?= $row['id'] ?>_keu"><?= number_format($row['capaian_keu'], 2, ',', '.') ?>%</span></td>
                                    <td><input type="text" name="data[<?= $row['id'] ?>][sumber_data]" value="<?= htmlspecialchars($row['sumber_data'] ?? '') ?>" class="form-input text-left sumber-input" placeholder="https://..." style="width:100%;"></td>
                                    <td><input type="text" name="data[<?= $row['id'] ?>][faktor_penghambat]" value="<?= htmlspecialchars($row['faktor_penghambat']) ?>" class="form-input text-left" style="width:100%;"></td>
                                    <td><input type="text" name="data[<?= $row['id'] ?>][faktor_pendukung]" value="<?= htmlspecialchars($row['faktor_pendukung']) ?>" class="form-input text-left" style="width:100%;"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn-delete-row" onclick="confirmDelete(this, <?= $row['id'] ?>)" title="Hapus" data-row="<?= htmlspecialchars($row_label) ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- ============================================================
           TAB: AKUMULASI - PER BARIS (TIDAK DIGABUNG)
           ============================================================ -->
        <div class="tab-content <?= $tab_aktif == 'akumulasi' ? 'active' : '' ?>">
            <div class="akumulasi-section">
                <div class="akumulasi-header">
                    <div class="akumulasi-title">
                        <i class="fas fa-calculator"></i> Akumulasi Tahunan <?= $tahun_aktif ?>
                        <span style="font-size:11px; font-weight:400; color:#64748b; margin-left:8px;">
                            (Per Baris - Tidak Digabung)
                        </span>
                    </div>
                    <span class="akumulasi-auto">
                        <i class="fas fa-sync"></i> Otomatis diperbarui
                    </span>
                </div>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:30px;">No</th>
                                <th style="min-width:150px; text-align:left;">Sub Kegiatan</th>
                                <th style="min-width:150px; text-align:left;">Indikator</th>
                                <th style="width:80px;">Target<br><small>IK</small></th>
                                <th style="width:90px;">Target<br><small>KEU</small></th>
                                <th style="width:80px;">Realisasi<br><small>IK</small></th>
                                <th style="width:90px;">Realisasi<br><small>KEU</small></th>
                                <th style="width:80px;">Capaian<br><small>IK</small></th>
                                <th style="width:90px;">Capaian<br><small>KEU</small></th>
                                <th style="width:85px;">Predikat<br><small>IK</small></th>
                                <th style="width:85px;">Predikat<br><small>KEU</small></th>
                                <th style="width:85px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data_akumulasi)): ?>
                            <tr>
                                <td colspan="12" class="text-center" style="padding:30px; color:#94a3b8;">
                                    <i class="fas fa-info-circle"></i> Belum ada data akumulasi. Simpan data bulanan untuk menghitung otomatis.
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
                                <td><?= htmlspecialchars($row['sub_kegiatan']) ?></td>
                                <td><?= htmlspecialchars($row['indikator']) ?></td>
                                <td class="text-right"><?= number_format($row['target_ik'], 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($row['target_keu'], 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($row['realisasi_ik'], 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($row['realisasi_keu'], 0, ',', '.') ?></td>
                                <td class="text-center"><span class="capaian-value <?= $pred_ik['class'] ?>"><?= number_format($row['capaian_ik'], 2, ',', '.') ?>%</span></td>
                                <td class="text-center"><span class="capaian-value <?= $pred_keu['class'] ?>"><?= number_format($row['capaian_keu'], 2, ',', '.') ?>%</span></td>
                                <td class="text-center"><span class="predikat-badge <?= $pred_ik['class'] ?>"><?= $pred_ik['label'] ?></span></td>
                                <td class="text-center"><span class="predikat-badge <?= $pred_keu['class'] ?>"><?= $pred_keu['label'] ?></span></td>
                                <td class="text-center"><span class="status-badge <?= strtolower(str_replace(' ', '-', $row['status'])) ?>"><?= $row['status'] ?></span></td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</main>

<script>
// ============================================================
// CONFIRM DELETE POPUP
// ============================================================
var deleteId = null;
var deleteBtn = null;

function confirmDelete(btn, id) {
    var rowInfo = btn.getAttribute('data-row') || 'ini';
    document.getElementById('confirmRowInfo').textContent = '"' + rowInfo + '"';
    
    deleteId = id || null;
    deleteBtn = btn;
    
    document.getElementById('confirmOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeConfirm() {
    document.getElementById('confirmOverlay').classList.remove('show');
    document.body.style.overflow = '';
    deleteId = null;
    deleteBtn = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteId !== null) {
        // Hapus data dari database
        var form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input type="hidden" name="delete_id" value="' + deleteId + '">';
        document.body.appendChild(form);
        form.submit();
    } else if (deleteBtn !== null) {
        // Hapus baris baru (belum tersimpan)
        var tr = deleteBtn.closest('tr');
        if (tr) {
            tr.remove();
            updateRowNumbers();
        }
        closeConfirm();
    }
});

// Tutup popup dengan tombol Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirm();
    }
});

// ============================================================
// FORMAT NUMBER & UPDATE CAPAIAN
// ============================================================
function formatNumber(input) {
    var val = input.value.replace(/\./g, '').replace(',', '.');
    var num = parseFloat(val) || 0;
    input.value = num.toLocaleString('id-ID');
    updateCapaian(input);
}

function updateCapaian(input) {
    var row = input.closest('tr');
    if (!row) return;
    
    var id = input.dataset.id || 'new';
    var inputs = row.querySelectorAll('input[type="text"]');
    var target_ik = 0, target_keu = 0, realisasi_ik = 0, realisasi_keu = 0;
    
    inputs.forEach(function(inp) {
        var val = parseFloat(inp.value.replace(/\./g, '').replace(',', '.')) || 0;
        var dtype = inp.dataset.type || '';
        if (dtype === 'target_ik') target_ik = val;
        else if (dtype === 'target_keu') target_keu = val;
        else if (dtype === 'realisasi_ik') realisasi_ik = val;
        else if (dtype === 'realisasi_keu') realisasi_keu = val;
    });
    
    var capaian_ik = 0;
    if (target_ik > 0) capaian_ik = (realisasi_ik / target_ik) * 100;
    
    var capaian_keu = 0;
    if (target_keu > 0) capaian_keu = (realisasi_keu / target_keu) * 100;
    
    var capIk = row.querySelector('[data-capaian="' + id + '_ik"]');
    var capKeu = row.querySelector('[data-capaian="' + id + '_keu"]');
    
    if (capIk) {
        var predClass = getPredikatClass(capaian_ik);
        capIk.textContent = capaian_ik.toFixed(2).replace('.', ',') + '%';
        capIk.className = 'capaian-value ' + predClass;
    }
    if (capKeu) {
        var predClassKeu = getPredikatClass(capaian_keu);
        capKeu.textContent = capaian_keu.toFixed(2).replace('.', ',') + '%';
        capKeu.className = 'capaian-value ' + predClassKeu;
    }
}

function getPredikatClass(capaian) {
    if (capaian > 100) return 'istimewa';
    if (capaian >= 80) return 'baik';
    if (capaian >= 60) return 'butuh-perbaikan';
    if (capaian >= 20) return 'kurang';
    if (capaian > 0) return 'sangat-kurang';
    return 'belum-ada';
}

// ============================================================
// ADD ROW
// ============================================================
function addRow() {
    var tbody = document.getElementById('tableBody');
    var no = tbody.querySelectorAll('tr').length + 1;
    var newId = 'new_' + Date.now();
    
    var tr = document.createElement('tr');
    tr.id = 'row_' + newId;
    tr.innerHTML = `
        <td class="text-center">${no}</td>
        <td><input type="text" name="data[${newId}][sub_kegiatan]" class="form-input text-left" placeholder="Sub kegiatan" style="width:100%;"></td>
        <td><input type="text" name="data[${newId}][indikator]" class="form-input text-left" placeholder="Indikator" style="width:100%;"></td>
        <td><input type="text" name="data[${newId}][target_ik]" class="form-input" placeholder="0" oninput="formatNumber(this)" data-id="${newId}" data-type="target_ik"></td>
        <td><input type="text" name="data[${newId}][target_keu]" class="form-input" placeholder="0" oninput="formatNumber(this)" data-id="${newId}" data-type="target_keu"></td>
        <td><input type="text" name="data[${newId}][realisasi_ik]" class="form-input" placeholder="0" oninput="formatNumber(this)" data-id="${newId}" data-type="realisasi_ik"></td>
        <td><input type="text" name="data[${newId}][realisasi_keu]" class="form-input" placeholder="0" oninput="formatNumber(this)" data-id="${newId}" data-type="realisasi_keu"></td>
        <td class="text-center"><span class="capaian-value belum-ada" data-capaian="${newId}_ik">0%</span></td>
        <td class="text-center"><span class="capaian-value belum-ada" data-capaian="${newId}_keu">0%</span></td>
        <td><input type="text" name="data[${newId}][sumber_data]" class="form-input text-left sumber-input" placeholder="https://..." style="width:100%;"></td>
        <td><input type="text" name="data[${newId}][faktor_penghambat]" class="form-input text-left" placeholder="Faktor penghambat" style="width:100%;"></td>
        <td><input type="text" name="data[${newId}][faktor_pendukung]" class="form-input text-left" placeholder="Faktor pendukung" style="width:100%;"></td>
        <td class="text-center">
            <button type="button" class="btn-delete-row" onclick="confirmDelete(this)" title="Hapus" data-row="baris baru">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    updateRowNumbers();
}

// ============================================================
// UPDATE ROW NUMBERS
// ============================================================
function updateRowNumbers() {
    var rows = document.querySelectorAll('#tableBody tr');
    rows.forEach(function(row, index) {
        var td = row.querySelector('td:first-child');
        if (td) td.textContent = index + 1;
    });
}

// ============================================================
// SIDEBAR CLOCK
// ============================================================
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

// ============================================================
// AUTO HIDE SUCCESS ALERT
// ============================================================
var successAlert = document.getElementById('successAlert');
if (successAlert) {
    setTimeout(function() {
        successAlert.style.display = 'none';
    }, 5000);
}

// ============================================================
// UPDATE CAPAIAN OTOMATIS SAAT HALAMAN DIMUAT
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    var inputs = document.querySelectorAll('#tableBody input[data-type]');
    inputs.forEach(function(input) {
        updateCapaian(input);
    });
});
</script>

</body>
</html>