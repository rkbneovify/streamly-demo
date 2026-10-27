<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Streamlytv Support Ticket</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f5f9; font-family:Arial, Helvetica, sans-serif; color:#263247;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="background-color:#f3f5f9; padding:30px 12px;">
        <tr>
            <td align="center">

                <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                    style="max-width:650px; background-color:#ffffff; border-radius:10px; overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#173b72; padding:28px 32px; color:#ffffff;">
                            <h1 style="margin:0; font-size:25px; font-weight:700; letter-spacing:0.5px;">
                                Streamlytv
                            </h1>
                            <p style="margin:8px 0 0; font-size:13px; color:#d8e5ff;">
                                Customer Support Request
                            </p>
                        </td>
                    </tr>

                    {{-- Main Content --}}
                    <tr>
                        <td style="padding:30px 32px;">

                            <h2 style="margin:0 0 12px; font-size:21px; color:#172b4d;">
                                Hi Streamlytv Team,
                            </h2>

                            <p style="margin:0 0 24px; font-size:14px; line-height:1.7; color:#5d687b;">
                                A new support ticket has been submitted. Please review the details below.
                            </p>

                            {{-- Ticket Number --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="background-color:#eef4ff; border:1px solid #d9e6ff; border-radius:8px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="margin:0 0 6px; font-size:12px; color:#627493;">
                                            Ticket No.
                                        </p>
                                        <p style="margin:0; font-size:20px; font-weight:700; color:#174ea6;">
                                            {{ $data['ticket_no']  }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Ticket Details --}}
                            <h3 style="margin:28px 0 14px; font-size:16px; color:#172b4d;">
                                Ticket Details
                            </h3>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="border-collapse:collapse; font-size:14px;">

                                <tr>
                                    <td width="35%"
                                        style="padding:12px; background:#f8fafc; border:1px solid #e5eaf1; color:#68758a;">
                                        Email
                                    </td>
                                    <td
                                        style="padding:12px; border:1px solid #e5eaf1; color:#263247; word-break:break-word;">
                                        <a href="mailto:{{ $email }}" style="color:#175cd3; text-decoration:none;">
                                            {{ $email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:12px; background:#f8fafc; border:1px solid #e5eaf1; color:#68758a;">
                                        Platform
                                    </td>
                                    <td style="padding:12px; border:1px solid #e5eaf1; color:#263247;">
                                        {{ $data['platform'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding:12px; background:#f8fafc; border:1px solid #e5eaf1; color:#68758a;">
                                        Device Model
                                    </td>
                                    <td style="padding:12px; border:1px solid #e5eaf1; color:#263247;">
                                        {{ $data['device_model'] }}
                                    </td>
                                </tr>



                                <tr>
                                    <td
                                        style="padding:12px; background:#f8fafc; border:1px solid #e5eaf1; color:#68758a;">
                                        Subject
                                    </td>
                                    <td style="padding:12px; border:1px solid #e5eaf1; color:#263247; font-weight:600;">
                                        {{ $data['subject'] }}
                                    </td>
                                </tr>
                            </table>

                            {{-- Message --}}
                            <h3 style="margin:28px 0 12px; font-size:16px; color:#172b4d;">
                                Message
                            </h3>

                            <div
                                style="padding:18px 20px; background:#f8fafc; border-left:4px solid #3478f6; border-radius:4px; font-size:14px; line-height:1.8; color:#465369; overflow-wrap:anywhere; text-align:justify;">
                                {!! $data['description'] !!}
                            </div>

                            <p style="margin:26px 0 0; font-size:14px; line-height:1.7; color:#5d687b;">
                                Please review this request and respond to the user as soon as possible.
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td
                            style="padding:22px 32px; background-color:#f8fafc; border-top:1px solid #e8edf4; text-align:center;">
                            <p style="margin:0 0 7px; font-size:13px; font-weight:600; color:#263247;">
                                Streamlytv Support
                            </p>
                            <p style="margin:0; font-size:12px; line-height:1.6; color:#8490a3;">
                                This is an automated support ticket notification.<br>
                                Please do not reply directly unless this mailbox is monitored.
                            </p>
                        </td>
                    </tr>

                </table>

                <p style="margin:18px 0 0; font-size:11px; color:#9aa4b2; text-align:center;">
                    &copy; {{ date('Y') }} Streamlytv. All rights reserved.
                </p>

            </td>
        </tr>
    </table>

</body>

</html>