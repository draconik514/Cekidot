<?php
// login.php - Halaman Login Admin
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: admin/index.php');
    exit;
}

include 'config/database.php';

$error = '';
$success_anim = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi!';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_nama'] = $user['nama_admin'];
                $success_anim = true;
            } else {
                $error = 'Username atau password salah!';
            }
        } catch (PDOException $e) {
            $error = 'Error database: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - CEKIDOT</title>
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('assets/img/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 20px;
        }

        /* ============================================================
           OVERLAY - LEBIH TRANSPARAN
           ============================================================ */
        .login-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 59, 94, 0.30);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            z-index: 0;
        }

        /* ============================================================
           LOGIN CARD
           ============================================================ */
        .login-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 44px 40px 36px;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.20);
            border: 1px solid rgba(255, 255, 255, 0.30);
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ============================================================
           LOGO & BRAND
           ============================================================ */
        .login-logo {
            text-align: center;
            margin-bottom: 16px;
        }

        .login-logo img {
            height: 80px;
            width: auto;
            display: block;
            margin: 0 auto 6px;
        }

        .login-logo .brand-name {
            font-size: 28px;
            font-weight: 900;
            color: #0f3b5e;
            letter-spacing: 1px;
        }

        .login-logo .brand-name span {
            color: #eab308;
        }

        .login-logo .brand-sub {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* ============================================================
           DIVIDER
           ============================================================ */
        .login-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 18px 0 22px;
        }

        .login-divider .line {
            flex: 1;
            height: 1.5px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        }

        .login-divider .text {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ============================================================
           TITLE
           ============================================================ */
        .login-title {
            text-align: center;
            margin-bottom: 26px;
        }

        .login-title h2 {
            font-size: 20px;
            font-weight: 700;
            color: #0f3b5e;
        }

        .login-title p {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* ============================================================
           FORM
           ============================================================ */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .form-group .input-wrapper {
            position: relative;
        }

        .form-group .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
            transition: color 0.3s;
            z-index: 1;
        }

        .form-group .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 44px;
            border: 2px solid #e8ecf1;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
            background: #fafbfc;
            color: #1e293b;
            -webkit-box-shadow: 0 0 0 1000px #fafbfc inset !important;
            box-shadow: 0 0 0 1000px #fafbfc inset !important;
        }

        .form-group .input-wrapper input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #fafbfc inset !important;
            box-shadow: 0 0 0 1000px #fafbfc inset !important;
            -webkit-text-fill-color: #1e293b !important;
        }

        .form-group .input-wrapper input:focus {
            outline: none;
            border-color: #0f3b5e;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 59, 94, 0.06);
        }

        .form-group .input-wrapper input:focus ~ .input-icon {
            color: #0f3b5e;
        }

        .form-group .input-wrapper input::placeholder {
            color: #b0b8c4;
            font-size: 13px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 16px;
            padding: 4px 8px;
            transition: color 0.3s;
            z-index: 1;
            border-radius: 6px;
        }

        .toggle-password:hover {
            color: #0f3b5e;
            background: rgba(15, 59, 94, 0.06);
        }

        /* ============================================================
           ALERT
           ============================================================ */
        .alert-danger {
            background: rgba(254, 242, 242, 0.9);
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 18px;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(4px);
        }

        .alert-danger i {
            font-size: 18px;
            color: #dc2626;
        }

        /* ============================================================
           BUTTON
           ============================================================ */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0f3b5e 0%, #1a5276 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 6px;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(15, 59, 94, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            font-size: 17px;
        }

        .btn-login .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        .btn-login.loading i {
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ============================================================
           FOOTER
           ============================================================ */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .login-footer a {
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .login-footer a:hover {
            color: #0f3b5e;
        }

        /* ============================================================
           SUCCESS MODAL - PROFESIONAL, MINIMALIS
           ============================================================ */
        .success-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeInOverlay 0.4s ease-out;
        }

        .success-overlay.show {
            display: flex;
        }

        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .success-modal {
            background: #ffffff;
            border-radius: 24px;
            max-width: 400px;
            width: 100%;
            padding: 40px 32px 32px;
            text-align: center;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.25);
            animation: modalPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        /* === Subtle background glow === */
        .success-modal::before {
            content: '';
            position: absolute;
            top: -60%;
            left: -60%;
            width: 220%;
            height: 220%;
            background: radial-gradient(circle at 30% 20%, rgba(234, 179, 8, 0.04), transparent 50%);
            z-index: 0;
        }

        @keyframes modalPop {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* ===== SUCCESS ICON - CHECK MARK ===== */
        .success-modal .success-icon {
            position: relative;
            z-index: 1;
            width: 72px;
            height: 72px;
            margin: 0 auto 16px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: iconPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
            box-shadow: 0 4px 20px rgba(22, 163, 74, 0.15);
        }

        .success-modal .success-icon i {
            font-size: 34px;
            color: #16a34a;
            animation: checkBounce 0.6s ease 0.4s both;
        }

        @keyframes iconPop {
            from {
                opacity: 0;
                transform: scale(0.6);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes checkBounce {
            0% {
                opacity: 0;
                transform: scale(0) rotate(-15deg);
            }
            60% {
                transform: scale(1.1) rotate(3deg);
            }
            100% {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        /* ===== TEXT ===== */
        .success-modal h3 {
            position: relative;
            z-index: 1;
            font-size: 22px;
            font-weight: 700;
            color: #0f3b5e;
            margin-bottom: 2px;
        }

        .success-modal .greeting {
            position: relative;
            z-index: 1;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .success-modal .user-name {
            position: relative;
            z-index: 1;
            font-weight: 700;
            color: #0f3b5e;
            font-size: 18px;
        }

        /* ===== LOADING BAR ===== */
        .success-modal .loading-bar {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 4px;
            background: #e8ecf1;
            border-radius: 2px;
            margin: 22px 0 16px;
            overflow: hidden;
        }

        .success-modal .loading-bar .progress {
            height: 100%;
            background: linear-gradient(90deg, #0f3b5e, #eab308);
            border-radius: 2px;
            width: 0%;
            animation: progressLoad 2.5s ease-in-out forwards;
        }

        @keyframes progressLoad {
            0% { width: 0%; }
            20% { width: 20%; }
            40% { width: 45%; }
            60% { width: 70%; }
            80% { width: 88%; }
            100% { width: 100%; }
        }

        /* ===== REDIRECT TEXT ===== */
        .success-modal .redirect-text {
            position: relative;
            z-index: 1;
            font-size: 13px;
            color: #94a3b8;
            animation: blinkText 1.2s ease-in-out infinite;
            margin-bottom: 12px;
        }

        @keyframes blinkText {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        /* ===== DASHBOARD BUTTON ===== */
        .success-modal .btn-dashboard {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 28px;
            background: #0f3b5e;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .success-modal .btn-dashboard:hover {
            background: #0a2a44;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 59, 94, 0.25);
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px 28px;
                border-radius: 20px;
            }

            .login-logo img {
                height: 65px;
            }

            .login-logo .brand-name {
                font-size: 24px;
            }

            .login-title h2 {
                font-size: 18px;
            }

            .form-group .input-wrapper input {
                padding: 10px 14px 10px 38px;
                font-size: 13px;
            }

            .btn-login {
                padding: 12px;
                font-size: 14px;
            }

            .success-modal {
                padding: 28px 20px 24px;
            }

            .success-modal .success-icon {
                width: 60px;
                height: 60px;
            }

            .success-modal .success-icon i {
                font-size: 28px;
            }

            .success-modal h3 {
                font-size: 19px;
            }

            .success-modal .user-name {
                font-size: 16px;
            }
        }

        @media (max-width: 360px) {
            .login-card {
                padding: 24px 16px 20px;
            }

            .login-logo img {
                height: 55px;
            }

            .login-logo .brand-name {
                font-size: 20px;
            }

            .success-modal .success-icon {
                width: 52px;
                height: 52px;
            }

            .success-modal .success-icon i {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<!-- ===== OVERLAY ===== -->
<div class="login-overlay"></div>

<!-- ===== LOGIN CARD ===== -->
<div class="login-card">

    <!-- LOGO & BRAND -->
    <div class="login-logo">
        <img src="assets/img/logo-dispar.png" alt="Logo Dinas Pariwisata Sulawesi Tengah">
        <div class="brand-name">CEK<span>IDOT</span></div>
        <div class="brand-sub">Cek IKU Dan Dokumen Terpadu</div>
    </div>

    <!-- DIVIDER -->
    <div class="login-divider">
        <span class="line"></span>
        <span class="text">Login Admin</span>
        <span class="line"></span>
    </div>

    <!-- TITLE -->
    <div class="login-title">
        <h2>Selamat Datang</h2>
        <p>Masuk untuk mengelola konten website</p>
    </div>

    <!-- ERROR MESSAGE -->
    <?php if ($error): ?>
    <div class="alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= $error ?></span>
    </div>
    <?php endif; ?>

    <!-- FORM LOGIN -->
    <form method="post" id="loginForm" autocomplete="off">
        <div class="form-group">
            <label>Username</label>
            <div class="input-wrapper">
                <input type="text" name="username" id="usernameInput" placeholder="Masukkan username" required autofocus autocomplete="off">
                <span class="input-icon"><i class="fas fa-user"></i></span>
            </div>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="input-wrapper">
                <input type="password" name="password" id="passwordInput" placeholder="Masukkan password" required autocomplete="off">
                <span class="input-icon"><i class="fas fa-lock"></i></span>
                <button type="button" class="toggle-password" id="togglePassword" title="Tampilkan/Sembunyikan password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-login" id="loginBtn">
            <i class="fas fa-arrow-right-to-bracket"></i>
            <span class="btn-text">Masuk</span>
            <span class="spinner"></span>
        </button>
    </form>

    <!-- FOOTER -->
    <div class="login-footer">
        <a href="index.php">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

</div>

<!-- ============================================================
   SUCCESS MODAL - PROFESIONAL (CHECK MARK ONLY)
   ============================================================ -->
<div class="success-overlay" id="successOverlay">
    <div class="success-modal">
        <!-- Hanya check mark, tanpa maskot/emot -->
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h3>Login Berhasil</h3>
        <p class="greeting">Selamat datang kembali,</p>
        <p class="user-name" id="successName">Administrator</p>

        <div class="loading-bar">
            <div class="progress"></div>
        </div>

        <p class="redirect-text">Mengarahkan ke Dashboard...</p>

        <a href="admin/index.php" class="btn-dashboard" id="dashboardBtn">
            <i class="fas fa-arrow-right"></i> Dashboard
        </a>
    </div>
</div>

<!-- ============================================================
   JAVASCRIPT
   ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== PASSWORD TOGGLE =====
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
    }

    // ===== RESET FORM =====
    const loginForm = document.getElementById('loginForm');
    const usernameInput = document.getElementById('usernameInput');

    <?php if ($success_anim): ?>
    // Jika login berhasil, tampilkan modal sukses
    const successOverlay = document.getElementById('successOverlay');
    const successName = document.getElementById('successName');

    successName.textContent = '<?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Administrator') ?>';

    setTimeout(function() {
        successOverlay.classList.add('show');
    }, 300);

    setTimeout(function() {
        window.location.href = 'admin/index.php';
    }, 3500);
    <?php endif; ?>

    // ===== LOADING SPINNER =====
    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            <?php if (!$success_anim && !$error): ?>
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
            <?php endif; ?>
        });
    }

    // ===== ENTER KEY =====
    if (passwordInput) {
        passwordInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                loginForm.submit();
            }
        });
    }

    if (usernameInput) {
        usernameInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                loginForm.submit();
            }
        });
    }
});
</script>

</body>
</html>