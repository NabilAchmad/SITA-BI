<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email Address</title>
</head>
<body>
    <p>Hi {{ $name }},</p>
    <p>Thank you for registering. Please click the link below to verify your email address:</p>
    <p><a href="{{ $verificationUrl }}">Verify Email</a></p>
    <p>If you did not create an account, no further action is required.</p>
    <p>Regards,<br>Your Application Team</p>
</body>
</html>
