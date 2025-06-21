<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Verifikasi Email</title>
</head>
<body>
    <p>Halo {{ $name }},</p>
    <p>Terima kasih telah mendaftar. Berikut adalah kode OTP untuk verifikasi email Anda:</p>
    <h2 style="color: #2c3e50;">{{ $otp }}</h2>
    <p>Kode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapapun.</p>
    <p>Jika Anda tidak melakukan pendaftaran, abaikan email ini.</p>
    <br>
    <p>Salam,</p>
    <p>Tim Administrasi</p>
</body>
</html>
