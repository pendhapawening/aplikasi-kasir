# 🔐 PANDUAN INTEGRASI TRIPAY PAYMENT GATEWAY

## 📋 OVERVIEW

Sistem kasir sekarang mendukung **2 metode pembayaran**:
1. **CASH** - Pembayaran tunai (langsung paid)
2. **ONLINE** - Pembayaran online via Tripay (pending sampai dibayar)

---

## 🔑 KONFIGURASI TRIPAY

### Kredensial Tripay (Sandbox)
```
Merchant Code: T45209
API Key: DEV-kDvEeaLScxqMjjmET8WqOWYn2G0DRUMEibGRJFd2
Private Key: 3LGMh-icDui-vCXET-i80fI-rnWV3
Callback URL: https://website.pendhapawening.my.id/tripay_callback.php
```

### File Konfigurasi: `config/tripay.php`
```php
return [
    'api_key' => env('TRIPAY_API_KEY', 'DEV-kDvEeaLScxqMjjmET8WqOWYn2G0DRUMEibGRJFd2'),
    'private_key' => env('TRIPAY_PRIVATE_KEY', '3LGMh-icDui-vCXET-i80fI-rnWV3'),
    'merchant_code' => env('TRIPAY_MERCHANT_CODE', 'T45209'),
    'base_url' => env('TRIPAY_BASE_URL', 'https://tripay.co.id/api-sandbox'),
    'callback_url' => env('TRIPAY_CALLBACK_URL', 'https://website.pendhapawening.my.id/tripay_callback.php'),
];
```

---

## 🆕 ENDPOINT BARU

### 1. Get Payment Channels
**GET** `/api/payment/channels`

Mendapatkan daftar metode pembayaran yang tersedia dari Tripay.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Payment Channels Retrieved",
  "data": [
    {
      "code": "BRIVA",
      "name": "BRI Virtual Account",
      "fee_merchant": {
        "flat": 4000,
        "percent": 0
      },
      "active": true
    },
    {
      "code": "QRIS",
      "name": "QRIS",
      "fee_merchant": {
        "flat": 0,
        "percent": 0.7
      },
      "active": true
    }
  ]
}
```

---

### 2. Check Payment Status
**GET** `/api/payment/status/{transactionId}`

Mengecek status pembayaran transaksi.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (Cash):**
```json
{
  "success": true,
  "message": "Payment Status Retrieved",
  "data": {
    "transaction_id": 1,
    "payment_type": "cash",
    "payment_status": "paid",
    "paid_at": "2024-01-13T10:30:00.000000Z"
  }
}
```

**Response (Online - Pending):**
```json
{
  "success": true,
  "message": "Payment Status Retrieved",
  "data": {
    "transaction_id": 2,
    "payment_type": "online",
    "payment_status": "pending",
    "tripay_status": "UNPAID",
    "tripay_reference": "T4520912345678",
    "checkout_url": "https://tripay.co.id/checkout/T4520912345678",
    "paid_at": null
  }
}
```

**Response (Online - Paid):**
```json
{
  "success": true,
  "message": "Payment Status Retrieved",
  "data": {
    "transaction_id": 2,
    "payment_type": "online",
    "payment_status": "paid",
    "tripay_status": "PAID",
    "tripay_reference": "T4520912345678",
    "checkout_url": "https://tripay.co.id/checkout/T4520912345678",
    "paid_at": "2024-01-13T11:00:00.000000Z"
  }
}
```

---

### 3. Tripay Callback (Public)
**POST** `/api/payment/callback`

Endpoint untuk menerima notifikasi dari Tripay ketika status pembayaran berubah.

**Note:** Endpoint ini PUBLIC (tidak perlu token) karena dipanggil oleh server Tripay.

---

## 💰 CARA MEMBUAT TRANSAKSI

### A. TRANSAKSI CASH (Pembayaran Tunai)

**POST** `/api/transactions`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "payment_type": "cash",
  "payment_method": "cash",
  "total_price": 50000,
  "paid_amount": 100000,
  "change": 50000,
  "items": [
    {
      "id": 1,
      "qty": 2,
      "price": 15000
    },
    {
      "id": 2,
      "qty": 1,
      "price": 20000
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Transaction Created Successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "total_price": 50000,
    "paid_amount": 100000,
    "change": 50000,
    "payment_method": "cash",
    "payment_type": "cash",
    "payment_status": "paid",
    "paid_at": "2024-01-13T10:30:00.000000Z",
    "items": [...]
  }
}
```

---

### B. TRANSAKSI ONLINE (Pembayaran via Tripay)

**POST** `/api/transactions`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "payment_type": "online",
  "tripay_method": "BRIVA",
  "customer_phone": "08123456789",
  "total_price": 50000,
  "items": [
    {
      "id": 1,
      "qty": 2,
      "price": 15000
    },
    {
      "id": 2,
      "qty": 1,
      "price": 20000
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Transaction Created Successfully",
  "data": {
    "id": 2,
    "user_id": 1,
    "total_price": 50000,
    "paid_amount": 0,
    "change": 0,
    "payment_method": null,
    "payment_type": "online",
    "payment_status": "pending",
    "tripay_reference": "T4520912345678",
    "tripay_merchant_ref": "TRX-1705140000-1234",
    "tripay_payment_method": "BRIVA",
    "tripay_payment_name": "BRI Virtual Account",
    "tripay_checkout_url": "https://tripay.co.id/checkout/T4520912345678",
    "paid_at": null,
    "items": [...]
  }
}
```

**Instruksi untuk Customer:**
1. Buka `tripay_checkout_url` di browser
2. Pilih metode pembayaran (sudah dipilih BRIVA)
3. Ikuti instruksi pembayaran
4. Setelah bayar, status otomatis update via callback

---

## 🔄 ALUR PEMBAYARAN

### ALUR CASH:
```
1. Customer pilih produk
2. Kasir input jumlah bayar
3. POST /api/transactions (payment_type: cash)
4. Status langsung "paid"
5. Stok produk berkurang
6. Selesai ✅
```

### ALUR ONLINE:
```
1. Customer pilih produk
2. Customer pilih metode pembayaran (BRIVA/QRIS/dll)
3. POST /api/transactions (payment_type: online)
4. Status "pending"
5. Stok produk berkurang (reserved)
6. Customer dapat checkout_url
7. Customer bayar via Tripay
8. Tripay kirim callback ke server
9. Status update jadi "paid"
10. Selesai ✅

Jika tidak dibayar dalam 24 jam:
- Status jadi "expired"
- Stok produk dikembalikan
```

---

## 📊 STATUS PEMBAYARAN

| Status | Deskripsi |
|--------|-----------|
| `pending` | Menunggu pembayaran (online payment) |
| `paid` | Sudah dibayar |
| `failed` | Pembayaran gagal |
| `expired` | Pembayaran kadaluarsa (24 jam) |

---

## 🔍 FILTER TRANSAKSI

**GET** `/api/transactions?payment_status=paid`
**GET** `/api/transactions?payment_type=cash`
**GET** `/api/transactions?payment_status=pending&payment_type=online`

---

## 🎯 METODE PEMBAYARAN TRIPAY YANG TERSEDIA

### Virtual Account:
- BRIVA (BRI)
- BNIVA (BNI)
- BRIVA (Mandiri)
- PERMATAVA (Permata)
- BCAVA (BCA)

### E-Wallet:
- OVO
- DANA
- SHOPEEPAY
- LINKAJA

### Retail:
- ALFAMART
- INDOMARET

### QRIS:
- QRIS (Scan QR)

---

## 💡 TIPS IMPLEMENTASI FRONTEND

### 1. Tampilkan Pilihan Metode Pembayaran
```javascript
// Get payment channels
const channels = await fetch('/api/payment/channels', {
  headers: { 'Authorization': `Bearer ${token}` }
});

// Tampilkan di UI
channels.data.forEach(channel => {
  console.log(channel.name, channel.code);
});
```

### 2. Buat Transaksi Online
```javascript
const transaction = await fetch('/api/transactions', {
  method: 'POST',
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    payment_type: 'online',
    tripay_method: 'BRIVA', // dari pilihan user
    customer_phone: '08123456789',
    total_price: 50000,
    items: [...]
  })
});

// Redirect ke checkout URL
window.open(transaction.data.tripay_checkout_url, '_blank');
```

### 3. Polling Status Pembayaran
```javascript
// Check status setiap 5 detik
const checkStatus = setInterval(async () => {
  const status = await fetch(`/api/payment/status/${transactionId}`, {
    headers: { 'Authorization': `Bearer ${token}` }
  });
  
  if (status.data.payment_status === 'paid') {
    clearInterval(checkStatus);
    alert('Pembayaran berhasil!');
    // Redirect ke halaman sukses
  }
}, 5000);
```

---

## 🚨 TROUBLESHOOTING

### Error: "Failed to create payment"
- Cek kredensial Tripay di `config/tripay.php`
- Pastikan menggunakan API Sandbox untuk testing
- Cek log di `storage/logs/laravel.log`

### Callback tidak diterima
- Pastikan callback URL sudah terdaftar di dashboard Tripay
- Endpoint `/api/payment/callback` harus PUBLIC (tidak perlu auth)
- Cek signature validation

### Stok tidak kembali saat expired
- Callback dari Tripay akan otomatis restore stok
- Bisa juga buat cron job untuk cek transaksi expired

---

## 📝 CATATAN PENTING

1. **Sandbox vs Production:**
   - Saat ini menggunakan Tripay Sandbox
   - Untuk production, ganti base_url ke `https://tripay.co.id/api`
   - Ganti kredensial dengan yang production

2. **Keamanan:**
   - Callback endpoint sudah dilindungi signature validation
   - Jangan expose private key di frontend

3. **Stok Management:**
   - Stok berkurang saat transaksi dibuat (baik cash maupun online)
   - Untuk online, jika expired/failed, stok dikembalikan otomatis

4. **Expired Time:**
   - Default: 24 jam
   - Bisa diubah di `TripayService.php` line 67

---

## 🎉 SELESAI!

Sistem pembayaran dual-channel (Cash + Online) sudah siap digunakan!

**Testing:**
1. Login: `POST /api/login`
2. Get channels: `GET /api/payment/channels`
3. Create transaction: `POST /api/transactions`
4. Check status: `GET /api/payment/status/{id}`
