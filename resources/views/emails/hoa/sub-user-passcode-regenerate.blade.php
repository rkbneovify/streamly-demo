<!DOCTYPE html>
<html>

<head>
    <title>Regenerate Passcode</title>
    <style>
        .body {
            padding: 24px;
        }

        .lead {
            margin: 0 0 18px 0;
            font-size: 15px;
            color: #333;
            line-height: 1.4;
        }

        .card {
            background: #f8fafc;
            border: 1px solid #eef3fb;
            border-radius: 6px;
            padding: 16px;
            margin: 16px 0;
        }

        .label {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 6px;
            display: block;
        }

        .passcode {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 6px 0 10px 0;
            color: #111827;
        }

        .cta {
            display: inline-block;
            margin-top: 8px;
            padding: 10px 16px;
            border-radius: 6px;
            background: #ffd75e;
            color: #000000;
            text-decoration: none;
            font-weight: 600;
        }

        .list {
            padding-left: 18px;
            margin: 12px 0;
            color: #333;
        }

        .footer {
            padding: 20px 24px;
            font-size: 13px;
            color: #6b7280;
            background: #fbfdff;
            border-top: 1px solid #eef3fb;
        }

        .footer-link {
            color: #0b6efd;
            text-decoration: none;

        }

        .muted {
            color: #6b7280;
            font-size: 13px;
        }

        .support {
            margin-top: 10px;
        }

        @media (max-width:420px) {
            .header h1 {
                font-size: 18px;
            }

            .passcode {
                font-size: 18px;
            }
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
                            <div class="body">
                                <p class="lead">Hi {{ $user->name ?? $user->email }},</p>
                                <p>Your passcode has been successfully regenerated.</p>

                                <p>For your security, all devices previously logged in to your account have been signed
                                    out.
                                    You will need to log in again using your new passcode.</p>




                                <div class="card" role="region" aria-label="Passcode and Streaming Access">
                                    <span class="label">Login Credentials (Passcode Login)</span>
                                    <div class="passcode">{{ $user->passcode }}</div>

                                    <span class="label" style="margin-top:8px; display:block;">Streaming Access</span>
                                    <!-- Button-style link for visibility -->
                                    <p style="margin-top:12px;">To begin streaming, click the button above or visit the
                                        site directly.</p>

                                    <a href="{{ url('/') }}" class="cta" target="_blank"
                                        rel="noopener">Login</a>
                                    <div style="margin-top:10px;" class="muted">Or visit: <a href="{{ url('/') }}"
                                            target="_blank" rel="noopener"
                                            style="color:#0b6efd; text-decoration:none;">{{ url('/') }}</a>
                                    </div>
                                </div>



                                <p>If you did not regenerate your passcode, please contact support immediately.</p>


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
