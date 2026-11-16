<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reply - Streamlytv Support</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f9; font-family:Arial,Helvetica,sans-serif; color:#333333;">

    <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
        style="background-color:#f4f6f9; padding:30px 10px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" role="presentation"
                    style="max-width:600px; width:100%; background:#ffffff; border-radius:8px; overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background:#155eef; padding:25px 30px; color:#ffffff;">
                            <h1 style="margin:0; font-size:24px;">Streamlytv Support</h1>
                            <p style="margin:8px 0 0; font-size:14px;">
                                Support Ticket Update
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:30px;">

                            <h2 style="margin:0 0 18px; font-size:21px; color:#222222;">
                                New Reply to Your Support Ticket
                            </h2>

                            <p style="font-size:15px; line-height:1.7;">
                                Hi {{ $data['user_name'] }},
                            </p>

                            <p style="font-size:15px; line-height:1.7;">
                                Our support team has replied to your ticket.
                                Please review the response below.
                            </p>

                            <!-- Ticket Details -->
                            <table width="100%" cellpadding="10" cellspacing="0" role="presentation"
                                style="border:1px solid #e5e7eb; border-collapse:collapse; margin:20px 0;">
                                <tr>
                                    <td style="border-bottom:1px solid #e5e7eb; color:#666; width:35%;">
                                        <strong>Ticket ID</strong>
                                    </td>
                                    <td style="border-bottom:1px solid #e5e7eb;">
                                        #{{ $supportTicket->ticket_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid #e5e7eb; color:#666;">
                                        <strong>Subject</strong>
                                    </td>
                                    <td style="border-bottom:1px solid #e5e7eb;">
                                        {{ $supportTicket->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid #e5e7eb; color:#666;">
                                        <strong>Status</strong>
                                    </td>
                                    <td style="border-bottom:1px solid #e5e7eb;">
                                        {{ ucwords($supportTicket->status) }}
                                    </td>
                                </tr>

                            </table>

                            <!-- Reply Message -->
                            <h3 style="font-size:16px; margin:25px 0 10px;">
                                Support Team Reply
                            </h3>

                            <div style="background:#f8fafc; border-left:4px solid #155eef;
                                        padding:18px; border-radius:4px;
                                        font-size:14px; line-height:1.7;
                                        overflow-wrap:anywhere; text-align:justify;">
                                {!! $data['description'] !!}
                            </div>

                            <p style="font-size:14px; line-height:1.7; margin-top:25px;">
                                If you need further assistance, please continue the
                                conversation through your support ticket in Streamlytv.
                            </p>

                            <!-- Optional Ticket Link -->
                            @if(!empty($data['url']))
                                <table cellpadding="0" cellspacing="0" role="presentation" style="margin:25px 0;">
                                    <tr>
                                        <td style="background:#155eef; border-radius:5px;">
                                            <a href="{{ $data['url'] }}"
                                                style="display:inline-block; padding:13px 22px;
                                                                                                                                                                              color:#ffffff; text-decoration:none;
                                                                                                                                                                              font-size:14px; font-weight:bold;">
                                                View Support Ticket
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="font-size:14px; line-height:1.7;">
                                Thank you for your patience.
                            </p>

                            <p style="font-size:14px; line-height:1.7; margin-bottom:0;">
                                Best regards,<br>
                                <strong>Streamlytv Support Team</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8fafc; padding:20px 30px;
                                   border-top:1px solid #e5e7eb; text-align:center;">
                            <p style="font-size:12px; color:#6b7280; line-height:1.6; margin:0;">
                                This is an automated notification from Streamlytv Support.
                                Please do not reply directly to this email.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>