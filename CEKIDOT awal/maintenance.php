<?php
// maintenance.php - Halaman Maintenance untuk Website
// Letakkan file ini di root folder SI-PARI
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Dalam Pemeliharaan - CEKIDOT</title>
    <link rel="icon" href="assets/img/logo-sulteng.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* ============================================================
           RESET & BASE
           ============================================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0a1628;
            overflow: hidden;
            position: relative;
        }

        /* ============================================================
           ANIMATED BACKGROUND PARTICLES
           ============================================================ */
        .bg-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .bg-particles .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(234, 179, 8, 0.06);
            animation: floatParticle linear infinite;
        }

        .bg-particles .particle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -50px;
            animation-duration: 25s;
            background: rgba(234, 179, 8, 0.04);
        }

        .bg-particles .particle:nth-child(2) {
            width: 400px;
            height: 400px;
            bottom: -150px;
            left: -100px;
            animation-duration: 30s;
            background: rgba(15, 59, 94, 0.06);
        }

        .bg-particles .particle:nth-child(3) {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-duration: 20s;
            background: rgba(234, 179, 8, 0.03);
        }

        .bg-particles .particle:nth-child(4) {
            width: 150px;
            height: 150px;
            top: 20%;
            right: 20%;
            animation-duration: 18s;
            background: rgba(255, 255, 255, 0.02);
        }

        .bg-particles .particle:nth-child(5) {
            width: 250px;
            height: 250px;
            bottom: 20%;
            right: 10%;
            animation-duration: 22s;
            background: rgba(15, 59, 94, 0.05);
        }

        .bg-particles .particle:nth-child(6) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-duration: 15s;
            background: rgba(234, 179, 8, 0.04);
        }

        @keyframes floatParticle {
            0% {
                transform: translate(0, 0) scale(1) rotate(0deg);
            }
            25% {
                transform: translate(30px, -30px) scale(1.05) rotate(5deg);
            }
            50% {
                transform: translate(-20px, 20px) scale(0.95) rotate(-3deg);
            }
            75% {
                transform: translate(40px, 10px) scale(1.02) rotate(4deg);
            }
            100% {
                transform: translate(0, 0) scale(1) rotate(0deg);
            }
        }

        /* ============================================================
           ORB BACKGROUND EFFECT
           ============================================================ */
        .orb-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: orbFloat 20s ease-in-out infinite alternate;
        }

        .orb:nth-child(1) {
            width: 500px;
            height: 500px;
            background: #eab308;
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .orb:nth-child(2) {
            width: 400px;
            height: 400px;
            background: #0f3b5e;
            bottom: -100px;
            left: -100px;
            animation-delay: -5s;
        }

        .orb:nth-child(3) {
            width: 250px;
            height: 250px;
            background: #eab308;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -10s;
            opacity: 0.08;
        }

        @keyframes orbFloat {
            0% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -40px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(40px, -10px) scale(1.05);
            }
        }

        /* ============================================================
           GRID OVERLAY
           ============================================================ */
        .grid-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            opacity: 0.3;
        }

        /* ============================================================
           MAIN CONTENT
           ============================================================ */
        .maintenance-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 750px;
            padding: 20px;
            text-align: center;
        }

        /* ----- Card ----- */
        .maintenance-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border-radius: 28px;
            padding: 45px 40px 35px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 
                0 40px 80px rgba(0, 0, 0, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            animation: cardIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .maintenance-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(
                ellipse at center,
                rgba(234, 179, 8, 0.03) 0%,
                transparent 70%
            );
            animation: shimmer 10s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes shimmer {
            0% {
                transform: translate(-20%, -20%) scale(1);
            }
            100% {
                transform: translate(20%, 20%) scale(1.2);
            }
        }

        /* ----- Logo / Icon ----- */
        .maintenance-icon {
            position: relative;
            z-index: 1;
            margin-bottom: 20px;
        }

        .maintenance-icon .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(234, 179, 8, 0.15), rgba(234, 179, 8, 0.05));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(234, 179, 8, 0.15);
            position: relative;
            animation: pulseGlow 3s ease-in-out infinite;
        }

        .maintenance-icon .icon-wrapper .fa-tools {
            font-size: 36px;
            color: #eab308;
            filter: drop-shadow(0 0 20px rgba(234, 179, 8, 0.15));
        }

        @keyframes pulseGlow {
            0%, 100% {
                box-shadow: 0 0 40px rgba(234, 179, 8, 0.05);
            }
            50% {
                box-shadow: 0 0 80px rgba(234, 179, 8, 0.12);
            }
        }

        /* ----- Gear Spinner ----- */
        .gear-spinner {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 32px;
            height: 32px;
            background: rgba(234, 179, 8, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(234, 179, 8, 0.15);
            animation: spinGear 6s linear infinite;
        }

        .gear-spinner .fa-cog {
            font-size: 16px;
            color: #eab308;
        }

        @keyframes spinGear {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ----- Typography ----- */
        .maintenance-card .status-badge {
            display: inline-block;
            padding: 4px 18px;
            background: rgba(234, 179, 8, 0.12);
            border: 1px solid rgba(234, 179, 8, 0.15);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: #eab308;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            z-index: 1;
            margin-bottom: 14px;
        }

        .maintenance-card .status-badge .dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            background: #eab308;
            border-radius: 50%;
            margin-right: 8px;
            animation: blinkDot 1.5s ease-in-out infinite;
        }

        @keyframes blinkDot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        .maintenance-card h1 {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            position: relative;
            z-index: 1;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #ffffff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .maintenance-card .subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.6);
            position: relative;
            z-index: 1;
            font-weight: 300;
            max-width: 450px;
            margin: 0 auto 6px;
            line-height: 1.6;
        }

        .maintenance-card .description {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.35);
            position: relative;
            z-index: 1;
            font-weight: 300;
            max-width: 420px;
            margin: 0 auto 28px;
            line-height: 1.6;
        }

        /* ----- Update Log ----- */
        .update-log {
            position: relative;
            z-index: 1;
            margin-top: 4px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
        }

        .update-log .log-title {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.2);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .update-log .log-title i {
            margin-right: 6px;
            color: #eab308;
            opacity: 0.5;
        }

        .update-log .log-items {
            display: flex;
            flex-direction: column;
            gap: 5px;
            align-items: center;
        }

        .update-log .log-items .log-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.3);
            font-weight: 300;
        }

        .update-log .log-items .log-item .log-icon {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #eab308;
            flex-shrink: 0;
            animation: blinkDot 2s ease-in-out infinite;
        }

        .update-log .log-items .log-item .log-icon.done {
            background: #10b981;
            animation: none;
        }

        .update-log .log-items .log-item .log-icon.pending {
            background: #f59e0b;
            animation: blinkDot 1.5s ease-in-out infinite;
        }

        /* ----- Social Media ----- */
        .footer-social {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
        }

        .footer-social a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.04);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.3);
            font-size: 16px;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
        }

        .footer-social a:hover {
            background: rgba(234, 179, 8, 0.12);
            border-color: rgba(234, 179, 8, 0.2);
            color: #eab308;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 30px rgba(234, 179, 8, 0.1);
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 768px) {
            .maintenance-card {
                padding: 32px 24px 28px;
                border-radius: 20px;
            }

            .maintenance-card h1 {
                font-size: 26px;
            }

            .maintenance-card .subtitle {
                font-size: 14px;
            }

            .maintenance-card .description {
                font-size: 13px;
                margin-bottom: 22px;
            }

            .maintenance-icon .icon-wrapper {
                width: 64px;
                height: 64px;
            }

            .maintenance-icon .icon-wrapper .fa-tools {
                font-size: 28px;
            }

            .gear-spinner {
                width: 28px;
                height: 28px;
                top: -6px;
                right: -6px;
            }

            .gear-spinner .fa-cog {
                font-size: 14px;
            }

            .footer-social {
                gap: 12px;
            }

            .footer-social a {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            .update-log .log-items .log-item {
                font-size: 11px;
            }

            .bg-particles .particle:nth-child(1) {
                width: 200px;
                height: 200px;
            }

            .bg-particles .particle:nth-child(2) {
                width: 250px;
                height: 250px;
            }

            .orb:nth-child(1) {
                width: 300px;
                height: 300px;
            }

            .orb:nth-child(2) {
                width: 250px;
                height: 250px;
            }
        }

        @media (max-width: 480px) {
            .maintenance-card {
                padding: 24px 16px 20px;
                border-radius: 16px;
            }

            .maintenance-card h1 {
                font-size: 22px;
            }

            .maintenance-card .subtitle {
                font-size: 13px;
            }

            .maintenance-card .description {
                font-size: 12px;
                margin-bottom: 18px;
            }

            .maintenance-icon .icon-wrapper {
                width: 56px;
                height: 56px;
            }

            .maintenance-icon .icon-wrapper .fa-tools {
                font-size: 24px;
            }

            .gear-spinner {
                width: 24px;
                height: 24px;
                top: -5px;
                right: -5px;
            }

            .gear-spinner .fa-cog {
                font-size: 12px;
            }

            .maintenance-card .status-badge {
                font-size: 10px;
                padding: 3px 14px;
            }

            .footer-social {
                gap: 10px;
                padding-top: 16px;
            }

            .footer-social a {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }

            .update-log .log-items .log-item {
                font-size: 10px;
            }
        }

        /* ============================================================
           SCROLLBAR STYLING
           ============================================================ */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(234, 179, 8, 0.2);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(234, 179, 8, 0.3);
        }

        /* ============================================================
           SELECTION COLOR
           ============================================================ */
        ::selection {
            background: rgba(234, 179, 8, 0.2);
            color: #eab308;
        }
    </style>
</head>
<body>

    <!-- ============================================================
       BACKGROUND PARTICLES
       ============================================================ -->
    <div class="bg-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- ============================================================
       ORB BACKGROUND
       ============================================================ -->
    <div class="orb-container">
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
    </div>

    <!-- ============================================================
       GRID OVERLAY
       ============================================================ -->
    <div class="grid-overlay"></div>

    <!-- ============================================================
       MAIN CONTENT
       ============================================================ -->
    <div class="maintenance-container">
        <div class="maintenance-card">

            <!-- Icon -->
            <div class="maintenance-icon">
                <div class="icon-wrapper">
                    <i class="fas fa-tools"></i>
                    <div class="gear-spinner">
                        <i class="fas fa-cog"></i>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="status-badge">
                <span class="dot"></span>
                Sedang Dalam Pemeliharaan
            </div>

            <!-- Title -->
            <h1>Kami Segera Kembali</h1>

            <!-- Subtitle -->
            <p class="subtitle">
                Website sedang dalam proses pemeliharaan untuk memberikan pengalaman terbaik bagi Anda.
            </p>

            <!-- Description -->
            <p class="description">
                Tim kami sedang bekerja keras melakukan peningkatan dan perbaikan sistem. 
                Mohon bersabar, kami akan segera kembali dengan layanan yang lebih baik.
            </p>

            <!-- Update Log -->
            <div class="update-log">
                <div class="log-title">
                    <i class="fas fa-list-check"></i> Status Pemeliharaan
                </div>
                <div class="log-items">
                    <div class="log-item">
                        <span class="log-icon done"></span>
                        Perencanaan &amp; Persiapan Sistem
                    </div>
                    <div class="log-item">
                        <span class="log-icon done"></span>
                        Peningkatan Keamanan Server
                    </div>
                    <div class="log-item">
                        <span class="log-icon pending"></span>
                        Optimasi Database &amp; Performa
                    </div>
                    <div class="log-item">
                        <span class="log-icon pending"></span>
                        Uji Coba &amp; Finalisasi
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="footer-social">
                <a href="https://www.facebook.com/share/1XYixEkxXT/?mibextid=wwXIfr" target="_blank" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.tiktok.com/@dispar.sulteng?_r=1&_t=ZS-988tKvc8ZOu" target="_blank" aria-label="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://www.instagram.com/dinaspariwisatasulteng?igsh=MXVobWZ2aWIxdzlyYQ==" target="_blank" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.youtube.com/@DinasPariwisataSulteng" target="_blank" aria-label="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>

        </div>
    </div>

    <!-- ============================================================
       JAVASCRIPT - INTERACTIVE
       ============================================================ -->
    <script>
        // ============================================================
        // SMOOTH SCROLL BEHAVIOR
        // ============================================================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // ============================================================
        // PARALLAX EFFECT ON PARTICLES
        // ============================================================
        document.addEventListener('mousemove', function(e) {
            const particles = document.querySelectorAll('.particle');
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;

            particles.forEach((particle, index) => {
                const speed = 0.5 + (index * 0.1);
                particle.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
            });
        });

        // ============================================================
        // KEYBOARD SHORTCUT: ESC TO REFRESH (Optional)
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.location.reload();
            }
        });
    </script>

</body>
</html>