<!DOCTYPE html>
<html>

<head>
    <title>Welcome </title>
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
                                <p class="lead">Thank you for subscribing to <strong>Streamly</strong>. Your account
                                    is now active — use the
                                    details below to access your streaming service.</p>




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

                                <div>
                                    <span class="label">Subscription Details</span>
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                        border="0">
                                        <tr>
                                            <td style="padding:6px 0;"><strong>Plan:</strong></td>
                                            <td style="padding:6px 0;">{{ $user->subscriptionLatest->plan_name }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td style="padding:6px 0;"><strong>Start Date:</strong></td>
                                            <td style="padding:6px 0;">
                                                {{ date('F j, Y', strtotime($user->subscriptionLatest->plan_start_at)) }}
                                            </td>
                                        </tr> --}}

                                        <tr>
                                            <td style="padding:6px 0;"><strong>Next Billing Date:</strong></td>
                                            <td style="padding:6px 0;">
                                                {{ date('F j, Y', strtotime($user->subscriptionLatest->plan_expires_at)) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div style="margin-top:14px;">
                                    <span class="label">Your Benefits</span>
                                    <ul class="list">
                                        <li>Unlimited access to movies, TV shows, and exclusive content</li>
                                        <li>Stream across all supported devices</li>
                                        <li>Personalized recommendations based on your viewing preferences</li>
                                    </ul>
                                </div>
                                <div class="support">
                                    <span class="label">Support</span>
                                    <div class="muted">If you need assistance, please contact us:</div>
                                    <div style="margin-top:8px;">
                                        <div><strong>Email:</strong> <a href="mailto:{{ $supportEmail }}"
                                                style="color:#0b6efd; text-decoration:none;">{{ $supportEmail }}</a>
                                        </div>
                                        {{-- <div><strong>Helpline:</strong> 1234567890</div> --}}
                                    </div>
                                </div>

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
