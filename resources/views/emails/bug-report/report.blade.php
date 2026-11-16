<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>New Bug Report</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <table width="600" cellpadding="20" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px;">

                    <tr>
                        <td>

                            <p>Dear {{ $bugData['name'] ?? 'User' }},</p>

                            <p>
                                We have received a new bug report with the following details:
                            </p>

                            <table width="100%" cellpadding="10" cellspacing="0"
                                style="border-collapse: collapse; margin: 20px 0;">

                                <tr>
                                    <td style="border: 1px solid #ddd; font-weight: bold; width: 35%;">
                                        Name
                                    </td>
                                    <td style="border: 1px solid #ddd;">
                                        {{ $bugData['name'] ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid #ddd; font-weight: bold;">
                                        Email
                                    </td>
                                    <td style="border: 1px solid #ddd;">
                                        {{ $bugData['email'] ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid #ddd; font-weight: bold;">
                                        Bug Title
                                    </td>
                                    <td style="border: 1px solid #ddd;">
                                        {{ $bugData['bug_title'] ?? '-' }}
                                    </td>
                                </tr>



                                <tr>
                                    <td style="border: 1px solid #ddd; font-weight: bold;">
                                        Priority
                                    </td>
                                    <td style="border: 1px solid #ddd;">
                                        {{ ucfirst($bugData['priority'] ?? '-') }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: 1px solid #ddd; font-weight: bold;">
                                        Platform
                                    </td>
                                    <td style="border: 1px solid #ddd;">
                                        {{ $bugData['platform'] ?? '-' }}
                                    </td>
                                </tr>



                            </table>


                            <!-- Description outside the table -->
                            <p style="font-weight: bold; margin-bottom: 8px;">
                                Description
                            </p>

                            <div style="border: 1px solid #ddd; padding: 12px; background-color: #fafafa;">
                                {{ $bugData['description'] ?? '-' }}
                            </div>


                            <p>
                                Our team will review the reported issue and take the necessary action.
                            </p>

                            <p>
                                If you have any additional information regarding this issue,
                                please contact our support team.
                            </p>

                            <br>

                            <p>
                                Best Regards,<br>
                                <strong>Team Streamly</strong>
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
