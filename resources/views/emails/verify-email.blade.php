<!DOCTYPE html>
<html>

<head>
    <title>Verify Your Email Address</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td style="padding: 30px; background-color: #f9f9f9; border-radius: 5px;">
                            <p style="margin: 0 0 20px 0;">Hi {{ $name }},</p>
                            <p style="margin: 0 0 20px 0;">Thank you for registering. Berikut adalah kode OTP untuk
                                verifikasi email Anda:</p>

                            <div
                                style="background: #eee; padding: 15px; text-align: center; margin: 20px 0; border-radius: 5px;">
                                <h2 style="font-weight: bold; font-size: 24px; margin: 0; color: #333;">
                                    {{ $token }}</h2>
                            </div>

                            <p style="margin: 0 0 20px 0;">Masukkan kode ini pada halaman verifikasi untuk menyelesaikan
                                proses pendaftaran.</p>
                            <p style="margin: 0 0 20px 0;">Jika Anda tidak membuat akun ini, abaikan email ini.</p>

                            <p style="margin: 20px 0 0 0;">
                                Regards,<br>
                                <strong>Your Application Team</strong>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
