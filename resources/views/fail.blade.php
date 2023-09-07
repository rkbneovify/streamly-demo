<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failure</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .failure-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .failure-icon {
            font-size: 50px;
            color: #ff3300;
            margin-bottom: 20px;
        }

        .failure-message {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .details {
            font-size: 16px;
            color: #555;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="failure-container">
        <div class="failure-icon">&#10006;</div>
        <div class="failure-message">
            Payment Failed
        </div>
        <div class="details">
            Unfortunately, your payment could not be processed.
        </div>
        <!-- <a href="#" class="back-link">Back to Home</a> -->
    </div>
</body>
</html>
