<?php

return [
    'api_key' => env('TRIPAY_API_KEY', 'DEV-kDvEeaLScxqMjjmET8WqOWYn2G0DRUMEibGRJFd2'),
    'private_key' => env('TRIPAY_PRIVATE_KEY', '3LGMh-icDui-vCXET-i80fI-rnWV3'),
    'merchant_code' => env('TRIPAY_MERCHANT_CODE', 'T45209'),
    'base_url' => env('TRIPAY_BASE_URL', 'https://tripay.co.id/api-sandbox'),
    'callback_url' => env('TRIPAY_CALLBACK_URL', 'https://website.pendhapawening.my.id/tripay_callback.php'),
    'return_url' => env('TRIPAY_RETURN_URL', 'http://127.0.0.1:8000/payment/return'),
];
