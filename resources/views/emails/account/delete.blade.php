<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Account Deletion Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table width="600px" style="background: #ffffff; padding: 20px; border-radius: 8px;">



                    <tr>
                        <td>
                            <p>Hi {{ ucwords($user->name) }},</p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p>Your account has been <strong>successfully deleted</strong>.</p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p>
                                Please note that your active subscription has also been cancelled as part of this
                                process,
                                and no further charges will be applied.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p>
                                If you wish to reactivate your account in the future, you will need to contact our
                                support/admin team.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <br>
                            <p>Thanks,<br><strong>Team Streamly</strong></p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
