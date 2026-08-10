<?php
// admin/capaian.php - Halaman Capaian Program
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

include '../config/database.php';

$success = '';
$error = '';

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

// ============================================================
// CREATE TABLE JIKA BELUM ADA
// ============================================================
try {
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
} catch (PDOException $e) {
    // Table already exists or error
}

// ============================================================
// CEK KOLOM FILE_SUMBER (TAMBAHKAN JIKA BELUM ADA)
// ============================================================
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM capaian_program LIKE 'file_sumber'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE capaian_program ADD COLUMN `file_sumber` varchar(255) DEFAULT NULL AFTER `sumber_data`");
    }
} catch (PDOException $e) {
    // Column already exists or error
}

// ============================================================
// CEK DAN UPDATE KOLOM DECIMAL JIKA PERLU
// ============================================================
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM capaian_program LIKE 'target'");
    $col = $stmt->fetch();
    if ($col && strpos($col['Type'], 'decimal(20,6)') === false) {
        $pdo->exec("ALTER TABLE capaian_program MODIFY COLUMN `target` decimal(20,6) DEFAULT 0");
        $pdo->exec("ALTER TABLE capaian_program MODIFY COLUMN `realisasi` decimal(20,6) DEFAULT 0");
        $pdo->exec("ALTER TABLE capaian_program MODIFY COLUMN `capaian` decimal(10,4) DEFAULT 0");
    }
} catch (PDOException $e) {
    // Error
}

// ============================================================
// AMBIL DATA CAPAIAN PROGRAM PER TAHUN
// ============================================================
$stmt = $pdo->prepare("SELECT * FROM capaian_program WHERE tahun = ? ORDER BY id");
$stmt->execute([$tahun_aktif]);
$capaian_data = $stmt->fetchAll();

// ============================================================
// CEK APAKAH DATA ADA UNTUK TAHUN INI
// ============================================================
if (empty($capaian_data)) {
    // Data default dari screenshot
    $default_data = [
        [
            'program' => 'Program Pengembangan Destinasi Pariwisata',
            'sasaran' => 'Meningkatnya Rasio PDRB Penyediaan Akomodasi Makan Minum',
            'indikator' => 'Rata-Rata Lama Kunjungan Wisatawan Mancanegara (Hari)',
            'target' => 3,
            'realisasi' => 0,
            'capaian' => 0,
            'frekwensi' => 'Tahunan',
            'sumber_data' => 'BPS',
            'penanggung_jawab' => 'BIDANG Pengembangan Destinasi Pariwisata'
        ],
        [
            'program' => 'Program Pengembangan Destinasi Pariwisata',
            'sasaran' => 'Meningkatnya Rasio PDRB Penyediaan Akomodasi Makan Minum',
            'indikator' => 'Rata-rata pengeluaran wisatawan mancanegara ($)',
            'target' => 600,
            'realisasi' => 0,
            'capaian' => 0,
            'frekwensi' => 'Tahunan',
            'sumber_data' => 'BPS',
            'penanggung_jawab' => 'BIDANG Pengembangan Destinasi Pariwisata'
        ],
        [
            'program' => 'Program Pemasaran Pariwisata',
            'sasaran' => 'Meningkatnya Jumlah Kunjungan Wisatawan Mancanegara',
            'indikator' => 'Jumlah pergerakan wisatawan mancanegara (ribu perhari)',
            'target' => 28750,
            'realisasi' => 3847,
            'capaian' => 13.38,
            'frekwensi' => 'Bulanan / Tahunan',
            'sumber_data' => 'BPS, Dinas Pariwisata Kab./Kota',
            'penanggung_jawab' => 'BIDANG Pemasaran Pariwisata'
        ],
        [
            'program' => 'Program Pemasaran Pariwisata',
            'sasaran' => 'Meningkatnya Jumlah Kunjungan Wisatawan Mancanegara',
            'indikator' => 'Jumlah pergerakan wisatawan mancanegara (juta orang)',
            'target' => 9925000,
            'realisasi' => 4988167,
            'capaian' => 50.28,
            'frekwensi' => 'Bulanan / Tahunan',
            'sumber_data' => 'BPS, Dinas Pariwisata Kab./Kota',
            'penanggung_jawab' => 'BIDANG Pemasaran Pariwisata'
        ],
        [
            'program' => 'Program Ekonomi Kreatif Melalui Pemanfaatan Dan Perlindungan Hak Kekayaan Intelektual',
            'sasaran' => 'Meningkatnya Proporsi PDRB Ekonomi Kreatif Terhadap ADHB',
            'indikator' => 'Nilai Tambah Ekonomi Kreatif (Rp)',
            'target' => 143750000000,
            'realisasi' => 0,
            'capaian' => 0,
            'frekwensi' => 'Tahunan',
            'sumber_data' => 'BPS',
            'penanggung_jawab' => 'BIDANG Pengembangan Ekonomi Kreatif'
        ],
        [
            'program' => 'Program Pengembangan Sumber Daya Pariwisata dan Ekraf',
            'sasaran' => 'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi',
            'indikator' => 'Jumlah tenaga Kerja Pariwisata (orang)',
            'target' => 9259,
            'realisasi' => 0,
            'capaian' => 0,
            'frekwensi' => 'Tahunan',
            'sumber_data' => 'BPS',
            'penanggung_jawab' => 'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'
        ],
        [
            'program' => 'Program Pengembangan Sumber Daya Pariwisata dan Ekraf',
            'sasaran' => 'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi',
            'indikator' => 'Jumlah Tenaga Kerja Ekonomi Kreatif (orang)',
            'target' => 2571,
            'realisasi' => 0,
            'capaian' => 0,
            'frekwensi' => 'Tahunan',
            'sumber_data' => 'BPS',
            'penanggung_jawab' => 'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'
        ],
        [
            'program' => 'Program Pengembangan Sumber Daya Pariwisata dan Ekraf',
            'sasaran' => 'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi',
            'indikator' => 'Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata tersertifikasi (orang)',
            'target' => 200,
            'realisasi' => 0,
            'capaian' => 0,
            'frekwensi' => 'Tahunan',
            'sumber_data' => 'BPS',
            'penanggung_jawab' => 'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'
        ],
        [
            'program' => 'Program Pengembangan Sumber Daya Pariwisata dan Ekraf',
            'sasaran' => 'Meningkatnya Jumlah Tenaga Kerja/Pelaku Usaha Pariwisata dan Ekonomi Kreatif tersertifikasi',
            'indikator' => 'Jumlah Tenaga Kerja/Pelaku Usaha Ekonomi Kreatif tersertifikasi (orang)',
            'target' => 200,
            'realisasi' => 0,
            'capaian' => 0,
            'frekwensi' => 'Tahunan',
            'sumber_data' => 'BPS',
            'penanggung_jawab' => 'BIDANG Pengembangan Sumber Daya Pariwisata dan Ekraf'
        ]
    ];
    
    foreach ($default_data as $data) {
        $stmt = $pdo->prepare("INSERT INTO capaian_program 
            (program, sasaran, indikator, target, realisasi, capaian, frekwensi, sumber_data, penanggung_jawab, tahun) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['program'],
            $data['sasaran'],
            $data['indikator'],
            $data['target'],
            $data['realisasi'],
            $data['capaian'],
            $data['frekwensi'],
            $data['sumber_data'],
            $data['penanggung_jawab'],
            $tahun_aktif
        ]);
    }
    
    $stmt = $pdo->prepare("SELECT * FROM capaian_program WHERE tahun = ? ORDER BY id");
    $stmt->execute([$tahun_aktif]);
    $capaian_data = $stmt->fetchAll();
}

// ============================================================
// PROSES UPDATE DATA - FLEKSIBEL JUMLAH DESIMAL
// ============================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    foreach ($_POST['data'] as $id => $row) {
        // ===== FIX: Proses Target dengan koma - FLEKSIBEL =====
        $target_raw = trim($row['target'] ?? '0');
        // Hapus titik ribuan, ubah koma ke titik (format Indonesia -> format PHP)
        $target = str_replace('.', '', $target_raw);
        $target = str_replace(',', '.', $target);
        $target = (float) $target;
        
        // ===== FIX: Proses Realisasi dengan koma - FLEKSIBEL =====
        $realisasi_raw = trim($row['realisasi'] ?? '0');
        // Hapus titik ribuan, ubah koma ke titik (format Indonesia -> format PHP)
        $realisasi = str_replace('.', '', $realisasi_raw);
        $realisasi = str_replace(',', '.', $realisasi);
        $realisasi = (float) $realisasi;
        
        // Hitung capaian dengan presisi penuh (6 desimal)
        $capaian = 0;
        if ($target > 0) {
            $capaian = ($realisasi / $target) * 100;
            // Presisi penuh, tidak dibulatkan
        }
        
        $program = trim($row['program'] ?? '');
        $sasaran = trim($row['sasaran'] ?? '');
        $indikator = trim($row['indikator'] ?? '');
        $penanggung_jawab = trim($row['penanggung_jawab'] ?? '');
        $sumber_data = trim($row['sumber_data'] ?? '');
        $frekwensi = $row['frekwensi'] ?? 'Tahunan';
        
        $stmt = $pdo->prepare("UPDATE capaian_program SET 
            program = ?,
            sasaran = ?,
            indikator = ?,
            target = ?, 
            realisasi = ?, 
            capaian = ?,
            sumber_data = ?,
            frekwensi = ?,
            penanggung_jawab = ?
            WHERE id = ? AND tahun = ?");
        $stmt->execute([
            $program,
            $sasaran,
            $indikator,
            $target, 
            $realisasi, 
            $capaian,
            $sumber_data,
            $frekwensi,
            $penanggung_jawab,
            $id, 
            $tahun_aktif
        ]);
    }
    
    if (isset($_FILES['file_sumber']) && is_array($_FILES['file_sumber']['tmp_name'])) {
        $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
        $max_size = 10 * 1024 * 1024;
        
        foreach ($_FILES['file_sumber']['tmp_name'] as $id => $tmp_name) {
            if ($_FILES['file_sumber']['error'][$id] == 0 && !empty($tmp_name)) {
                $file_ext = strtolower(pathinfo($_FILES['file_sumber']['name'][$id], PATHINFO_EXTENSION));
                
                if (in_array($file_ext, $allowed) && $_FILES['file_sumber']['size'][$id] <= $max_size) {
                    $target_dir = '../uploads/capaian/';
                    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                    
                    $stmt = $pdo->prepare("SELECT file_sumber FROM capaian_program WHERE id = ? AND tahun = ?");
                    $stmt->execute([$id, $tahun_aktif]);
                    $old_file = $stmt->fetchColumn();
                    if ($old_file) {
                        $old_path = '../uploads/capaian/' . $old_file;
                        if (file_exists($old_path)) unlink($old_path);
                    }
                    
                    $file_name = 'sumber_' . $id . '_' . time() . '.' . $file_ext;
                    $target_file = $target_dir . $file_name;
                    
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $stmt = $pdo->prepare("UPDATE capaian_program SET file_sumber = ? WHERE id = ? AND tahun = ?");
                        $stmt->execute([$file_name, $id, $tahun_aktif]);
                    }
                }
            }
        }
    }
    
    $success = 'Data capaian program berhasil diperbarui!';
    
    $stmt = $pdo->prepare("SELECT * FROM capaian_program WHERE tahun = ? ORDER BY id");
    $stmt->execute([$tahun_aktif]);
    $capaian_data = $stmt->fetchAll();
}

// ============================================================
// HAPUS FILE SUMBER PER ID
// ============================================================
if (isset($_GET['delete_file']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT file_sumber FROM capaian_program WHERE id = ? AND tahun = ?");
    $stmt->execute([$id, $tahun_aktif]);
    $file_sumber = $stmt->fetchColumn();
    if ($file_sumber) {
        $file_path = '../uploads/capaian/' . $file_sumber;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $stmt = $pdo->prepare("UPDATE capaian_program SET file_sumber = NULL WHERE id = ? AND tahun = ?");
        $stmt->execute([$id, $tahun_aktif]);
        $success = 'File sumber berhasil dihapus!';
        
        $stmt = $pdo->prepare("SELECT * FROM capaian_program WHERE tahun = ? ORDER BY id");
        $stmt->execute([$tahun_aktif]);
        $capaian_data = $stmt->fetchAll();
    }
}

// ============================================================
// RESET DATA
// ============================================================
if (isset($_GET['reset']) && $_GET['reset'] == 1) {
    $stmt = $pdo->prepare("SELECT id, file_sumber FROM capaian_program WHERE tahun = ? AND file_sumber IS NOT NULL AND file_sumber != ''");
    $stmt->execute([$tahun_aktif]);
    $files = $stmt->fetchAll();
    
    foreach ($files as $f) {
        if (!empty($f['file_sumber'])) {
            $file_path = '../uploads/capaian/' . $f['file_sumber'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    
    $stmt = $pdo->prepare("UPDATE capaian_program SET 
        target = 0, 
        realisasi = 0, 
        capaian = 0, 
        sumber_data = NULL, 
        file_sumber = NULL 
        WHERE tahun = ?");
    $stmt->execute([$tahun_aktif]);
    
    $success = 'Data berhasil direset! Target, realisasi, capaian, dan sumber data telah dikosongkan.';
    
    $stmt = $pdo->prepare("SELECT * FROM capaian_program WHERE tahun = ? ORDER BY id");
    $stmt->execute([$tahun_aktif]);
    $capaian_data = $stmt->fetchAll();
}

// ============================================================
// HITUNG TOTAL CAPAIAN RATA-RATA
// ============================================================
$total_capaian = 0;
$total_data = count($capaian_data);
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

$admin_nama = $_SESSION['admin_nama'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capaian Program - CEKIDOT</title>
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

        .sidebar-nav { 
            flex: 1; 
            padding: 0 12px 12px; 
            overflow-y: auto;
        }
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
        .sidebar-footer .footer-version {
            text-align: center;
            margin-top: 8px;
            font-size: 8px;
            color: rgba(255,255,255,0.15);
        }

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
        
        .stats-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .stats-grid {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .stats-grid .stat-card {
            background: #ffffff;
            padding: 14px 24px;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            text-align: center;
            min-width: 120px;
            transition: all 0.3s;
        }
        .stats-grid .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .stats-grid .stat-card .stat-number {
            font-size: 24px;
            font-weight: 800;
            color: #0f3b5e;
            display: block;
        }
        .stats-grid .stat-card .stat-label {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
        }
        
        .tahun-nav {
            display: flex;
            align-items: center;
            gap: 4px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            padding: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            flex-shrink: 0;
        }
        .tahun-nav .btn-tahun {
            padding: 6px 14px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            background: transparent;
            color: #64748b;
            text-decoration: none;
        }
        .tahun-nav .btn-tahun:hover {
            background: rgba(15, 59, 94, 0.05);
            color: #0f3b5e;
        }
        .tahun-nav .btn-tahun.active {
            background: #0f3b5e;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(15,59,94,0.2);
        }
        
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
        
        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 1600px;
        }
        table th {
            text-align: center;
            padding: 10px 8px;
            background: #f8fafc;
            font-weight: 700;
            color: #0f3b5e;
            border-bottom: 2px solid #e2e8f0;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        table th:first-child { text-align: left; min-width: 180px; }
        table th:nth-child(2) { min-width: 150px; }
        table th:nth-child(3) { min-width: 170px; }
        table td {
            padding: 6px 8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        table tr:hover td {
            background: #f8fafc;
        }
        table tr:last-child td {
            border-bottom: none;
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
        
        table .input-text {
            width: 100%;
            padding: 4px 6px;
            border: 1.5px solid #e2e8f0;
            border-radius: 4px;
            font-size: 11px;
            font-family: inherit;
            background: #ffffff;
            transition: border-color 0.3s;
            min-width: 100px;
        }
        table .input-text:focus {
            outline: none;
            border-color: #0f3b5e;
            box-shadow: 0 0 0 3px rgba(15,59,94,0.06);
        }
        
        table .num-input {
            width: 100%;
            padding: 4px 6px;
            border: 1.5px solid #e2e8f0;
            border-radius: 4px;
            font-size: 12px;
            font-family: inherit;
            background: #ffffff;
            transition: border-color 0.3s;
            text-align: right;
            max-width: 120px;
        }
        table .num-input:focus {
            outline: none;
            border-color: #0f3b5e;
            box-shadow: 0 0 0 3px rgba(15,59,94,0.06);
        }
        table .num-input.target-input {
            background: #f0f7ff;
        }
        table .num-input.realisasi-input {
            background: #f0fdf4;
        }
        table .num-input.sumber-input {
            background: #fffbeb;
            max-width: 200px;
            text-align: left;
            font-size: 11px;
        }
        
        table .frekwensi-select {
            padding: 4px 6px;
            border: 1.5px solid #e2e8f0;
            border-radius: 4px;
            font-size: 11px;
            font-family: inherit;
            background: #ffffff;
            transition: border-color 0.3s;
            width: 100%;
            max-width: 120px;
            text-align: center;
        }
        table .frekwensi-select:focus {
            outline: none;
            border-color: #0f3b5e;
            box-shadow: 0 0 0 3px rgba(15,59,94,0.06);
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
        
        .file-upload-wrapper {
            position: relative;
            display: inline-block;
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }
        .file-upload-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
            top: 0;
            left: 0;
        }
        .file-upload-wrapper .file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            border: 1px solid #e2e8f0;
        }
        .file-upload-wrapper .file-label:hover {
            background: #dbeafe;
            color: #0f3b5e;
            border-color: #0f3b5e;
        }
        .file-upload-wrapper .file-label i {
            font-size: 14px;
        }
        
        .file-info-cell {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .file-info-cell .file-name {
            font-size: 10px;
            color: #0f3b5e;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .file-info-cell .file-name i {
            margin-right: 3px;
        }
        .file-info-cell .file-preview-text {
            font-size: 10px;
            color: #16a34a;
            font-weight: 500;
            background: #d1fae5;
            padding: 1px 8px;
            border-radius: 10px;
            display: none;
        }
        .file-info-cell .file-preview-text.show {
            display: inline-block;
        }
        
        .file-info-cell .btn-delete-file {
            width: 24px;
            height: 24px;
            border: none;
            border-radius: 50%;
            background: #fef2f2;
            color: #991b1b;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            text-decoration: none;
        }
        .file-info-cell .btn-delete-file:hover {
            background: #fecaca;
        }
        
        .upload-status {
            font-size: 10px;
            color: #16a34a;
            display: none;
            white-space: nowrap;
        }
        .upload-status.show {
            display: inline-block;
        }
        .upload-status i {
            margin-right: 3px;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 16px 0;
            border-top: 1px solid #e8ecf1;
            margin-top: 4px;
        }
        .form-actions-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .form-actions-right {
            display: flex;
            align-items: center;
            gap: 12px;
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
        }
        .btn-save:hover {
            background: #0a2a44;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15,59,94,0.3);
        }
        
        .btn-reset {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 50%;
            background: #fef2f2;
            color: #991b1b;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            text-decoration: none;
        }
        .btn-reset:hover {
            background: #fecaca;
            transform: scale(1.05);
        }
        
        .form-note {
            font-size: 12px;
            color: #94a3b8;
            text-align: right;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #f1f5f9;
        }
        .form-note i {
            color: #eab308;
            margin-right: 4px;
        }
        
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
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .confirm-box .confirm-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
        .confirm-box .confirm-icon .fa-save {
            color: #0f3b5e;
        }
        .confirm-box .confirm-icon .fa-trash {
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
            background: #0f3b5e;
            color: #fff;
        }
        .confirm-box .confirm-actions .confirm-btn-confirm:hover {
            background: #0a2a44;
            transform: scale(1.02);
        }
        .confirm-box .confirm-actions .confirm-btn-confirm.danger {
            background: #dc2626;
        }
        .confirm-box .confirm-actions .confirm-btn-confirm.danger:hover {
            background: #b91c1c;
        }
        
        @media (max-width: 992px) {
            .stats-wrapper {
                flex-direction: column;
                align-items: stretch;
            }
            .stats-grid {
                justify-content: center;
            }
            .tahun-nav {
                justify-content: center;
            }
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .form-actions-left,
            .form-actions-right {
                justify-content: center;
            }
            .btn-save {
                width: 100%;
                justify-content: center;
            }
        }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar {
                width: 100%;
                min-height: auto;
                height: auto;
                position: relative;
                top: 0;
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
            .main-content .overlay { padding: 16px; }
            .header { flex-direction: column; align-items: flex-start; }
            .stats-grid .stat-card { padding: 10px 16px; min-width: 80px; }
            .stats-grid .stat-card .stat-number { font-size: 18px; }
            table { font-size: 10px; min-width: 900px; }
            table th, table td { padding: 4px 6px; }
            table .num-input { font-size: 10px; max-width: 80px; padding: 2px 4px; }
            table .num-input.sumber-input { max-width: 120px; }
            table .frekwensi-select { font-size: 10px; max-width: 90px; }
            table .input-text { font-size: 10px; min-width: 70px; }
            .file-info-cell .file-name { max-width: 60px; }
            .predikat-badge { font-size: 9px; min-width: 60px; padding: 2px 8px; }
        }
        @media (max-width: 480px) {
            .sidebar-brand .brand-logo { width: 28px; height: 28px; padding: 2px; }
            .sidebar-brand .brand-text h2 { font-size: 13px; }
            .nav-list li a { padding: 4px 8px; font-size: 11px; }
            .nav-list li a i { font-size: 12px; }
            .header h1 { font-size: 20px; }
            .main-content .overlay { padding: 12px; }
            .stats-grid .stat-card { padding: 8px 12px; min-width: 60px; }
            .stats-grid .stat-card .stat-number { font-size: 16px; }
            .stats-grid .stat-card .stat-label { font-size: 9px; }
            .tahun-nav .btn-tahun { padding: 4px 10px; font-size: 11px; }
            table { font-size: 9px; min-width: 700px; }
            table th, table td { padding: 3px 4px; }
            table .num-input { font-size: 9px; max-width: 60px; padding: 1px 3px; }
            table .num-input.sumber-input { max-width: 80px; }
            table .frekwensi-select { font-size: 9px; max-width: 70px; padding: 1px 3px; }
            table .input-text { font-size: 9px; min-width: 50px; padding: 2px 3px; }
            .file-info-cell .file-name { max-width: 40px; }
            .predikat-badge { font-size: 8px; min-width: 50px; padding: 1px 6px; }
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <div class="overlay">
        
        <div class="header">
            <div>
                <h1><i class="fas fa-flag-checkered"></i> Capaian Program</h1>
                <span class="info">Tahun <?= $tahun_aktif ?></span>
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
        
        <div class="stats-wrapper">
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number"><?= $total_data ?></span>
                    <span class="stat-label">Total Indikator</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?= number_format($rata_capaian, 2, ',', '.') ?>%</span>
                    <span class="stat-label">Rata-rata Capaian</span>
                </div>
            </div>
            
            <div class="tahun-nav">
                <?php foreach($tahun_list as $t): ?>
                <a href="?tahun=<?= urlencode($t) ?>" class="btn-tahun <?= $tahun_aktif == $t ? 'active' : '' ?>">
                    <?= $t ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <form method="post" enctype="multipart/form-data" id="formCapaian">
            <input type="hidden" name="action" value="update">
        
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="min-width:180px;">Program</th>
                            <th style="min-width:150px;">Sasaran</th>
                            <th style="min-width:170px;">Indikator</th>
                            <th style="width:100px;">Target</th>
                            <th style="width:100px;">Realisasi</th>
                            <th style="width:80px;">Capaian</th>
                            <th style="width:110px;">Predikat</th>
                            <th style="width:110px;">Frekwensi</th>
                            <th style="min-width:180px;">Sumber Data</th>
                            <th style="min-width:150px;">Penanggung Jawab</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($capaian_data as $d): 
                            $capaian = (float)$d['capaian'];
                            $class = 'zero';
                            if ($capaian >= 80) $class = 'high';
                            elseif ($capaian >= 50) $class = 'medium';
                            elseif ($capaian > 0) $class = 'low';
                            
                            $predikat = getPredikat($capaian);
                            // Format angka sesuai jumlah desimal (flexible)
                            $target_formatted = number_format($d['target'], 6, ',', '.');
                            $target_formatted = rtrim(rtrim($target_formatted, '0'), ',');
                            $realisasi_formatted = number_format($d['realisasi'], 6, ',', '.');
                            $realisasi_formatted = rtrim(rtrim($realisasi_formatted, '0'), ',');
                        ?>
                        <tr>
                            <td>
                                <input type="text" name="data[<?= $d['id'] ?>][program]" 
                                       value="<?= htmlspecialchars($d['program']) ?>" 
                                       class="input-text" placeholder="Nama Program">
                            </td>
                            <td>
                                <input type="text" name="data[<?= $d['id'] ?>][sasaran]" 
                                       value="<?= htmlspecialchars($d['sasaran']) ?>" 
                                       class="input-text" placeholder="Sasaran">
                            </td>
                            <td>
                                <input type="text" name="data[<?= $d['id'] ?>][indikator]" 
                                       value="<?= htmlspecialchars($d['indikator']) ?>" 
                                       class="input-text" placeholder="Indikator">
                            </td>
                            <td class="text-center">
                                <input type="text" name="data[<?= $d['id'] ?>][target]" 
                                       value="<?= $target_formatted ?>" 
                                       class="num-input target-input" 
                                       data-id="<?= $d['id'] ?>"
                                       placeholder="0">
                            </td>
                            <td class="text-center">
                                <input type="text" name="data[<?= $d['id'] ?>][realisasi]" 
                                       value="<?= $realisasi_formatted ?>" 
                                       class="num-input realisasi-input" 
                                       data-id="<?= $d['id'] ?>"
                                       placeholder="0">
                            </td>
                            <td class="text-center">
                                <span class="capaian-badge <?= $class ?>" id="capaian-<?= $d['id'] ?>">
                                    <?= number_format($capaian, 2, ',', '.') ?>%
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="predikat-badge <?= $predikat['class'] ?>" id="predikat-<?= $d['id'] ?>">
                                    <?= $predikat['label'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <select name="data[<?= $d['id'] ?>][frekwensi]" class="frekwensi-select">
                                    <option value="Tahunan" <?= $d['frekwensi'] == 'Tahunan' ? 'selected' : '' ?>>Tahunan</option>
                                    <option value="Bulanan" <?= $d['frekwensi'] == 'Bulanan' ? 'selected' : '' ?>>Bulanan</option>
                                    <option value="Bulanan / Tahunan" <?= $d['frekwensi'] == 'Bulanan / Tahunan' ? 'selected' : '' ?>>Bulanan / Tahunan</option>
                                </select>
                            </td>
                            <td>
                                <div class="file-info-cell">
                                    <input type="text" name="data[<?= $d['id'] ?>][sumber_data]" 
                                           value="<?= htmlspecialchars($d['sumber_data'] ?? '') ?>" 
                                           class="num-input sumber-input" 
                                           placeholder="Contoh: https://bps.go.id" 
                                           style="flex:1; min-width:120px; max-width:200px;">
                                    
                                    <div class="file-upload-wrapper" id="uploadWrapper-<?= $d['id'] ?>">
                                        <input type="file" name="file_sumber[<?= $d['id'] ?>]" 
                                               id="fileInput-<?= $d['id'] ?>"
                                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                               data-id="<?= $d['id'] ?>">
                                        <span class="file-label" title="Upload File Sumber">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                    </div>
                                    
                                    <span class="file-preview-text" id="filePreview-<?= $d['id'] ?>">
                                        <i class="fas fa-check-circle"></i> <span id="fileName-<?= $d['id'] ?>"></span>
                                    </span>
                                    
                                    <?php if (!empty($d['file_sumber'])): ?>
                                    <span class="file-name" title="<?= htmlspecialchars($d['file_sumber']) ?>">
                                        <i class="fas fa-file"></i> <?= htmlspecialchars($d['file_sumber']) ?>
                                    </span>
                                    <a href="?delete_file=1&id=<?= $d['id'] ?>&tahun=<?= urlencode($tahun_aktif) ?>" 
                                       class="btn-delete-file" 
                                       onclick="return confirm('Yakin ingin menghapus file ini?')" 
                                       title="Hapus File">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="data[<?= $d['id'] ?>][penanggung_jawab]" 
                                       value="<?= htmlspecialchars($d['penanggung_jawab']) ?>" 
                                       class="input-text" placeholder="Penanggung Jawab">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="form-actions">
                <div class="form-actions-left">
                    <button type="button" class="btn-reset" id="btnReset" title="Reset Semua Data">
                        <i class="fas fa-undo-alt"></i>
                    </button>
                    <span style="font-size:12px; color:#94a3b8;">
                        <i class="fas fa-info-circle"></i> Reset target, realisasi, dan sumber data menjadi kosong
                    </span>
                </div>
                <div class="form-actions-right">
                    <button type="button" class="btn-save" id="btnSave">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
            
            <div class="form-note">
                <i class="fas fa-calculator"></i> Capaian dihitung otomatis = (Realisasi / Target) × 100%
                <span style="margin-left:16px;"><i class="fas fa-upload"></i> Upload file per program (PDF, DOC, XLS, JPG, PNG) Max 10MB</span>
                <span style="margin-left:16px;"><i class="fas fa-link"></i> Sumber Data dapat diisi link</span>
                <span style="margin-left:16px;">
                    <i class="fas fa-tag"></i> 
                    <span style="color:#1d4ed8;">ISTIMEWA</span> &gt; 100% | 
                    <span style="color:#065f46;">BAIK</span> 80-100% | 
                    <span style="color:#92400e;">BUTUH PERBAIKAN</span> 60-80% | 
                    <span style="color:#9a3412;">KURANG</span> 20-60% | 
                    <span style="color:#991b1b;">SANGAT KURANG</span> 0-20%
                </span>
            </div>
            
        </form>
        
    </div>
</main>

<!-- ============================================================
   CONFIRM POPUP - RESET
   ============================================================ -->
<div class="confirm-overlay" id="confirmResetOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-trash" style="color:#dc2626;"></i></div>
        <div class="confirm-title">Reset Semua Data?</div>
        <div class="confirm-text">
            Semua nilai <strong>Target</strong>, <strong>Realisasi</strong>, <strong>Capaian</strong>, dan <strong>Sumber Data</strong> akan direset menjadi <strong>0 / kosong</strong> untuk tahun <?= $tahun_aktif ?>.<br>
            <span style="color:#dc2626;"><i class="fas fa-exclamation-triangle"></i> File yang diupload juga akan dihapus.</span><br>
            Tindakan ini <strong>tidak dapat dibatalkan</strong>.
        </div>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="resetCancel">Batal</button>
            <a href="?tahun=<?= urlencode($tahun_aktif) ?>&reset=1" class="confirm-btn confirm-btn-confirm danger" id="resetConfirm">Ya, Reset</a>
        </div>
    </div>
</div>

<!-- ============================================================
   CONFIRM POPUP - SAVE
   ============================================================ -->
<div class="confirm-overlay" id="confirmSaveOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-save" style="color:#0f3b5e;"></i></div>
        <div class="confirm-title">Simpan Perubahan?</div>
        <div class="confirm-text">
            Apakah Anda yakin ingin menyimpan semua perubahan yang telah dilakukan pada data capaian program tahun <?= $tahun_aktif ?>?
        </div>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="saveCancel">Batal</button>
            <button class="confirm-btn confirm-btn-confirm" id="saveConfirm">Ya, Simpan</button>
        </div>
    </div>
</div>

<!-- ============================================================
   JAVASCRIPT - FLEKSIBEL JUMLAH DESIMAL
   ============================================================ -->
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

var successAlert = document.getElementById('successAlert');
if (successAlert) {
    setTimeout(function() {
        successAlert.style.display = 'none';
    }, 5000);
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.target-input, .realisasi-input').forEach(function(input) {
        input.addEventListener('input', function() {
            hitungCapaian(this.dataset.id);
        });
        input.addEventListener('change', function() {
            hitungCapaian(this.dataset.id);
        });
    });
    
    function getPredikatLabel(capaian) {
        if (capaian > 100) {
            return { label: 'ISTIMEWA', class: 'predikat-istimewa' };
        } else if (capaian > 80) {
            return { label: 'BAIK', class: 'predikat-baik' };
        } else if (capaian > 60) {
            return { label: 'BUTUH PERBAIKAN', class: 'predikat-butuh' };
        } else if (capaian > 20) {
            return { label: 'KURANG', class: 'predikat-kurang' };
        } else if (capaian > 0) {
            return { label: 'SANGAT KURANG', class: 'predikat-sangat' };
        } else {
            return { label: 'BELUM ADA', class: 'predikat-belum' };
        }
    }
    
    function hitungCapaian(id) {
        var targetInput = document.querySelector('input[name="data[' + id + '][target]"]');
        var realisasiInput = document.querySelector('input[name="data[' + id + '][realisasi]"]');
        var capaianEl = document.getElementById('capaian-' + id);
        var predikatEl = document.getElementById('predikat-' + id);
        
        if (!targetInput || !realisasiInput || !capaianEl || !predikatEl) return;
        
        // Ambil nilai dengan format Indonesia (koma sebagai desimal)
        var targetVal = targetInput.value.trim() || '0';
        var realisasiVal = realisasiInput.value.trim() || '0';
        
        // Ubah ke format PHP (titik sebagai desimal)
        var target = parseFloat(targetVal.replace(/\./g, '').replace(',', '.')) || 0;
        var realisasi = parseFloat(realisasiVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        var capaian = 0;
        if (target > 0) {
            capaian = (realisasi / target) * 100;
        }
        
        // Tampilkan capaian dengan 2 desimal
        var capaianFormatted = capaian.toFixed(2).replace('.', ',');
        capaianEl.textContent = capaianFormatted + '%';
        
        capaianEl.className = 'capaian-badge';
        if (capaian >= 80) capaianEl.classList.add('high');
        else if (capaian >= 50) capaianEl.classList.add('medium');
        else if (capaian > 0) capaianEl.classList.add('low');
        else capaianEl.classList.add('zero');
        
        var predikat = getPredikatLabel(capaian);
        predikatEl.textContent = predikat.label;
        predikatEl.className = 'predikat-badge ' + predikat.class;
    }
    
    document.querySelectorAll('input[type="file"][name^="file_sumber"]').forEach(function(input) {
        input.addEventListener('change', function() {
            var id = this.dataset.id;
            var file = this.files[0];
            var previewEl = document.getElementById('filePreview-' + id);
            var fileNameEl = document.getElementById('fileName-' + id);
            
            if (file) {
                previewEl.classList.add('show');
                fileNameEl.textContent = file.name;
                
                var oldFile = this.closest('.file-info-cell').querySelector('.file-name');
                if (oldFile) {
                    oldFile.style.display = 'none';
                }
                var oldDelete = this.closest('.file-info-cell').querySelector('.btn-delete-file');
                if (oldDelete) {
                    oldDelete.style.display = 'none';
                }
                
                var label = this.parentElement.querySelector('.file-label');
                if (label) {
                    label.style.background = '#d1fae5';
                    label.style.color = '#16a34a';
                    label.style.borderColor = '#16a34a';
                    label.innerHTML = '<i class="fas fa-check"></i>';
                }
                
                var statusEl = document.createElement('span');
                statusEl.className = 'upload-status show';
                statusEl.innerHTML = '<i class="fas fa-clock"></i> siap upload';
                statusEl.id = 'status-' + id;
                
                var oldStatus = document.getElementById('status-' + id);
                if (oldStatus) oldStatus.remove();
                
                this.closest('.file-info-cell').appendChild(statusEl);
            }
        });
    });
    
    // ============================================================
    // CONFIRM POPUP - RESET
    // ============================================================
    var btnReset = document.getElementById('btnReset');
    var resetOverlay = document.getElementById('confirmResetOverlay');
    var resetCancel = document.getElementById('resetCancel');
    var resetConfirm = document.getElementById('resetConfirm');
    
    if (btnReset) {
        btnReset.addEventListener('click', function(e) {
            e.preventDefault();
            resetOverlay.classList.add('show');
        });
    }
    
    if (resetCancel) {
        resetCancel.addEventListener('click', function() {
            resetOverlay.classList.remove('show');
        });
    }
    
    if (resetOverlay) {
        resetOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    }
    
    // ============================================================
    // CONFIRM POPUP - SAVE
    // ============================================================
    var btnSave = document.getElementById('btnSave');
    var saveOverlay = document.getElementById('confirmSaveOverlay');
    var saveCancel = document.getElementById('saveCancel');
    var saveConfirm = document.getElementById('saveConfirm');
    var form = document.getElementById('formCapaian');
    
    if (btnSave) {
        btnSave.addEventListener('click', function(e) {
            e.preventDefault();
            
            var hasPendingFile = false;
            document.querySelectorAll('input[type="file"][name^="file_sumber"]').forEach(function(input) {
                if (input.files.length > 0) {
                    hasPendingFile = true;
                }
            });
            
            if (hasPendingFile) {
                document.querySelector('#confirmSaveOverlay .confirm-text').innerHTML = 
                    'Ada file baru yang dipilih tetapi belum tersimpan. Lanjutkan menyimpan?';
            } else {
                document.querySelector('#confirmSaveOverlay .confirm-text').innerHTML = 
                    'Apakah Anda yakin ingin menyimpan semua perubahan yang telah dilakukan pada data capaian program tahun <?= $tahun_aktif ?>?';
            }
            
            saveOverlay.classList.add('show');
        });
    }
    
    if (saveCancel) {
        saveCancel.addEventListener('click', function() {
            saveOverlay.classList.remove('show');
        });
    }
    
    if (saveConfirm) {
        saveConfirm.addEventListener('click', function() {
            saveOverlay.classList.remove('show');
            form.submit();
        });
    }
    
    if (saveOverlay) {
        saveOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    }
});
</script>

</body>
</html>