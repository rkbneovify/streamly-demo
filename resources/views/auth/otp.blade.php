<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(to top left, #fff, #0089ff 0%, #002749 35%, #031c32 92%);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            text-align: center;
        }
        h2 {
            color: #e5e5e5;
            margin-bottom: 20px;
        }
        p {
            color: #bed8ff;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .otp {
            font-size: 36px;
            font-weight: bold;
            color: #0089ff;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 20px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>OTP Verification</h2>
        <p>Your OTP for verification is:</p>
        <p class="otp">{{ $otp }}</p>
        <p>This OTP is valid for 1 minute.</p>
        <div class="footer">
            <p>If you didn't request this OTP, please ignore this email.</p>
            <p>&copy; 2024 Streamly</p>
        </div>
    </div>
</body>
</html>
