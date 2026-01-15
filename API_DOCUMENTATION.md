# Dokumentasi API Sistem Kasir Laravel 11

## Base URL
```
http://127.0.0.1:8000/api
```

## Format Response
Semua endpoint mengembalikan response dalam format JSON:

```json
{
  "success": true/false,
  "message": "Pesan response",
  "data": {}
}
```

---

## Authentication

### Login
Endpoint untuk login dan mendapatkan token autentikasi.

**Endpoint:** `POST /login`

**Request Body:**
```json
{
  "email": "admin@kasir.com",
  "password": "password"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Login Success",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "admin@kasir.com",
      "role": "admin"
    },
    "token": "1|xxxxxxxxxxxxxxxx"
  }
}
```

**Response Error (401):**
```json
{
  "success": false,
  "message": "Invalid Credentials",
  "data": null
}
```

---

### Logout
Endpoint untuk logout dan menghapus token.

**Endpoint:** `POST /logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Logout Success",
  "data": null
}
```

---

## Products

### Get All Products
Mendapatkan daftar semua produk dengan pagination.

**Endpoint:** `GET /products`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters (Optional):**
- `category` - Filter berdasarkan kategori (food/drink/snack)

**Response Success (200):**
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
        "stock": 100,
        "category": "food",
        "photo": "1234567890.jpg",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "per_page": 10,
    "total": 10
  }
}
```

---

### Get Product Detail
Mendapatkan detail produk berdasarkan ID.

**Endpoint:** `GET /products/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Detail Data Product",
  "data": {
    "id": 1,
    "name": "Nasi Goreng",
    "description": "Nasi goreng spesial",
    "price": 15000,
    "stock": 100,
    "category": "food",
    "photo": "1234567890.jpg"
  }
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Product Not Found",
  "data": null
}
```

---

### Create Product
Membuat produk baru.

**Endpoint:** `POST /products`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Nasi Goreng",
  "description": "Nasi goreng spesial",
  "price": 15000,
  "stock": 100,
  "category": "food",
  "photo": "file" // Optional, gunakan multipart/form-data untuk upload
}
```

**Validasi:**
- `name`: required, string, max 255 karakter
- `description`: optional, string
- `price`: required, integer
- `stock`: required, integer
- `category`: required, enum (food/drink/snack)
- `photo`: optional, image (jpeg,png,jpg,gif,svg), max 2MB

**Response Success (201):**
```json
{
  "success": true,
  "message": "Product Created Successfully",
  "data": {
    "id": 11,
    "name": "Nasi Goreng",
    "description": "Nasi goreng spesial",
    "price": 15000,
    "stock": 100,
    "category": "food",
    "photo": "1234567890.jpg"
  }
}
```

---

### Update Product
Mengupdate produk yang sudah ada.

**Endpoint:** `PUT /products/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Nasi Goreng Updated",
  "description": "Deskripsi baru",
  "price": 20000,
  "stock": 150,
  "category": "food",
  "photo": "file" // Optional
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Product Updated Successfully",
  "data": {
    "id": 1,
    "name": "Nasi Goreng Updated",
    "price": 20000,
    "stock": 150
  }
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Product Not Found",
  "data": null
}
```

---

### Delete Product
Menghapus produk.

**Endpoint:** `DELETE /products/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Product Deleted Successfully",
  "data": null
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Product Not Found",
  "data": null
}
```

---

## Transactions

### Get All Transactions
Mendapatkan daftar semua transaksi dengan pagination.

**Endpoint:** `GET /transactions`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
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
        "created_at": "2024-01-01T00:00:00.000000Z",
        "items": [
          {
            "id": 1,
            "product_id": 1,
            "qty": 2,
            "price": 15000,
            "subtotal": 30000,
            "product": {
              "id": 1,
              "name": "Nasi Goreng"
            }
          }
        ]
      }
    ],
    "per_page": 10,
    "total": 5
  }
}
```

---

### Get Transaction Detail
Mendapatkan detail transaksi berdasarkan ID.

**Endpoint:** `GET /transactions/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
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
    "created_at": "2024-01-01T00:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "admin@kasir.com"
    },
    "items": [
      {
        "id": 1,
        "product_id": 1,
        "qty": 2,
        "price": 15000,
        "subtotal": 30000,
        "product": {
          "id": 1,
          "name": "Nasi Goreng",
          "price": 15000
        }
      }
    ]
  }
}
```

**Response Error (404):**
```json
{
  "success": false,
  "message": "Transaction Not Found",
  "data": null
}
```

---

### Create Transaction
Membuat transaksi baru dan mengurangi stok produk.

**Endpoint:** `POST /transactions`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "total_price": 50000,
  "paid_amount": 100000,
  "change": 50000,
  "payment_method": "cash",
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

**Validasi:**
- `total_price`: required, integer
- `paid_amount`: required, integer
- `change`: required, integer
- `payment_method`: optional, string (default: cash)
- `items`: required, array
- `items.*.id`: required, exists in products table
- `items.*.qty`: required, integer, min 1
- `items.*.price`: required, integer

**Response Success (201):**
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
          "name": "Nasi Goreng"
        }
      }
    ]
  }
}
```

**Response Error (500):**
```json
{
  "success": false,
  "message": "Transaction Failed: Error message",
  "data": null
}
```

---

## Profile

### Get Profile
Mendapatkan profil user yang sedang login.

**Endpoint:** `GET /profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "User Profile",
  "data": {
    "id": 1,
    "name": "Admin",
    "email": "admin@kasir.com",
    "role": "admin",
    "created_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

---

### Update Profile
Mengupdate profil user yang sedang login.

**Endpoint:** `PUT /profile`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Admin Updated",
  "email": "admin@kasir.com",
  "password": "newpassword123" // Optional
}
```

**Validasi:**
- `name`: required, string, max 255 karakter
- `email`: required, email, unique (kecuali email sendiri)
- `password`: optional, string, min 8 karakter

**Response Success (200):**
```json
{
  "success": true,
  "message": "Profile Updated",
  "data": {
    "id": 1,
    "name": "Admin Updated",
    "email": "admin@kasir.com",
    "role": "admin"
  }
}
```

---

## Error Responses

### 401 Unauthorized
Terjadi ketika token tidak valid atau tidak disertakan.

```json
{
  "message": "Unauthenticated."
}
```

### 422 Validation Error
Terjadi ketika data yang dikirim tidak valid.

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ],
    "password": [
      "The password field is required."
    ]
  }
}
```

### 404 Not Found
Terjadi ketika resource tidak ditemukan.

```json
{
  "success": false,
  "message": "Product Not Found",
  "data": null
}
```

### 500 Internal Server Error
Terjadi ketika ada error di server.

```json
{
  "success": false,
  "message": "Transaction Failed: Error message",
  "data": null
}
```

---

## Catatan Penting

1. **Authentication**: Semua endpoint kecuali `/login` memerlukan token Bearer di header
2. **Token Format**: `Authorization: Bearer {token}`
3. **Content Type**: Gunakan `application/json` untuk semua request (kecuali upload file)
4. **File Upload**: Untuk upload foto, gunakan `multipart/form-data`
5. **Pagination**: Default 10 items per page
6. **Stock Management**: Stok produk otomatis berkurang saat transaksi dibuat
7. **Transaction**: Tidak bisa diupdate atau dihapus setelah dibuat
8. **Role**: Ada 2 role: `admin` dan `cashier`

---

## Kredensial Default

**Admin User:**
- Email: admin@kasir.com
- Password: password
- Role: admin

---

## Sample Products (Seeder)

1. Nasi Goreng - Rp 15.000 (food)
2. Mie Goreng - Rp 12.000 (food)
3. Ayam Goreng - Rp 20.000 (food)
4. Es Teh - Rp 5.000 (drink)
5. Es Jeruk - Rp 7.000 (drink)
6. Kopi - Rp 8.000 (drink)
7. Keripik - Rp 10.000 (snack)
8. Coklat - Rp 15.000 (snack)
9. Permen - Rp 5.000 (snack)
10. Biskuit - Rp 12.000 (snack)
