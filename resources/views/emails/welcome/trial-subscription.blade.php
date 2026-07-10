<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Welcome to Streamly</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff"
                    style="margin:30px auto;border-radius:8px;overflow:hidden;">

                    <tr>
                        <td style="background:#111827;color:#ffffff;padding:25px;text-align:center;">
                            <h1 style="margin:0;">Welcome to Streamly!</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px;">

                            <p>Hi <strong>{{ $user->name }}</strong>,</p>

                            <p>
                                Your account has been created successfully, and your
                                <strong>7-day free trial</strong> is now active.
                            </p>

                            <h3>Login Credentials</h3>

                            <table cellpadding="8" cellspacing="0" width="100%"
                                style="border:1px solid #ddd;border-collapse:collapse;">
                                <tr>
                                    <td style="border:1px solid #ddd;"><strong>Username</strong></td>
                                    <td style="border:1px solid #ddd;">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #ddd;"><strong>Temporary Password</strong></td>
                                    <td style="border:1px solid #ddd;">{{ $temporaryPassword }}</td>
                                </tr>
                            </table>

                            <br>

                            <h3>🔒 Change Your Password</h3>

                            <p>
                                For your security, you'll be asked to create a new password
                                after your first login.
                            </p>

                            <p style="text-align:center;margin:30px 0;">
                                <a href="{{ $loginUrl }}"
                                    style="background:#2563eb;color:#ffffff;text-decoration:none;padding:14px 30px;border-radius:5px;font-weight:bold;">
                                    Login
                                </a>
                            </p>

                            <hr>

                            <h3>Trial & Subscription</h3>

                            <table cellpadding="8" cellspacing="0" width="100%"
                                style="border:1px solid #ddd;border-collapse:collapse;">
                                <tr>
                                    <td style="border:1px solid #ddd;"><strong>Free Trial</strong></td>
                                    <td style="border:1px solid #ddd;">
                                        {{ date('F j, Y', $trialStart) }} – {{ date('F j, Y', $trialEnd) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border:1px solid #ddd;"><strong>Plan</strong></td>
                                    <td style="border:1px solid #ddd;">
                                        {{ strtolower($plan) == 'month' ? 'Monthly' : ucfirst($plan) }} –
                                        ${{ number_format($amount / 100, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border:1px solid #ddd;"><strong>First Billing Date</strong></td>
                                    <td style="border:1px solid #ddd;">
                                        {{ date('F j, Y', $billingDate) }}
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top:20px;">
                                Your subscription will automatically renew every month after
                                the trial period. To avoid being charged, cancel auto-renewal
                                before <strong>{{ date('F j, Y', $billingDate) }}</strong>.
                            </p>

                            <hr>

                            <h3>Start Watching</h3>

                            <p>
                                Enjoy streaming on <strong>Web, Mobile, and TV devices.</strong>
                            </p>

                            <p>
                                Need help?
                                Contact us at
                                <a href="mailto:support@streamlytv.com">
                                    support@streamlytv.com
                                </a>.
                            </p>

                            <br>

                            <p>
                                Thank you,<br>
                                <strong>The Streamly Team</strong>
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
