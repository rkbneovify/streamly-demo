<!DOCTYPE html>
<html>

<head>
    <title>Upgrade to Premium</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 0; margin: 0;">

    <!-- Outer container -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center">
                <!-- Main email container -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">

                    <!-- Header with logo -->
                    <tr>
                        <td align="center" style="background-color: #000c25; padding: 20px;">
                            <img src="{{ asset('frontend/img/streamly-logo.png') }}" alt="Company Logo" width="150"
                                style="display: block;">
                        </td>
                    </tr>

                    <!-- Main content -->
                    <tr>
                        <td>
                            <h2>Unlock More with Streamly TV Premium – Upgrade Today!</h2>
                            <p>Choose a plan that fits you best and start enjoying premium features!</p>

                            @foreach ($plans as $plan)
                                <div style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
                                    <h3>{{ $plan['name'] }}</h3>
                                    <p>{{ $plan['description'] }}</p>
                                    <p><strong>${{ $plan['amount'] }}</strong> / {{ $plan['interval'] }}</p>
                                    <a href="{{ $plan['url'] }}"
                                        style="display:inline-block; padding:8px 12px; background:#007bff; color:#fff; text-decoration:none;">
                                        Subscribe Now
                                    </a>
                                </div>
                            @endforeach

                            <p>Thank you for being with us!</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="background-color: #f4f4f4; padding: 20px; font-size: 12px; color: #777;">
                            © {{ date('Y') }} Streamly, LLC. All rights reserved.<br>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
