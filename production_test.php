<?php

echo "=== PRODUCTION MODE API TEST ===\n\n";

function apiTest($method, $url, $data = null, $token = null) {
    $ch = curl_init();
    
    $headers = ['Content-Type: application/json', 'Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => $response ? json_decode($response, true) : null,
        'error' => $error
    ];
}

$baseUrl = 'http://127.0.0.1:8000/api';
$allPassed = true;

// TEST 1: Login
echo "[TEST 1] Login dengan kredensial benar\n";
$login = apiTest('POST', "$baseUrl/login", [
    'email' => 'admin@kasir.com',
    'password' => 'password'
]);

if ($login['code'] === 200 && isset($login['response']['success']) && $login['response']['success'] === true) {
    echo "✅ PASSED - Login berhasil\n";
    $token = $login['response']['data']['token'] ?? null;
    echo "   Token: " . substr($token, 0, 20) . "...\n";
} else {
    echo "❌ FAILED - Login gagal\n";
    echo "   Code: {$login['code']}\n";
    echo "   Response: " . json_encode($login['response']) . "\n";
    $allPassed = false;
    exit(1);
}
echo "\n";

// TEST 2: Login dengan password salah
echo "[TEST 2] Login dengan password salah\n";
$loginFail = apiTest('POST', "$baseUrl/login", [
    'email' => 'admin@kasir.com',
    'password' => 'wrongpassword'
]);

if ($loginFail['code'] === 401 && isset($loginFail['response']['success']) && $loginFail['response']['success'] === false) {
    echo "✅ PASSED - Error handling bekerja dengan baik\n";
} else {
    echo "❌ FAILED - Error handling tidak sesuai\n";
    $allPassed = false;
}
echo "\n";

// TEST 3: Access tanpa token
echo "[TEST 3] Akses endpoint tanpa token\n";
$noToken = apiTest('GET', "$baseUrl/products");

if ($noToken['code'] === 401) {
    echo "✅ PASSED - Middleware autentikasi bekerja\n";
} else {
    echo "❌ FAILED - Middleware autentikasi tidak bekerja\n";
    $allPassed = false;
}
echo "\n";

// TEST 4: Get Products
echo "[TEST 4] Get semua produk\n";
$products = apiTest('GET', "$baseUrl/products", null, $token);

if ($products['code'] === 200 && isset($products['response']['success']) && $products['response']['success'] === true) {
    echo "✅ PASSED - Berhasil mendapatkan daftar produk\n";
    $productCount = count($products['response']['data']['data'] ?? []);
    echo "   Jumlah produk: $productCount\n";
} else {
    echo "❌ FAILED - Gagal mendapatkan produk\n";
    $allPassed = false;
}
echo "\n";

// TEST 5: Get Product Detail
echo "[TEST 5] Get detail produk (ID: 1)\n";
$productDetail = apiTest('GET', "$baseUrl/products/1", null, $token);

if ($productDetail['code'] === 200 && isset($productDetail['response']['data']['id'])) {
    echo "✅ PASSED - Berhasil mendapatkan detail produk\n";
    echo "   Nama: {$productDetail['response']['data']['name']}\n";
    echo "   Harga: Rp " . number_format($productDetail['response']['data']['price'], 0, ',', '.') . "\n";
} else {
    echo "❌ FAILED - Gagal mendapatkan detail produk\n";
    $allPassed = false;
}
echo "\n";

// TEST 6: Create Product
echo "[TEST 6] Buat produk baru\n";
$newProduct = apiTest('POST', "$baseUrl/products", [
    'name' => 'Test Product Production',
    'description' => 'Produk untuk testing production mode',
    'price' => 25000,
    'stock' => 100,
    'category' => 'snack'
], $token);

if ($newProduct['code'] === 201 && isset($newProduct['response']['data']['id'])) {
    echo "✅ PASSED - Berhasil membuat produk baru\n";
    $newProductId = $newProduct['response']['data']['id'];
    echo "   ID Produk: $newProductId\n";
} else {
    echo "❌ FAILED - Gagal membuat produk\n";
    $allPassed = false;
    $newProductId = null;
}
echo "\n";

// TEST 7: Update Product
if ($newProductId) {
    echo "[TEST 7] Update produk (ID: $newProductId)\n";
    $updateProduct = apiTest('PUT', "$baseUrl/products/$newProductId", [
        'name' => 'Test Product Updated',
        'description' => 'Produk sudah diupdate',
        'price' => 30000,
        'stock' => 150,
        'category' => 'food'
    ], $token);

    if ($updateProduct['code'] === 200 && $updateProduct['response']['data']['name'] === 'Test Product Updated') {
        echo "✅ PASSED - Berhasil update produk\n";
    } else {
        echo "❌ FAILED - Gagal update produk\n";
        $allPassed = false;
    }
    echo "\n";
}

// TEST 8: Create Transaction
echo "[TEST 8] Buat transaksi baru\n";
$transaction = apiTest('POST', "$baseUrl/transactions", [
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

if ($transaction['code'] === 201 && isset($transaction['response']['data']['id'])) {
    echo "✅ PASSED - Berhasil membuat transaksi\n";
    $transactionId = $transaction['response']['data']['id'];
    echo "   ID Transaksi: $transactionId\n";
    echo "   Total: Rp " . number_format($transaction['response']['data']['total_price'], 0, ',', '.') . "\n";
} else {
    echo "❌ FAILED - Gagal membuat transaksi\n";
    echo "   Response: " . json_encode($transaction['response']) . "\n";
    $allPassed = false;
    $transactionId = null;
}
echo "\n";

// TEST 9: Get Transactions
echo "[TEST 9] Get semua transaksi\n";
$transactions = apiTest('GET', "$baseUrl/transactions", null, $token);

if ($transactions['code'] === 200 && isset($transactions['response']['success'])) {
    echo "✅ PASSED - Berhasil mendapatkan daftar transaksi\n";
    $transactionCount = count($transactions['response']['data']['data'] ?? []);
    echo "   Jumlah transaksi: $transactionCount\n";
} else {
    echo "❌ FAILED - Gagal mendapatkan transaksi\n";
    $allPassed = false;
}
echo "\n";

// TEST 10: Get Transaction Detail
if ($transactionId) {
    echo "[TEST 10] Get detail transaksi (ID: $transactionId)\n";
    $transactionDetail = apiTest('GET', "$baseUrl/transactions/$transactionId", null, $token);

    if ($transactionDetail['code'] === 200 && isset($transactionDetail['response']['data']['items'])) {
        echo "✅ PASSED - Berhasil mendapatkan detail transaksi\n";
        $itemCount = count($transactionDetail['response']['data']['items']);
        echo "   Jumlah item: $itemCount\n";
    } else {
        echo "❌ FAILED - Gagal mendapatkan detail transaksi\n";
        $allPassed = false;
    }
    echo "\n";
}

// TEST 11: Get Profile
echo "[TEST 11] Get profil user\n";
$profile = apiTest('GET', "$baseUrl/profile", null, $token);

if ($profile['code'] === 200 && isset($profile['response']['data']['email'])) {
    echo "✅ PASSED - Berhasil mendapatkan profil\n";
    echo "   Nama: {$profile['response']['data']['name']}\n";
    echo "   Email: {$profile['response']['data']['email']}\n";
    echo "   Role: {$profile['response']['data']['role']}\n";
} else {
    echo "❌ FAILED - Gagal mendapatkan profil\n";
    $allPassed = false;
}
echo "\n";

// TEST 12: Update Profile
echo "[TEST 12] Update profil\n";
$updateProfile = apiTest('PUT', "$baseUrl/profile", [
    'name' => 'Admin Production',
    'email' => 'admin@kasir.com'
], $token);

if ($updateProfile['code'] === 200 && $updateProfile['response']['data']['name'] === 'Admin Production') {
    echo "✅ PASSED - Berhasil update profil\n";
} else {
    echo "❌ FAILED - Gagal update profil\n";
    $allPassed = false;
}
echo "\n";

// TEST 13: Product Not Found
echo "[TEST 13] Get produk yang tidak ada (ID: 99999)\n";
$notFound = apiTest('GET', "$baseUrl/products/99999", null, $token);

if ($notFound['code'] === 404 && $notFound['response']['success'] === false) {
    echo "✅ PASSED - Error 404 handling bekerja\n";
} else {
    echo "❌ FAILED - Error 404 handling tidak bekerja\n";
    $allPassed = false;
}
echo "\n";

// TEST 14: Delete Product
if ($newProductId) {
    echo "[TEST 14] Hapus produk test (ID: $newProductId)\n";
    $deleteProduct = apiTest('DELETE', "$baseUrl/products/$newProductId", null, $token);

    if ($deleteProduct['code'] === 200 && $deleteProduct['response']['success'] === true) {
        echo "✅ PASSED - Berhasil menghapus produk\n";
    } else {
        echo "❌ FAILED - Gagal menghapus produk\n";
        $allPassed = false;
    }
    echo "\n";
}

// TEST 15: Logout
echo "[TEST 15] Logout\n";
$logout = apiTest('POST', "$baseUrl/logout", null, $token);

if ($logout['code'] === 200 && $logout['response']['success'] === true) {
    echo "✅ PASSED - Berhasil logout\n";
} else {
    echo "❌ FAILED - Gagal logout\n";
    $allPassed = false;
}
echo "\n";

// TEST 16: Access dengan token yang sudah logout
echo "[TEST 16] Akses dengan token yang sudah logout\n";
$afterLogout = apiTest('GET', "$baseUrl/products", null, $token);

if ($afterLogout['code'] === 401) {
    echo "✅ PASSED - Token berhasil di-invalidate setelah logout\n";
} else {
    echo "❌ FAILED - Token masih valid setelah logout\n";
    $allPassed = false;
}
echo "\n";

// SUMMARY
echo "=================================\n";
echo "HASIL TESTING PRODUCTION MODE\n";
echo "=================================\n";

if ($allPassed) {
    echo "✅ SEMUA TEST BERHASIL!\n";
    echo "API siap untuk production.\n";
} else {
    echo "❌ ADA TEST YANG GAGAL!\n";
    echo "Silakan periksa error di atas.\n";
}

echo "\n";
