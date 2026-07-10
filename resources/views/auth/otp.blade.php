<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff"
                    style="margin:30px auto;border-radius:8px;overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background:#111827;color:#ffffff;padding:25px;text-align:center;">
                            <h1 style="margin:0;">Email Verification</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px;">

                            <p>Hi,</p>

                            <p>
                                Use the OTP below to verify your email and continue your
                                <strong>Streamly</strong> registration.
                            </p>

                            <!-- OTP Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:30px 0;">
                                <tr>
                                    <td align="center">
                                        <div
                                            style="
                                        display:inline-block;
                                        padding:18px 40px;
                                        font-size:32px;
                                        font-weight:bold;
                                        letter-spacing:8px;
                                        background:#f3f4f6;
                                        border:2px dashed #2563eb;
                                        color:#111827;
                                        border-radius:8px;">
                                            {{ $otp }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <p style="text-align:center;font-size:15px;">
                                <strong>This code is valid for 15 minutes.</strong>
                            </p>

                            <hr style="border:none;border-top:1px solid #e5e7eb;margin:30px 0;">

                            <p style="color:#6b7280;">
                                If you didn't request this OTP, please ignore this email.
                            </p>

                            <br>

                            <p>
                                Thank you,<br>
                                <strong>The Streamly Team</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f9fafb;padding:18px;text-align:center;font-size:13px;color:#6b7280;">
                            © {{ date('Y') }} Streamly. All Rights Reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
