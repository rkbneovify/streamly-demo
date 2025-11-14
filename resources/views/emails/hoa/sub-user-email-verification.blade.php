<!DOCTYPE html>
<html>

<head>
    <title>Email Verification</title>
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
                                <p>Hi <strong>{{ $user->name ?? $user->email }}</strong>,</p>

                                <p>You have been added as a <strong>Sub User</strong> under the StreamlyTV HOA account.
                                    To activate your access, please verify your email address.</p>

                                <p style="text-align:center;">
                                    <a href="{{ $verificationUrl }}" class="btn">VERIFY EMAIL</a>
                                </p>

                                <p>Once verified, your StreamlyTV access will be activated instantly.</p>

                                <p>If you did not request this, you may safely ignore this email.</p>
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
