<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CEKIDOT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f3b5e;
            padding: 20px;
        }
        .reset-container {
            background: #fff;
            max-width: 480px;
            width: 100%;
            padding: 40px 36px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .reset-container .icon { font-size: 48px; color: #eab308; margin-bottom: 12px; }
        .reset-container h2 { font-size: 24px; color: #0f3b5e; margin-bottom: 4px; }
        .reset-container p { color: #64748b; font-size: 14px; margin-bottom: 20px; line-height: 1.6; }
        .reset-container .btn {
            display: inline-block; padding: 12px 32px;
            background: #0f3b5e; color: #fff; border: none;
            border-radius: 10px; font-size: 15px; font-weight: 600;
            text-decoration: none; transition: all 0.3s;
        }
        .reset-container .btn:hover {
            background: #eab308; transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(234,179,8,0.3);
        }
        .reset-container .btn i { margin-right: 8px; }
        .success-text { color: #16a34a; font-weight: 600; }
        .error-text { color: #dc2626; font-weight: 600; }
        .divider { width: 60px; height: 3px; background: #eab308; margin: 16px auto; border-radius: 2px; }
        @media (max-width: 480px) {
            .reset-container { padding: 28px 20px; }
            .reset-container h2 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="icon"><i class="fas fa-key"></i></div>
        <h2>Reset Password Admin</h2>
        <div class="divider"></div>
        
        @if(session('success'))
            <p class="success-text"><i class="fas fa-check-circle"></i> {{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p class="error-text"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</p>
        @endif
        
        <p style="margin-bottom:20px;">
            Klik tombol di bawah untuk mereset password admin ke default.
            <br><br>
            <strong>Username:</strong> admin<br>
            <strong>Password:</strong> password
        </p>
        
        <a href="{{ route('reset.password') }}" class="btn">
            <i class="fas fa-undo"></i> Reset Password
        </a>
        
        <p style="margin-top:16px; font-size:13px;">
            <a href="{{ route('login') }}" style="color:#0f3b5e; text-decoration:underline;">Kembali ke Login</a>
        </p>
    </div>
</body>
</html>