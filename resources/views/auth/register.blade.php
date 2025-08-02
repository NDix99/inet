<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Tidak Tersedia</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        /* Floating particles animation */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            pointer-events: none;
        }
        
        /* Floating particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255,255,255,0.6);
            border-radius: 50%;
            animation: float 6s infinite linear;
        }
        
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 1s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 2s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 3s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 4s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 0.5s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 1.5s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 2.5s; }
        .particle:nth-child(10) { left: 15%; animation-delay: 3.5s; }
        
        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .container {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 48px 40px;
            text-align: center;
            max-width: 400px;
            position: relative;
            z-index: 1;
            animation: slideInUp 1s ease-out, pulse 3s ease-in-out infinite;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        .icon {
            font-size: 64px;
            margin-bottom: 18px;
            color: #ff5252;
            animation: shake 1s infinite alternate, glow 2s ease-in-out infinite alternate;
            text-shadow: 0 0 20px rgba(255,82,82,0.5);
        }
        
        @keyframes shake {
            0% { transform: rotate(-5deg);}
            100% { transform: rotate(5deg);}
        }
        
        @keyframes glow {
            from { text-shadow: 0 0 20px rgba(255,82,82,0.5); }
            to { text-shadow: 0 0 30px rgba(255,82,82,0.8), 0 0 40px rgba(255,82,82,0.6); }
        }
        
        h1 {
            font-size: 2.2rem;
            margin-bottom: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            animation: fadeInDown 1s ease-out 0.3s both;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        p {
            font-size: 1.1rem;
            margin-bottom: 0;
            color: #fff;
            text-shadow: 0 1px 5px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease-out 0.6s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .btn-home {
            margin-top: 28px;
            padding: 12px 32px;
            background: linear-gradient(45deg, #ff5252, #ff1744);
            color: #fff;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255,82,82,0.4);
            animation: bounceIn 1s ease-out 0.9s both;
        }
        
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .btn-home:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(255,82,82,0.6);
            background: linear-gradient(45deg, #ff1744, #d50000);
            animation: none;
        }
        
        /* Typing effect for the highlight text */
        .typing-effect {
            color: #ffeb3b;
            font-weight: 600;
            text-shadow: 0 0 10px rgba(255,235,59,0.5);
            animation: typing 3s steps(40, end), blink-caret 0.75s step-end infinite;
            border-right: 2px solid #ffeb3b;
            white-space: nowrap;
            overflow: hidden;
        }
        
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        
        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #ffeb3b; }
        }
    </style>
</head>
<body>
    <!-- Floating particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    <div class="container">
        <div class="icon">🚫</div>
        <h1>Maaf, Registrasi Ditutup!</h1>
        <p>Untuk saat ini, pendaftaran akun baru tidak tersedia.<br>
        Silakan hubungi admin jika kamu belum mempunyai akun<br>
        <span class="typing-effect">Gagal daftar? Santai bro, coba lain waktu 😎</span></p>
        <div style="margin-top: 2.5em;">
            <a href="{{ url('/') }}" class="btn-home">Kembali ke Beranda bro</a>
        </div>
    </div>
</body>
</html>