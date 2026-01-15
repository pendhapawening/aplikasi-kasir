# 📚 DAFTAR LENGKAP SEMUA API & FUNGSINYA

## 🌐 BASE URL
```
http://127.0.0.1:8000/api
```

---

## 🔐 1. AUTHENTICATION (2 Endpoints)

### 1.1 LOGIN
**Fungsi:** Login dan mendapatkan token untuk akses API

**Endpoint:** `POST /api/login`

**Request:**
```json
{
  "email": "admin@kasir.com",
  "password": "password"
}
```

**Response Success:**
```json
{
  "success": true,
  "message": "Login Success",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin Kasir",
      "email": "admin@kasir.com",
      "created_at": "2024-01-13T10:00:00.000000Z",
      "updated_at": "2024-01-13T10:00:00.000000Z"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz1234567890"
  }
}
```

**Response Error:**
```json
{
  "success": false,
  "message": "Invalid Credentials",
  "data": null
}
```

---

### 1.2 LOGOUT
**Fungsi:** Logout dan menghapus token

**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Logout Success",
  "data": null
}
```

---

## 📦 2. PRODUCTS (5 Endpoints)

### 2.1 GET ALL PRODUCTS
**Fungsi:** Mendapatkan daftar semua produk dengan pagination dan filter

**Endpoint:** `GET /api/products`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters (Optional):**
- `category` - Filter by category (food, drink, snack)
- `page` - Nomor halaman (default: 1)

**Example:**
```
GET /api/products?category=food&page=1
```

**Response:**
```json
{
  "success": true,
  "message": "List Data Product",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Nasi Goreng",
        "description": "Nasi goreng spesial",
        "price": 15000,
        "stock": 50,
        "category": "food",
        "photo": "1705140000.jpg",
        "created_at": "2024-01-13T10:00:00.000000Z",
        "updated_at": "2024-01-13T10:00:00.000000Z"
      },
      {
        "id": 2,
        "name": "Mie Goreng",
        "description": "Mie goreng pedas",
        "price": 20000,
        "stock": 50,
        "category": "food",
        "photo": "1705140001.jpg",
        "created_at": "2024-01-13T10:00:00.000000Z",
        "updated_at": "2024-01-13T10:00:00.000000Z"
      }
    ],
    "per_page": 10,
    "total": 10
  }
}
```

---

### 2.2 GET PRODUCT DETAIL
**Fungsi:** Mendapatkan detail satu produk

**Endpoint:** `GET /api/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Example:**
```
GET /api/products/1
```

**Response Success:**
```json
{
  "success": true,
  "message": "Detail Data Product",
  "data": {
    "id": 1,
    "name": "Nasi Goreng",
    "description": "Nasi goreng spesial",
    "price": 15000,
    "stock": 50,
    "category": "food",
    "photo": "1705140000.jpg",
    "created_at": "2024-01-13T10:00:00.000000Z",
    "updated_at": "2024-01-13T10:00:00.000000Z"
  }
}
```

**Response Error (Not Found):**
```json
{
  "success": false,
  "message": "Product Not Found",
  "data": null
}
```

---

### 2.3 CREATE PRODUCT
**Fungsi:** Membuat produk baru (dengan upload foto)

**Endpoint:** `POST /api/products`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
name: Nasi Goreng Spesial
description: Nasi goreng dengan telur dan ayam
price: 25000
stock: 100
category: food
photo: [file upload]
```

**Response:**
```json
{
  "success": true,
  "message": "Product Created Successfully",
  "data": {
    "id": 11,
    "name": "Nasi Goreng Spesial",
    "description": "Nasi goreng dengan telur dan ayam",
    "price": 25000,
    "stock": 100,
    "category": "food",
    "photo": "1705140500.jpg",
    "created_at": "2024-01-13T10:30:00.000000Z",
    "updated_at": "2024-01-13T10:30:00.000000Z"
  }
}
```

**Validation Rules:**
- `name`: required, string, max 255
- `description`: optional, string
- `price`: required, integer
- `stock`: required, integer
- `category`: required, in:food,drink,snack
- `photo`: optional, image (jpeg,png,jpg,gif,svg), max 2MB

---

### 2.4 UPDATE PRODUCT
**Fungsi:** Update produk yang sudah ada

**Endpoint:** `PUT /api/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
name: Nasi Goreng Special Updated
description: Updated description
price: 30000
stock: 80
category: food
photo: [file upload - optional]
```

**Response:**
```json
{
  "success": true,
  "message": "Product Updated Successfully",
  "data": {
    "id": 1,
    "name": "Nasi Goreng Special Updated",
    "description": "Updated description",
    "price": 30000,
    "stock": 80,
    "category": "food",
    "photo": "1705140600.jpg",
    "created_at": "2024-01-13T10:00:00.000000Z",
    "updated_at": "2024-01-13T10:40:00.000000Z"
  }
}
```

---

### 2.5 DELETE PRODUCT
**Fungsi:** Menghapus produk (termasuk foto)

**Endpoint:** `DELETE /api/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Product Deleted Successfully",
  "data": null
}
```

---

## 💰 3. TRANSACTIONS (3 Endpoints)

### 3.1 GET ALL TRANSACTIONS
**Fungsi:** Mendapatkan daftar semua transaksi dengan filter

**Endpoint:** `GET /api/transactions`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters (Optional):**
- `payment_status` - Filter by status (pending, paid, failed, expired)
- `payment_type` - Filter by type (cash, online)
- `page` - Nomor halaman

**Examples:**
```
GET /api/transactions
GET /api/transactions?payment_status=paid
GET /api/transactions?payment_type=cash
GET /api/transactions?payment_status=pending&payment_type=online
```

**Response:**
```json
{
  "success": true,
  "message": "List Data Transaction",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "user_id": 1,
        "total_price": 50000,
        "paid_amount": 100000,
        "change": 50000,
        "payment_method": "cash",
        "payment_type": "cash",
        "payment_status": "paid",
        "tripay_reference": null,
        "tripay_merchant_ref": null,
        "tripay_payment_method": null,
        "tripay_payment_name": null,
        "tripay_checkout_url": null,
        "paid_at": "2024-01-13T10:30:00.000000Z",
        "created_at": "2024-01-13T10:30:00.000000Z",
        "updated_at": "2024-01-13T10:30:00.000000Z",
        "user": {
          "id": 1,
          "name": "Admin Kasir",
          "email": "admin@kasir.com"
        },
        "items": [
          {
            "id": 1,
            "transaction_id": 1,
            "product_id": 1,
            "qty": 2,
            "price": 15000,
            "subtotal": 30000,
            "product": {
              "id": 1,
              "name": "Nasi Goreng",
              "price": 15000,
              "stock": 48
            }
          },
          {
            "id": 2,
            "transaction_id": 1,
            "product_id": 2,
            "qty": 1,
            "price": 20000,
            "subtotal": 20000,
            "product": {
              "id": 2,
              "name": "Mie Goreng",
              "price": 20000,
              "stock": 49
            }
          }
        ]
      }
    ],
    "per_page": 10,
    "total": 1
  }
}
```

---

### 3.2 CREATE TRANSACTION (CASH)
**Fungsi:** Membuat transaksi pembayaran tunai

**Endpoint:** `POST /api/transactions`

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

**Validation Rules:**
- `payment_type`: required, in:cash,online
- `total_price`: required, integer
- `paid_amount`: required (jika cash), integer
- `change`: required (jika cash), integer
- `items`: required, array
- `items.*.id`: required, exists in products
- `items.*.qty`: required, integer, min:1
- `items.*.price`: required, integer

---

### 3.3 CREATE TRANSACTION (ONLINE)
**Fungsi:** Membuat transaksi pembayaran online via Tripay

**Endpoint:** `POST /api/transactions`

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
    "created_at": "2024-01-13T10:35:00.000000Z",
    "updated_at": "2024-01-13T10:35:00.000000Z",
    "items": [...]
  }
}
```

**Validation Rules:**
- `payment_type`: required, in:cash,online
- `tripay_method`: required (jika online), string
- `customer_phone`: optional, string
- `total_price`: required, integer
- `items`: required, array

**Catatan:**
- Customer harus membuka `tripay_checkout_url` untuk melakukan pembayaran
- Status akan otomatis update dari "pending" ke "paid" setelah customer bayar
- Jika tidak dibayar dalam 24 jam, status akan jadi "expired"

---

### 3.4 GET TRANSACTION DETAIL
**Fungsi:** Mendapatkan detail satu transaksi

**Endpoint:** `GET /api/transactions/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Detail Data Transaction",
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
    "user": {
      "id": 1,
      "name": "Admin Kasir",
      "email": "admin@kasir.com"
    },
    "items": [
      {
        "id": 1,
        "transaction_id": 1,
        "product_id": 1,
        "qty": 2,
        "price": 15000,
        "subtotal": 30000,
        "product": {
          "id": 1,
          "name": "Nasi Goreng",
          "price": 15000,
          "photo": "1705140000.jpg"
        }
      }
    ]
  }
}
```

---

## 💳 4. PAYMENT (3 Endpoints)

### 4.1 GET PAYMENT CHANNELS
**Fungsi:** Mendapatkan daftar metode pembayaran online dari Tripay

**Endpoint:** `GET /api/payment/channels`

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
      "fee_customer": {
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
      "fee_customer": {
        "flat": 0,
        "percent": 0
      },
      "active": true
    },
    {
      "code": "BCAVA",
      "name": "BCA Virtual Account",
      "fee_merchant": {
        "flat": 4000,
        "percent": 0
      },
      "fee_customer": {
        "flat": 4000,
        "percent": 0
      },
      "active": true
    },
    {
      "code": "OVO",
      "name": "OVO",
      "fee_merchant": {
        "flat": 0,
        "percent": 2
      },
      "fee_customer": {
        "flat": 0,
        "percent": 0
      },
      "active": true
    },
    {
      "code": "DANA",
      "name": "DANA",
      "fee_merchant": {
        "flat": 0,
        "percent": 2
      },
      "fee_customer": {
        "flat": 0,
        "percent": 0
      },
      "active": true
    }
  ]
}
```

**Kegunaan:**
- Tampilkan daftar metode pembayaran ke customer
- Customer pilih metode yang diinginkan
- Gunakan `code` untuk parameter `tripay_method` saat create transaction

---

### 4.2 CHECK PAYMENT STATUS
**Fungsi:** Mengecek status pembayaran transaksi

**Endpoint:** `GET /api/payment/status/{transactionId}`

**Headers:**
```
Authorization: Bearer {token}
```

**Example:**
```
GET /api/payment/status/2
```

**Response (Cash Transaction):**
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

**Kegunaan:**
- Polling status pembayaran online
- Cek apakah customer sudah bayar atau belum
- Update UI berdasarkan status

---

### 4.3 TRIPAY CALLBACK (PUBLIC)
**Fungsi:** Menerima notifikasi dari Tripay ketika status pembayaran berubah

**Endpoint:** `POST /api/payment/callback`

**Headers:**
```
X-Callback-Signature: {signature dari Tripay}
Content-Type: application/json
```

**Request Body (dari Tripay):**
```json
{
  "reference": "T4520912345678",
  "merchant_ref": "TRX-1705140000-1234",
  "payment_method": "BRIVA",
  "payment_name": "BRI Virtual Account",
  "customer_name": "Admin Kasir",
  "customer_email": "admin@kasir.com",
  "customer_phone": "08123456789",
  "amount": 50000,
  "status": "PAID"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Callback Processed"
}
```

**Catatan:**
- Endpoint ini PUBLIC (tidak perlu token)
- Dipanggil otomatis oleh server Tripay
- Signature divalidasi untuk keamanan
- Jika status "PAID", transaksi di-update jadi paid
- Jika status "EXPIRED/FAILED", stok produk dikembalikan

---

## 👤 5. PROFILE (2 Endpoints)

### 5.1 GET PROFILE
**Fungsi:** Mendapatkan data profil user yang sedang login

**Endpoint:** `GET /api/profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "User Profile",
  "data": {
    "id": 1,
    "name": "Admin Kasir",
    "email": "admin@kasir.com",
    "created_at": "2024-01-13T10:00:00.000000Z",
    "updated_at": "2024-01-13T10:00:00.000000Z"
  }
}
```

---

### 5.2 UPDATE PROFILE
**Fungsi:** Update data profil user

**Endpoint:** `PUT /api/profile`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Admin Kasir Updated",
  "email": "admin.updated@kasir.com",
  "password": "newpassword123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Profile Updated",
  "data": {
    "id": 1,
    "name": "Admin Kasir Updated",
    "email": "admin.updated@kasir.com",
    "created_at": "2024-01-13T10:00:00.000000Z",
    "updated_at": "2024-01-13T11:00:00.000000Z"
  }
}
```

**Validation Rules:**
- `name`: required, string, max 255
- `email`: required, email, unique (kecuali email sendiri)
- `password`: optional, string, min 8

---

## 📊 RINGKASAN SEMUA API

| No | Method | Endpoint | Fungsi | Auth |
|----|--------|----------|--------|------|
| 1 | POST | /api/login | Login & dapat token | ❌ |
| 2 | POST | /api/logout | Logout & hapus token | ✅ |
| 3 | GET | /api/products | List semua produk | ✅ |
| 4 | POST | /api/products | Buat produk baru | ✅ |
| 5 | GET | /api/products/{id} | Detail produk | ✅ |
| 6 | PUT | /api/products/{id} | Update produk | ✅ |
| 7 | DELETE | /api/products/{id} | Hapus produk | ✅ |
| 8 | GET | /api/transactions | List semua transaksi | ✅ |
| 9 | POST | /api/transactions | Buat transaksi (cash/online) | ✅ |
| 10 | GET | /api/transactions/{id} | Detail transaksi | ✅ |
| 11 | GET | /api/payment/channels | List metode pembayaran | ✅ |
| 12 | GET | /api/payment/status/{id} | Cek status pembayaran | ✅ |
| 13 | POST | /api/payment/callback | Callback dari Tripay | ❌ |
| 14 | GET | /api/profile | Lihat profil | ✅ |
| 15 | PUT | /api/profile | Update profil | ✅ |

**Total: 15 Endpoints**

---

## 🎯 FLOW PENGGUNAAN

### FLOW 1: PEMBAYARAN TUNAI
```
1. POST /api/login → Dapat token
2. GET /api/products → Pilih produk
3. POST /api/transactions (payment_type: cash) → Buat transaksi
4. Status langsung "paid" ✅
```

### FLOW 2: PEMBAYARAN ONLINE
```
1. POST /api/login → Dapat token
2. GET /api/products → Pilih produk
3. GET /api/payment/channels → Pilih metode pembayaran
4. POST /api/transactions (payment_type: online) → Buat transaksi
5. Dapat checkout_url → Customer bayar
6. GET /api/payment/status/{id} → Polling status
7. Tripay kirim callback → Status update jadi "paid" ✅
```

---

## 🔐 AUTHENTICATION

Semua endpoint (kecuali login & callback) membutuhkan token:

**Header:**
```
Authorization: Bearer {token}
```

**Cara dapat token:**
1. POST /api/login dengan email & password
2. Simpan token dari response
3. Gunakan token di header untuk request selanjutnya

---

## ✅ RESPONSE FORMAT

Semua endpoint menggunakan format response yang konsisten:

**Success:**
```json
{
  "success": true,
  "message": "Pesan sukses",
  "data": {}
}
```

**Error:**
```json
{
  "success": false,
  "message": "Pesan error",
  "data": null
}
```

---

## 🎉 SELESAI!

Ini adalah daftar lengkap semua 15 API beserta fungsi, request, dan response-nya!
