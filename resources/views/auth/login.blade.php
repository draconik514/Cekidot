<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - CEKIDOT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* ===== SAMA SEPERTI LOGIN.PHP ASLI ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('{{ asset('assets/img/background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 20px;
        }
        .login-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 59, 94, 0.30);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            z-index: 0;
        }
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
            box-shadow: 0 30px 80px rgba(0,0,0,0.20);
            border: 1px solid rgba(255,255,255,0.30);
            animation: fadeInUp 0.7s cubic-bezier(0.16,1,0.3,1);
        }
        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(40px) scale(0.96); }
            to { opacity:1; transform:translateY(0) scale(1); }
        }
        .login-logo { text-align: center; margin-bottom: 16px; }
        .login-logo img { height: 80px; width: auto; display: block; margin: 0 auto 6px; }
        .login-logo .brand-name { font-size: 28px; font-weight: 900; color: #0f3b5e; letter-spacing: 1px; }
        .login-logo .brand-name span { color: #eab308; }
        .login-logo .brand-sub { font-size: 11px; color: #94a3b8; font-weight: 500; letter-spacing: 3px; text-transform: uppercase; margin-top: 2px; }
        .login-divider { display: flex; align-items: center; gap: 14px; margin: 18px 0 22px; }
        .login-divider .line { flex: 1; height: 1.5px; background: linear-gradient(to right, transparent, #e2e8f0, transparent); }
        .login-divider .text { font-size: 11px; color: #94a3b8; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; }
        .login-title { text-align: center; margin-bottom: 26px; }
        .login-title h2 { font-size: 20px; font-weight: 700; color: #0f3b5e; }
        .login-title p { font-size: 13px; color: #94a3b8; margin-top: 4px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 4px; }
        .form-group .input-wrapper { position: relative; }
        .form-group .input-wrapper .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 15px; transition: color 0.3s; z-index: 1;
        }
        .form-group .input-wrapper input {
            width: 100%; padding: 12px 14px 12px 44px;
            border: 2px solid #e8ecf1; border-radius: 12px; font-size: 14px;
            font-family: inherit; transition: all 0.3s; background: #fafbfc; color: #1e293b;
            box-shadow: 0 0 0 1000px #fafbfc inset !important;
        }
        .form-group .input-wrapper input:focus {
            outline: none; border-color: #0f3b5e; background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15,59,94,0.06);
        }
        .form-group .input-wrapper input:focus ~ .input-icon { color: #0f3b5e; }
        .form-group .input-wrapper input::placeholder { color: #b0b8c4; font-size: 13px; }
        .toggle-password {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 16px;
            padding: 4px 8px; transition: color 0.3s; z-index: 1; border-radius: 6px;
        }
        .toggle-password:hover { color: #0f3b5e; background: rgba(15,59,94,0.06); }
        .alert-danger {
            background: rgba(254,242,242,0.9); color: #991b1b; padding: 12px 16px;
            border-radius: 12px; font-size: 13px; margin-bottom: 18px; border: 1px solid #fecaca;
            display: flex; align-items: center; gap: 10px; backdrop-filter: blur(4px);
        }
        .alert-danger i { font-size: 18px; color: #dc2626; }
        .btn-login {
            width: 100%; padding: 14px; background: linear-gradient(135deg, #0f3b5e 0%, #1a5276 100%);
            color: #fff; border: none; border-radius: 12px; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all 0.3s; display: flex; align-items: center;
            justify-content: center; gap: 10px; margin-top: 6px; letter-spacing: 0.5px;
            position: relative; overflow: hidden;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(15,59,94,0.35); }
        .btn-login:active { transform: translateY(0); }
        .btn-login i { font-size: 17px; }
        .btn-login .spinner {
            display: none; width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.3); border-top: 2px solid #ffffff;
            border-radius: 50%; animation: spin 0.8s linear infinite;
        }
        .btn-login.loading .spinner { display: inline-block; }
        .btn-login.loading .btn-text { display: none; }
        .btn-login.loading i { display: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .login-footer { text-align: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid rgba(0,0,0,0.05); }
        .login-footer a { color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px; }
        .login-footer a:hover { color: #0f3b5e; }
        @media (max-width: 480px) {
            .login-card { padding: 32px 24px 28px; border-radius: 20px; }
            .login-logo img { height: 65px; }
            .login-logo .brand-name { font-size: 24px; }
            .login-title h2 { font-size: 18px; }
            .form-group .input-wrapper input { padding: 10px 14px 10px 38px; font-size: 13px; }
            .btn-login { padding: 12px; font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="login-overlay"></div>

    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('assets/img/logo-dispar.png') }}" alt="Logo Dinas Pariwisata Sulawesi Tengah">
            <div class="brand-name">CEK<span>IDOT</span></div>
            <div class="brand-sub">Cek IKU Dan Dokumen Terpadu</div>
        </div>

        <div class="login-divider">
            <span class="line"></span>
            <span class="text">Login Admin</span>
            <span class="line"></span>
        </div>

        <div class="login-title">
            <h2>Selamat Datang</h2>
            <p>Masuk untuk mengelola konten website</p>
        </div>

        @if(session('error'))
        <div class="alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="post" id="loginForm" autocomplete="off">
            @csrf
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

        <div class="login-footer">
            <a href="{{ route('home') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
            });
        }

        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function() {
                const btn = document.getElementById('loginBtn');
                btn.classList.add('loading');
                btn.disabled = true;
            });
        }
    });
    </script>
</body>
</html>