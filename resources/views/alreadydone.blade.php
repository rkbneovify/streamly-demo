<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            font-size: 50px;
            color: #00cc66;
            margin-bottom: 20px;
        }

        .confirmation-message {
            font-size: 20px;
            color: #333;
        }

        .back-button {
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="payment-container">
    <div class="confirmation-message">
        Hi<br>
        <?php echo $masteruserData->name; ?>
    </div>
        <div class="success-icon">&#10004;</div>
        <div class="confirmation-message">
            Thank you! Your payment has already successfully processed.
        </div>
        <!-- <a href="#" class="back-button">Back to Home</a> -->
    </div>
</body>
</html>
