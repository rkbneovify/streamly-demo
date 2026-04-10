<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Account Deletion Request – OTP Verification</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <table width="600" cellpadding="20" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px;">

                    <tr>
                        <td>


                            <p>Dear {{ $name ?? 'User' }},</p>

                            <p>
                                We have received a request to delete your account.
                                To proceed, please verify your identity using the OTP below:
                            </p>

                            <div style="text-align: center; margin: 30px 0;">
                                <span style="font-size: 28px; font-weight: bold; letter-spacing: 5px; color: #333;">
                                    {{ $otp }}
                                </span>
                            </div>

                            <p>
                                This code is valid for <strong>15 minutes</strong> and can be used only once.
                            </p>

                            <p>
                                If you did not initiate this request, please ignore this email
                                or contact our support team immediately to secure your account.
                            </p>

                            <br>

                            <p>Best Regards,<br>
                                <strong>Team Streamly</strong>
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
