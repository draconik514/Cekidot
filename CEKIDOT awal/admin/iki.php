<?php
// admin/iki.php - Kelola Dokumen IKI
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// ===== PERBAIKAN PATH =====
// Database ada di SI-PARI/config/database.php
include '../config/database.php';

$success = '';
$error = '';

// --- Upload ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'upload') {
    $judul = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tahun = trim($_POST['tahun'] ?? date('Y'));
    $tipe_konten = $_POST['tipe_konten'] ?? 'file';
    $link_url = trim($_POST['link_url'] ?? '');
    
    if (empty($judul)) {
        $error = 'Judul wajib diisi!';
    } elseif ($tipe_konten == 'file' && (!isset($_FILES['file_dokumen']) || $_FILES['file_dokumen']['error'] == 4)) {
        $error = 'Silakan pilih file untuk diupload!';
    } elseif ($tipe_konten == 'link' && empty($link_url)) {
        $error = 'Silakan masukkan URL/Link dokumen!';
    } elseif ($tipe_konten == 'link' && !filter_var($link_url, FILTER_VALIDATE_URL)) {
        $error = 'URL/Link tidak valid! Pastikan format URL benar (contoh: https://...).';
    } else {
        $file_name = '';
        $file_type = '';
        $file_size = 0;
        
        if ($tipe_konten == 'file' && isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] == 0) {
            $target_dir = '../uploads/iki/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            
            $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['file_dokumen']['name']));
            $target_file = $target_dir . $file_name;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $file_size = $_FILES['file_dokumen']['size'];
            
            // Format yang didukung - termasuk gambar
            $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 
                       'zip', 'rar', '7z', 'jpg', 'jpeg', 'png'];
            
            if (!in_array($file_type, $allowed)) {
                $error = 'Format file tidak didukung! Gunakan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, 7z, JPG, JPEG, PNG.';
            } elseif ($file_size > 50 * 1024 * 1024) {
                $error = 'Ukuran file maksimal 50MB!';
            } elseif (!move_uploaded_file($_FILES['file_dokumen']['tmp_name'], $target_file)) {
                $error = 'Gagal mengupload file!';
            }
        }
        
        if (empty($error)) {
            $stmt = $pdo->query("SELECT IFNULL(MAX(urutan), 0) + 1 AS next_urutan FROM dokumen_iki WHERE tahun = '$tahun'");
            $row = $stmt->fetch();
            $next_urutan = $row['next_urutan'] ?? 1;
            
            $stmt = $pdo->prepare("INSERT INTO dokumen_iki 
                (judul, deskripsi, file_dokumen, tipe_konten, link_url, file_type, file_size, tahun, urutan) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $judul, 
                $deskripsi, 
                $file_name, 
                $tipe_konten, 
                $link_url, 
                $file_type, 
                $file_size, 
                $tahun, 
                $next_urutan
            ]);
            $success = 'Dokumen berhasil ' . ($tipe_konten == 'file' ? 'diupload' : 'ditambahkan') . '!';
        }
    }
}

// ============================================================
// EDIT - FIX: TIDAK WAJIB UPLOAD FILE
// ============================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id']) && $_POST['action'] == 'edit') {
    $id = (int)$_POST['edit_id'];
    $judul = trim($_POST['edit_judul'] ?? '');
    $deskripsi = trim($_POST['edit_deskripsi'] ?? '');
    $tahun = trim($_POST['edit_tahun'] ?? date('Y'));
    $tipe_konten = $_POST['edit_tipe_konten'] ?? 'file';
    $link_url = trim($_POST['edit_link_url'] ?? '');
    
    if (empty($judul)) {
        $error = 'Judul wajib diisi!';
    } elseif ($tipe_konten == 'link' && empty($link_url)) {
        $error = 'Silakan masukkan URL/Link dokumen!';
    } elseif ($tipe_konten == 'link' && !filter_var($link_url, FILTER_VALIDATE_URL)) {
        $error = 'URL/Link tidak valid!';
    } else {
        // Ambil data lama
        $stmt = $pdo->prepare("SELECT * FROM dokumen_iki WHERE id = ?");
        $stmt->execute([$id]);
        $old = $stmt->fetch();
        
        if (!$old) {
            $error = 'Dokumen tidak ditemukan!';
        } else {
            // Ambil data lama sebagai default
            $file_name = $old['file_dokumen'] ?? '';
            $file_type = $old['file_type'] ?? '';
            $file_size = $old['file_size'] ?? 0;
            $old_link_url = $old['link_url'] ?? '';
            
            // Cek apakah ada file baru yang diupload
            $file_uploaded = isset($_FILES['edit_file']) && $_FILES['edit_file']['error'] == 0 && !empty($_FILES['edit_file']['name']);
            
            // === PROSES UPLOAD FILE BARU (JIKA ADA) ===
            if ($tipe_konten == 'file' && $file_uploaded) {
                $target_dir = '../uploads/iki/';
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                
                $new_file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['edit_file']['name']));
                $target_file = $target_dir . $new_file_name;
                $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $file_size = $_FILES['edit_file']['size'];
                
                $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 
                           'zip', 'rar', '7z', 'jpg', 'jpeg', 'png'];
                
                if (!in_array($file_type, $allowed)) {
                    $error = 'Format file tidak didukung!';
                } elseif ($file_size > 50 * 1024 * 1024) {
                    $error = 'Ukuran file maksimal 50MB!';
                } elseif (move_uploaded_file($_FILES['edit_file']['tmp_name'], $target_file)) {
                    // Hapus file lama jika ada dan berbeda
                    if (!empty($old['file_dokumen']) && $old['file_dokumen'] != $new_file_name) {
                        $old_path = '../uploads/iki/' . $old['file_dokumen'];
                        if (file_exists($old_path)) unlink($old_path);
                    }
                    $file_name = $new_file_name;
                } else {
                    $error = 'Gagal mengupload file baru!';
                }
            }
            
            // === PROSES LINK ===
            if ($tipe_konten == 'link' && !empty($link_url)) {
                // Jika pindah dari file ke link, hapus file fisik
                if ($old['tipe_konten'] == 'file' && !empty($old['file_dokumen'])) {
                    $old_path = '../uploads/iki/' . $old['file_dokumen'];
                    if (file_exists($old_path)) unlink($old_path);
                }
                $file_name = $link_url;
                $file_type = '';
                $file_size = 0;
            }
            
            // === JIKA PINDAH DARI LINK KE FILE TANPA UPLOAD ===
            if ($tipe_konten == 'file' && !$file_uploaded && empty($file_name)) {
                $error = 'Silakan upload file! (Tidak ada file yang tersimpan)';
            }
            
            // === UPDATE DATABASE ===
            if (empty($error)) {
                $stmt = $pdo->prepare("UPDATE dokumen_iki 
                    SET judul = ?, deskripsi = ?, file_dokumen = ?, tipe_konten = ?, 
                        link_url = ?, file_type = ?, file_size = ?, tahun = ? 
                    WHERE id = ?");
                $stmt->execute([
                    $judul, 
                    $deskripsi, 
                    $file_name, 
                    $tipe_konten, 
                    ($tipe_konten == 'link' ? $link_url : null), 
                    $file_type, 
                    $file_size, 
                    $tahun, 
                    $id
                ]);
                $success = 'Dokumen berhasil diupdate!';
            }
        }
    }
}

// --- Delete ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT file_dokumen, tipe_konten FROM dokumen_iki WHERE id = ?");
    $stmt->execute([$id]);
    $dok = $stmt->fetch();
    if ($dok) {
        if ($dok['tipe_konten'] == 'file' && !empty($dok['file_dokumen'])) {
            $file_path = '../uploads/iki/' . $dok['file_dokumen'];
            if (file_exists($file_path)) unlink($file_path);
        }
        $pdo->prepare("DELETE FROM dokumen_iki WHERE id = ?")->execute([$id]);
        $success = 'Dokumen berhasil dihapus!';
    }
    header('Location: iki.php?tahun=' . ($_GET['tahun'] ?? date('Y')));
    exit;
}

// --- Toggle Status ---
if (isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    $stmt = $pdo->prepare("SELECT status FROM dokumen_iki WHERE id = ?");
    $stmt->execute([$id]);
    $dok = $stmt->fetch();
    if ($dok) {
        $new_status = $dok['status'] == 'aktif' ? 'nonaktif' : 'aktif';
        $pdo->prepare("UPDATE dokumen_iki SET status = ? WHERE id = ?")->execute([$new_status, $id]);
        $success = 'Status dokumen berhasil diubah!';
    }
    header('Location: iki.php?tahun=' . ($_GET['tahun'] ?? date('Y')));
    exit;
}

// ===== FILTER TAHUN =====
$tahun_aktif = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
$tahun_list = range(2025, 2030);

if (!in_array($tahun_aktif, $tahun_list)) {
    $tahun_aktif = $tahun_list[0];
}

$stmt = $pdo->prepare("SELECT * FROM dokumen_iki WHERE tahun = ? ORDER BY urutan");
$stmt->execute([$tahun_aktif]);
$dokumen = $stmt->fetchAll();

$total_baru = $pdo->query("SELECT COUNT(*) FROM surat_masuk WHERE status='baru'")->fetchColumn();
$admin_nama = $_SESSION['admin_nama'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola IKI - CEKIDOT</title>
    <link rel="icon" href="assets/img/logo-sulteng.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
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

        .sidebar-nav { flex: 1; padding: 0 12px 12px; }
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
            margin-bottom: 20px;
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
        
        /* ============================================================
        FILTER TAHUN - PREMIUM & ELEGAN
        ============================================================ */
        .filter-tahun {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 28px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 6px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 4px 24px rgba(0, 0, 0, 0.04),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            justify-content: center;
            flex-wrap: nowrap;
            position: relative;
            overflow: visible;
        }

        .filter-tahun::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 17px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(234, 179, 8, 0.15), transparent 50%, rgba(15, 59, 94, 0.08));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .filter-tahun .btn-tahun {
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 10px;
            margin: 0 2px;
            position: relative;
        }

        .filter-tahun .btn-tahun:hover:not(:disabled) {
            background: rgba(15, 59, 94, 0.06);
            color: #0f3b5e;
            transform: scale(1.08);
        }

        .filter-tahun .btn-tahun:disabled {
            opacity: 0.2;
            cursor: not-allowed;
            transform: none !important;
        }

        .filter-tahun .btn-tahun .tooltip {
            position: absolute;
            bottom: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%) scale(0.8);
            background: rgba(15, 59, 94, 0.9);
            backdrop-filter: blur(10px);
            color: #fff;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .filter-tahun .btn-tahun:hover .tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) scale(1);
        }

        .filter-tahun .btn-tahun .tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: rgba(15, 59, 94, 0.9);
        }

        .filter-tahun .tahun-items {
            display: flex;
            align-items: center;
            gap: 3px;
            flex: 1;
            justify-content: center;
            padding: 0 8px;
        }

        .filter-tahun .tahun-items .tahun-item {
            padding: 7px 16px;
            border: none;
            background: transparent;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-family: 'Inter', sans-serif;
            position: relative;
            min-width: 52px;
            text-align: center;
            text-decoration: none;
            letter-spacing: 0.3px;
        }

        .filter-tahun .tahun-items .tahun-item .year-label {
            display: block;
            font-weight: 500;
        }

        .filter-tahun .tahun-items .tahun-item .year-count {
            display: block;
            font-size: 9px;
            opacity: 0.4;
            font-weight: 400;
            margin-top: 1px;
            transition: all 0.3s;
        }

        .filter-tahun .tahun-items .tahun-item:hover {
            color: #0f3b5e;
            background: rgba(15, 59, 94, 0.05);
            transform: translateY(-2px);
        }

        .filter-tahun .tahun-items .tahun-item:hover .year-count {
            opacity: 0.7;
        }

        .filter-tahun .tahun-items .tahun-item.active {
            background: linear-gradient(135deg, #0f3b5e, #1a5a7a);
            color: #ffffff;
            box-shadow: 
                0 4px 16px rgba(15, 59, 94, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .filter-tahun .tahun-items .tahun-item.active .year-count {
            opacity: 0.7;
            color: rgba(255, 255, 255, 0.7);
        }

        .filter-tahun .tahun-items .tahun-item.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: #eab308;
            border-radius: 2px;
            animation: activeLine 0.4s ease-out;
        }

        @keyframes activeLine {
            from {
                width: 0;
                opacity: 0;
            }
            to {
                width: 20px;
                opacity: 1;
            }
        }

        .filter-tahun .tahun-range-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #94a3b8;
            padding: 0 16px;
            font-weight: 400;
            letter-spacing: 0.3px;
            flex-shrink: 0;
            border-left: 1px solid rgba(0, 0, 0, 0.06);
            margin-left: 4px;
            padding-left: 16px;
            font-variant-numeric: tabular-nums;
        }

        .filter-tahun .tahun-range-label i {
            font-size: 12px;
            opacity: 0.4;
            color: #eab308;
        }

        .filter-tahun .tahun-range-label .range-arrow {
            opacity: 0.3;
            margin: 0 4px;
        }

        /* ----- Tahun Active Badge ----- */
        .filter-tahun .active-year-badge {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 4px 14px 4px 18px;
            background: rgba(234, 179, 8, 0.1);
            border: 1px solid rgba(234, 179, 8, 0.15);
            border-radius: 20px;
            font-size: 11px;
            color: #eab308;
            font-weight: 500;
            flex-shrink: 0;
            margin-left: 8px;
        }

        .filter-tahun .active-year-badge .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #eab308;
            animation: blinkDot 1.5s ease-in-out infinite;
        }

        @media (min-width: 769px) {
            .filter-tahun .active-year-badge {
                display: flex;
            }
        }

        /* ----- Responsive Filter Tahun ----- */
        @media (max-width: 992px) {
            .filter-tahun .tahun-items .tahun-item {
                padding: 5px 12px;
                font-size: 13px;
                min-width: 44px;
            }
            .filter-tahun .tahun-range-label {
                font-size: 10px;
                padding: 0 10px;
                padding-left: 12px;
            }
            .filter-tahun .active-year-badge {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .filter-tahun {
                flex-wrap: wrap;
                padding: 4px;
                gap: 2px;
                border-radius: 12px;
            }
            .filter-tahun .btn-tahun {
                width: 34px;
                height: 34px;
                font-size: 13px;
                margin: 0 1px;
            }
            .filter-tahun .tahun-items {
                flex-wrap: wrap;
                gap: 2px;
                padding: 2px;
            }
            .filter-tahun .tahun-items .tahun-item {
                padding: 4px 10px;
                font-size: 12px;
                min-width: 36px;
                border-radius: 8px;
            }
            .filter-tahun .tahun-items .tahun-item .year-count {
                font-size: 8px;
            }
            .filter-tahun .tahun-range-label {
                font-size: 10px;
                padding: 0 8px;
                padding-left: 10px;
                border-left: none;
                margin-left: 0;
                padding-left: 0;
                border-top: 1px solid rgba(0, 0, 0, 0.04);
                padding-top: 6px;
                margin-top: 2px;
                width: 100%;
                justify-content: center;
            }
            .filter-tahun .active-year-badge {
                display: none;
            }
            .filter-tahun .tahun-items .tahun-item.active::after {
                width: 14px;
                bottom: -1px;
            }
            @keyframes activeLine {
                from { width: 0; opacity: 0; }
                to { width: 14px; opacity: 1; }
            }
        }

        @media (max-width: 480px) {
            .filter-tahun .btn-tahun {
                width: 30px;
                height: 30px;
                font-size: 11px;
            }
            .filter-tahun .tahun-items .tahun-item {
                padding: 3px 7px;
                font-size: 11px;
                min-width: 30px;
                border-radius: 6px;
            }
            .filter-tahun .tahun-items .tahun-item .year-count {
                font-size: 7px;
            }
            .filter-tahun .tahun-range-label {
                font-size: 9px;
                padding: 4px 0 0;
            }
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
        
        .upload-form {
            background: #f8fafc;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid #e8ecf1;
        }
        .upload-form .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .upload-form .form-grid .full-width {
            grid-column: 1 / -1;
        }
        .upload-form .form-group { margin-bottom: 0; }
        .upload-form .form-group label {
            font-weight: 600;
            font-size: 13px;
            display: block;
            margin-bottom: 4px;
            color: #1e293b;
        }
        .upload-form .form-group label .required {
            color: #ef4444;
        }
        .upload-form .form-group label .optional {
            color: #94a3b8;
            font-weight: 400;
            font-size: 11px;
        }
        .upload-form .form-group input[type="text"],
        .upload-form .form-group textarea,
        .upload-form .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.3s;
        }
        .upload-form .form-group input[type="text"]:focus,
        .upload-form .form-group textarea:focus,
        .upload-form .form-group select:focus {
            outline: none;
            border-color: #0f3b5e;
        }
        .upload-form .form-group textarea {
            min-height: 38px;
            resize: vertical;
        }
        .upload-form .form-group select {
            appearance: auto;
        }
        
        .tipe-konten-toggle {
            display: flex;
            gap: 0;
            background: #e8ecf1;
            border-radius: 8px;
            padding: 3px;
            margin-bottom: 4px;
        }
        .tipe-konten-toggle .toggle-btn {
            flex: 1;
            padding: 7px 16px;
            border: none;
            background: transparent;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .tipe-konten-toggle .toggle-btn i {
            font-size: 13px;
        }
        .tipe-konten-toggle .toggle-btn.active {
            background: #fff;
            color: #0f3b5e;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .tipe-konten-toggle .toggle-btn:hover:not(.active) {
            color: #1e293b;
        }
        
        .upload-form .file-upload-wrapper {
            position: relative;
            width: 100%;
        }
        .upload-form .file-upload-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
            top: 0;
            left: 0;
        }
        .upload-form .file-upload-wrapper .file-label {
            display: block;
            padding: 8px 12px;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            min-height: 38px;
            line-height: 20px;
        }
        .upload-form .file-upload-wrapper:hover .file-label {
            background: #f8fafc;
            border-color: #0f3b5e;
        }
        .upload-form .file-upload-wrapper .file-label i {
            margin-right: 6px;
            color: #0f3b5e;
        }
        
        .upload-form .file-preview-wrapper {
            display: none;
            align-items: center;
            gap: 10px;
            background: #f1f5f9;
            padding: 6px 12px 6px 16px;
            border-radius: 8px;
            margin-top: 6px;
            border: 1px solid #e2e8f0;
        }
        .upload-form .file-preview-wrapper.show {
            display: flex;
        }
        .upload-form .file-preview-wrapper .file-icon {
            font-size: 18px;
            color: #0f3b5e;
        }
        .upload-form .file-preview-wrapper .file-name {
            flex: 1;
            font-size: 13px;
            color: #1e293b;
            word-break: break-all;
        }
        .upload-form .file-preview-wrapper .file-size {
            font-size: 11px;
            color: #94a3b8;
        }
        .upload-form .file-preview-wrapper .btn-remove-file {
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 2px 10px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
            line-height: 24px;
        }
        .upload-form .file-preview-wrapper .btn-remove-file:hover {
            background: #b91c1c;
        }
        
        .upload-form .link-input-wrapper {
            display: none;
        }
        .upload-form .link-input-wrapper.show {
            display: block;
        }
        .upload-form .link-input-wrapper input {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.3s;
        }
        .upload-form .link-input-wrapper input:focus {
            outline: none;
            border-color: #0f3b5e;
        }
        .upload-form .link-input-wrapper .link-hint {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
            display: block;
        }
        
        .upload-form .form-actions {
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn-upload {
            padding: 8px 28px;
            background: #0f3b5e;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            height: 40px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-upload:hover {
            background: #0a2a44;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15,59,94,0.25);
        }
        
        .upload-form .form-group .format-hint {
            font-size: 11px;
            color: #94a3b8;
            display: block;
            margin-top: 4px;
        }
        .upload-form .form-group .format-hint i {
            margin-right: 4px;
        }
        
        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            background: #fff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 700px;
        }
        table th {
            text-align: left;
            padding: 12px 16px;
            background: #f8fafc;
            font-weight: 600;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        table tr:hover td {
            background: #f8fafc;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .status-badge.aktif {
            background: #d1fae5;
            color: #065f46;
        }
        .status-badge.nonaktif {
            background: #fef2f2;
            color: #991b1b;
        }
        
        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
        }
        .type-badge.file {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .type-badge.link {
            background: #fef3c7;
            color: #b45309;
        }
        
        .action-group {
            display: flex;
            gap: 4px;
            align-items: center;
            flex-wrap: wrap;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 13px;
            text-decoration: none;
        }
        .btn-action:hover {
            transform: scale(1.08);
        }
        .btn-action.btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .btn-action.btn-edit:hover {
            background: #93c5fd;
        }
        .btn-action.btn-view {
            background: #d1fae5;
            color: #065f46;
        }
        .btn-action.btn-view:hover {
            background: #a7f3d0;
        }
        .btn-action.btn-delete {
            background: #fef2f2;
            color: #991b1b;
        }
        .btn-action.btn-delete:hover {
            background: #fecaca;
        }
        .btn-action.btn-toggle {
            background: #f1f5f9;
            color: #64748b;
        }
        .btn-action.btn-toggle:hover {
            background: #e2e8f0;
        }
        .btn-action.btn-toggle.aktif {
            background: #d1fae5;
            color: #065f46;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }
        .empty-state i {
            font-size: 40px;
            opacity: 0.3;
            display: block;
            margin-bottom: 12px;
        }
        .empty-state h3 {
            font-size: 17px;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.show {
            display: flex;
        }
        .modal-box {
            background: #fff;
            border-radius: 20px;
            max-width: 720px;
            width: 100%;
            max-height: 92vh;
            overflow-y: auto;
            padding: 32px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.35);
            animation: modalIn 0.3s ease-out;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-box .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e8ecf1;
        }
        .modal-box .modal-header h3 {
            font-size: 20px;
            color: #0f3b5e;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-box .modal-header h3 i { color: #eab308; }
        .modal-box .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.3s;
            padding: 0 8px;
            border-radius: 6px;
            line-height: 1;
        }
        .modal-box .modal-close:hover {
            color: #dc2626;
            background: #fef2f2;
        }
        
        .modal-box .edit-form .form-group {
            margin-bottom: 14px;
        }
        .modal-box .edit-form .form-group label {
            font-weight: 600;
            font-size: 13px;
            display: block;
            margin-bottom: 4px;
            color: #1e293b;
        }
        .modal-box .edit-form .form-group input,
        .modal-box .edit-form .form-group textarea,
        .modal-box .edit-form .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
        }
        .modal-box .edit-form .form-group input:focus,
        .modal-box .edit-form .form-group textarea:focus {
            outline: none;
            border-color: #0f3b5e;
        }
        .modal-box .edit-form .form-group textarea {
            min-height: 60px;
            resize: vertical;
        }
        .modal-box .edit-form .form-group .file-info {
            font-size: 13px;
            color: #64748b;
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        .modal-box .edit-form .form-group .file-info i { margin-right: 6px; }
        
        .modal-box .edit-form .file-preview-wrapper {
            display: none;
            align-items: center;
            gap: 10px;
            background: #f1f5f9;
            padding: 6px 12px 6px 16px;
            border-radius: 8px;
            margin-top: 6px;
            border: 1px solid #e2e8f0;
        }
        .modal-box .edit-form .file-preview-wrapper.show {
            display: flex;
        }
        
        .modal-box .edit-form .file-upload-wrapper {
            position: relative;
            width: 100%;
        }
        .modal-box .edit-form .file-upload-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
            top: 0;
            left: 0;
        }
        .modal-box .edit-form .file-upload-wrapper .file-label {
            display: block;
            padding: 8px 12px;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            min-height: 38px;
            line-height: 20px;
        }
        .modal-box .edit-form .file-upload-wrapper:hover .file-label {
            background: #f8fafc;
            border-color: #0f3b5e;
        }
        .modal-box .edit-form .file-upload-wrapper .file-label i {
            margin-right: 6px;
            color: #0f3b5e;
        }
        
        .modal-box .edit-form .link-input-wrapper {
            display: none;
        }
        .modal-box .edit-form .link-input-wrapper.show {
            display: block;
        }
        .modal-box .edit-form .link-input-wrapper input {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            background: #fff;
        }
        .modal-box .edit-form .link-input-wrapper input:focus {
            outline: none;
            border-color: #0f3b5e;
        }
        
        .modal-box .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e8ecf1;
        }
        .modal-box .modal-actions .btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .modal-box .modal-actions .btn-primary {
            background: #0f3b5e;
            color: #fff;
        }
        .modal-box .modal-actions .btn-primary:hover {
            background: #0a2a44;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15,59,94,0.3);
        }
        .modal-box .modal-actions .btn-secondary {
            background: #f1f5f9;
            color: #1e293b;
        }
        .modal-box .modal-actions .btn-secondary:hover {
            background: #e2e8f0;
        }
        .modal-box .modal-actions .btn-danger {
            background: #dc2626;
            color: #fff;
        }
        .modal-box .modal-actions .btn-danger:hover {
            background: #b91c1c;
        }
        
        .modal-box .view-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 24px;
            background: #f8fafc;
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 16px;
        }
        .modal-box .view-info .item {
            display: flex;
            flex-direction: column;
        }
        .modal-box .view-info .item .label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .modal-box .view-info .item .value {
            font-size: 14px;
            color: #1e293b;
            font-weight: 500;
            word-break: break-all;
        }
        .modal-box .view-preview {
            background: #f1f5f9;
            border-radius: 10px;
            margin-bottom: 16px;
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .modal-box .view-preview iframe {
            width: 100%;
            height: 350px;
            border: none;
            border-radius: 10px;
        }
        .modal-box .view-preview .no-preview {
            text-align: center;
            padding: 30px 20px;
            color: #94a3b8;
        }
        .modal-box .view-preview .no-preview i {
            font-size: 48px;
            display: block;
            margin-bottom: 12px;
            opacity: 0.3;
        }
        .modal-box .view-preview .no-preview .ext {
            font-size: 16px;
            font-weight: 500;
            color: #1e293b;
        }
        .modal-box .security-warning {
            background: #fef3c7;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid #fde68a;
        }
        .modal-box .security-warning i {
            color: #b45309;
            font-size: 18px;
            margin-top: 2px;
        }
        .modal-box .security-warning div {
            font-size: 13px;
            color: #92400e;
        }
        .modal-box .security-warning div strong {
            display: block;
        }
        
        .modal-box.confirm-box {
            max-width: 420px;
            text-align: center;
        }
        .modal-box.confirm-box .confirm-icon {
            font-size: 56px;
            color: #dc2626;
            margin-bottom: 12px;
        }
        .modal-box.confirm-box h3 {
            font-size: 20px;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .modal-box.confirm-box p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .modal-box.confirm-box .modal-actions {
            justify-content: center;
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }
        
        @media (max-width: 992px) {
            .upload-form .form-grid {
                grid-template-columns: 1fr;
            }
            .upload-form .form-grid .full-width {
                grid-column: 1;
            }
            .modal-box .view-info { grid-template-columns: 1fr; }
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
            .modal-box { padding: 20px; }
            .modal-box .view-preview iframe { height: 200px; }
            .filter-tahun { flex-wrap: wrap; }
            .filter-tahun .tahun-label { font-size: 17px; min-width: 60px; }
            .filter-tahun .tahun-range { font-size: 11px; }
        }
        @media (max-width: 480px) {
            .sidebar-brand .brand-logo { width: 28px; height: 28px; padding: 2px; }
            .sidebar-brand .brand-text h2 { font-size: 13px; }
            .nav-list li a { padding: 4px 8px; font-size: 11px; }
            .nav-list li a i { font-size: 12px; }
            .header h1 { font-size: 20px; }
            .main-content .overlay { padding: 12px; }
            table { font-size: 12px; min-width: 500px; }
            table th, table td { padding: 8px 10px; }
            .action-group { gap: 3px; }
            .btn-action { width: 28px; height: 28px; font-size: 12px; }
            .filter-tahun { padding: 8px 14px; }
            .filter-tahun .btn-tahun { width: 32px; height: 32px; font-size: 14px; }
            .upload-form { padding: 14px 16px; }
            .tipe-konten-toggle .toggle-btn { font-size: 11px; padding: 6px 10px; }
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<!-- MAIN CONTENT -->
<main class="main-content">
    <div class="overlay">
        
        <!-- HEADER -->
        <div class="header">
            <div>
                <h1><i class="fas fa-user-check"></i> Kelola Dokumen IKI</h1>
                <span class="info">Total: <?= count($dokumen) ?> dokumen untuk tahun <?= $tahun_aktif ?></span>
            </div>
            <div class="admin-welcome">
                <i class="fas fa-user-circle"></i> <?= htmlspecialchars($admin_nama) ?>
            </div>
        </div>
        
        <!-- ALERT -->
        <?php if (isset($success) && !empty($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $success ?>
        </div>
        <?php endif; ?>
        <?php if (isset($error) && !empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= $error ?>
        </div>
        <?php endif; ?>
        
        <!-- FILTER TAHUN - PREMIUM -->
        <div class="filter-tahun">
            <!-- Tombol Kiri -->
            <button class="btn-tahun" onclick="changeYear(<?= $tahun_aktif - 1 ?>)" <?= $tahun_aktif <= 2025 ? 'disabled' : '' ?>>
                <i class="fas fa-chevron-left"></i>
                <span class="tooltip">Tahun Sebelumnya</span>
            </button>
            
            <!-- Daftar Tahun -->
            <div class="tahun-items" id="tahunItems">
                <?php foreach(range(2025, 2030) as $t): 
                    // Hitung jumlah dokumen per tahun
                    $stmt_count = $pdo->prepare("SELECT COUNT(*) as total FROM dokumen_iki WHERE tahun = ? AND status='aktif'");
                    $stmt_count->execute([$t]);
                    $count = $stmt_count->fetch()['total'];
                    $is_active = $t == $tahun_aktif;
                ?>
                <a href="?tahun=<?= $t ?>" class="tahun-item <?= $is_active ? 'active' : '' ?>" data-year="<?= $t ?>">
                    <span class="year-label"><?= $t ?></span>
                    <span class="year-count"><?= $count ?> dokumen</span>
                </a>
                <?php endforeach; ?>
            </div>
            
            <!-- Range Label -->
            <span class="tahun-range-label">
                <i class="fas fa-calendar-alt"></i>
                2025 <span class="range-arrow">—</span> 2030
            </span>
            
            <!-- Active Badge -->
            <div class="active-year-badge">
                <span class="badge-dot"></span>
                <?= $tahun_aktif ?>
            </div>
            
            <!-- Tombol Kanan -->
            <button class="btn-tahun" onclick="changeYear(<?= $tahun_aktif + 1 ?>)" <?= $tahun_aktif >= 2030 ? 'disabled' : '' ?>>
                <i class="fas fa-chevron-right"></i>
                <span class="tooltip">Tahun Berikutnya</span>
            </button>
        </div>
        
        <!-- UPLOAD FORM -->
        <div class="upload-form">
            <form method="post" enctype="multipart/form-data" id="uploadForm">
                <input type="hidden" name="action" value="upload">
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Judul Dokumen <span class="required">*</span></label>
                        <input type="text" name="judul" placeholder="Masukkan judul dokumen" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Tahun <span class="required">*</span></label>
                        <select name="tahun" required>
                            <?php foreach(range(2025, 2030) as $t): ?>
                            <option value="<?= $t ?>" <?= $tahun_aktif == $t ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Tipe Konten <span class="required">*</span></label>
                        <div class="tipe-konten-toggle" id="tipeKontenToggle">
                            <button type="button" class="toggle-btn active" data-value="file">
                                <i class="fas fa-upload"></i> Upload File
                            </button>
                            <button type="button" class="toggle-btn" data-value="link">
                                <i class="fas fa-link"></i> URL/Link
                            </button>
                        </div>
                        <input type="hidden" name="tipe_konten" id="tipeKontenInput" value="file">
                    </div>
                    
                    <!-- File Upload Section -->
                    <div class="form-group full-width" id="fileUploadSection">
                        <label>Upload File <span class="required">*</span> <span class="optional">(Maks 50MB)</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="file_dokumen" id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.jpg,.jpeg,.png">
                            <span class="file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih File (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, 7z, JPG, JPEG, PNG)</span>
                        </div>
                        <span class="format-hint"><i class="fas fa-info-circle"></i> Format didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, 7z, JPG, JPEG, PNG | Maks 50MB</span>
                        <div class="file-preview-wrapper" id="filePreview">
                            <span class="file-icon"><i class="fas fa-file"></i></span>
                            <span class="file-name" id="fileName">nama-file.pdf</span>
                            <span class="file-size" id="fileSize">(2.4 MB)</span>
                            <button type="button" class="btn-remove-file" id="btnRemoveFile">
                                <i class="fas fa-times"></i> Hapus
                            </button>
                        </div>
                    </div>
                    
                    <!-- Link URL Section -->
                    <div class="form-group full-width link-input-wrapper" id="linkInputSection">
                        <label>URL/Link Dokumen <span class="required">*</span></label>
                        <input type="text" name="link_url" id="linkUrlInput" placeholder="https://drive.google.com/... atau https://...">
                        <span class="link-hint"><i class="fas fa-external-link-alt"></i> Masukkan URL lengkap dokumen (Google Drive, OneDrive, Dropbox, atau URL lainnya)</span>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Deskripsi <span class="optional">(Opsional)</span></label>
                        <textarea name="deskripsi" placeholder="Deskripsi singkat dokumen..." rows="2"></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-upload" id="btnUpload">
                        <i class="fas fa-upload"></i> Tambah Dokumen
                    </button>
                    <span style="font-size:12px; color:#94a3b8;">
                        <i class="fas fa-info-circle"></i> Isi minimal: Judul + File atau Link
                    </span>
                </div>
            </form>
        </div>
        
        <!-- TABLE -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width:35px;">#</th>
                        <th>Judul</th>
                        <th style="width:80px;">Tahun</th>
                        <th style="width:90px;">Tipe</th>
                        <th style="width:80px;">Status</th>
                        <th style="width:130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dokumen)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-file-alt"></i>
                                <h3>Belum Ada Dokumen</h3>
                                <p style="font-size:14px;">Upload dokumen pertama atau tambahkan link untuk tahun <?= $tahun_aktif ?></p>
                            </div>
                        </td>
                    </tr>
                    <?php else: $no=1; foreach($dokumen as $d): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div style="font-weight:500;"><?= htmlspecialchars($d['judul']) ?></div>
                            <?php if ($d['deskripsi']): ?>
                            <div style="font-size:12px; color:#64748b;"><?= htmlspecialchars(substr($d['deskripsi'], 0, 50)) . (strlen($d['deskripsi']) > 50 ? '...' : '') ?></div>
                            <?php endif; ?>
                        </td>
                        <td><span style="background:#f1f5f9; padding:2px 12px; border-radius:12px; font-size:13px;"><?= $d['tahun'] ?></span></td>
                        <td>
                            <span class="type-badge <?= $d['tipe_konten'] ?? 'file' ?>">
                                <i class="fas <?= ($d['tipe_konten'] ?? 'file') == 'file' ? 'fa-upload' : 'fa-link' ?>"></i>
                                <?= ($d['tipe_konten'] ?? 'file') == 'file' ? 'File' : 'Link' ?>
                            </span>
                            <?php if (($d['tipe_konten'] ?? 'file') == 'file' && !empty($d['file_type'])): ?>
                            <span style="font-size:10px; color:#94a3b8; display:block;">.<?= strtoupper($d['file_type']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge <?= $d['status'] ?? 'aktif' ?>">
                                <i class="fas <?= ($d['status'] ?? 'aktif') == 'aktif' ? 'fa-check-circle' : 'fa-circle' ?>"></i>
                                <?= $d['status'] ?? 'aktif' ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <button class="btn-action btn-edit" onclick="openEditModal(
                                    '<?= $d['id'] ?>',
                                    '<?= addslashes(htmlspecialchars($d['judul'])) ?>',
                                    '<?= addslashes(htmlspecialchars($d['deskripsi'] ?? '')) ?>',
                                    '<?= $d['tahun'] ?>',
                                    '<?= $d['tipe_konten'] ?? 'file' ?>',
                                    '<?= addslashes(htmlspecialchars($d['file_dokumen'] ?? '')) ?>',
                                    '<?= addslashes(htmlspecialchars($d['link_url'] ?? '')) ?>'
                                )" title="Edit Dokumen">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn-action btn-view" onclick="openViewModal(
                                    '<?= $d['id'] ?>',
                                    '<?= addslashes(htmlspecialchars($d['judul'])) ?>',
                                    '<?= addslashes(htmlspecialchars($d['deskripsi'] ?? '')) ?>',
                                    '<?= $d['tahun'] ?>',
                                    '<?= $d['tipe_konten'] ?? 'file' ?>',
                                    '<?= addslashes(htmlspecialchars($d['file_dokumen'] ?? '')) ?>',
                                    '<?= addslashes(htmlspecialchars($d['link_url'] ?? '')) ?>',
                                    '<?= $d['status'] ?? 'aktif' ?>'
                                )" title="Lihat Dokumen">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="?toggle_status=<?= $d['id'] ?>&tahun=<?= $tahun_aktif ?>" class="btn-action btn-toggle <?= $d['status'] ?? 'aktif' ?>" title="<?= ($d['status'] ?? 'aktif') == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                    <i class="fas <?= ($d['status'] ?? 'aktif') == 'aktif' ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                </a>
                                <button class="btn-action btn-delete" onclick="openDeleteModal(<?= $d['id'] ?>, '<?= addslashes(htmlspecialchars($d['judul'])) ?>')" title="Hapus Dokumen">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</main>

<!-- MODAL EDIT -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-pen"></i> Edit Dokumen</h3>
            <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form method="post" enctype="multipart/form-data" class="edit-form" id="editForm">
            <input type="hidden" name="edit_id" id="edit_id">
            <input type="hidden" name="action" value="edit">
            
            <div class="form-group">
                <label>Judul Dokumen <span class="required">*</span></label>
                <input type="text" name="edit_judul" id="edit_judul" required>
            </div>
            
            <div class="form-group">
                <label>Tahun <span class="required">*</span></label>
                <select name="edit_tahun" id="edit_tahun" required>
                    <?php foreach(range(2025, 2030) as $t): ?>
                    <option value="<?= $t ?>"><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tipe Konten <span class="required">*</span></label>
                <div class="tipe-konten-toggle" id="editTipeKontenToggle">
                    <button type="button" class="toggle-btn" data-value="file">
                        <i class="fas fa-upload"></i> Upload File
                    </button>
                    <button type="button" class="toggle-btn" data-value="link">
                        <i class="fas fa-link"></i> URL/Link
                    </button>
                </div>
                <input type="hidden" name="edit_tipe_konten" id="editTipeKontenInput" value="file">
            </div>
            
            <div class="form-group" id="editFileSection">
                <label>File Saat Ini</label>
                <div class="file-info" id="edit_file_info">
                    <i class="fas fa-file"></i> <span id="edit_file_name">-</span>
                </div>
                <label style="margin-top:8px;">Ganti File <span class="optional">(Kosongkan jika tidak ingin mengganti)</span></label>
                <div class="file-upload-wrapper">
                    <input type="file" name="edit_file" id="editFileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.jpg,.jpeg,.png">
                    <span class="file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih File Baru</span>
                </div>
                <div class="file-preview-wrapper" id="editFilePreview">
                    <span class="file-icon"><i class="fas fa-file"></i></span>
                    <span class="file-name" id="editFileName">nama-file.pdf</span>
                    <span class="file-size" id="editFileSize">(2.4 MB)</span>
                    <button type="button" class="btn-remove-file" id="editBtnRemoveFile">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div>
                <span class="format-hint"><i class="fas fa-info-circle"></i> Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, 7z, JPG, JPEG, PNG | Maks 50MB</span>
            </div>
            
            <div class="form-group link-input-wrapper" id="editLinkSection">
                <label>URL/Link Dokumen <span class="required">*</span></label>
                <input type="text" name="edit_link_url" id="edit_link_url" placeholder="https://drive.google.com/... atau https://...">
                <span class="link-hint"><i class="fas fa-external-link-alt"></i> Masukkan URL lengkap dokumen</span>
            </div>
            
            <div class="form-group">
                <label>Deskripsi <span class="optional">(Opsional)</span></label>
                <textarea name="edit_deskripsi" id="edit_deskripsi" rows="2"></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL VIEW -->
<div class="modal-overlay" id="viewModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-eye"></i> <span id="viewTitle">Detail Dokumen</span></h3>
            <button class="modal-close" onclick="closeModal('viewModal')">&times;</button>
        </div>
        
        <div class="view-info" id="viewInfo">
            <div class="item">
                <span class="label">Judul</span>
                <span class="value" id="viewJudul">-</span>
            </div>
            <div class="item">
                <span class="label">Tahun</span>
                <span class="value" id="viewTahun">-</span>
            </div>
            <div class="item">
                <span class="label">Tipe</span>
                <span class="value" id="viewTipe">-</span>
            </div>
            <div class="item">
                <span class="label">Status</span>
                <span class="value" id="viewStatus">-</span>
            </div>
            <div class="item" style="grid-column: 1 / -1;">
                <span class="label">Deskripsi</span>
                <span class="value" id="viewDeskripsi">-</span>
            </div>
            <div class="item" style="grid-column: 1 / -1;">
                <span class="label">Nama File / Link</span>
                <span class="value" id="viewFileName">-</span>
            </div>
        </div>
        
        <div class="view-preview" id="viewPreview">
            <div class="no-preview">
                <i class="fas fa-file"></i>
                <span class="ext">Memuat file...</span>
            </div>
        </div>
        
        <div class="security-warning">
            <i class="fas fa-shield-alt"></i>
            <div>
                <strong>⚠️ Peringatan Keamanan</strong>
                Pastikan file/link aman sebelum diakses. Scan file terlebih dahulu jika ragu.
            </div>
        </div>
        
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal('viewModal')"><i class="fas fa-times"></i> Tutup</button>
            <a href="#" class="btn btn-primary" id="viewDownloadBtn" target="_blank"><i class="fas fa-external-link-alt"></i> Buka / Download</a>
        </div>
    </div>
</div>

<!-- MODAL CONFIRM DELETE -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box confirm-box">
        <div class="confirm-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Hapus Dokumen?</h3>
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus dokumen ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Batal</button>
            <a href="#" class="btn btn-danger" id="deleteConfirmBtn"><i class="fas fa-trash"></i> Hapus</a>
        </div>
    </div>
</div>

<!-- JAVASCRIPT -->
<script>
// ===== SIDEBAR CLOCK =====
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

// ===== MODAL HELPERS =====
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow = 'auto';
}

function openModal(id) {
    document.getElementById(id).classList.add('show');
    document.body.style.overflow = 'hidden';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.show').forEach(function(el) {
            el.classList.remove('show');
            document.body.style.overflow = 'auto';
        });
    }
});

document.querySelectorAll('.modal-overlay').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    });
});

// ===== GET FILE ICON =====
function getFileIcon(ext) {
    var iconMap = {
        'pdf': 'fa-file-pdf',
        'doc': 'fa-file-word',
        'docx': 'fa-file-word',
        'xls': 'fa-file-excel',
        'xlsx': 'fa-file-excel',
        'ppt': 'fa-file-powerpoint',
        'pptx': 'fa-file-powerpoint',
        'zip': 'fa-file-archive',
        'rar': 'fa-file-archive',
        '7z': 'fa-file-archive',
        'jpg': 'fa-file-image',
        'jpeg': 'fa-file-image',
        'png': 'fa-file-image'
    };
    return iconMap[ext] || 'fa-file';
}

// ===== TIPE KONTEN TOGGLE - UPLOAD FORM =====
document.addEventListener('DOMContentLoaded', function() {
    var toggleButtons = document.querySelectorAll('#tipeKontenToggle .toggle-btn');
    var tipeKontenInput = document.getElementById('tipeKontenInput');
    var fileSection = document.getElementById('fileUploadSection');
    var linkSection = document.getElementById('linkInputSection');
    var fileInput = document.getElementById('fileInput');
    var linkInput = document.getElementById('linkUrlInput');
    
    function setTipeKonten(value) {
        toggleButtons.forEach(function(btn) {
            btn.classList.toggle('active', btn.dataset.value === value);
        });
        tipeKontenInput.value = value;
        
        if (value === 'file') {
            fileSection.style.display = 'block';
            linkSection.classList.remove('show');
            fileInput.required = true;
            linkInput.required = false;
            linkInput.value = '';
        } else {
            fileSection.style.display = 'none';
            linkSection.classList.add('show');
            fileInput.required = false;
            linkInput.required = true;
            fileInput.value = '';
            document.getElementById('filePreview').classList.remove('show');
        }
        
        var fileLabel = fileSection.querySelector('label');
        if (fileLabel) {
            if (value === 'file') {
                fileLabel.innerHTML = 'Upload File <span class="required">*</span> <span class="optional">(Maks 50MB)</span>';
            } else {
                fileLabel.innerHTML = 'Upload File <span class="optional">(Opsional - diabaikan)</span>';
            }
        }
    }
    
    toggleButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            setTipeKonten(this.dataset.value);
        });
    });
    
    setTipeKonten('file');
});

// ===== FILE UPLOAD PREVIEW - Upload Form =====
document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('fileInput');
    var filePreview = document.getElementById('filePreview');
    var fileName = document.getElementById('fileName');
    var fileSize = document.getElementById('fileSize');
    var btnRemoveFile = document.getElementById('btnRemoveFile');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var file = this.files[0];
                var size = (file.size / 1024 / 1024).toFixed(2);
                var ext = file.name.split('.').pop().toLowerCase();
                var icon = getFileIcon(ext);
                
                document.querySelector('#filePreview .file-icon i').className = 'fas ' + icon;
                fileName.textContent = file.name;
                fileSize.textContent = '(' + size + ' MB)';
                filePreview.classList.add('show');
            }
        });
    }

    if (btnRemoveFile) {
        btnRemoveFile.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.classList.remove('show');
            fileName.textContent = '';
            fileSize.textContent = '';
        });
    }
});

// ===== FILE UPLOAD PREVIEW - Edit Modal =====
document.addEventListener('DOMContentLoaded', function() {
    var editFileInput = document.getElementById('editFileInput');
    var editFilePreview = document.getElementById('editFilePreview');
    var editFileName = document.getElementById('editFileName');
    var editFileSize = document.getElementById('editFileSize');
    var editBtnRemoveFile = document.getElementById('editBtnRemoveFile');

    if (editFileInput) {
        editFileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var file = this.files[0];
                var size = (file.size / 1024 / 1024).toFixed(2);
                var ext = file.name.split('.').pop().toLowerCase();
                var icon = getFileIcon(ext);
                
                document.querySelector('#editFilePreview .file-icon i').className = 'fas ' + icon;
                editFileName.textContent = file.name;
                editFileSize.textContent = '(' + size + ' MB)';
                editFilePreview.classList.add('show');
            }
        });
    }

    if (editBtnRemoveFile) {
        editBtnRemoveFile.addEventListener('click', function() {
            editFileInput.value = '';
            editFilePreview.classList.remove('show');
            editFileName.textContent = '';
            editFileSize.textContent = '';
        });
    }
});

// ============================================================
// EDIT MODAL - FIX: TIDAK WAJIB UPLOAD FILE
// ============================================================
function openEditModal(id, judul, deskripsi, tahun, tipe_konten, file_dokumen, link_url) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_judul').value = judul;
    document.getElementById('edit_deskripsi').value = deskripsi || '';
    document.getElementById('edit_tahun').value = tahun;
    document.getElementById('edit_file_name').textContent = file_dokumen || 'Tidak ada file';
    document.getElementById('edit_link_url').value = link_url || '';
    
    var tipe = tipe_konten || 'file';
    var editToggle = document.querySelectorAll('#editTipeKontenToggle .toggle-btn');
    editToggle.forEach(function(btn) {
        btn.classList.toggle('active', btn.dataset.value === tipe);
    });
    document.getElementById('editTipeKontenInput').value = tipe;
    
    var editFileSection = document.getElementById('editFileSection');
    var editLinkSection = document.getElementById('editLinkSection');
    var editFileInput = document.getElementById('editFileInput');
    var editLinkInput = document.getElementById('edit_link_url');
    
    if (tipe === 'file') {
        editFileSection.style.display = 'block';
        editLinkSection.classList.remove('show');
        // FILE TIDAK WAJIB UNTUK EDIT
        editFileInput.required = false;
        editLinkInput.required = false;
    } else {
        editFileSection.style.display = 'none';
        editLinkSection.classList.add('show');
        editFileInput.required = false;
        editLinkInput.required = true;
    }
    
    // Reset file preview
    document.getElementById('editFilePreview').classList.remove('show');
    document.getElementById('editFileInput').value = '';
    
    openModal('editModal');
}

// ===== EDIT MODAL - TIPE KONTEN TOGGLE =====
document.addEventListener('DOMContentLoaded', function() {
    var editToggle = document.querySelectorAll('#editTipeKontenToggle .toggle-btn');
    var editTipeInput = document.getElementById('editTipeKontenInput');
    var editFileSection = document.getElementById('editFileSection');
    var editLinkSection = document.getElementById('editLinkSection');
    var editFileInput = document.getElementById('editFileInput');
    var editLinkInput = document.getElementById('edit_link_url');
    
    function setEditTipeKonten(value) {
        editToggle.forEach(function(btn) {
            btn.classList.toggle('active', btn.dataset.value === value);
        });
        editTipeInput.value = value;
        
        if (value === 'file') {
            editFileSection.style.display = 'block';
            editLinkSection.classList.remove('show');
            // FILE TIDAK WAJIB UNTUK EDIT
            editFileInput.required = false;
            editLinkInput.required = false;
        } else {
            editFileSection.style.display = 'none';
            editLinkSection.classList.add('show');
            editFileInput.required = false;
            editLinkInput.required = true;
        }
    }
    
    editToggle.forEach(function(btn) {
        btn.addEventListener('click', function() {
            setEditTipeKonten(this.dataset.value);
        });
    });
});

// ===== VIEW MODAL =====
function openViewModal(id, judul, deskripsi, tahun, tipe_konten, file_dokumen, link_url, status) {
    var tipe = tipe_konten || 'file';
    
    document.getElementById('viewTitle').textContent = judul;
    document.getElementById('viewJudul').textContent = judul;
    document.getElementById('viewTahun').textContent = tahun;
    document.getElementById('viewTipe').innerHTML = 
        '<span class="type-badge ' + tipe + '"><i class="fas ' + (tipe === 'file' ? 'fa-upload' : 'fa-link') + '"></i> ' + 
        (tipe === 'file' ? 'File' : 'Link') + '</span>';
    document.getElementById('viewStatus').innerHTML = 
        '<span class="status-badge ' + (status || 'aktif') + '">' + (status || 'aktif') + '</span>';
    document.getElementById('viewDeskripsi').textContent = deskripsi || '-';
    document.getElementById('viewFileName').textContent = file_dokumen || link_url || '-';
    
    var preview = document.getElementById('viewPreview');
    var downloadBtn = document.getElementById('viewDownloadBtn');
    
    if (tipe === 'file' && file_dokumen) {
        var filePath = '../uploads/iki/' + file_dokumen;
        var ext = (file_dokumen || '').split('.').pop().toLowerCase();
        var isImage = ['jpg','jpeg','png','gif','webp','bmp'].includes(ext);
        var isPDF = ext === 'pdf';
        var isArchive = ['zip','rar','7z','tar','gz','tgz','bz2','xz'].includes(ext);
        var isOffice = ['doc','docx','xls','xlsx','ppt','pptx'].includes(ext);
        
        if (isImage) {
            preview.innerHTML = '<img src="' + filePath + '" style="width:100%; max-height:350px; object-fit:contain; border-radius:8px;" alt="' + file_dokumen + '" onerror="this.parentElement.innerHTML=\'<div class=\\\'no-preview\\\'><i class=\\\'fas fa-file-image\\\' style=\\\'font-size:48px; color:#0f3b5e;\\\'></i><span class=\\\'ext\\\'>File tidak ditemukan</span></div>\'">';
        } else if (isPDF) {
            preview.innerHTML = '<iframe src="' + filePath + '#toolbar=1" style="width:100%; height:350px; border:none; border-radius:8px;"></iframe>';
        } else if (isArchive || isOffice) {
            var icon = getFileIcon(ext);
            preview.innerHTML = `
                <div class="no-preview">
                    <i class="fas ${icon}" style="font-size:48px; color:#0f3b5e;"></i>
                    <span class="ext">File ${ext.toUpperCase()}</span>
                    <p style="font-size:13px; color:#94a3b8; margin-top:8px;">File ${isArchive ? 'arsip' : 'dokumen'} tidak dapat ditampilkan di browser. Silakan download.</p>
                </div>
            `;
        } else {
            var icon = getFileIcon(ext);
            preview.innerHTML = `
                <div class="no-preview">
                    <i class="fas ${icon}" style="font-size:48px; color:#0f3b5e;"></i>
                    <span class="ext">File ${ext.toUpperCase()}</span>
                    <p style="font-size:13px; color:#94a3b8; margin-top:8px;">File tidak dapat ditampilkan. Silakan download.</p>
                </div>
            `;
        }
        
        downloadBtn.href = filePath;
        downloadBtn.setAttribute('download', file_dokumen);
        downloadBtn.removeAttribute('target');
        downloadBtn.innerHTML = '<i class="fas fa-download"></i> Download';
        downloadBtn.style.opacity = '1';
        downloadBtn.style.cursor = 'pointer';
    } else if (tipe === 'link' && link_url) {
        preview.innerHTML = `
            <div class="no-preview">
                <i class="fas fa-external-link-alt" style="font-size:48px; color:#0f3b5e;"></i>
                <span class="ext">Dokumen via Link</span>
                <p style="font-size:13px; color:#94a3b8; margin-top:8px;">Klik tombol "Buka Link" untuk membuka dokumen.</p>
                <p style="font-size:12px; color:#94a3b8; margin-top:4px; word-break:break-all;">' + link_url + '</p>
            </div>
        `;
        downloadBtn.href = link_url;
        downloadBtn.removeAttribute('download');
        downloadBtn.innerHTML = '<i class="fas fa-external-link-alt"></i> Buka Link';
        downloadBtn.target = '_blank';
        downloadBtn.style.opacity = '1';
        downloadBtn.style.cursor = 'pointer';
    } else {
        preview.innerHTML = `
            <div class="no-preview">
                <i class="fas fa-file" style="font-size:48px; color:#94a3b8;"></i>
                <span class="ext">Tidak ada file</span>
                <p style="font-size:13px; color:#94a3b8; margin-top:8px;">Dokumen ini tidak memiliki file atau link.</p>
            </div>
        `;
        downloadBtn.href = '#';
        downloadBtn.removeAttribute('download');
        downloadBtn.innerHTML = '<i class="fas fa-times"></i> Tidak tersedia';
        downloadBtn.style.opacity = '0.5';
        downloadBtn.style.cursor = 'not-allowed';
    }
    
    openModal('viewModal');
}

// ===== DELETE MODAL =====
function openDeleteModal(id, judul) {
    document.getElementById('deleteMessage').textContent = 'Apakah Anda yakin ingin menghapus dokumen "' + judul + '"? Tindakan ini tidak dapat dibatalkan.';
    document.getElementById('deleteConfirmBtn').href = '?delete=' + id + '&tahun=<?= $tahun_aktif ?>';
    openModal('deleteModal');
}

// ============================================================
// FILTER TAHUN - SMOOTH TRANSITION
// ============================================================
function changeYear(year) {
    // Cek apakah tahun dalam range
    var minYear = 2025;
    var maxYear = 2030;
    if (year < minYear || year > maxYear) return;
    
    // Animasi fade out
    var items = document.querySelectorAll('.tahun-item');
    items.forEach(function(item) {
        item.style.transition = 'all 0.3s ease';
        if (parseInt(item.dataset.year) !== year) {
            item.style.opacity = '0.4';
            item.style.transform = 'scale(0.95)';
        }
    });
    
    // Redirect setelah animasi
    setTimeout(function() {
        window.location.href = '?tahun=' + year;
    }, 250);
}

// ============================================================
// HIGHLIGHT ACTIVE YEAR ON LOAD
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    var activeItem = document.querySelector('.tahun-item.active');
    if (activeItem) {
        // Scroll ke active item jika perlu
        var container = document.getElementById('tahunItems');
        if (container) {
            var itemRect = activeItem.getBoundingClientRect();
            var containerRect = container.getBoundingClientRect();
            if (itemRect.left < containerRect.left || itemRect.right > containerRect.right) {
                activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        }
    }
    
    // Hover effect untuk tahun items
    var tahunItems = document.querySelectorAll('.tahun-item');
    tahunItems.forEach(function(item) {
        item.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(-3px)';
            }
        });
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
});

</script>

</body>
</html>