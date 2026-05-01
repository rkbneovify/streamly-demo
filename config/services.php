<?php

$servername = env('DB_HOST', '127.0.0.1');
$port = (int) env('DB_PORT', 3306);
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$dbname = env('DB_DATABASE');

// Best-effort: avoid taking down the whole app if DB isn't reachable yet (e.g. Docker startup)
$conn = new mysqli($servername, $username, $password, $dbname, $port);
if (!$conn->connect_error) {
    $sql = "SELECT stripe_key, stripe_secret FROM Stripe_keys WHERE status = 'active'";
    if ($result = $conn->query($sql)) {
        if ($row = mysqli_fetch_assoc($result)) {
            $stripeKey = $row['stripe_key'];
            $stripeSecret = $row['stripe_secret'];
        }
        $result->free();
    }
    $conn->close();
}

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => env('CASHIER_MODEL'),
        'key' => $stripeKey,
        'secret' => $stripeSecret,
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT')
    ],
    'usps' => [

        'userid' => env('USPS_USER_ID'), // string
        'verifyssl' => env('USPS_VERIFY_SSL'), // bool
        'env' => env('USPS_ENV') //string
    ],

];
