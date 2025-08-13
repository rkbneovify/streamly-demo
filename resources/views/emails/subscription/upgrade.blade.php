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
                            <p>Hi {{ $user->name }},</p>

                            <p>We noticed you’re enjoying Streamly TV — why not take it to the next level?</p>
                            <p>Upgrade to Premium and unlock all the features you’ve been missing.</p>
                            <b>Why Go Premium?</b>
                            <ul>
                                <li><b>100+ Live TV Channels </b>– Local, national, and exclusive streams.</li>
                                <li><b>HD & 4K Ultra Streaming</b> – Crystal-clear picture quality.</li>
                                <li><b>Ad-Free On-Demand Library</b> – Movies and shows, anytime you want.</li>
                                <li><b>Multi-Device Access</b> – Watch on TV, mobile, tablet, and web.</li>
                                <li><b>Priority Support</b> – Fast, dedicated help whenever you need it.</li>
                            </ul>

                            <p>Please click the button below to log in to your account.</p>
                            <p>
                                <a href="{{ url('/auth/login') }}"
                                    style="
           display: inline-block;
           padding: 10px 20px;
           background-color: #ffd84e;
           color: #000;
           text-decoration: none;
           border-radius: 5px;
           font-weight: bold;
           font-family: sans-serif;
           transition: background-color 0.3s ease;
       "
                                    onmouseover="this.style.backgroundColor='#e6c442'"
                                    onmouseout="this.style.backgroundColor='#ffd84e'">
                                    Login Your Account
                                </a>
                            </p>



                            <b>How to Activate:</b>
                            <ol>
                                <li>Log in to your account using the details above.</li>
                                <li>After logging in, you’ll be taken directly to the premium subscription purchase
                                    page.</li>
                            </ol>
                            <p>If you have questions or need help, reply to this email and our support team will assist
                                you.</p>

                            <b>Need Help?</b>
                            <p>Our support team is ready 24/7 to answer your questions. Just reply to this email, and
                                we’ll take care of the rest.</p>
                            <p>We can’t wait for you to experience Streamly TV at its very best.</p>
                            <b>Best regards,</b>
                            <p> Streamly TV Team</p>
                            <a style="color: blue" href="{{ url('/') }}">Website</a> &nbsp; | &nbsp;
                            <a style="color: blue" href="{{ url('/help-and-support') }}">Contact Support</a>
                            <p></p>
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
