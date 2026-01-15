<?php

function testAPI($method, $url, $data = null, $token = null) {
    $ch = curl_init();
    
    $headers = ['Content-Type: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

$baseUrl = 'http://127.0.0.1:8000/api';

echo "=== TEST 1: LOGIN (Kredensial Benar) ===\n";
$login = testAPI('POST', "$baseUrl/login", [
    'email' => 'admin@kasir.com',
    'password' => 'password'
]);
echo "Status: {$login['code']}\n";
echo "Response: " . json_encode($login['response'], JSON_PRETTY_PRINT) . "\n\n";

$token = $login['response']['data']['token'] ?? null;

if (!$token) {
    echo "ERROR: Token tidak ditemukan!\n";
    exit;
}

echo "=== TEST 2: LOGIN (Kredensial Salah) ===\n";
$loginFail = testAPI('POST', "$baseUrl/login", [
    'email' => 'admin@kasir.com',
    'password' => 'wrongpassword'
]);
echo "Status: {$loginFail['code']}\n";
echo "Response: " . json_encode($loginFail['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TEST 3: GET Products (Tanpa Token) ===\n";
$noToken = testAPI('GET', "$baseUrl/products");
echo "Status: {$noToken['code']}\n";
echo "Response: " . json_encode($noToken['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TEST 4: GET Products (Dengan Token) ===\n";
$products = testAPI('GET', "$baseUrl/products", null, $token);
echo "Status: {$products['code']}\n";
echo "Response: " . json_encode($products['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TEST 5: GET Product Detail (ID: 1) ===\n";
$productDetail = testAPI('GET', "$baseUrl/products/1", null, $token);
echo "Status: {$productDetail['code']}\n";
echo "Response: " . json_encode($productDetail['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TEST 6: CREATE Product ===\n";
$newProduct = testAPI('POST', "$baseUrl/products", [
    'name' => 'Test Product',
    'description' => 'Produk untuk testing',
    'price' => 15000,
    'stock' => 50,
    'category' => 'snack'
], $token);
echo "Status: {$newProduct['code']}\n";
echo "Response: " . json_encode($newProduct['response'], JSON_PRETTY_PRINT) . "\n\n";

$newProductId = $newProduct['response']['data']['id'] ?? null;

if ($newProductId) {
    echo "=== TEST 7: UPDATE Product (ID: $newProductId) ===\n";
    $updateProduct = testAPI('PUT', "$baseUrl/products/$newProductId", [
        'name' => 'Test Product Updated',
        'description' => 'Produk sudah diupdate',
        'price' => 20000,
        'stock' => 100,
        'category' => 'food'
    ], $token);
    echo "Status: {$updateProduct['code']}\n";
    echo "Response: " . json_encode($updateProduct['response'], JSON_PRETTY_PRINT) . "\n\n";
}

echo "=== TEST 8: CREATE Transaction ===\n";
$transaction = testAPI('POST', "$baseUrl/transactions", [
    'total_price' => 50000,
    'paid_amount' => 100000,
    'change' => 50000,
    'payment_method' => 'cash',
    'items' => [
        [
            'id' => 1,
            'qty' => 2,
            'price' => 15000
        ],
        [
            'id' => 2,
            'qty' => 1,
            'price' => 20000
        ]
    ]
], $token);
echo "Status: {$transaction['code']}\n";
echo "Response: " . json_encode($transaction['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TEST 9: GET Transactions ===\n";
$transactions = testAPI('GET', "$baseUrl/transactions", null, $token);
echo "Status: {$transactions['code']}\n";
echo "Response: " . json_encode($transactions['response'], JSON_PRETTY_PRINT) . "\n\n";

$transactionId = $transaction['response']['data']['id'] ?? null;
if ($transactionId) {
    echo "=== TEST 10: GET Transaction Detail (ID: $transactionId) ===\n";
    $transactionDetail = testAPI('GET', "$baseUrl/transactions/$transactionId", null, $token);
    echo "Status: {$transactionDetail['code']}\n";
    echo "Response: " . json_encode($transactionDetail['response'], JSON_PRETTY_PRINT) . "\n\n";
}

echo "=== TEST 11: GET Profile ===\n";
$profile = testAPI('GET', "$baseUrl/profile", null, $token);
echo "Status: {$profile['code']}\n";
echo "Response: " . json_encode($profile['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TEST 12: UPDATE Profile ===\n";
$updateProfile = testAPI('PUT', "$baseUrl/profile", [
    'name' => 'Admin Updated',
    'email' => 'admin@kasir.com'
], $token);
echo "Status: {$updateProfile['code']}\n";
echo "Response: " . json_encode($updateProfile['response'], JSON_PRETTY_PRINT) . "\n\n";

if ($newProductId) {
    echo "=== TEST 13: DELETE Product (ID: $newProductId) ===\n";
    $deleteProduct = testAPI('DELETE', "$baseUrl/products/$newProductId", null, $token);
    echo "Status: {$deleteProduct['code']}\n";
    echo "Response: " . json_encode($deleteProduct['response'], JSON_PRETTY_PRINT) . "\n\n";
}

echo "=== TEST 14: GET Product Not Found (ID: 9999) ===\n";
$notFound = testAPI('GET', "$baseUrl/products/9999", null, $token);
echo "Status: {$notFound['code']}\n";
echo "Response: " . json_encode($notFound['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TEST 15: LOGOUT ===\n";
$logout = testAPI('POST', "$baseUrl/logout", null, $token);
echo "Status: {$logout['code']}\n";
echo "Response: " . json_encode($logout['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== TESTING SELESAI ===\n";
