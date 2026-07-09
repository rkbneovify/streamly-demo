<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Subscription Activated</title>
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
                            <h1 style="margin:0;">Subscription Activated!</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px;">

                            <p>Hi <strong>{{ $user->name }}</strong>,</p>

                            <p>
                                Thank you for subscribing to <strong>Streamly!</strong>
                            </p>

                            <p>
                                Your subscription is now <strong>active</strong>, and you can start watching
                                immediately.
                            </p>

                            <h3>Subscription Details</h3>

                            <table cellpadding="8" cellspacing="0" width="100%"
                                style="border:1px solid #ddd;border-collapse:collapse;">
                                <tr>
                                    <td style="border:1px solid #ddd;"><strong>Plan</strong></td>
                                    <td style="border:1px solid #ddd;">
                                        {{ strtolower($plan) == 'month' ? 'Monthly' : ucfirst($plan) }} –
                                        ${{ number_format($amount / 100, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border:1px solid #ddd;"><strong>Next Billing Date</strong></td>
                                    <td style="border:1px solid #ddd;">
                                        {{ date('F j, Y', $nextBillingDate) }}
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top:20px;">
                                Your subscription will automatically renew on
                                <strong>{{ date('F j, Y', $nextBillingDate) }}</strong>
                                unless canceled beforehand.
                            </p>

                            <p style="text-align:center;margin:35px 0;">
                                <a href="{{ $loginUrl }}"
                                    style="background:#2563eb;color:#ffffff;text-decoration:none;padding:14px 32px;border-radius:5px;font-weight:bold;">
                                    Login
                                </a>
                            </p>

                            <hr style="border:none;border-top:1px solid #e5e7eb;">

                            <h3>Start Watching</h3>

                            <p>
                                Enjoy streaming on <strong>Web, Mobile, and TV devices.</strong>
                            </p>

                            <p>
                                Need help?
                                Contact us at
                                <a href="mailto:support@streamlytv.com">support@streamlytv.com</a>.
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
