<!DOCTYPE html>
<html>

<head>
    <title>Forgot Passcode</title>
    <style>
        .content {
            padding: 25px;
            color: #444444;
            font-size: 16px;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 12px 22px;
            background-color: #ffd75e;
            color: #000000;
            font-size: 14px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 0; margin: 0;">

    <!-- Outer container -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 50px 0px;">
                <!-- Main email container -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">

                    <!-- Header with logo -->
                    <tr>
                        <td align="center" style="background-color: #006c93; padding: 20px;">
                            <img src="{{ asset('frontend/img/streamly-logo.png') }}" alt="Company Logo" width="150"
                                style="display: block;">
                        </td>
                    </tr>

                    <!-- Main content -->
                    <tr>
                        <td>
                            <div class="content">
                                <h2 style="color: #006c93;">Regenerate Passcode Request</h2>
                                <p>We received a request to regenerate your passcode. If you made this request, click
                                    the
                                    button below to set a new passcode.</p>

                                <p><strong>Security Notice:</strong> After you regenerate your passcode, you will be
                                    automatically signed out of all devices where your account is currently logged in.
                                    You will need to log in again using your new passcode.</p>

                                <a href="{{ $url }}" class="btn">Regenerate Passcode</a>

                                <p>This link will expire in 30 minutes .</p>

                                <p>If you did not request a regenerate passcode, please ignore this email.</p>
                                <p>Thank you</p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="background-color: #f4f4f4; padding: 20px; font-size: 12px; color: #777;">
                            © {{ date('Y') }} <a style="text-decoration: none;color:#0b6efd;"
                                href="{{ url('/') }}">Streamly</a>, LLC. All rights
                            reserved.<br>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
