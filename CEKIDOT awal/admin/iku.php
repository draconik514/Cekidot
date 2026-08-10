<?php
// admin/iku.php - Kelola IKU
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

include '../config/database.php';

// ============================================================
// FUNGSI GET PREDIKAT
// ============================================================
function getPredikat($capaian) {
    if ($capaian === null || $capaian === '') {
        return ['label' => 'BELUM ADA', 'class' => 'belum-ada', 'icon' => 'fa-minus-circle'];
    }
    $capaian = (float) str_replace(',', '.', str_replace('.', '', $capaian));
    if ($capaian > 100) {
        return ['label' => 'ISTIMEWA', 'class' => 'istimewa', 'icon' => 'fa-star'];
    } elseif ($capaian >= 80) {
        return ['label' => 'BAIK', 'class' => 'baik', 'icon' => 'fa-check-circle'];
    } elseif ($capaian >= 60) {
        return ['label' => 'BUTUH PERBAIKAN', 'class' => 'butuh-perbaikan', 'icon' => 'fa-exclamation-triangle'];
    } elseif ($capaian >= 20) {
        return ['label' => 'KURANG', 'class' => 'kurang', 'icon' => 'fa-times-circle'];
    } elseif ($capaian > 0) {
        return ['label' => 'SANGAT KURANG', 'class' => 'sangat-kurang', 'icon' => 'fa-exclamation-circle'];
    } else {
        return ['label' => 'BELUM ADA', 'class' => 'belum-ada', 'icon' => 'fa-minus-circle'];
    }
}

// Hitung predikat untuk setiap kategori
$predikat = getPredikat($capaian_formatted ?? '0');

// ============================================================
// TOTAL SURAT BARU
// ============================================================
$total_baru = $pdo->query("SELECT COUNT(*) FROM surat_masuk WHERE status='baru'")->fetchColumn();

// ============================================================
// KATEGORI IKU
// ============================================================
$kategori_list = ['Makan Minum', 'Wisatawan', 'Ekraf'];
$kategori_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : 'Makan Minum';

if (!in_array($kategori_aktif, $kategori_list)) {
    $kategori_aktif = $kategori_list[0];
}

// ============================================================
// TAHUN UNTUK SEMUA KATEGORI (MAKAN MINUM, WISATAWAN, EKRAF)
// ============================================================
$tahun_list = ['2025', '2026', '2027', '2028', '2029', '2030'];
$tahun_aktif = isset($_GET['tahun']) ? $_GET['tahun'] : '2025';

if (!in_array($tahun_aktif, $tahun_list)) {
    $tahun_aktif = $tahun_list[0];
}

// Subkategori untuk Wisatawan
$subkategori_wisata = isset($_GET['sub']) ? $_GET['sub'] : 'Nusantara';
$subkategori_list = ['Nusantara', 'Mancanegara', 'Akumulasi'];

if (!in_array($subkategori_wisata, $subkategori_list)) {
    $subkategori_wisata = $subkategori_list[0];
}

// ============================================================
// DELETE FILE SUMBER
// ============================================================
if (isset($_GET['delete_file']) && isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    $kategori = $_GET['kategori'] ?? 'Makan Minum';
    
    $stmt = $pdo->prepare("SELECT file_sumber FROM iku_penilaian WHERE kategori = ? AND nama_kriteria = 'Sumber Data'");
    $stmt->execute([$kategori]);
    $row = $stmt->fetch();
    
    if ($row && !empty($row['file_sumber'])) {
        $files = explode('|', $row['file_sumber']);
        $new_files = array_filter($files, function($f) use ($filename) {
            return $f !== $filename;
        });
        
        $file_path = '../uploads/iku/' . $kategori . '/' . $filename;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        $new_file_str = implode('|', $new_files);
        $stmt = $pdo->prepare("UPDATE iku_penilaian SET file_sumber = ? WHERE kategori = ? AND nama_kriteria = 'Sumber Data'");
        $stmt->execute([$new_file_str, $kategori]);
    }
    
    header("Location: ?kategori=" . urlencode($kategori) . "&tahun=" . urlencode($tahun_aktif) . "&sub=" . urlencode($subkategori_wisata) . "&success=1");
    exit;
}

// ============================================================
// DELETE INFOGRAFIS
// ============================================================
if (isset($_GET['delete_infografis'])) {
    $kategori = $_GET['kategori'] ?? 'Makan Minum';
    
    $stmt = $pdo->prepare("SELECT file_name FROM iku_infografis WHERE kategori = ?");
    $stmt->execute([$kategori]);
    $infografis = $stmt->fetch();
    
    if ($infografis && !empty($infografis['file_name'])) {
        $file_path = '../uploads/iku/' . $kategori . '/' . $infografis['file_name'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        $stmt = $pdo->prepare("UPDATE iku_infografis SET file_name = '' WHERE kategori = ?");
        $stmt->execute([$kategori]);
    }
    
    header("Location: ?kategori=" . urlencode($kategori) . "&tahun=" . urlencode($tahun_aktif) . "&sub=" . urlencode($subkategori_wisata) . "&delete_infografis_success=1");
    exit;
}

// ============================================================
// AJAX UPLOAD INFOGRAFIS
// ============================================================
if (isset($_POST['ajax_upload_infografis']) && $_POST['ajax_upload_infografis'] == 1) {
    $response = ['success' => false, 'message' => '', 'file_path' => '', 'file_name' => ''];
    
    if (isset($_FILES['infografis']) && $_FILES['infografis']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_ext = strtolower(pathinfo($_FILES['infografis']['name'], PATHINFO_EXTENSION));
        $max_size = 5 * 1024 * 1024;
        
        if (in_array($file_ext, $allowed) && $_FILES['infografis']['size'] <= $max_size) {
            $kategori_folder = '../uploads/iku/' . $kategori_aktif;
            if (!is_dir($kategori_folder)) {
                mkdir($kategori_folder, 0777, true);
            }
            
            $file_name = 'infografis_' . $kategori_aktif . '_' . time() . '.' . $file_ext;
            $upload_path = $kategori_folder . '/' . $file_name;
            
            // Hapus file lama
            $stmt = $pdo->prepare("SELECT file_name FROM iku_infografis WHERE kategori = ?");
            $stmt->execute([$kategori_aktif]);
            $old = $stmt->fetch();
            if ($old && !empty($old['file_name'])) {
                $old_path = $kategori_folder . '/' . $old['file_name'];
                if (file_exists($old_path)) {
                    unlink($old_path);
                }
            }
            
            if (move_uploaded_file($_FILES['infografis']['tmp_name'], $upload_path)) {
                $stmt = $pdo->prepare("SELECT id FROM iku_infografis WHERE kategori = ?");
                $stmt->execute([$kategori_aktif]);
                $existing = $stmt->fetch();
                
                if ($existing) {
                    $stmt = $pdo->prepare("UPDATE iku_infografis SET file_name = ? WHERE kategori = ?");
                    $stmt->execute([$file_name, $kategori_aktif]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO iku_infografis (kategori, file_name) VALUES (?, ?)");
                    $stmt->execute([$kategori_aktif, $file_name]);
                }
                
                $response['success'] = true;
                $response['message'] = 'Infografis berhasil diupload!';
                $response['file_path'] = '../uploads/iku/' . $kategori_aktif . '/' . $file_name;
                $response['file_name'] = $file_name;
            } else {
                $response['message'] = 'Gagal mengupload file!';
            }
        } else {
            $response['message'] = 'Format file tidak didukung atau ukuran terlalu besar (max 5MB)!';
        }
    } else {
        $response['message'] = 'Tidak ada file yang dipilih!';
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// ============================================================
// AMBIL DATA BERDASARKAN KATEGORI
// ============================================================

// CEK BERDASARKAN KATEGORI
if ($kategori_aktif == 'Makan Minum') {
    // MAKAN MINUM - PAKAI TAHUN
    $stmt = $pdo->prepare("SELECT * FROM iku_penilaian WHERE kategori = ? AND tahun = ? AND nama_kriteria NOT IN ('Sumber Data', 'Infografis') AND nilai IS NOT NULL ORDER BY id");
    $stmt->execute([$kategori_aktif, $tahun_aktif]);
    $kriteria = $stmt->fetchAll();

    if (empty($kriteria)) {
        // HAPUS DATA LAMA UNTUK TAHUN INI
        $stmt = $pdo->prepare("DELETE FROM iku_penilaian WHERE kategori = ? AND tahun = ? AND nama_kriteria NOT IN ('Sumber Data', 'Infografis')");
        $stmt->execute([$kategori_aktif, $tahun_aktif]);
        
        // DEFAULT NILAI 0 (ZERO) UNTUK TAHUN BARU
        $default_data = [
            ['nama_kriteria' => 'Penyediaan Akomodasi dan Makan Minum', 'nilai' => 0],
            ['nama_kriteria' => 'PDRB ADHB Sulawesi Tengah', 'nilai' => 0]
        ];
        foreach ($default_data as $d) {
            $stmt = $pdo->prepare("INSERT INTO iku_penilaian (kategori, tahun, nama_kriteria, nilai, bobot, target, realisasi) VALUES (?, ?, ?, ?, 0, 0, 0)");
            $stmt->execute([$kategori_aktif, $tahun_aktif, $d['nama_kriteria'], $d['nilai']]);
        }
        $stmt = $pdo->prepare("SELECT * FROM iku_penilaian WHERE kategori = ? AND tahun = ? AND nama_kriteria NOT IN ('Sumber Data', 'Infografis') AND nilai IS NOT NULL ORDER BY id");
        $stmt->execute([$kategori_aktif, $tahun_aktif]);
        $kriteria = $stmt->fetchAll();
    }
    
} elseif ($kategori_aktif == 'Ekraf') {
    // EKRAF - PAKAI TAHUN
    $stmt = $pdo->prepare("SELECT * FROM iku_penilaian WHERE kategori = ? AND tahun = ? AND nama_kriteria NOT IN ('Sumber Data', 'Infografis') ORDER BY id");
    $stmt->execute([$kategori_aktif, $tahun_aktif]);
    $kriteria = $stmt->fetchAll();
    
    // Jika tidak ada data di database, buat data dummy
    if (empty($kriteria)) {
        $tahun_int = (int) $tahun_aktif;
        $nilai = 0;
        // Hanya untuk tahun 2025, ambil dari data 2025 (jika ada)
        if ($tahun_int == 2025) {
            $stmt = $pdo->prepare("SELECT nilai FROM iku_penilaian WHERE kategori = ? AND tahun = '2025' AND nama_kriteria = 'PDRB ADHB Sulawesi Tengah'");
            $stmt->execute([$kategori_aktif]);
            $nilai_2025 = $stmt->fetchColumn();
            if ($nilai_2025 !== false) $nilai = (float) $nilai_2025;
        }
        // Untuk tahun > 2025, nilai tetap 0
        $kriteria = [
            [
                'id' => 0,
                'kategori' => $kategori_aktif,
                'tahun' => $tahun_aktif,
                'nama_kriteria' => 'PDRB ADHB Sulawesi Tengah',
                'nilai' => $nilai,
                'bobot' => 0,
                'target' => 0,
                'realisasi' => 0
            ]
        ];
    }
}

// ============================================================
// AMBIL DATA EKRAF PER TAHUN
// ============================================================
$ekraf_data = [];
if ($kategori_aktif == 'Ekraf') {
    // Ambil data dari database untuk tahun ini
    $stmt = $pdo->prepare("SELECT * FROM iku_ekraf WHERE kategori = ? AND tahun = ? ORDER BY id LIMIT 10");
    $stmt->execute([$kategori_aktif, $tahun_aktif]);
    $ekraf_data = $stmt->fetchAll();
    
    // Jika tidak ada data di database, buat 10 baris dummy dengan ID=0
    if (count($ekraf_data) == 0) {
        // Ambil struktur sektor dari tahun 2025 (patokan)
        $stmt = $pdo->prepare("SELECT sektor FROM iku_ekraf WHERE kategori = ? AND tahun = '2025' ORDER BY id LIMIT 10");
        $stmt->execute([$kategori_aktif]);
        $sektor_list = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($sektor_list)) {
            $sektor_list = [
                'Industri Makanan dan Minuman (C.2)',
                'Industri Tekstil dan Pakaian Jadi (C.4)',
                'Industri Kulit, Barang dari Kulit, dan Alas Kaki (C.5)',
                'Industri Kayu, Barang dari Kayu dan Gabus; dan Barang Anyaman dari Bambu, Rotan, dan Sejenisnya (C.6)',
                'Industri Kertas dan Barang dari Kertas; Percetakan dan Reproduksi Media Rekaman (C.7)',
                'Industri Furnitur (C.15)',
                'Penyediaan Makan Minum (I.2)',
                'Informasi dan Komunikasi (J)',
                'Jasa Perusahaan (M,N)',
                'Jasa Lainnya (R,S,T,U)'
            ];
        }
        
        foreach ($sektor_list as $sektor) {
            $ekraf_data[] = [
                'id' => 0,
                'kategori' => $kategori_aktif,
                'tahun' => $tahun_aktif,
                'sektor' => $sektor,
                'koofisien' => 0,
                'nilai_bps' => 0,
                'jumlah_rp' => 0,
                'hasil_penjumlahan' => 0
            ];
        }
    }
}

// ============================================================
// AMBIL DATA WISATAWAN
// ============================================================
$wisatawan_data = [];
$wisatawan_kabkota = [
    'BANGGAI KEPULAUAN',
    'BANGGAI',
    'MOROWALI',
    'POSO',
    'DONGGALA',
    'TOLI-TOLI',
    'BUOL',
    'PARIGI MOUTONG',
    'TOJO UNA-UNA',
    'SIGI',
    'BANGGAI LAUT',
    'MOROWALI UTARA',
    'KOTA PALU'
];

if ($kategori_aktif == 'Wisatawan') {
    // Buat tabel jika belum ada
    $stmt = $pdo->query("SHOW TABLES LIKE 'iku_wisatawan'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `iku_wisatawan` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `kategori` varchar(50) NOT NULL,
            `subkategori` varchar(50) NOT NULL,
            `tahun` varchar(4) NOT NULL,
            `kabkota` varchar(100) NOT NULL,
            `januari` decimal(15,0) DEFAULT 0,
            `februari` decimal(15,0) DEFAULT 0,
            `maret` decimal(15,0) DEFAULT 0,
            `april` decimal(15,0) DEFAULT 0,
            `mei` decimal(15,0) DEFAULT 0,
            `juni` decimal(15,0) DEFAULT 0,
            `juli` decimal(15,0) DEFAULT 0,
            `agustus` decimal(15,0) DEFAULT 0,
            `september` decimal(15,0) DEFAULT 0,
            `oktober` decimal(15,0) DEFAULT 0,
            `november` decimal(15,0) DEFAULT 0,
            `desember` decimal(15,0) DEFAULT 0,
            `total` decimal(15,0) DEFAULT 0,
            `created_at` datetime DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `kategori` (`kategori`,`subkategori`,`tahun`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
    
    // Cek apakah data untuk tahun dan subkategori ini ada
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM iku_wisatawan WHERE kategori = 'Wisatawan' AND subkategori = ? AND tahun = ?");
    $stmt->execute([$subkategori_wisata, $tahun_aktif]);
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        foreach ($wisatawan_kabkota as $kab) {
            $stmt = $pdo->prepare("INSERT INTO iku_wisatawan (kategori, subkategori, tahun, kabkota, januari, februari, maret, april, mei, juni, juli, agustus, september, oktober, november, desember, total) VALUES ('Wisatawan', ?, ?, ?, 0,0,0,0,0,0,0,0,0,0,0,0,0)");
            $stmt->execute([$subkategori_wisata, $tahun_aktif, $kab]);
        }
    }
    
    // Ambil data wisatawan
    $stmt = $pdo->prepare("SELECT * FROM iku_wisatawan WHERE kategori = 'Wisatawan' AND subkategori = ? AND tahun = ? ORDER BY id");
    $stmt->execute([$subkategori_wisata, $tahun_aktif]);
    $wisatawan_data = $stmt->fetchAll();
}

// ============================================================
// UPDATE DATA - FIX: LOGIKA SAMA DENGAN DEBUG YANG BERHASIL
// ============================================================
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // ============================================================
    // 1. UPDATE MAKAN MINUM - NILAI KRITERIA
    // ============================================================
    if (isset($_POST['nilai']) && is_array($_POST['nilai'])) {
        foreach ($_POST['nilai'] as $id => $value) {
            $value = trim($value);
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            $value_clean = (float) $value;
            
            $stmt = $pdo->prepare("UPDATE iku_penilaian SET nilai = ? WHERE id = ? AND kategori = ?");
            $stmt->execute([$value_clean, $id, $kategori_aktif]);
        }
    }
    
// ============================================================
// 2. UPDATE TARGET, REALITAS, CAPAIAN (PDRB) - FIX KOMA
// ============================================================

if (isset($_POST['target']) && isset($_POST['realitas'])) {
    
    $target = trim($_POST['target']);
    $realitas = trim($_POST['realitas']);
    
    // Hapus titik ribuan dan ubah koma desimal ke titik
    $target = str_replace('.', '', $target);
    $target = str_replace(',', '.', $target);
    $target = (float) $target;
    
    $realitas = str_replace('.', '', $realitas);
    $realitas = str_replace(',', '.', $realitas);
    $realitas = (float) $realitas;
    
    // Hitung capaian
    $capaian = 0;
    if ($target > 0) {
        $capaian = ($realitas / $target) * 100;
        $capaian = round($capaian, 4);
    }
    
    // Cek data existing
    $stmt = $pdo->prepare("SELECT * FROM iku_pdrb WHERE kategori = ? AND tahun = ?");
    $stmt->execute([$kategori_aktif, $tahun_aktif]);
    $pdrb = $stmt->fetch();
    
    if ($pdrb) {
        $stmt = $pdo->prepare("UPDATE iku_pdrb SET target = ?, realitas = ?, capaian = ? WHERE kategori = ? AND tahun = ?");
        $stmt->execute([$target, $realitas, $capaian, $kategori_aktif, $tahun_aktif]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO iku_pdrb (kategori, tahun, target, realitas, capaian) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$kategori_aktif, $tahun_aktif, $target, $realitas, $capaian]);
    }
}

/// ============================================================
// 3. UPDATE EKRAF SEKTOR - DELETE + INSERT ULANG
// ============================================================
if ($kategori_aktif == 'Ekraf' && isset($_POST['ekraf'])) {
    // Hapus semua data untuk tahun ini
    $stmt = $pdo->prepare("DELETE FROM iku_ekraf WHERE kategori = ? AND tahun = ?");
    $stmt->execute([$kategori_aktif, $tahun_aktif]);
    
    // Insert ulang semua data dari POST
    foreach ($_POST['ekraf'] as $index => $data) {
        // Lewati jika key adalah 'count' (hidden field)
        if ($index === 'count') continue;
        
        $sektor = trim($data['sektor'] ?? '');
        $koofisien = trim($data['koofisien'] ?? '0');
        $nilai_bps = trim($data['nilai_bps'] ?? '0');
        
        $koofisien = (float) str_replace(',', '.', str_replace('.', '', $koofisien));
        $nilai_bps = (float) str_replace(',', '.', str_replace('.', '', $nilai_bps));
        
        $jumlah_rp = $nilai_bps * 1000000000;
        $hasil_penjumlahan = $jumlah_rp * $koofisien;
        
        $stmt = $pdo->prepare("INSERT INTO iku_ekraf (kategori, tahun, sektor, koofisien, nilai_bps, jumlah_rp, hasil_penjumlahan) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$kategori_aktif, $tahun_aktif, $sektor, $koofisien, $nilai_bps, $jumlah_rp, $hasil_penjumlahan]);
    }
}

    // ============================================================
    // PDRB EKRAF - UPDATE TARGET DAN CAPAIAN OTOMATIS
    // ============================================================
    if (isset($_POST['target']) && $kategori_aktif == 'Ekraf') {
        
        $target = trim($_POST['target']);
        $target = str_replace('.', '', $target);
        $target = str_replace(',', '.', $target);
        $target = (float) $target;
        
        // Gunakan proporsi_ekraf dengan presisi penuh (JANGAN dibulatkan)
        $realitas = isset($proporsi_ekraf) ? $proporsi_ekraf : 0;
        
        // ===== HITUNG CAPAIAN OTOMATIS =====
        $capaian = 0;
        if ($target > 0) {
            $capaian = ($realitas / $target) * 100;
            // BULATKAN KE 3 DESIMAL UNTUK TAMPILAN
            $capaian = round($capaian, 3);
        }
        
        // Cek apakah data sudah ada
        $stmt = $pdo->prepare("SELECT * FROM iku_pdrb WHERE kategori = ? AND tahun = ?");
        $stmt->execute([$kategori_aktif, $tahun_aktif]);
        $pdrb = $stmt->fetch();
        
        if ($pdrb) {
            $stmt = $pdo->prepare("UPDATE iku_pdrb SET target = ?, realitas = ?, capaian = ? WHERE kategori = ? AND tahun = ?");
            $stmt->execute([$target, $realitas, $capaian, $kategori_aktif, $tahun_aktif]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO iku_pdrb (kategori, tahun, target, realitas, capaian) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$kategori_aktif, $tahun_aktif, $target, $realitas, $capaian]);
        }
    }

// ============================================================
// 4. UPDATE PDRB ADHB EKRAF - TAMBAHKAN TAHUN
// ============================================================
if (isset($_POST['pdrb_adhb_ekraf'])) {
    $pdrb_adhb = trim($_POST['pdrb_adhb_ekraf']);
    $pdrb_adhb = (float) str_replace(',', '.', str_replace('.', '', $pdrb_adhb));
    
    // Cek apakah data sudah ada
    $stmt = $pdo->prepare("SELECT id FROM iku_penilaian WHERE kategori = ? AND tahun = ? AND nama_kriteria = 'PDRB ADHB Sulawesi Tengah'");
    $stmt->execute([$kategori_aktif, $tahun_aktif]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // UPDATE data yang sudah ada
        $stmt = $pdo->prepare("UPDATE iku_penilaian SET nilai = ? WHERE kategori = ? AND tahun = ? AND nama_kriteria = 'PDRB ADHB Sulawesi Tengah'");
        $stmt->execute([$pdrb_adhb, $kategori_aktif, $tahun_aktif]);
    } else {
        // INSERT data baru
        $stmt = $pdo->prepare("INSERT INTO iku_penilaian (kategori, tahun, nama_kriteria, nilai, bobot, target, realisasi) VALUES (?, ?, 'PDRB ADHB Sulawesi Tengah', ?, 0, 0, 0)");
        $stmt->execute([$kategori_aktif, $tahun_aktif, $pdrb_adhb]);
    }
}
    
    // ============================================================
    // 5. UPDATE WISATAWAN
    // ============================================================
    if ($kategori_aktif == 'Wisatawan' && isset($_POST['wisatawan'])) {
        foreach ($_POST['wisatawan'] as $id => $data) {
            $jan = (float) str_replace('.', '', trim($data['januari'] ?? '0'));
            $feb = (float) str_replace('.', '', trim($data['februari'] ?? '0'));
            $mar = (float) str_replace('.', '', trim($data['maret'] ?? '0'));
            $apr = (float) str_replace('.', '', trim($data['april'] ?? '0'));
            $mei = (float) str_replace('.', '', trim($data['mei'] ?? '0'));
            $jun = (float) str_replace('.', '', trim($data['juni'] ?? '0'));
            $jul = (float) str_replace('.', '', trim($data['juli'] ?? '0'));
            $agu = (float) str_replace('.', '', trim($data['agustus'] ?? '0'));
            $sep = (float) str_replace('.', '', trim($data['september'] ?? '0'));
            $okt = (float) str_replace('.', '', trim($data['oktober'] ?? '0'));
            $nov = (float) str_replace('.', '', trim($data['november'] ?? '0'));
            $des = (float) str_replace('.', '', trim($data['desember'] ?? '0'));
            $total = $jan + $feb + $mar + $apr + $mei + $jun + $jul + $agu + $sep + $okt + $nov + $des;
            
            $stmt = $pdo->prepare("UPDATE iku_wisatawan SET januari = ?, februari = ?, maret = ?, april = ?, mei = ?, juni = ?, juli = ?, agustus = ?, september = ?, oktober = ?, november = ?, desember = ?, total = ? WHERE id = ? AND kategori = 'Wisatawan'");
            $stmt->execute([$jan, $feb, $mar, $apr, $mei, $jun, $jul, $agu, $sep, $okt, $nov, $des, $total, $id]);
        }
    }
    
    // ============================================================
    // 6. UPDATE SUMBER DATA - LINK
    // ============================================================
    if (isset($_POST['link_sumber'])) {
        $link_sumber = trim($_POST['link_sumber']);
        
        $stmt = $pdo->prepare("SELECT id FROM iku_penilaian WHERE kategori = ? AND nama_kriteria = 'Sumber Data'");
        $stmt->execute([$kategori_aktif]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $stmt = $pdo->prepare("UPDATE iku_penilaian SET link_sumber = ? WHERE kategori = ? AND nama_kriteria = 'Sumber Data'");
            $stmt->execute([$link_sumber, $kategori_aktif]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO iku_penilaian (kategori, nama_kriteria, link_sumber) VALUES (?, 'Sumber Data', ?)");
            $stmt->execute([$kategori_aktif, $link_sumber]);
        }
    }
    
    // ============================================================
    // 7. UPLOAD FILE SUMBER
    // ============================================================
    if (isset($_FILES['file_sumber']) && !empty($_FILES['file_sumber']['name'][0])) {
        $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
        $max_size = 10 * 1024 * 1024;
        $max_files = 15;
        $uploaded_files = [];
        
        $kategori_folder = '../uploads/iku/' . $kategori_aktif;
        if (!is_dir($kategori_folder)) {
            mkdir($kategori_folder, 0777, true);
        }
        
        $stmt = $pdo->prepare("SELECT file_sumber FROM iku_penilaian WHERE kategori = ? AND nama_kriteria = 'Sumber Data'");
        $stmt->execute([$kategori_aktif]);
        $old = $stmt->fetch();
        $old_files = !empty($old['file_sumber']) ? explode('|', $old['file_sumber']) : [];
        
        $total_files = count($_FILES['file_sumber']['name']);
        if ($total_files > $max_files) {
            $error = 'Maksimal ' . $max_files . ' file!';
        } else {
            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['file_sumber']['error'][$i] !== UPLOAD_ERR_OK) {
                    continue;
                }
                
                $file_ext = strtolower(pathinfo($_FILES['file_sumber']['name'][$i], PATHINFO_EXTENSION));
                
                if (in_array($file_ext, $allowed) && $_FILES['file_sumber']['size'][$i] <= $max_size) {
                    $file_name = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['file_sumber']['name'][$i]);
                    $upload_path = $kategori_folder . '/' . $file_name;
                    
                    if (move_uploaded_file($_FILES['file_sumber']['tmp_name'][$i], $upload_path)) {
                        $uploaded_files[] = $file_name;
                    }
                }
            }
            
            if (!empty($uploaded_files)) {
                $all_files = array_merge($old_files, $uploaded_files);
                $all_files = array_slice($all_files, 0, $max_files);
                $file_names = implode('|', $all_files);
                
                $stmt = $pdo->prepare("SELECT id FROM iku_penilaian WHERE kategori = ? AND nama_kriteria = 'Sumber Data'");
                $stmt->execute([$kategori_aktif]);
                $existing = $stmt->fetch();
                
                if ($existing) {
                    $stmt = $pdo->prepare("UPDATE iku_penilaian SET file_sumber = ? WHERE kategori = ? AND nama_kriteria = 'Sumber Data'");
                    $stmt->execute([$file_names, $kategori_aktif]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO iku_penilaian (kategori, nama_kriteria, file_sumber) VALUES (?, 'Sumber Data', ?)");
                    $stmt->execute([$kategori_aktif, $file_names]);
                }
            }
        }
    }
    
    // ============================================================
    // REDIRECT - PASTIKAN REDIRECT
    // ============================================================
    $success = 'Data berhasil diperbarui!';
    $redirect = "?kategori=" . urlencode($kategori_aktif) . "&tahun=" . urlencode($tahun_aktif) . "&sub=" . urlencode($subkategori_wisata) . "&success=1";
    header("Location: " . $redirect);
    exit;
}

// ============================================================
// CEK PARAMETER SUCCESS
// ============================================================
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = 'Data berhasil diperbarui!';
}

if (isset($_GET['delete_infografis_success'])) {
    $success = 'Infografis berhasil dihapus!';
}

// ============================================================
// AMBIL DATA INFOGRAFIS
// ============================================================
$infografis_file = '';
$infografis_exists = false;
$infografis_path = '';

$stmt = $pdo->prepare("SELECT file_name FROM iku_infografis WHERE kategori = ?");
$stmt->execute([$kategori_aktif]);
$infografis = $stmt->fetch();

if ($infografis && !empty($infografis['file_name'])) {
    $infografis_file = $infografis['file_name'];
    $full_path = '../uploads/iku/' . $kategori_aktif . '/' . $infografis_file;
    if (file_exists($full_path)) {
        $infografis_exists = true;
        $infografis_path = $full_path;
    } else {
        $stmt = $pdo->prepare("UPDATE iku_infografis SET file_name = '' WHERE kategori = ?");
        $stmt->execute([$kategori_aktif]);
    }
}

// ============================================================
// AMBIL DATA PDRB PER TAHUN
// ============================================================
$stmt = $pdo->prepare("SELECT link_sumber, file_sumber FROM iku_penilaian WHERE kategori = ? AND nama_kriteria = 'Sumber Data'");
$stmt->execute([$kategori_aktif]);
$sumber = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM iku_pdrb WHERE kategori = ? AND tahun = ?");
$stmt->execute([$kategori_aktif, $tahun_aktif]);
$pdrb_data = $stmt->fetch();

// Jika tidak ada data, buat data dummy dengan nilai 0 (tidak disimpan)
if (!$pdrb_data) {
    $pdrb_data = [
        'target' => 0,
        'realitas' => 0,
        'capaian' => 0
    ];
}

// ============================================================
// HITUNG TOTAL WISATAWAN PER BULAN
// ============================================================
$total_bulan = [];
$total_keseluruhan = 0;
$total_nusantara = 0;
$total_mancanegara = 0;

if ($kategori_aktif == 'Wisatawan') {
    $bulan_keys = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    foreach ($bulan_keys as $key) {
        $total_bulan[$key] = 0;
    }
    foreach ($wisatawan_data as $w) {
        foreach ($bulan_keys as $key) {
            $total_bulan[$key] += (float) $w[$key];
        }
        if ($w['subkategori'] == 'Nusantara') {
            $total_nusantara += (float) $w['total'];
        } else if ($w['subkategori'] == 'Mancanegara') {
            $total_mancanegara += (float) $w['total'];
        }
        $total_keseluruhan += (float) $w['total'];
    }
}

// ============================================================
// DATA UNTUK AKUMULASI
// ============================================================
$akumulasi_data = [];
if ($kategori_aktif == 'Wisatawan') {
    if ($subkategori_wisata == 'Akumulasi') {
        $stmt = $pdo->prepare("SELECT * FROM iku_wisatawan WHERE kategori = 'Wisatawan' AND subkategori = 'Nusantara' AND tahun = ? ORDER BY id");
        $stmt->execute([$tahun_aktif]);
        $data_nusantara = $stmt->fetchAll();
        
        $stmt = $pdo->prepare("SELECT * FROM iku_wisatawan WHERE kategori = 'Wisatawan' AND subkategori = 'Mancanegara' AND tahun = ? ORDER BY id");
        $stmt->execute([$tahun_aktif]);
        $data_mancanegara = $stmt->fetchAll();
        
        $akumulasi_data = [];
        foreach ($wisatawan_kabkota as $index => $kab) {
            $nus = isset($data_nusantara[$index]) ? $data_nusantara[$index] : null;
            $man = isset($data_mancanegara[$index]) ? $data_mancanegara[$index] : null;
            
            $akumulasi_data[] = [
                'kabkota' => $kab,
                'nusantara' => $nus,
                'mancanegara' => $man
            ];
        }
    } else {
        // Default: array kosong untuk menghindari error
        $akumulasi_data = [];
        foreach ($wisatawan_kabkota as $kab) {
            $akumulasi_data[] = [
                'kabkota' => $kab,
                'nusantara' => null,
                'mancanegara' => null
            ];
        }
    }
}

// ============================================================
// HITUNG HASIL
// ============================================================
$hasil = 0;
$nilai1 = 0;
$nilai2 = 0;
$total_ekraf = 0;
$pdrb_adhb_ekraf = 0;
$proporsi_ekraf = 0;
$total_nusantara = 0;
$total_mancanegara = 0;

if ($kategori_aktif === 'Ekraf') {
    // Reset nilai
    $total_ekraf = 0;
    $pdrb_adhb_ekraf = 0;
    
    foreach ($ekraf_data as $e) {
        $total_ekraf += (float) $e['hasil_penjumlahan'];
    }
    
    // AMBIL PDRB ADHB SULAWESI TENGAH DARI KRITERIA
    foreach ($kriteria as $k) {
        if ($k['nama_kriteria'] == 'PDRB ADHB Sulawesi Tengah') {
            $pdrb_adhb_ekraf = (float) $k['nilai'];
            break;
        }
    }
    
    // KALAU PDRB ADHB 0, PROPORSINYA 0
    if ($pdrb_adhb_ekraf > 0) {
        $proporsi_ekraf = ($total_ekraf / ($pdrb_adhb_ekraf * 1000000000)) * 100;
    } else {
        $proporsi_ekraf = 0;
    }
    
    $nilai1 = $total_ekraf;
    $nilai2 = $pdrb_adhb_ekraf;
    $hasil = $proporsi_ekraf;
}

else if ($kategori_aktif == 'Wisatawan') {
    // ============================================================
    // AMBIL TOTAL NUSANTARA DARI DATABASE (SEMUA DATA)
    // ============================================================
    $stmt = $pdo->prepare("SELECT SUM(total) as total_nusantara FROM iku_wisatawan WHERE kategori = 'Wisatawan' AND subkategori = 'Nusantara' AND tahun = ?");
    $stmt->execute([$tahun_aktif]);
    $row = $stmt->fetch();
    $total_nusantara = $row['total_nusantara'] ? (float) $row['total_nusantara'] : 0;
    
    // ============================================================
    // AMBIL TOTAL MANCANEGARA DARI DATABASE (SEMUA DATA)
    // ============================================================
    $stmt = $pdo->prepare("SELECT SUM(total) as total_mancanegara FROM iku_wisatawan WHERE kategori = 'Wisatawan' AND subkategori = 'Mancanegara' AND tahun = ?");
    $stmt->execute([$tahun_aktif]);
    $row = $stmt->fetch();
    $total_mancanegara = $row['total_mancanegara'] ? (float) $row['total_mancanegara'] : 0;
    
    $nilai1 = $total_nusantara;
    $nilai2 = $total_mancanegara;
    $hasil = $total_nusantara + $total_mancanegara;
} 

else {
    if (count($kriteria) >= 2) {
        $nilai1 = (float) $kriteria[0]['nilai'];
        $nilai2 = (float) $kriteria[1]['nilai'];
    }
    if ($nilai2 > 0) {
        $hasil = ($nilai1 / $nilai2) * 100;
    }
}

// Format angka - Dalam Miliar untuk Makan Minum
$nilai1_formatted = number_format($nilai1, 4, ',', '.');
$nilai2_formatted = number_format($nilai2, 4, ',', '.');
$hasil_formatted = number_format($hasil, 4, ',', '.');

// Format Ekraf - Dalam Miliar
$total_ekraf_formatted = isset($total_ekraf) ? number_format($total_ekraf / 1000000000, 2, ',', '.') : '0,00';
$pdrb_adhb_ekraf_formatted = isset($pdrb_adhb_ekraf) ? number_format($pdrb_adhb_ekraf, 2, ',', '.') : '0,00';
$proporsi_ekraf_formatted = isset($proporsi_ekraf) ? number_format($proporsi_ekraf, 3, ',', '.') : '0,000';

// Untuk display di result box (dalam Miliar)
$pdrb_adhb_ekraf_rp = $pdrb_adhb_ekraf; // Simpan dalam Miliar
$pdrb_adhb_ekraf_rp_formatted = number_format($pdrb_adhb_ekraf_rp, 2, ',', '.');
$pdrb_adhb_ekraf_display = number_format($pdrb_adhb_ekraf_rp, 2, ',', '.');

// Format PDRB - untuk semua kategori
if ($pdrb_data) {
    $target_formatted = isset($pdrb_data['target']) ? number_format($pdrb_data['target'], 2, ',', '.') : '0,00';
    // Realisasi dan capaian menggunakan 3 desimal untuk internal, tapi tampilkan 2
    $realitas_formatted = number_format($hasil, 4, ',', '.');
    $capaian_formatted = isset($pdrb_data['capaian']) ? number_format($pdrb_data['capaian'], 2, ',', '.') : '0,00';
} else {
    $target_formatted = '0,00';
    $realitas_formatted = '0,000';
    $capaian_formatted = '0,00';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola IKU - CEKIDOT</title>
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
           SIDEBAR - SAMA SEPERTI SLIDER.PHP
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
        
        .filter-kategori {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 24px;
            padding: 6px;
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e8ecf1;
            justify-content: center;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .filter-kategori .btn-kategori {
            padding: 8px 28px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            background: transparent;
            color: #64748b;
            text-decoration: none;
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .filter-kategori .btn-kategori .icon {
            font-size: 16px;
            opacity: 0.5;
            transition: all 0.3s;
        }
        .filter-kategori .btn-kategori:hover {
            color: #0f3b5e;
            background: rgba(15, 59, 94, 0.05);
        }
        .filter-kategori .btn-kategori:hover .icon {
            opacity: 1;
        }
        .filter-kategori .btn-kategori.active {
            background: #0f3b5e;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(15, 59, 94, 0.25);
        }
        .filter-kategori .btn-kategori.active .icon {
            opacity: 1;
            color: #eab308;
        }
        .filter-kategori .btn-kategori .badge-count {
            display: none;
        }
        .filter-kategori .btn-kategori.active .badge-count {
            display: none;
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
        
        .infografis-section {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e8ecf1;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .infografis-section .infografis-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f3b5e;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .infografis-section .infografis-title i { color: #eab308; }
        .infografis-section .infografis-title .tahun-label {
            font-size: 12px;
            font-weight: 400;
            color: #94a3b8;
            margin-left: 8px;
        }
        .infografis-section .infografis-title .tahun-label i {
            color: #94a3b8;
        }
        
        .infografis-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 24px;
            align-items: start;
        }
        
        .infografis-preview {
            position: relative;
        }
        .infografis-preview .preview-wrapper {
            position: relative;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
        }
        .infografis-preview .preview-wrapper .slide-wrapper {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            background: #f8fafc;
        }
        .infografis-preview .preview-wrapper .slide-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .infografis-preview .preview-wrapper .slide-wrapper .empty-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            color: #94a3b8;
            gap: 4px;
        }
        .infografis-preview .preview-wrapper .slide-wrapper .empty-slide i {
            font-size: 32px;
            opacity: 0.3;
        }
        .infografis-preview .preview-wrapper .slide-wrapper .empty-slide span {
            font-size: 13px;
        }
        .infografis-preview .preview-caption {
            text-align: center;
            padding: 6px 0 0;
            font-size: 11px;
            color: #94a3b8;
        }
        .infografis-preview .preview-caption i {
            color: #0f3b5e;
            margin-right: 4px;
        }
        
        .infografis-panel {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .infografis-panel .status-box {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e8ecf1;
        }
        .infografis-panel .status-box .status-icon {
            font-size: 20px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .infografis-panel .status-box .status-icon.ada {
            background: #d1fae5;
            color: #16a34a;
        }
        .infografis-panel .status-box .status-icon.tidak {
            background: #f1f5f9;
            color: #94a3b8;
        }
        .infografis-panel .status-box .status-text {
            flex: 1;
        }
        .infografis-panel .status-box .status-text .status-label {
            font-weight: 600;
            font-size: 14px;
            color: #0f3b5e;
        }
        .infografis-panel .status-box .status-text .status-label.ada { color: #16a34a; }
        .infografis-panel .status-box .status-text .status-label.tidak { color: #94a3b8; }
        .infografis-panel .status-box .status-text .file-name-text {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
            display: block;
        }
        
        .infografis-panel .upload-box {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            padding: 12px 16px;
            background: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .infografis-panel .upload-box:hover {
            border-color: #0f3b5e;
            background: #f8fafc;
        }
        .infografis-panel .upload-box .file-upload-wrapper {
            position: relative;
            flex: 1;
            min-width: 160px;
        }
        .infografis-panel .upload-box .file-upload-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }
        .infografis-panel .upload-box .file-upload-wrapper .file-label {
            display: block;
            padding: 8px 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #475569;
            font-size: 13px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        .infografis-panel .upload-box .file-upload-wrapper:hover .file-label {
            border-color: #0f3b5e;
            background: #f1f5f9;
        }
        .infografis-panel .upload-box .file-upload-wrapper .file-label i {
            margin-right: 6px;
            color: #0f3b5e;
        }
        .infografis-panel .upload-box .file-hint {
            font-size: 11px;
            color: #94a3b8;
            white-space: nowrap;
        }
        .infografis-panel .upload-box .file-hint i {
            color: #0f3b5e;
            margin-right: 4px;
        }
        .infografis-panel .upload-box .preview-status {
            font-size: 12px;
            color: #eab308;
            font-weight: 500;
            width: 100%;
            text-align: center;
            padding: 4px 0;
            display: none;
        }
        .infografis-panel .upload-box .preview-status.show {
            display: block;
        }
        
        .infografis-panel .action-box {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 4px 0;
            justify-content: flex-end;
        }
        .infografis-panel .action-box .btn-delete-icon {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 50%;
            background: #fef2f2;
            color: #991b1b;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            text-decoration: none;
        }
        .infografis-panel .action-box .btn-delete-icon:hover {
            background: #fecaca;
            transform: scale(1.05);
        }
        .infografis-panel .action-box .btn-delete-icon.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }
        .infografis-panel .action-box .btn-delete-icon i {
            color: #991b1b;
        }
        
        .btn-upload-infografis {
            padding: 6px 24px;
            background: #0f3b5e;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .btn-upload-infografis:hover {
            background: #0a2a44;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15,59,94,0.2);
        }
        
        .result-box {
            background: linear-gradient(135deg, #0f3b5e 0%, #1a5276 100%);
            border-radius: 16px;
            padding: 24px 32px;
            margin-bottom: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            text-align: center;
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(15, 59, 94, 0.25);
        }
        .result-box .item {
            padding: 12px 16px;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.06);
            transition: all 0.3s ease;
        }
        .result-box .item:hover {
            background: rgba(255,255,255,0.10);
            border-color: rgba(255,255,255,0.12);
            transform: translateY(-2px);
        }
        .result-box .item .label {
            font-size: 11px;
            opacity: 0.7;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .result-box .item .label span {
            font-weight: 300;
            text-transform: lowercase;
            opacity: 0.6;
        }
        .result-box .item .value {
            font-size: 24px;
            font-weight: 800;
            margin-top: 2px;
            word-break: break-all;
            line-height: 1.2;
        }
        .result-box .item .value .persen {
            font-size: 18px;
            font-weight: 400;
            opacity: 0.7;
        }
        
        .form-section {
            background: #ffffff;
            padding: 20px 24px;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .form-section .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 6px;
        }
        .form-section .form-header .form-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f3b5e;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-section .form-header .form-title i { color: #eab308; }
        .form-section .form-header .form-note {
            font-size: 11px;
            color: #94a3b8;
            font-style: italic;
            background: #f1f5f9;
            padding: 3px 14px;
            border-radius: 20px;
        }
        
        .form-vertical {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .form-group-vertical {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #f8fafc;
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid #e8ecf1;
            transition: border-color 0.3s;
        }
        .form-group-vertical:hover {
            border-color: #0f3b5e;
        }
        .form-group-vertical label {
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
            min-width: 200px;
            flex-shrink: 0;
        }
        .form-group-vertical .input-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .form-group-vertical .input-wrapper input {
            flex: 1;
            padding: 6px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            font-family: inherit;
            background: #ffffff;
            transition: border-color 0.3s;
            text-align: right;
        }
        .form-group-vertical .input-wrapper input:focus {
            outline: none;
            border-color: #0f3b5e;
            box-shadow: 0 0 0 3px rgba(15,59,94,0.06);
        }
        .form-group-vertical .input-wrapper .unit {
            font-size: 12px;
            color: #94a3b8;
            min-width: 35px;
        }
        
        .kontribusi-row {
            background: #f0f4f8;
            padding: 12px 20px;
            border-radius: 8px;
            border: 1.5px solid #0f3b5e;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }
        .kontribusi-row .label-kontribusi {
            font-weight: 600;
            font-size: 13px;
            color: #0f3b5e;
        }
        .kontribusi-row .label-kontribusi i {
            color: #eab308;
            margin-right: 6px;
        }
        .kontribusi-row .value-kontribusi {
            font-size: 22px;
            font-weight: 800;
            color: #eab308;
        }
        .kontribusi-row .value-kontribusi .persen {
            font-size: 16px;
            font-weight: 400;
            color: #94a3b8;
        }
        
        .ekraf-table-wrapper {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .ekraf-table-wrapper .ekraf-header {
            padding: 16px 20px;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .ekraf-table-wrapper .ekraf-header .ekraf-title {
            font-weight: 700;
            color: #0f3b5e;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ekraf-table-wrapper .ekraf-header .ekraf-title i {
            color: #eab308;
        }
        .ekraf-table-wrapper .ekraf-header .ekraf-note {
            font-size: 12px;
            color: #94a3b8;
            font-style: italic;
            background: #ffffff;
            padding: 3px 14px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }
        .ekraf-table-wrapper .table-scroll {
            overflow-x: auto;
            padding: 0 4px;
        }
        .ekraf-table-wrapper table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 850px;
        }
        .ekraf-table-wrapper table th {
            text-align: center;
            padding: 10px 8px;
            background: #fafbfc;
            font-weight: 600;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .ekraf-table-wrapper table td {
            padding: 8px 8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .ekraf-table-wrapper table tr:last-child td {
            border-bottom: none;
        }
        .ekraf-table-wrapper table .text-center { text-align: center; }
        .ekraf-table-wrapper table .text-right { text-align: right; }
        .ekraf-table-wrapper table .text-left { text-align: left; }
        .ekraf-table-wrapper table .sektor-input {
            width: 100%;
            padding: 4px 8px;
            border: 1.5px solid #e2e8f0;
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.3s;
            text-align: left;
        }
        .ekraf-table-wrapper table .sektor-input:focus {
            outline: none;
            border-color: #0f3b5e;
        }
        .ekraf-table-wrapper table .num-input {
            width: 100%;
            padding: 4px 8px;
            border: 1.5px solid #e2e8f0;
            border-radius: 4px;
            font-size: 13px;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.3s;
            text-align: right;
        }
        .ekraf-table-wrapper table .num-input:focus {
            outline: none;
            border-color: #0f3b5e;
        }
        .ekraf-table-wrapper table .value-display {
            font-weight: 500;
            color: #1e293b;
        }
        .ekraf-table-wrapper table .total-row {
            background: #f8fafc;
            font-weight: 700;
            border-top: 2px solid #e2e8f0;
        }
        .ekraf-table-wrapper table .total-row td {
            padding: 10px 8px;
            font-size: 14px;
        }
        .ekraf-table-wrapper table .total-row .total-label {
            color: #0f3b5e;
            font-weight: 700;
            text-align: left;
            padding-left: 8px;
        }
        .ekraf-table-wrapper table .total-row .total-value {
            color: #eab308;
            font-weight: 700;
            text-align: right;
        }
        .ekraf-table-wrapper table .adhb-row td {
            padding: 10px 8px;
        }
        .ekraf-table-wrapper table .adhb-row .adhb-label {
            color: #0f3b5e;
            font-weight: 700;
            text-align: left;
            padding-left: 8px;
        }
        .ekraf-table-wrapper table .adhb-row .adhb-value {
            font-weight: 600;
            color: #0f3b5e;
            text-align: right;
        }
        .ekraf-table-wrapper table .proporsi-row {
            background: #fef3c7;
            border-top: 2px solid #eab308;
        }
        .ekraf-table-wrapper table .proporsi-row td {
            padding: 10px 8px;
        }
        .ekraf-table-wrapper table .proporsi-row .proporsi-label {
            color: #0f3b5e;
            font-weight: 700;
            text-align: left;
            padding-left: 8px;
        }
        .ekraf-table-wrapper table .proporsi-row .proporsi-value {
            color: #dc2626;
            font-size: 18px;
            font-weight: 700;
            text-align: right;
        }
        
        .pdrb-section {
            background: #ffffff;
            padding: 16px 24px;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .pdrb-section .pdrb-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f3b5e;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .pdrb-section .pdrb-title i { color: #eab308; }
        
        .pdrb-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }
        .pdrb-grid .form-group {
            margin-bottom: 0;
        }
        .pdrb-grid .form-group label {
            font-weight: 600;
            font-size: 12px;
            display: block;
            margin-bottom: 3px;
            color: #1e293b;
        }
        .pdrb-grid .form-group input {
            width: 100%;
            padding: 6px 10px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.3s;
            text-align: right;
        }
        .pdrb-grid .form-group input:focus {
            outline: none;
            border-color: #0f3b5e;
        }
        .pdrb-grid .form-group .input-hint {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
        }
        .pdrb-grid .form-group input[readonly] {
            background: #f1f5f9;
            cursor: not-allowed;
        }

        .pdrb-grid .form-group .predikat-box {
        margin-top: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        text-align: center;
        transition: all 0.3s;
        }
        .pdrb-grid .form-group .predikat-box .predikat-icon {
            margin-right: 6px;
        }
        .pdrb-grid .form-group .predikat-box.istimewa {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #93c5fd;
        }
        .pdrb-grid .form-group .predikat-box.baik {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #86efac;
        }
        .pdrb-grid .form-group .predikat-box.butuh-perbaikan {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        .pdrb-grid .form-group .predikat-box.kurang {
            background: #ffedd5;
            color: #9a3412;
            border: 1px solid #fdba74;
        }
        .pdrb-grid .form-group .predikat-box.sangat-kurang {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .pdrb-grid .form-group .predikat-box.belum-ada {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }
        
        .sumber-section {
            background: #ffffff;
            padding: 16px 24px;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .sumber-section .sumber-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f3b5e;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sumber-section .sumber-title i { color: #eab308; }
        .sumber-section .sumber-title .tahun-label {
            font-size: 12px;
            font-weight: 400;
            color: #94a3b8;
            margin-left: 8px;
        }
        .sumber-section .sumber-title .tahun-label i {
            color: #94a3b8;
        }
        
        .sumber-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .sumber-section .file-upload-wrapper {
            position: relative;
        }
        .sumber-section .file-upload-wrapper input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }
        .sumber-section .file-upload-wrapper .file-label {
            display: block;
            padding: 8px 14px;
            background: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 6px;
            color: #475569;
            font-size: 12px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        .sumber-section .file-upload-wrapper:hover .file-label {
            background: #f8fafc;
            border-color: #0f3b5e;
        }
        .sumber-section .file-upload-wrapper .file-label i {
            margin-right: 4px;
            color: #0f3b5e;
        }
        .sumber-section .form-group {
            margin-bottom: 0;
        }
        .sumber-section .form-group label {
            font-weight: 600;
            font-size: 12px;
            display: block;
            margin-bottom: 3px;
            color: #1e293b;
        }
        .sumber-section .form-group textarea {
            width: 100%;
            padding: 6px 10px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            font-size: 12px;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.3s;
            resize: vertical;
            min-height: 80px;
        }
        .sumber-section .form-group textarea:focus {
            outline: none;
            border-color: #0f3b5e;
        }
        .sumber-section .input-hint {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
        }
        
        .file-list {
            margin-top: 6px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .file-list .file-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px;
            background: #f8fafc;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            font-size: 12px;
        }
        .file-list .file-item i {
            color: #0f3b5e;
            font-size: 14px;
        }
        .file-list .file-item .file-name {
            flex: 1;
            color: #1e293b;
            font-weight: 500;
        }
        .file-list .file-item .file-size {
            color: #94a3b8;
            font-size: 11px;
        }
        .file-list .file-item .file-status-text {
            font-size: 10px;
            color: #16a34a;
        }
        .file-status-list {
            margin-top: 4px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .file-status-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px;
            background: #f8fafc;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            font-size: 12px;
        }
        .file-status-item .status-icon.ada { color: #16a34a; }
        .file-status-item .status-text {
            flex: 1;
            color: #1e293b;
        }
        .file-status-item .status-text .nama-file {
            font-weight: 500;
            color: #0f3b5e;
        }
        .file-status-item .btn-lihat {
            color: #0f3b5e;
            text-decoration: none;
            padding: 1px 6px;
            border-radius: 4px;
            transition: all 0.3s;
            font-size: 12px;
        }
        .file-status-item .btn-lihat:hover {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .file-status-item .btn-hapus-file {
            color: #991b1b;
            text-decoration: none;
            padding: 1px 6px;
            border-radius: 4px;
            transition: all 0.3s;
            background: #fef2f2;
            border: none;
            cursor: pointer;
            font-size: 12px;
        }
        .file-status-item .btn-hapus-file:hover {
            background: #fecaca;
        }
        .sumber-section .file-status {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px;
            background: #f8fafc;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            margin-top: 4px;
            font-size: 12px;
        }
        .sumber-section .file-status .status-icon.ada { color: #16a34a; }
        .sumber-section .file-status .status-icon.tidak { color: #94a3b8; }
        .sumber-section .file-status .status-text {
            color: #1e293b;
        }
        
        .btn-save {
            padding: 10px 40px;
            background: #0f3b5e;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-save:hover {
            background: #0a2a44;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15,59,94,0.3);
        }
        .btn-save i { margin-right: 8px; }
        
        .swal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s;
        }
        .swal-overlay.show { display: flex; }
        .swal-box {
            background: #fff;
            border-radius: 16px;
            padding: 30px 35px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
        }
        .swal-box .swal-icon {
            font-size: 48px;
            color: #dc2626;
            margin-bottom: 12px;
        }
        .swal-box .swal-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f3b5e;
            margin-bottom: 8px;
        }
        .swal-box .swal-text {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .swal-box .swal-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .swal-box .swal-actions .swal-btn {
            padding: 8px 28px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .swal-box .swal-actions .swal-btn-cancel {
            background: #f1f5f9;
            color: #475569;
        }
        .swal-box .swal-actions .swal-btn-cancel:hover {
            background: #e2e8f0;
        }
        .swal-box .swal-actions .swal-btn-confirm {
            background: #dc2626;
            color: #fff;
        }
        .swal-box .swal-actions .swal-btn-confirm:hover {
            background: #b91c1c;
            transform: scale(1.02);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .upload-loading {
            display: none;
            align-items: center;
            gap: 10px;
            color: #0f3b5e;
            font-weight: 500;
            font-size: 14px;
            padding: 8px 16px;
            background: #f0f4f8;
            border-radius: 8px;
        }
        .upload-loading.show {
            display: flex;
        }
        .upload-loading .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #e2e8f0;
            border-top: 3px solid #0f3b5e;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .confirm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }
        .confirm-overlay.show { display: flex; }
        .confirm-box {
            background: #fff;
            border-radius: 16px;
            padding: 30px 35px;
            max-width: 440px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
        }
        .confirm-box .confirm-icon {
            font-size: 48px;
            color: #eab308;
            margin-bottom: 12px;
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
            padding: 8px 28px;
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
        
        .wisatawan-section {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e8ecf1;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .wisatawan-section .wisatawan-header {
            padding: 16px 20px;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .wisatawan-section .wisatawan-header .wisatawan-title {
            font-weight: 700;
            color: #0f3b5e;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .wisatawan-section .wisatawan-header .wisatawan-title i {
            color: #eab308;
        }
        .wisatawan-section .wisatawan-header .wisatawan-sub {
            display: flex;
            gap: 6px;
        }
        .wisatawan-section .wisatawan-header .wisatawan-sub .btn-sub {
            padding: 4px 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            background: transparent;
            color: #64748b;
            text-decoration: none;
        }
        .wisatawan-section .wisatawan-header .wisatawan-sub .btn-sub:hover {
            background: rgba(15, 59, 94, 0.05);
            color: #0f3b5e;
        }
        .wisatawan-section .wisatawan-header .wisatawan-sub .btn-sub.active {
            background: #0f3b5e;
            color: #ffffff;
        }
        .wisatawan-section .table-scroll {
            overflow-x: auto;
            padding: 0 4px;
        }
        .wisatawan-section table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 900px;
        }
        .wisatawan-section table th {
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
        .wisatawan-section table th:first-child {
            text-align: left;
            min-width: 140px;
        }
        .wisatawan-section table td {
            padding: 6px 4px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .wisatawan-section table td:first-child {
            font-weight: 500;
            color: #0f3b5e;
            font-size: 11px;
        }
        .wisatawan-section table .wisatawan-input {
            width: 100%;
            padding: 4px 4px;
            border: 1.5px solid #e2e8f0;
            border-radius: 4px;
            font-size: 12px;
            font-family: inherit;
            background: #fff;
            transition: border-color 0.3s;
            text-align: right;
            min-width: 60px;
        }
        .wisatawan-section table .wisatawan-input:focus {
            outline: none;
            border-color: #0f3b5e;
        }
        .wisatawan-section table .wisatawan-input:disabled {
            background: #f8fafc;
            cursor: not-allowed;
        }
        .wisatawan-section table .total-kab {
            font-weight: 700;
            color: #0f3b5e;
            text-align: right;
        }
        .wisatawan-section table .total-bulan {
            font-weight: 700;
            color: #eab308;
            text-align: right;
            background: #fefce8;
        }
        .wisatawan-section table .total-row td {
            padding: 8px 4px;
            background: #f8fafc;
            border-top: 2px solid #e2e8f0;
        }
        .wisatawan-section table .grand-total td {
            padding: 8px 4px;
            background: #fef3c7;
            border-top: 2px solid #eab308;
            font-weight: 700;
        }
        .wisatawan-section table .grand-total .grand-label {
            color: #0f3b5e;
            font-size: 13px;
        }
        .wisatawan-section table .grand-total .grand-value {
            color: #dc2626;
            font-size: 16px;
        }
        .wisatawan-section table .text-right { text-align: right; }
        .wisatawan-section table .text-center { text-align: center; }
        .wisatawan-section table .text-left { text-align: left; }
        
        .wisatawan-section .akumulasi-table th:first-child {
            min-width: 120px;
        }
        .wisatawan-section .akumulasi-table .sub-label {
            font-size: 10px;
            color: #94a3b8;
            font-weight: 500;
        }
        .wisatawan-section .akumulasi-table .total-akumulasi {
            font-weight: 700;
            color: #0f3b5e;
        }
        .wisatawan-section .akumulasi-table .total-bulan-akumulasi {
            font-weight: 700;
            color: #eab308;
            background: #fefce8;
        }
        
        .tahun-nav {
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
            margin-bottom: 16px;
            padding: 6px 12px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e8ecf1;
            flex-wrap: wrap;
        }
        .tahun-nav .btn-tahun {
            padding: 4px 14px;
            border: none;
            border-radius: 6px;
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
        }
        .tahun-nav .tahun-label {
            font-weight: 700;
            color: #0f3b5e;
            font-size: 14px;
            padding: 0 12px;
        }
        
        .wisatawan-caption {
            padding: 10px 20px;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e8ecf1;
            background: #fafbfc;
            text-align: right;
        }
        .wisatawan-caption i {
            color: #0f3b5e;
            margin-right: 4px;
        }
        
        @media (max-width: 992px) {
            .sidebar { width: 200px; }
            .sidebar .brand .logo-wrapper img { height: 65px; }
            .sidebar .brand h2 { font-size: 16px; }
            .sidebar .menu li a { padding: 8px 16px; font-size: 13px; }
            
            .infografis-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .infografis-preview .preview-wrapper {
                max-width: 400px;
                margin: 0 auto;
            }
            .sumber-row {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .result-box {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
            .result-box .item .value { font-size: 18px; }
            .pdrb-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            .form-group-vertical {
                flex-direction: column;
                align-items: stretch;
                gap: 6px;
            }
            .form-group-vertical label {
                min-width: auto;
            }
            .form-group-vertical .input-wrapper {
                flex-wrap: wrap;
            }
            .kontribusi-row {
                flex-direction: column;
                text-align: center;
            }
            .infografis-panel .action-box {
                justify-content: center;
            }
            .ekraf-table-wrapper table { font-size: 12px; }
            .ekraf-table-wrapper table th,
            .ekraf-table-wrapper table td { padding: 6px 6px; }
            .wisatawan-section table { font-size: 11px; min-width: 700px; }
            .wisatawan-section table th,
            .wisatawan-section table td { padding: 4px 4px; }
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
            .filter-kategori { padding: 4px; gap: 4px; border-radius: 10px; }
            .filter-kategori .btn-kategori { padding: 6px 16px; font-size: 13px; }
            .result-box { grid-template-columns: 1fr; }
            .result-box .item .value { font-size: 20px; }
            .pdrb-grid { grid-template-columns: 1fr 1fr; }
            .form-section { padding: 16px; }
            .sumber-section { padding: 16px; }
            .pdrb-section { padding: 16px; }
            .form-group-vertical { padding: 8px 12px; }
            .file-status-item { flex-wrap: wrap; }
            .file-status-item .btn-hapus-file { margin-left: auto; }
            .kontribusi-row .value-kontribusi { font-size: 20px; }
            .infografis-grid { grid-template-columns: 1fr; }
            .infografis-preview .preview-wrapper { max-width: 400px; margin: 0 auto; }
            .infografis-panel .upload-box { flex-direction: column; align-items: stretch; }
            .infografis-panel .upload-box .file-upload-wrapper { min-width: auto; }
            .infografis-panel .upload-box .file-hint { white-space: normal; text-align: center; }
            .sumber-row { grid-template-columns: 1fr; }
            .wisatawan-section .wisatawan-header { flex-direction: column; align-items: flex-start; gap: 4px; }
            .wisatawan-section .wisatawan-header .wisatawan-sub { flex-wrap: wrap; }
            .tahun-nav { flex-wrap: wrap; }
        }
        @media (max-width: 480px) {
            .sidebar-brand .brand-logo { width: 28px; height: 28px; padding: 2px; }
            .sidebar-brand .brand-text h2 { font-size: 13px; }
            .nav-list li a { padding: 4px 8px; font-size: 11px; }
            .nav-list li a i { font-size: 12px; }
            .header h1 { font-size: 20px; }
            .main-content .overlay { padding: 12px; }
            .filter-kategori .btn-kategori { padding: 4px 12px; font-size: 12px; }
            .form-group-vertical .input-wrapper input { font-size: 13px; }
            .result-box { padding: 16px; }
            .result-box .item .value { font-size: 16px; }
            .pdrb-grid { grid-template-columns: 1fr; gap: 8px; }
            .kontribusi-row .value-kontribusi { font-size: 18px; }
            .infografis-panel .action-box { flex-direction: column; align-items: stretch; }
            .wisatawan-section table { font-size: 9px; min-width: 400px; }
            .wisatawan-section table th,
            .wisatawan-section table td { padding: 2px 2px; }
            .wisatawan-section table .wisatawan-input { font-size: 9px; padding: 1px 2px; min-width: 30px; }
            .wisatawan-section table th:first-child { min-width: 80px; font-size: 8px; }
            .wisatawan-section table td:first-child { font-size: 9px; }
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <div class="overlay">
        
        <div class="header">
            <div>
                <h1><i class="fas fa-chart-line"></i> Kelola IKU</h1>
                <span class="info"><?= $kategori_aktif == 'Wisatawan' ? 'Kelola data wisatawan per tahun' : 'Kelola data untuk perhitungan IKU' ?></span>
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
        
        <div class="filter-kategori">
            <?php 
            $icons = [
                'Makan Minum' => 'fa-utensils',
                'Wisatawan' => 'fa-globe-asia',
                'Ekraf' => 'fa-palette'
            ];
            foreach($kategori_list as $k): 
            ?>
            <a href="?kategori=<?= urlencode($k) ?>&tahun=<?= urlencode($tahun_aktif) ?>&sub=<?= urlencode($subkategori_wisata) ?>" class="btn-kategori <?= $kategori_aktif == $k ? 'active' : '' ?>">
                <span class="icon"><i class="fas <?= $icons[$k] ?? 'fa-tag' ?>"></i></span>
                <?= $k ?>
            </a>
            <?php endforeach; ?>
        </div>
        
        <!-- INFOGRAFIS SECTION -->
        <div class="infografis-section">
            <div class="infografis-title">
                <i class="fas fa-image"></i> Infografis <?= $kategori_aktif ?>
                <?php if ($kategori_aktif == 'Wisatawan'): ?>
                <span class="tahun-label"><i class="fas fa-calendar"></i> <?= $tahun_aktif ?></span>
                <?php endif; ?>
            </div>
            
            <div class="infografis-grid">
                <div class="infografis-preview">
                    <div class="preview-wrapper">
                        <div class="slide-wrapper" id="previewContainer">
                            <?php if ($infografis_exists && $infografis_path): ?>
                            <img src="<?= $infografis_path . '?v=' . time() ?>" alt="Infografis IKU" id="previewImage">
                            <?php else: ?>
                            <div class="empty-slide" id="emptyPreview">
                                <i class="fas fa-image"></i>
                                <span>Belum ada infografis</span>
                                <span style="font-size:11px; color:#94a3b8;">Upload gambar di panel sebelah kanan</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="preview-caption">
                        <i class="fas fa-info-circle"></i> Ukuran 16:9 (Rekomendasi: 1920 x 1080 px)
                    </div>
                </div>
                
                <div class="infografis-panel">
                    <div class="status-box" id="statusBox">
                        <div class="status-icon <?= $infografis_exists ? 'ada' : 'tidak' ?>" id="statusIcon">
                            <i class="fas <?= $infografis_exists ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                        </div>
                        <div class="status-text">
                            <span class="status-label <?= $infografis_exists ? 'ada' : 'tidak' ?>" id="statusLabel">
                                <?= $infografis_exists ? 'Infografis sudah terupload' : 'Belum ada infografis' ?>
                            </span>
                            <?php if ($infografis_exists && $infografis_file): ?>
                            <span class="file-name-text" id="fileNameText">
                                <i class="fas fa-file-image"></i> <?= htmlspecialchars($infografis_file) ?>
                            </span>
                            <?php else: ?>
                            <span class="file-name-text" id="fileNameText" style="display:none;"></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="upload-box">
                        <div class="file-upload-wrapper">
                            <input type="file" name="infografis" id="infografisInput" accept=".jpg,.jpeg,.png,.gif,.webp">
                            <span class="file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih Infografis</span>
                        </div>
                        <span class="file-hint"><i class="fas fa-info-circle"></i> JPG, PNG, GIF, WEBP | 5MB</span>
                        <button type="button" class="btn-upload-infografis" id="uploadInfografisBtn">
                            <i class="fas fa-upload"></i> Upload Infografis
                        </button>
                        <div class="upload-loading" id="uploadLoading">
                            <div class="spinner"></div>
                            <span>Mengupload...</span>
                        </div>
                        <div class="preview-status" id="previewStatus">
                            <i class="fas fa-eye"></i> Preview baru siap, klik "Upload Infografis" untuk menyimpan
                        </div>
                    </div>
                    
                    <div class="action-box">
                        <?php if ($infografis_exists): ?>
                        <a href="#" class="btn-delete-icon" id="deleteInfografisBtn" title="Hapus Infografis">
                            <i class="fas fa-trash"></i>
                        </a>
                        <?php else: ?>
                        <a href="#" class="btn-delete-icon disabled" id="deleteInfografisBtn" title="Belum ada infografis untuk dihapus">
                            <i class="fas fa-trash"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
            <!-- RESULT BOX -->
            <div class="result-box" id="resultBox">
            <?php if ($kategori_aktif == 'Ekraf'): ?>
            <div class="item">
                <div class="label">PDRB EKRAF <span style="font-size:9px; opacity:0.6;">(Miliar)</span></div>
                <div class="value" id="displayPariwisata" style="color: #eab308;"><?= $total_ekraf_formatted ?></div>
            </div>
            <div class="item">
                <div class="label">PDRB ADHB SULTENG <span style="font-size:9px; opacity:0.6;">(Miliar)</span></div>
                <div class="value" id="displayTotal"><?= $pdrb_adhb_ekraf_rp_formatted ?></div>
            </div>
            <div class="item">
                <div class="label">PROPORSI EKRAF</div>
                <div class="value" id="displayHasil" style="color: #eab308;"><?= $proporsi_ekraf_formatted ?> <span class="persen">%</span></div>
            </div>
            <?php elseif ($kategori_aktif == 'Wisatawan'): ?>
            <div class="item">
                <div class="label">Wisatawan Nusantara</div>
                <div class="value" id="displayPariwisata"><?= number_format($total_nusantara, 0, ',', '.') ?></div>
            </div>
            <div class="item">
                <div class="label">Wisatawan Mancanegara</div>
                <div class="value" id="displayTotal"><?= number_format($total_mancanegara, 0, ',', '.') ?></div>
            </div>
            <div class="item">
                <div class="label">TOTAL KUNJUNGAN</div>
                <div class="value" id="displayHasil" style="color: #eab308;"><?= number_format($total_nusantara + $total_mancanegara, 0, ',', '.') ?></div>
            </div>
            <?php else: ?>
            <div class="item">
                <div class="label">Penyediaan Akomodasi & Mamin <span style="font-size:9px; opacity:0.6;">(Miliar)</span></div>
                <div class="value" id="displayPariwisata" style="color: #eab308;"><?= $nilai1_formatted ?></div>
            </div>
            <div class="item">
                <div class="label">PDRB ADHB SULTENG <span style="font-size:9px; opacity:0.6;">(Miliar)</span></div>
                <div class="value" id="displayTotal"><?= $nilai2_formatted ?></div>
            </div>
            <div class="item">
                <div class="label">KONTRIBUSI</div>
                <div class="value" id="displayHasil" style="color: #eab308;"><?= $hasil_formatted ?> <span class="persen">%</span></div>
            </div>
            <?php endif; ?>
        </div>

             <!-- TAHUN NAVIGATION - UNTUK SEMUA KATEGORI -->
             <div class="tahun-nav">
                <?php foreach($tahun_list as $t): ?>
                <a href="?kategori=<?= urlencode($kategori_aktif) ?>&tahun=<?= urlencode($t) ?>&sub=<?= urlencode($subkategori_wisata) ?>" 
                class="btn-tahun <?= $tahun_aktif == $t ? 'active' : '' ?>">
                <?= $t ?>
            </a>
            <?php endforeach; ?>
        </div>       
        
        <!-- FORM INPUT -->
        <form method="post" enctype="multipart/form-data" autocomplete="off" id="mainForm">
            
            <?php if ($kategori_aktif == 'Wisatawan'): ?>
            
            <!-- WISATAWAN TABLE -->
            <div class="wisatawan-section">
                <div class="wisatawan-header">
                    <div class="wisatawan-title">
                        <i class="fas fa-users"></i> Input Data Wisatawan <?= $tahun_aktif ?>
                    </div>
                    <div class="wisatawan-sub">
                        <?php foreach($subkategori_list as $sub): ?>
                        <a href="?kategori=Wisatawan&tahun=<?= urlencode($tahun_aktif) ?>&sub=<?= urlencode($sub) ?>" class="btn-sub <?= $subkategori_wisata == $sub ? 'active' : '' ?>">
                            <?= $sub ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="table-scroll">
                    <?php if ($subkategori_wisata == 'Akumulasi' && !empty($akumulasi_data)): ?>
                    <!-- AKUMULASI TABLE -->
                    <table class="akumulasi-table">
                        <thead>
                            <tr>
                                <th style="text-align:left;">Kab/Kota</th>
                                <th>Wisatawan Nusantara</th>
                                <th>Wisatawan Mancanegara</th>
                                <th style="min-width:80px;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $akumulasi_total = 0;
                            foreach($akumulasi_data as $data): 
                                $nus_total = $data['nusantara'] ? (float) $data['nusantara']['total'] : 0;
                                $man_total = $data['mancanegara'] ? (float) $data['mancanegara']['total'] : 0;
                                $total_kab = $nus_total + $man_total;
                                $akumulasi_total += $total_kab;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($data['kabkota']) ?></td>
                                <td class="text-right"><?= number_format($nus_total, 0, ',', '.') ?></td>
                                <td class="text-right"><?= number_format($man_total, 0, ',', '.') ?></td>
                                <td class="total-kab"><?= number_format($total_kab, 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="total-row">
                                <td style="font-weight:700; color:#0f3b5e;">Total</td>
                                <td class="total-kab" style="text-align:right;"><?= number_format($total_nusantara, 0, ',', '.') ?></td>
                                <td class="total-kab" style="text-align:right;"><?= number_format($total_mancanegara, 0, ',', '.') ?></td>
                                <td class="total-kab" style="text-align:right; font-size:16px; color:#dc2626;"><?= number_format($akumulasi_total, 0, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <!-- NUSANTARA / MANCANEGARA TABLE -->
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align:left;">Kab/Kota</th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>Mei</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Ags</th>
                                <th>Sep</th>
                                <th>Okt</th>
                                <th>Nov</th>
                                <th>Des</th>
                                <th style="min-width:80px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($wisatawan_data as $w): ?>
                            <tr>
                                <td><?= htmlspecialchars($w['kabkota']) ?></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][januari]" value="<?= number_format($w['januari'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="januari"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][februari]" value="<?= number_format($w['februari'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="februari"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][maret]" value="<?= number_format($w['maret'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="maret"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][april]" value="<?= number_format($w['april'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="april"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][mei]" value="<?= number_format($w['mei'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="mei"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][juni]" value="<?= number_format($w['juni'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="juni"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][juli]" value="<?= number_format($w['juli'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="juli"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][agustus]" value="<?= number_format($w['agustus'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="agustus"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][september]" value="<?= number_format($w['september'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="september"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][oktober]" value="<?= number_format($w['oktober'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="oktober"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][november]" value="<?= number_format($w['november'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="november"></td>
                                <td><input type="text" name="wisatawan[<?= $w['id'] ?>][desember]" value="<?= number_format($w['desember'], 0, ',', '.') ?>" class="wisatawan-input wisata-bulan" data-id="<?= $w['id'] ?>" data-bulan="desember"></td>
                                <td class="total-kab" data-total-kab="<?= $w['id'] ?>"><?= number_format($w['total'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <!-- Total Per Bulan -->
                            <tr class="total-row">
                                <td style="font-weight:700; color:#0f3b5e;">Total</td>
                                <?php 
                                $bulan_keys = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
                                foreach($bulan_keys as $key): 
                                    $total_bulan_val = isset($total_bulan[$key]) ? $total_bulan[$key] : 0;
                                ?>
                                <td class="total-bulan" data-total-bulan="<?= $key ?>"><?= number_format($total_bulan_val, 0, ',', '.') ?></td>
                                <?php endforeach; ?>
                                <td class="total-bulan" style="font-size:14px; color:#dc2626;" data-grand-total><?= number_format($total_keseluruhan, 0, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <div class="wisatawan-caption">
                    <i class="fas fa-edit"></i> Input angka kunjungan wisatawan per bulan (tanpa tanda pemisah)
                </div>
            </div>
            
            <!-- PDRB WISATAWAN -->
            <div class="pdrb-section">
                <div class="pdrb-title">
                    <i class="fas fa-chart-bar"></i> PDRB Wisatawan
                </div>
                <div class="pdrb-grid" style="grid-template-columns: 1fr 1fr 1fr;">
                    <div class="form-group">
                        <label>Target Mancanegara</label>
                        <input type="text" name="target" id="wisatawanTarget" placeholder="0" value="<?= isset($pdrb_data['target']) ? number_format($pdrb_data['target'], 0, ',', '.') : '0' ?>">
                        <div class="input-hint">Jumlah orang</div>
                    </div>
                    <div class="form-group">
                        <label>Realisasi Mancanegara</label>
                        <input type="text" name="realitas" id="realitasWisatawan" placeholder="0" value="<?= number_format($total_mancanegara, 0, ',', '.') ?>" readonly style="background:#f1f5f9; cursor:not-allowed;">
                        <div class="input-hint">Total kunjungan mancanegara (otomatis)</div>
                    </div>
                    <div class="form-group">
                        <label>Capaian <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="capaian" id="wisatawanCapaian" placeholder="0,000" value="<?= $capaian_formatted ?>" readonly style="background:#f1f5f9; cursor:not-allowed;">
                        <div class="input-hint">Otomatis dari (Realisasi / Target) × 100%</div>
                        <div class="predikat-box <?= $predikat['class'] ?>" id="predikatWisatawan">
                            <i class="fas <?= $predikat['icon'] ?> predikat-icon"></i>
                            <span><?= $predikat['label'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php elseif ($kategori_aktif == 'Ekraf'): ?>

            <!-- Hidden field untuk memastikan jumlah baris Ekraf -->
            <input type="hidden" name="ekraf_count" value="<?= count($ekraf_data) ?>">
            
            <!-- EKRAF TABLE -->
            <div class="ekraf-table-wrapper">
                <div class="ekraf-header">
                    <div class="ekraf-title">
                        <i class="fas fa-calculator"></i> Input Data Ekraf
                    </div>
                </div>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:35px;">No</th>
                                <th style="min-width:220px; text-align:left;">Sektor</th>
                                <th style="width:100px;">Koofisien</th>
                                <th style="width:140px;">Nilai BPS (Miliar)</th>
                                <th style="width:170px;">Jumlah Rp.</th>
                                <th style="width:190px;">Hasil Penjumlahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            foreach($ekraf_data as $index => $e): 
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td>
                                    <input type="text" name="ekraf[<?= $index ?>][sektor]" value="<?= htmlspecialchars($e['sektor']) ?>" class="sektor-input" placeholder="Nama sektor">
                                </td>
                                <td>
                                    <input type="text" name="ekraf[<?= $index ?>][koofisien]" value="<?= number_format($e['koofisien'], 2, ',', '.') ?>" class="num-input" data-koofisien>
                                </td>
                                <td>
                                    <input type="text" name="ekraf[<?= $index ?>][nilai_bps]" value="<?= number_format($e['nilai_bps'], 2, ',', '.') ?>" class="num-input" data-nilai-bps>
                                </td>
                                <td class="text-right value-display" data-jumlah><?= number_format($e['jumlah_rp'], 0, ',', '.') ?></td>
                                <td class="text-right value-display" data-hasil><?= number_format($e['hasil_penjumlahan'] / 1000000000, 2, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="5" class="total-label">Total PDRB EKRAF (Milliar)</td>
                                <td class="total-value" data-total-ekraf><?= $total_ekraf_formatted ?></td>
                            </tr>
                            <tr class="adhb-row">
                                <td colspan="3" class="adhb-label">PDRB ADHB Sulawesi Tengah (Milliar)</td>
                                <td>
                                    <input type="text" name="pdrb_adhb_ekraf" id="pdrbAdhbEkraf" value="<?= $pdrb_adhb_ekraf_formatted ?>" class="num-input" style="max-width:150px;" placeholder="0,00">
                                </td>
                                <td></td>
                                <td class="adhb-value" data-adhb-rp><?= $pdrb_adhb_ekraf_rp_formatted ?></td>
                            </tr>
                            <tr class="proporsi-row">
                                <td colspan="5" class="proporsi-label">PROPORSI</td>
                                <td class="proporsi-value" data-proporsi><?= $proporsi_ekraf_formatted ?> %</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- PDRB EKRAF -->
            <div class="pdrb-section">
                <div class="pdrb-title">
                    <i class="fas fa-chart-bar"></i> PDRB Ekraf
                </div>
                <div class="pdrb-grid">
                    <div class="form-group">
                        <label>Target <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="target" id="ekrafTarget" placeholder="0,00" value="<?= $target_formatted ?>">
                        <div class="input-hint">Persen (%)</div>
                    </div>
                    <div class="form-group">
                        <label>Realisasi <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="realitas" id="ekrafRealisasi" placeholder="0,0000" value="<?= $proporsi_ekraf_formatted ?>" readonly>
                        <div class="input-hint">Otomatis dari Proporsi (%)</div>
                    </div>
                    <div class="form-group">
                        <label>Capaian <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="capaian" id="ekrafCapaian" placeholder="0,000" value="<?= $capaian_formatted ?>" readonly style="background:#f1f5f9; cursor:not-allowed;">
                        <div class="input-hint">Otomatis dari (Realisasi / Target) × 100%</div>
                        <div class="predikat-box <?= $predikat['class'] ?>" id="predikatEkraf">
                            <i class="fas <?= $predikat['icon'] ?> predikat-icon"></i>
                            <span><?= $predikat['label'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            
            <!-- MAKAN MINUM -->
            <div class="form-section">
                <div class="form-header">
                    <div class="form-title">
                        <i class="fas fa-edit"></i> Input Data PDRB
                    </div>
                    <div class="form-note">
                        <i class="fas fa-info-circle"></i> Angka dalam Miliar Rupiah
                    </div>
                </div>
                
                <div class="form-vertical">
                    <?php foreach($kriteria as $k): ?>
                    <div class="form-group-vertical">
                        <label>
                            <?= htmlspecialchars($k['nama_kriteria']) ?>
                        </label>
                        <div class="input-wrapper">
                            <input type="text" name="nilai[<?= $k['id'] ?>]" value="<?= number_format($k['nilai'], 2, ',', '.') ?>" placeholder="0,0000" class="input-nilai" autocomplete="off">
                            <span class="unit">Miliar</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="kontribusi-row">
                    <span class="label-kontribusi">
                        <i class="fas fa-calculator"></i> Kontribusi PDRB sektor penyediaan akomodasi dan makan minum terhadap total PDRB
                    </span>
                    <span class="value-kontribusi" id="kontribusiDisplay">
                        <?= $hasil_formatted ?> <span class="persen">%</span>
                    </span>
                </div>
            </div>
            
            <!-- PDRB MAKAN MINUM -->
            <div class="pdrb-section">
                <div class="pdrb-title">
                    <i class="fas fa-chart-bar"></i> PDRB Makan Minum
                </div>
                <div class="pdrb-grid">
                    <div class="form-group">
                        <label>Target <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="target" id="pdrbTarget" placeholder="0,00" value="<?= $target_formatted ?>">
                        <div class="input-hint">Persen (%)</div>
                    </div>
                    <div class="form-group">
                        <label>Realisasi <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="realitas" id="pdrbRealisasi" placeholder="0,0000" value="<?= $hasil_formatted ?>" readonly>
                        <div class="input-hint">Otomatis dari Kontribusi (%)</div>
                    </div>
                    <div class="form-group">
                        <label>Capaian <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="capaian" id="pdrbCapaian" placeholder="0,00" value="<?= $capaian_formatted ?>" readonly style="background:#f1f5f9; cursor:not-allowed;">
                        <div class="input-hint">Otomatis dari (Realisasi / Target) × 100%</div>
                        <div class="predikat-box <?= $predikat['class'] ?>" id="predikatMakanMinum">
                            <i class="fas <?= $predikat['icon'] ?> predikat-icon"></i>
                            <span><?= $predikat['label'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php endif; ?>
            
            <!-- SUMBER DATA -->
            <div class="sumber-section">
                <div class="sumber-title">
                    <i class="fas fa-database"></i> Sumber Data
                    <?php if ($kategori_aktif == 'Wisatawan'): ?>
                    <span class="tahun-label"><i class="fas fa-calendar"></i> <?= $tahun_aktif ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="sumber-row">
                    <div class="form-group">
                        <label>Link Sumber (Maks 5, pisahkan dengan enter)</label>
                        <textarea name="link_sumber" placeholder="https://example.com/sumber-data&#10;https://example.com/sumber-data-2"><?= htmlspecialchars($sumber['link_sumber'] ?? '') ?></textarea>
                        <div class="input-hint">Link referensi sumber data, maksimal 5 link</div>
                    </div>
                    <div class="form-group">
                        <label>Upload File (Maks 15 file)</label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="file_sumber[]" id="fileSumberInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" multiple>
                            <span class="file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih File</span>
                        </div>
                        <div class="input-hint">PDF, DOC, XLS, JPG, PNG | Max 10MB/file | Maks 15 file</div>
                        
                        <div class="file-list" id="filePreviewList"></div>
                        
                        <?php if (!empty($sumber['file_sumber'])): ?>
                        <div class="file-status-list">
                            <?php 
                            $files = explode('|', $sumber['file_sumber']);
                            foreach ($files as $f): 
                                if (empty($f)) continue;
                            ?>
                            <div class="file-status-item" id="file-<?= md5($f) ?>">
                                <span class="status-icon ada"><i class="fas fa-check-circle"></i></span>
                                <span class="status-text">
                                    <span class="nama-file"><?= htmlspecialchars($f) ?></span>
                                </span>
                                <a href="../uploads/iku/<?= $kategori_aktif ?>/<?= $f ?>" target="_blank" class="btn-lihat" title="Lihat File">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="?delete_file=1&filename=<?= urlencode($f) ?>&kategori=<?= urlencode($kategori_aktif) ?>&tahun=<?= urlencode($tahun_aktif) ?>&sub=<?= urlencode($subkategori_wisata) ?>" 
                                   class="btn-hapus-file" 
                                   onclick="return confirm('Yakin hapus file ini?')"
                                   title="Hapus File">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div style="margin-top:16px; text-align:right;">
            <button type="submit" name="update" value="1" class="btn-save" id="btnSave"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </form>
        
    </div>
</main>

<!-- CONFIRM POPUP -->
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-save"></i></div>
        <div class="confirm-title">Simpan Perubahan?</div>
        <div class="confirm-text">Apakah Anda yakin ingin menyimpan semua perubahan yang telah dilakukan?</div>
        <div class="confirm-actions">
            <button class="confirm-btn confirm-btn-cancel" id="confirmCancel">Batal</button>
            <button class="confirm-btn confirm-btn-confirm" id="confirmConfirm">Ya, Simpan</button>
        </div>
    </div>
</div>

<!-- SWEET ALERT -->
<div class="swal-overlay" id="swalOverlay">
    <div class="swal-box">
        <div class="swal-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="swal-title">Hapus Infografis?</div>
        <div class="swal-text">Apakah Anda yakin ingin menghapus infografis ini? Tindakan ini tidak dapat dibatalkan.</div>
        <div class="swal-actions">
            <button class="swal-btn swal-btn-cancel" id="swalCancel">Batal</button>
            <button class="swal-btn swal-btn-confirm" id="swalConfirm">Hapus</button>
        </div>
    </div>
</div>

<script>
// ============================================================
// CONFIRM SAVE - TANPA PREVENT DEFAULT
// ============================================================
var confirmOverlay = document.getElementById('confirmOverlay');
var confirmCancel = document.getElementById('confirmCancel');
var confirmConfirm = document.getElementById('confirmConfirm');
var mainForm = document.getElementById('mainForm');
var saveBtn = document.getElementById('btnSave');
var formSubmitted = false;

if (saveBtn) {
    saveBtn.addEventListener('click', function(e) {
        // Hanya tampilkan overlay jika form belum disubmit
        if (!formSubmitted) {
            e.preventDefault();
            confirmOverlay.classList.add('show');
        }
    });
}

if (confirmCancel) {
    confirmCancel.addEventListener('click', function() {
        confirmOverlay.classList.remove('show');
    });
}

if (confirmConfirm) {
    confirmConfirm.addEventListener('click', function() {
        confirmOverlay.classList.remove('show');
        formSubmitted = true;
        mainForm.submit();
    });
}

if (confirmOverlay) {
    confirmOverlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('show');
        }
    });
}

// ============================================================
// WISATAWAN - PERHITUNGAN REAL-TIME
// ============================================================
function hitungWisatawan() {
    var rows = document.querySelectorAll('.wisatawan-section table tbody tr');
    var bulanKeys = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    var totalPerBulan = {};
    var grandTotal = 0;
    
    bulanKeys.forEach(function(key) {
        totalPerBulan[key] = 0;
    });
    
    rows.forEach(function(row) {
        if (row.classList.contains('total-row')) return;
        
        var inputs = row.querySelectorAll('.wisata-bulan');
        var totalKab = 0;
        
        inputs.forEach(function(input) {
            if (input.disabled) return;
            var val = input.value.replace(/\./g, '').replace(',', '.') || '0';
            var num = parseFloat(val) || 0;
            totalKab += num;
            
            var bulan = input.dataset.bulan;
            if (bulan && totalPerBulan[bulan] !== undefined) {
                totalPerBulan[bulan] += num;
            }
        });
        
        var totalTd = row.querySelector('[data-total-kab]');
        if (totalTd) {
            totalTd.textContent = totalKab.toLocaleString('id-ID');
            totalTd.dataset.total = totalKab;
        }
        
        grandTotal += totalKab;
    });
    
    // Update total per bulan di table
    bulanKeys.forEach(function(key) {
        var td = document.querySelector('[data-total-bulan="' + key + '"]');
        if (td) {
            td.textContent = totalPerBulan[key].toLocaleString('id-ID');
        }
    });
    
    var grandTd = document.querySelector('[data-grand-total]');
    if (grandTd) {
        grandTd.textContent = grandTotal.toLocaleString('id-ID');
    }
    
    // ============================================================
    // UPDATE RESULT BOX - WISATAWAN
    // ============================================================
    var subkategori = '<?= $subkategori_wisata ?>';
    var displayPariwisata = document.getElementById('displayPariwisata');
    var displayTotal = document.getElementById('displayTotal');
    var displayHasil = document.getElementById('displayHasil');
    var realisasiInput = document.getElementById('realitasWisatawan');

    // Data dari database (nilai tetap dari PHP)
    var totalNusantaraDb = <?= $total_nusantara ?>;
    var totalMancanegaraDb = <?= $total_mancanegara ?>;

    var wisnus = 0;
    var wisman = 0;

    if (subkategori === 'Nusantara') {
        wisnus = grandTotal;
        wisman = totalMancanegaraDb;
    } 
    else if (subkategori === 'Mancanegara') {
        wisnus = totalNusantaraDb;
        wisman = grandTotal;
    } 
    else if (subkategori === 'Akumulasi') {
        var akumulasiRows = document.querySelectorAll('.akumulasi-table tbody tr:not(.total-row)');
        var nusAkum = 0;
        var manAkum = 0;
        akumulasiRows.forEach(function(row) {
            var tds = row.querySelectorAll('td');
            if (tds.length >= 3) {
                var nusVal = parseFloat(tds[1].textContent.replace(/\./g, '').replace(',', '.')) || 0;
                var manVal = parseFloat(tds[2].textContent.replace(/\./g, '').replace(',', '.')) || 0;
                nusAkum += nusVal;
                manAkum += manVal;
            }
        });
        wisnus = nusAkum;
        wisman = manAkum;
    }

    // UPDATE DISPLAY
    if (displayPariwisata) {
        displayPariwisata.textContent = wisnus.toLocaleString('id-ID');
    }
    if (displayTotal) {
        displayTotal.textContent = wisman.toLocaleString('id-ID');
    }
    if (displayHasil) {
        var totalKeseluruhan = wisnus + wisman;
        displayHasil.textContent = totalKeseluruhan.toLocaleString('id-ID');
    }
    if (realisasiInput) {
        realisasiInput.value = wisman.toLocaleString('id-ID');
    }

    // ===== HITUNG CAPAIAN WISATAWAN OTOMATIS =====
    var targetInput = document.getElementById('wisatawanTarget');
    var capaianInput = document.getElementById('wisatawanCapaian');
    
    if (targetInput && capaianInput && realisasiInput) {
        // Ambil nilai target (tanpa titik dan koma)
        var targetVal = targetInput.value.trim() || '0';
        var target = parseFloat(targetVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        // Ambil nilai realisasi (tanpa titik dan koma)
        var realisasiVal = realisasiInput.value.trim() || '0';
        var realisasi = parseFloat(realisasiVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        var capaian = 0;
        if (target > 0) {
            capaian = (realisasi / target) * 100;
            capaian = Math.round(capaian * 1000) / 1000;
        }
        
        var capaianFormatted = capaian.toFixed(3).replace('.', ',');
        capaianInput.value = capaianFormatted;

        updatePredikat(capaianFormatted, 'predikatWisatawan');
    }
}

// ============================================================
// FUNGSI: HITUNG EKRAF
// ============================================================
function hitungEkraf() {
    var total = 0;
    var rows = document.querySelectorAll('.ekraf-table-wrapper table tbody tr');
    
    rows.forEach(function(row) {
        if (row.classList.contains('total-row') || row.classList.contains('adhb-row') || row.classList.contains('proporsi-row')) {
            return;
        }
        
        var koofisienInput = row.querySelector('[data-koofisien]');
        var nilaiBpsInput = row.querySelector('[data-nilai-bps]');
        var jumlahTd = row.querySelector('[data-jumlah]');
        var hasilTd = row.querySelector('[data-hasil]');
        
        if (koofisienInput && nilaiBpsInput && jumlahTd && hasilTd) {
            var koofisienVal = koofisienInput.value || '0';
            var nilaiBpsVal = nilaiBpsInput.value || '0';
            
            var koofisien = parseFloat(koofisienVal.replace(/\./g, '').replace(',', '.')) || 0;
            var nilaiBps = parseFloat(nilaiBpsVal.replace(/\./g, '').replace(',', '.')) || 0;
            
            var jumlahRp = nilaiBps * 1000000000;
            var hasilPenjumlahan = jumlahRp * koofisien;
            
            jumlahTd.textContent = jumlahRp.toLocaleString('id-ID');
            hasilTd.textContent = (hasilPenjumlahan / 1000000000).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            total += hasilPenjumlahan;
        }
    });
    
    // UPDATE TOTAL EKRAF DI TABLE
    var totalEkrafEl = document.querySelector('[data-total-ekraf]');
    if (totalEkrafEl) {
        var totalMiliar = total / 1000000000;
        totalEkrafEl.textContent = totalMiliar.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    
    // UPDATE RESULT BOX - PDRB EKRAF (DALAM MILIAR)
    var displayPariwisata = document.getElementById('displayPariwisata');
    if (displayPariwisata) {
        var totalMiliar = total / 1000000000;
        displayPariwisata.textContent = totalMiliar.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    
    // UPDATE PDRB ADHB
    var adhbInput = document.getElementById('pdrbAdhbEkraf');
    var adhbRpTd = document.querySelector('[data-adhb-rp]');
    var displayTotal = document.getElementById('displayTotal');
    
    if (adhbInput && adhbRpTd) {
        var adhbVal = adhbInput.value || '0';
        var adhb = parseFloat(adhbVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        adhbRpTd.textContent = adhb.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        if (displayTotal) {
            displayTotal.textContent = adhb.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        // HITUNG PROPORSI
        var proporsiTd = document.querySelector('[data-proporsi]');
        var displayHasil = document.getElementById('displayHasil');
        var realisasiInput = document.getElementById('ekrafRealisasi');

        if (proporsiTd) {
            var proporsi = 0;
            var adhbRp = adhb * 1000000000;
            if (adhbRp > 0 && total > 0) {
                proporsi = (total / adhbRp) * 100;
            }
            proporsiTd.textContent = proporsi.toFixed(3).replace('.', ',') + ' %';
            
            if (displayHasil) {
                displayHasil.innerHTML = proporsi.toFixed(3).replace('.', ',') + ' <span class="persen">%</span>';
            }
            
            if (realisasiInput) {
                realisasiInput.value = proporsi.toFixed(4).replace('.', ',');
            }
            
            // PANGGIL FUNGSI CAPAIAN
            hitungCapaianEkrafOtomatis();
        }
    }
}

// ============================================================
// FUNGSI: HITUNG CAPAIAN EKRAF OTOMATIS (REAL-TIME)
// ============================================================
function hitungCapaianEkrafOtomatis() {
    var targetInput = document.getElementById('ekrafTarget');
    var capaianInput = document.getElementById('ekrafCapaian');
    var realisasiInput = document.getElementById('ekrafRealisasi');
    
    if (!targetInput || !capaianInput) return;
    
    var targetVal = targetInput.value || '0';
    var target = parseFloat(targetVal.replace(/\./g, '').replace(',', '.')) || 0;
    
    var realisasiVal = realisasiInput ? realisasiInput.value || '0' : '0';
    var realisasi = parseFloat(realisasiVal.replace(/\./g, '').replace(',', '.')) || 0;
    
    var capaian = 0;
    if (target > 0) {
        capaian = (realisasi / target) * 100;
        // BULATKAN KE 3 DESIMAL
        capaian = Math.round(capaian * 1000) / 1000;
    }
    
    var capaianFormatted = capaian.toFixed(2).replace('.', ',');
    capaianInput.value = capaianFormatted;
    
    console.log('Target:', target, 'Realisasi:', realisasi, 'Capaian:', capaian, 'Formatted:', capaianFormatted);

    updatePredikat(capaianFormatted, 'predikatEkraf');
}

// ============================================================
// MAKAN MINUM - PERHITUNGAN REAL-TIME
// ============================================================
function hitungOtomatis() {
    var inputs = document.querySelectorAll('.input-nilai');
    if (inputs.length < 2) return;
    
    var raw1 = inputs[0]?.value || '0';
    var raw2 = inputs[1]?.value || '0';
    
    // Hapus titik ribuan dan ubah koma ke titik
    var nilai1 = parseFloat(raw1.replace(/\./g, '').replace(',', '.')) || 0;
    var nilai2 = parseFloat(raw2.replace(/\./g, '').replace(',', '.')) || 0;
    
    // HITUNG KONTRIBUSI (dalam persen)
    var hasil = 0;
    if (nilai2 > 0) {
        hasil = (nilai1 / nilai2) * 100;
    }
    
    // ===== UPDATE DISPLAY =====
    var displayPariwisata = document.getElementById('displayPariwisata');
    var displayTotal = document.getElementById('displayTotal');
    var displayHasil = document.getElementById('displayHasil');
    var kontribusiDisplay = document.getElementById('kontribusiDisplay');
    var realisasiInput = document.getElementById('pdrbRealisasi');
    var capaianInput = document.getElementById('pdrbCapaian');
    var targetInput = document.getElementById('pdrbTarget');
    
    // Format nilai1 dan nilai2 dengan 2 desimal (karena dalam Miliar)
    var nilai1Formatted = nilai1.toFixed(2).replace('.', ',');
    var parts1 = nilai1Formatted.split(',');
    parts1[0] = parts1[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    nilai1Formatted = parts1.join(',');
    
    var nilai2Formatted = nilai2.toFixed(2).replace('.', ',');
    var parts2 = nilai2Formatted.split(',');
    parts2[0] = parts2[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    nilai2Formatted = parts2.join(',');
    
    // Update display
    if (displayPariwisata) {
        displayPariwisata.textContent = nilai1Formatted;
    }
    if (displayTotal) {
        displayTotal.textContent = nilai2Formatted;
    }
    
    // Tampilkan hasil kontribusi dengan 4 desimal (realisasi)
    if (displayHasil) {
        var hasilDisplay = hasil.toFixed(4).replace('.', ',');
        var parts3 = hasilDisplay.split(',');
        parts3[0] = parts3[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        displayHasil.innerHTML = parts3.join(',') + ' <span class="persen">%</span>';
    }
    
    if (kontribusiDisplay) {
        var kontribusiFormatted = hasil.toFixed(4).replace('.', ',');
        var partsK = kontribusiFormatted.split(',');
        partsK[0] = partsK[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        kontribusiDisplay.innerHTML = partsK.join(',') + ' <span class="persen">%</span>';
    }
    
    // SET REALISASI (4 desimal)
    if (realisasiInput) {
        realisasiInput.value = hasil.toFixed(4).replace('.', ',');
    }
    
    // ===== HITUNG CAPAIAN =====
    if (capaianInput && targetInput) {
        var targetVal = targetInput.value || '0';
        var target = parseFloat(targetVal.replace(/\./g, '').replace(',', '.')) || 0;
        
        var capaian = 0;
        if (target > 0) {
            capaian = (hasil / target) * 100;
            capaian = Math.round(capaian * 100) / 100;
        }
        
        var capaianFormatted = capaian.toFixed(2).replace('.', ',');
        capaianInput.value = capaianFormatted;
        
        // ===== UPDATE PREDIKAT REALTIME =====
        updatePredikat(capaianFormatted, 'predikatMakanMinum');
    }
}

// ============================================================
// FUNGSI UPDATE PREDIKAT
// ============================================================
function updatePredikat(capaianValue, predikatElementId) {
    var predikatEl = document.getElementById(predikatElementId);
    if (!predikatEl) return;
    
    var capaian = parseFloat(capaianValue.replace(/\./g, '').replace(',', '.')) || 0;
    var label = '';
    var className = '';
    var icon = '';
    
    if (capaian > 100) {
        label = 'ISTIMEWA';
        className = 'istimewa';
        icon = 'fa-star';
    } else if (capaian >= 80) {
        label = 'BAIK';
        className = 'baik';
        icon = 'fa-check-circle';
    } else if (capaian >= 60) {
        label = 'BUTUH PERBAIKAN';
        className = 'butuh-perbaikan';
        icon = 'fa-exclamation-triangle';
    } else if (capaian >= 20) {
        label = 'KURANG';
        className = 'kurang';
        icon = 'fa-times-circle';
    } else if (capaian > 0) {
        label = 'SANGAT KURANG';
        className = 'sangat-kurang';
        icon = 'fa-exclamation-circle';
    } else {
        label = 'BELUM ADA';
        className = 'belum-ada';
        icon = 'fa-minus-circle';
    }
    
    predikatEl.className = 'predikat-box ' + className;
    predikatEl.innerHTML = '<i class="fas ' + icon + ' predikat-icon"></i><span>' + label + '</span>';
}

// ============================================================
// EVENT LISTENERS
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    var tahunAktif = '<?= $tahun_aktif ?>';
    var kategoriAktif = '<?= $kategori_aktif ?>';
    var subAktif = '<?= $subkategori_wisata ?>';

    // ============================================================
    // WISATAWAN
    // ============================================================
    if (document.querySelector('.wisata-bulan')) {
        document.querySelectorAll('.wisata-bulan').forEach(function(el) {
            el.addEventListener('input', function() {
                hitungWisatawan();
            });
            el.addEventListener('change', function() {
                hitungWisatawan();
            });
        });
        var capaianInputWisatawan = document.getElementById('wisatawanCapaian');
        if (capaianInputWisatawan) {
            updatePredikat(capaianInputWisatawan.value, 'predikatWisatawan');
        }
        
        // Event listener khusus untuk Target Wisatawan (update capaian)
        var wisatawanTarget = document.getElementById('wisatawanTarget');
        if (wisatawanTarget) {
            wisatawanTarget.addEventListener('input', function() {
                hitungWisatawan();
            });
            wisatawanTarget.addEventListener('change', function() {
                hitungWisatawan();
            });
        }
        
        hitungWisatawan();

        var capaianMamin = document.getElementById('pdrbCapaian');
        if (capaianMamin) {
            updatePredikat(capaianMamin.value, 'predikatMakanMinum');
        }

        var capaianWisatawan = document.getElementById('wisatawanCapaian');
        if (capaianWisatawan) {
            updatePredikat(capaianWisatawan.value, 'predikatWisatawan');
        }

        var capaianEkraf = document.getElementById('ekrafCapaian');
        if (capaianEkraf) {
            updatePredikat(capaianEkraf.value, 'predikatEkraf');
        }
    }
        
    
    // EKRAF
    if (document.querySelector('[data-koofisien]')) {
        document.querySelectorAll('[data-koofisien], [data-nilai-bps], #pdrbAdhbEkraf, #ekrafTarget').forEach(function(el) {
            el.addEventListener('input', function() {
                hitungEkraf();
            });
            el.addEventListener('change', function() {
                hitungEkraf();
            });
        });
        hitungEkraf();
    }

    // Event listener khusus untuk Target Ekraf (update capaian)
    var ekrafTarget = document.getElementById('ekrafTarget');
    if (ekrafTarget) {
        ekrafTarget.addEventListener('input', function() {
            hitungCapaianEkrafOtomatis();
        });
        ekrafTarget.addEventListener('change', function() {
            hitungCapaianEkrafOtomatis();
        });
    }
    var capaianInputEkraf = document.getElementById('ekrafCapaian');
    if (capaianInputEkraf) {
        updatePredikat(capaianInputEkraf.value, 'predikatEkraf');
    }
    
    // MAKAN MINUM
    if (document.querySelector('.input-nilai')) {
        document.querySelectorAll('.input-nilai').forEach(function(el) {
            el.addEventListener('input', function() {
                hitungOtomatis();
            });
            el.addEventListener('change', function() {
                hitungOtomatis();
            });
        });
        hitungOtomatis();
        var capaianInputMamin = document.getElementById('pdrbCapaian');
        if (capaianInputMamin) {
            updatePredikat(capaianInputMamin.value, 'predikatMakanMinum');
        }

        
    }

    // Event listener untuk Target (agar capaian otomatis berubah)
    var pdrbTarget = document.getElementById('pdrbTarget');
    if (pdrbTarget) {
        pdrbTarget.addEventListener('input', function() {
            hitungOtomatis();
        });
        pdrbTarget.addEventListener('change', function() {
            hitungOtomatis();
        });
    }
    
    // ============================================================
    // INFOGRAFIS UPLOAD
    // ============================================================
    var infografisInput = document.getElementById('infografisInput');
    var previewImage = document.getElementById('previewImage');
    var emptyPreview = document.getElementById('emptyPreview');
    var previewContainer = document.getElementById('previewContainer');
    var previewStatus = document.getElementById('previewStatus');
    var statusIcon = document.getElementById('statusIcon');
    var statusLabel = document.getElementById('statusLabel');
    var fileNameText = document.getElementById('fileNameText');
    var deleteBtn = document.getElementById('deleteInfografisBtn');
    var uploadBtn = document.getElementById('uploadInfografisBtn');
    var uploadLoading = document.getElementById('uploadLoading');
    var kategoriAktif = '<?= $kategori_aktif ?>';
    var tahunAktif = '<?= $tahun_aktif ?>';
    var subAktif = '<?= $subkategori_wisata ?>';
    
    function updateStatusUploaded(fileName, filePath) {
        if (emptyPreview) {
            emptyPreview.style.display = 'none';
        }
        
        if (previewImage) {
            previewImage.src = filePath + '?v=' + Date.now();
            previewImage.style.display = 'block';
        } else {
            var img = document.createElement('img');
            img.id = 'previewImage';
            img.src = filePath + '?v=' + Date.now();
            img.alt = 'Infografis IKU';
            previewContainer.appendChild(img);
        }
        
        if (statusIcon) {
            statusIcon.className = 'status-icon ada';
            statusIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
        }
        if (statusLabel) {
            statusLabel.className = 'status-label ada';
            statusLabel.textContent = 'Infografis sudah terupload';
        }
        if (fileNameText) {
            fileNameText.style.display = 'block';
            fileNameText.innerHTML = '<i class="fas fa-file-image"></i> ' + fileName;
        }
        if (previewStatus) {
            previewStatus.classList.remove('show');
        }
        
        if (deleteBtn) {
            deleteBtn.className = 'btn-delete-icon';
            deleteBtn.style.pointerEvents = 'auto';
            deleteBtn.style.opacity = '1';
        }
        
        if (uploadLoading) {
            uploadLoading.classList.remove('show');
        }
    }
    
    function updateStatusEmpty() {
        if (emptyPreview) {
            emptyPreview.style.display = 'flex';
        }
        if (previewImage) {
            previewImage.style.display = 'none';
        }
        
        if (statusIcon) {
            statusIcon.className = 'status-icon tidak';
            statusIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
        }
        if (statusLabel) {
            statusLabel.className = 'status-label tidak';
            statusLabel.textContent = 'Belum ada infografis';
        }
        if (fileNameText) {
            fileNameText.style.display = 'none';
        }
        if (previewStatus) {
            previewStatus.classList.remove('show');
        }
        
        if (deleteBtn) {
            deleteBtn.className = 'btn-delete-icon disabled';
            deleteBtn.style.pointerEvents = 'none';
            deleteBtn.style.opacity = '0.3';
        }
    }
    
    if (infografisInput) {
        infografisInput.addEventListener('change', function(e) {
            var file = this.files[0];
            if (!file) return;
            
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB.');
                this.value = '';
                return;
            }
            
            var ext = file.name.split('.').pop().toLowerCase();
            var allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!allowed.includes(ext)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WEBP.');
                this.value = '';
                return;
            }
            
            var reader = new FileReader();
            reader.onload = function(e) {
                if (emptyPreview) {
                    emptyPreview.style.display = 'none';
                }
                
                if (previewImage) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                } else {
                    var img = document.createElement('img');
                    img.id = 'previewImage';
                    img.src = e.target.result;
                    img.alt = 'Preview Infografis';
                    previewContainer.appendChild(img);
                }
                
                if (statusIcon) {
                    statusIcon.className = 'status-icon ada';
                    statusIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
                }
                if (statusLabel) {
                    statusLabel.className = 'status-label ada';
                    statusLabel.textContent = 'Preview baru siap (belum tersimpan)';
                }
                if (fileNameText) {
                    fileNameText.style.display = 'block';
                    fileNameText.innerHTML = '<i class="fas fa-file-image"></i> ' + file.name + ' (preview)';
                }
                if (previewStatus) {
                    previewStatus.classList.add('show');
                    previewStatus.innerHTML = '<i class="fas fa-eye"></i> Preview baru siap, klik "Upload Infografis" untuk menyimpan';
                }
            };
            reader.readAsDataURL(file);
        });
    }
    
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            var fileInput = document.getElementById('infografisInput');
            var file = fileInput.files[0];
            
            if (!file) {
                alert('Pilih file terlebih dahulu!');
                return;
            }
            
            if (uploadLoading) {
                uploadLoading.classList.add('show');
            }
            
            var formData = new FormData();
            formData.append('infografis', file);
            formData.append('ajax_upload_infografis', 1);
            formData.append('kategori', kategoriAktif);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateStatusUploaded(data.file_name, data.file_path);
                    showNotification('success', data.message);
                } else {
                    alert('Error: ' + data.message);
                    if (uploadLoading) {
                        uploadLoading.classList.remove('show');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat upload!');
                if (uploadLoading) {
                    uploadLoading.classList.remove('show');
                }
            });
        });
    }
    
    var swalOverlay = document.getElementById('swalOverlay');
    var swalCancel = document.getElementById('swalCancel');
    var swalConfirm = document.getElementById('swalConfirm');
    var deleteUrl = '';
    
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.classList.contains('disabled')) {
                return;
            }
            deleteUrl = '?delete_infografis=1&kategori=' + encodeURIComponent(kategoriAktif) + '&tahun=' + encodeURIComponent(tahunAktif) + '&sub=' + encodeURIComponent(subAktif);
            swalOverlay.classList.add('show');
        });
    }
    
    if (swalCancel) {
        swalCancel.addEventListener('click', function() {
            swalOverlay.classList.remove('show');
        });
    }
    
    if (swalConfirm) {
        swalConfirm.addEventListener('click', function() {
            swalOverlay.classList.remove('show');
            
            if (uploadLoading) {
                uploadLoading.classList.add('show');
                uploadLoading.querySelector('span').textContent = 'Menghapus infografis...';
            }
            
            fetch(deleteUrl)
                .then(response => response.text())
                .then(() => {
                    updateStatusEmpty();
                    showNotification('success', 'Infografis berhasil dihapus!');
                    if (uploadLoading) {
                        uploadLoading.classList.remove('show');
                        uploadLoading.querySelector('span').textContent = 'Mengupload...';
                    }
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus!');
                    if (uploadLoading) {
                        uploadLoading.classList.remove('show');
                    }
                });
        });
    }
    
    if (swalOverlay) {
        swalOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    }
    
    function showNotification(type, message) {
        var oldAlert = document.querySelector('.alert-notification');
        if (oldAlert) {
            oldAlert.remove();
        }
        
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-' + type + ' alert-notification';
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '99999';
        alertDiv.style.maxWidth = '400px';
        alertDiv.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
        alertDiv.style.animation = 'slideDown 0.3s ease';
        alertDiv.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(function() {
            alertDiv.style.opacity = '0';
            alertDiv.style.transition = 'opacity 0.3s';
            setTimeout(function() {
                alertDiv.remove();
            }, 300);
        }, 4000);
    }
    
    var styleSheet = document.createElement('style');
    styleSheet.textContent = `
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    `;
    document.head.appendChild(styleSheet);
    
    // FILE SUMBER PREVIEW
    var fileInput = document.getElementById('fileSumberInput');
    var previewList = document.getElementById('filePreviewList');
    
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            previewList.innerHTML = '';
            var files = this.files;
            var maxFiles = 15;
            
            if (files.length > maxFiles) {
                alert('Maksimal ' + maxFiles + ' file!');
                this.value = '';
                return;
            }
            
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var size = (file.size / 1024 / 1024).toFixed(2);
                var ext = file.name.split('.').pop().toLowerCase();
                var icon = 'fa-file';
                
                if (['pdf'].includes(ext)) icon = 'fa-file-pdf';
                else if (['doc', 'docx'].includes(ext)) icon = 'fa-file-word';
                else if (['xls', 'xlsx'].includes(ext)) icon = 'fa-file-excel';
                else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) icon = 'fa-file-image';
                
                var div = document.createElement('div');
                div.className = 'file-item';
                div.innerHTML = `
                    <i class="fas ${icon}"></i>
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">(${size} MB)</span>
                    <span class="file-status-text"><i class="fas fa-check-circle"></i> siap upload</span>
                `;
                previewList.appendChild(div);
            }
            
            if (files.length > 0) {
                var info = document.createElement('div');
                info.style.cssText = 'font-size:11px; color:#0f3b5e; margin-top:4px; font-weight:500;';
                info.textContent = '📁 ' + files.length + ' file siap diupload. Klik "Simpan Perubahan" untuk menyimpan.';
                previewList.appendChild(info);
            }
        });
    }
    
    var successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.display = 'none';
        }, 5000);
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
});
</script>

</body>
</html>