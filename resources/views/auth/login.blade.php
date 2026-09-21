<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Crypto Scanner & Trading Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: radial-gradient(circle at 50% 20%, rgba(16, 37, 66, 0.9) 0%, rgba(9, 19, 34, 1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            color: #fff;
            overflow-x: hidden;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
            box-sizing: border-box;
            z-index: 2;
        }

        .auth-card {
            background: rgba(23, 27, 36, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .auth-logo {
            font-size: 38px;
            margin-bottom: 8px;
            display: inline-block;
            filter: drop-shadow(0 0 12px rgba(0, 212, 160, 0.4));
        }

        .auth-title {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 6px 0;
            background: linear-gradient(135deg, #fff 0%, #a0aec0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .auth-subtitle {
            font-size: 13px;
            color: #8892a4;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .forgot-link {
            font-size: 12px;
            color: #00d4a0;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #4d9eff;
            text-decoration: underline;
        }

        .form-control {
            width: 100%;
            background: rgba(13, 17, 23, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            padding: 12px 16px;
            color: #fff;
            font-size: 14px;
            font-family: inherit;
            box-sizing: border-box;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #00d4a0;
            box-shadow: 0 0 0 3px rgba(0, 212, 160, 0.2);
            background: rgba(13, 17, 23, 0.95);
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 12px;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #8892a4;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #00d4a0 0%, #00b386 100%);
            border: none;
            border-radius: 10px;
            color: #091322;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(0, 212, 160, 0.35);
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(0, 212, 160, 0.5);
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: #8892a4;
        }

        .auth-footer a {
            color: #00d4a0;
            text-decoration: none;
            font-weight: 700;
        }

        .alert-error {
            background: rgba(255, 77, 109, 0.15);
            border: 1px solid rgba(255, 77, 109, 0.4);
            color: #ff4d6d;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: rgba(0, 212, 160, 0.12);
            border: 1px solid rgba(0, 212, 160, 0.3);
            color: #00d4a0;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .demo-credentials {
            background: rgba(77, 158, 255, 0.1);
            border: 1px dashed rgba(77, 158, 255, 0.4);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12px;
            margin-bottom: 20px;
            color: #a0aec0;
        }

        .demo-credentials strong {
            color: #4d9eff;
        }

        .risk-disclaimer {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 11px;
            color: #64748b;
            line-height: 1.5;
            text-align: center;
        }

        .risk-disclaimer span {
            color: #e2e8f0;
            font-weight: 600;
        }

        /* Modal dialog style for forgot password guidance */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }

        .modal-box {
            background: #171b24;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 24px;
            max-width: 380px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.8);
            color: #e2e8f0;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-top: 0;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-body {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .modal-close-btn {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">⚡</div>
            <h1 class="auth-title">Crypto Scanner Login</h1>
            <p class="auth-subtitle">Masuk untuk mengakses Signal Command Center</p>
        </div>

        @if(session('info'))
            <div class="alert-info">
                ℹ️ {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(isset($loginAllowed) && !$loginAllowed)
            <div class="login-disabled-box" style="background: rgba(255, 77, 109, 0.1); border: 1px dashed rgba(255, 77, 109, 0.4); border-radius: 12px; padding: 20px; text-align: center; color: #ff4d6d; margin-bottom: 20px;">
                <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700;">🔒 Login Dinonaktifkan</h3>
                <p style="margin: 0; font-size: 13px; color: #cbd5e1; line-height: 1.5;">
                    Akses autentikasi ke platform saat ini ditutup oleh Administrator melalui sistem pemeliharaan / konfigurasi lingkungan (<code style="color:#ff4d6d">ALLOW_LOGIN=false</code>).
                </p>
            </div>
        @else
            @if(config('auth.show_demo_credentials'))
                <div class="demo-credentials">
                    <strong>💡 Environment Demo Staging:</strong><br>
                    Email: <code style="color:#fff">admin@cryptoscanner.com</code><br>
                    Password: <code style="color:#fff">password123</code>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                </div>

                <div class="form-group">
                    <div class="form-label-wrapper">
                        <label class="form-label" for="password">Password</label>
                        <span class="forgot-link" onclick="openForgotModal()">Lupa Password?</span>
                    </div>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
                </div>

                <div class="form-actions">
                    <label class="remember-checkbox">
                        <input type="checkbox" name="remember" checked style="accent-color:#00d4a0">
                        Ingat Saya di Perangkat Ini
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    🔑 MASUK KE PLATFORM
                </button>
            </form>

            <div class="auth-footer">
                Belum memiliki akun? <a href="{{ route('register') }}">Daftar Akun Baru</a>
            </div>
        @endif

        <div class="risk-disclaimer">
            <span>⚠️ Disclaimer Risiko Trading:</span> Perdagangan aset kripto memiliki tingkat risiko yang tinggi. Semua indikator & sinyal dalam platform ini hanya sebagai alat bantu analisis teknis dan bukan merupakan nasehat keuangan (*Financial Advice*).
        </div>
    </div>
</div>

<!-- Modal Dialog Lupa Password -->
<div class="modal-overlay" id="forgotModal">
    <div class="modal-box">
        <h3 class="modal-title">🔐 Reset / Lupa Password</h3>
        <div class="modal-body">
            Untuk menjaga keamanan platform, pemulihan akun dilakukan secara terpusat oleh Administrator.
            <br><br>
            • Kontak tim IT internal / Admin terdaftar untuk mereset akun Anda.<br>
            • Jika Anda pemilik server, Anda dapat mengatur ulang password admin via terminal:<br>
            <code style="display:block; background:rgba(0,0,0,0.5); padding:8px; border-radius:6px; margin-top:6px; color:#00d4a0;">php artisan auth:secure-admin</code>
        </div>
        <button type="button" class="modal-close-btn" onclick="closeForgotModal()">Tutup</button>
    </div>
</div>

<script>
    function openForgotModal() {
        document.getElementById('forgotModal').style.display = 'flex';
    }
    function closeForgotModal() {
        document.getElementById('forgotModal').style.display = 'none';
    }
</script>

</body>
</html>
