<!-- includes/header.php - Header untuk Halaman Tamu (Guest) -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEKIDOT - Dinas Pariwisata</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ===== OVERRIDE HEADER - TRANSPARAN & SCROLL ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: transparent;
            padding: 12px 0;
            border-bottom: none;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        /* Saat di-scroll, header menjadi solid */
        .navbar.scrolled {
            background: rgba(15, 59, 94, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 8px 0;
            border-bottom: 2px solid #eab308;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
        }

        /* Untuk halaman selain index, header selalu solid */
        .navbar.always-solid {
            background: rgba(15, 59, 94, 0.95) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            padding: 8px 0 !important;
            border-bottom: 2px solid #eab308 !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15) !important;
        }

        /* ===== PADDING TOP UNTUK HALAMAN NON-INDEX ===== */
        body.has-fixed-header {
            padding-top: 50px;
        }

        .navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Brand / Logo - Lebih Kecil */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            font-size: 18px;
            font-weight: 800;
            text-decoration: none;
            letter-spacing: -0.3px;
        }

        .nav-brand .brand-logo {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 2px;
            border: 1px solid rgba(255,255,255,0.15);
        }

        .nav-brand .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .nav-brand .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .nav-brand .brand-text .brand-name {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .nav-brand .brand-text .brand-name span {
            color: #eab308;
        }

        .nav-brand .brand-text .brand-sub {
            font-size: 7px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: -1px;
        }

        /* Saat di-scroll, teks tetap putih */
        .navbar.scrolled .nav-brand .brand-text .brand-name,
        .navbar.always-solid .nav-brand .brand-text .brand-name {
            color: #ffffff;
        }

        .navbar.scrolled .nav-brand .brand-text .brand-sub,
        .navbar.always-solid .nav-brand .brand-text .brand-sub {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Menu Navigasi - Lebih Kecil */
        .nav-menu {
            list-style: none;
            display: flex;
            gap: 2px;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-menu li a {
            display: block;
            padding: 6px 14px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .nav-menu li a:hover,
        .nav-menu li a.active {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .nav-menu li a.active {
            background: #eab308;
            color: #0f3b5e;
        }

        /* Login Button - Lebih Kecil */
        .btn-login-nav {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 14px;
            background: rgba(255, 255, 255, 0.10);
            color: #ffffff;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s;
        }

        .btn-login-nav:hover {
            background: #eab308;
            color: #0f3b5e;
            border-color: #eab308;
        }

        .btn-login-nav i {
            font-size: 13px;
        }

        /* Tombol Hamburger */
        .nav-toggle {
            display: none;
            background: none;
            border: none;
            color: #ffffff;
            font-size: 22px;
            cursor: pointer;
            padding: 4px 8px;
        }

        /* RESPONSIVE HEADER */
        @media (max-width: 992px) {
            .nav-menu li a {
                padding: 5px 10px;
                font-size: 12px;
            }
        }

        @media (max-width: 768px) {
            body.has-fixed-header {
                padding-top: 68px;
            }

            .navbar .container {
                padding: 0 16px;
            }

            .nav-toggle {
                display: block;
            }

            .nav-menu {
                display: none;
                flex-direction: column;
                width: 100%;
                padding: 10px 0 6px;
                border-top: 1px solid rgba(255, 255, 255, 0.08);
                background: transparent;
            }

            .nav-menu.open {
                display: flex;
            }

            .nav-menu li a {
                padding: 8px 12px;
                width: 100%;
                text-align: center;
                font-size: 14px;
            }

            .navbar.scrolled .nav-menu,
            .navbar.always-solid .nav-menu {
                background: rgba(15, 59, 94, 0.95);
            }

            .nav-brand .brand-text .brand-name {
                font-size: 16px;
            }

            .nav-brand .brand-logo {
                width: 28px;
                height: 28px;
            }

            .btn-login-nav {
                padding: 4px 12px;
                font-size: 11px;
            }

            .nav-menu .btn-login-nav {
                margin-top: 4px;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            body.has-fixed-header {
                padding-top: 60px;
            }

            .nav-brand .brand-text .brand-name {
                font-size: 14px;
            }

            .nav-brand .brand-text .brand-sub {
                font-size: 6px;
            }

            .nav-brand .brand-logo {
                width: 24px;
                height: 24px;
            }

            .navbar.scrolled,
            .navbar.always-solid {
                padding: 6px 0;
            }
        }
    </style>
</head>
<body class="<?= $current_page != 'index.php' ? 'has-fixed-header' : '' ?>">

<header>
    <nav class="navbar <?php if ($current_page != 'index.php') echo 'always-solid'; ?>" id="mainNav">
        <div class="container">
            <a href="index.php" class="nav-brand">
                <div class="brand-logo">
                    <img src="assets/img/logo-sulteng.png" alt="Logo Sulawesi Tengah" onerror="this.style.display='none';this.parentElement.innerHTML='<span style=\'font-size:20px;font-weight:900;color:#eab308;\'>S</span>'">
                </div>
                <div class="brand-text">
                    <span class="brand-name" style="font-size:20px; font-weight:800; letter-spacing:-0.3px; line-height:1.2;">
                        CEK<span style="color:#eab308;">IDOT</span>
                    </span>
                    <span class="brand-sub" style="font-size:8px; font-weight:500; color:rgba(255,255,255,0.55); letter-spacing:1.8px; text-transform:uppercase; display:block; margin-top:1px;">
                        CEK IKU DAN DOKUMEN TERPADU
                    </span>
                </div>
            </a>

            <!-- Tombol Hamburger -->
            <button class="nav-toggle" id="navToggle" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Menu Navigasi - TAMBAH MONEV -->
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>"><i class="fas fa-home"></i> Beranda</a></li>
                <li><a href="kirim-surat.php" class="<?= $current_page == 'kirim-surat.php' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Kirim Surat</a></li>
                <li><a href="akip.php" class="<?= $current_page == 'akip.php' ? 'active' : '' ?>"><i class="fas fa-clipboard-check"></i> AKIP</a></li>
                <li><a href="iki.php" class="<?= $current_page == 'iki.php' ? 'active' : '' ?>"><i class="fas fa-user-check"></i> IKI</a></li>
                <li><a href="iku.php" class="<?= $current_page == 'iku.php' ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> IKU</a></li>
                <li><a href="capaian.php" class="<?= $current_page == 'capaian.php' ? 'active' : '' ?>"><i class="fas fa-flag-checkered"></i> Capaian Program</a></li>
                <li><a href="monev.php" class="<?= $current_page == 'monev.php' ? 'active' : '' ?>"><i class="fas fa-chart-pie"></i> Monev</a></li>
                <li>
                    <a href="login.php" class="btn-login-nav">
                        <i class="fas fa-user-shield"></i>
                        <span class="login-text">Admin</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<script>
// ===== HEADER SCROLL EFFECT =====
document.addEventListener('DOMContentLoaded', function() {
    var navbar = document.getElementById('mainNav');
    var scrollThreshold = 80;

    // Jika bukan index, langsung solid (sudah pakai class always-solid)
    if (!navbar.classList.contains('always-solid')) {
        function handleScroll() {
            if (window.scrollY > scrollThreshold) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        handleScroll();
        window.addEventListener('scroll', handleScroll);
    } else {
        navbar.classList.add('scrolled');
    }

    // ===== NAV TOGGLE (Mobile) =====
    var toggleBtn = document.getElementById('navToggle');
    var navMenu = document.getElementById('navMenu');

    if (toggleBtn && navMenu) {
        toggleBtn.addEventListener('click', function() {
            navMenu.classList.toggle('open');
            var icon = this.querySelector('i');
            if (navMenu.classList.contains('open')) {
                icon.className = 'fas fa-times';
            } else {
                icon.className = 'fas fa-bars';
            }
        });

        navMenu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                navMenu.classList.remove('open');
                var icon = toggleBtn.querySelector('i');
                if (icon) icon.className = 'fas fa-bars';
            });
        });
    }
});

// ===== CEK AKTIF LINK UNTUK MONEV =====
// Ini untuk memastikan Monev aktif jika URL mengandung monev.php
(function() {
    var currentPage = window.location.pathname.split('/').pop();
    var links = document.querySelectorAll('.nav-menu a');
    links.forEach(function(link) {
        link.classList.remove('active');
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        }
    });
})();
</script>

<!-- Mulai konten utama -->
<div style="padding-top: 0;">