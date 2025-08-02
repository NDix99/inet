<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Tidak Tersedia</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: rgba(30,30,30,0.85);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);
            padding: 48px 40px;
            text-align: center;
            max-width: 400px;
        }
        .icon {
            font-size: 64px;
            margin-bottom: 18px;
            color: #ff5252;
            animation: shake 1s infinite alternate;
        }
        @keyframes shake {
            0% { transform: rotate(-5deg);}
            100% { transform: rotate(5deg);}
        }
        h1 {
            font-size: 2.2rem;
            margin-bottom: 12px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 0;
            color: #e0e0e0;
        }
        .btn-home {
            margin-top: 28px;
            padding: 10px 28px;
            background: #ff5252;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-home:hover {
            background: #ff1744;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🚫</div>
        <h1>Maaf, Registrasi Ditutup!</h1>
        <p>Untuk saat ini, pendaftaran akun baru tidak tersedia.<br>
        Silakan hubungi admin jika kamu belum mempunyai akun<br>
        <span style="color:#ff5252;font-weight:600;">Gagal daftar? Santai bro, coba lain waktu 😎</span></p>
        <a href="{{ url('/') }}" class="btn-home">Kembali ke Beranda</a>
    </div>
</body>
</html>