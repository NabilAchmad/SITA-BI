<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email Address</title>
</head>
<body>
    <p>Hi {{ $name }},</p>
    <p>Thank you for registering. Berikut adalah kode OTP untuk verifikasi email Anda:</p>
    <h2 style="font-weight: bold; font-size: 24px;">{{ $token }}</h2>
    <p>Masukkan kode ini pada halaman verifikasi untuk menyelesaikan proses pendaftaran.</p>
    <p>Jika Anda tidak membuat akun ini, abaikan email ini.</p>
    <p>Regards,<br>Your Application Team</p>
</body>
</html>
