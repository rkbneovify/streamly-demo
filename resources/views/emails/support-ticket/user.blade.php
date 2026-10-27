<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">



    <p>Hi {{ $userName }},</p>

    <p>
        Thank you for contacting <strong>Streamlytv Support!</strong>
    </p>

    <p>
        Your support ticket has been successfully submitted.
    </p>

    <h3>Ticket Details</h3>

    <table cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px;">
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Ticket ID</strong></td>
            <td style="border: 1px solid #ddd;">{{ $ticket->ticket_number }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Subject</strong></td>
            <td style="border: 1px solid #ddd;">{{ $ticket->subject }}</td>
        </tr>

        <tr>
            <td style="border: 1px solid #ddd;"><strong>Status</strong></td>
            <td style="border: 1px solid #ddd;">{{ ucwords($ticket->status) }}</td>
        </tr>
    </table>

    <p>
        Our support team is reviewing your request and will provide an update soon.
    </p>

    <p>
        Thank you for contacting Streamlytv Support.
    </p>

    <p>Thank you for your patience.</p>

    <p>
        Best regards,<br>
        <strong>Streamlytv Support Team</strong>
    </p>

    <hr>

    <small>
        This is an automated notification confirming that your support
        ticket has been created successfully. Please do not reply directly
        to this email. For further assistance, update your ticket through
        Streamlytv.
    </small>

</body>

</html>