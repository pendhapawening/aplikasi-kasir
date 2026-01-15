# 📱 API ENDPOINTS REFERENCE - SISTEM KASIR

## 🌐 BASE URL
```
http://127.0.0.1:8000/api
```

---

## 🔐 AUTHENTICATION

### 1. Login
**Endpoint:** `POST /api/login`

**Request:**
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
    "token": "1|xxxxxxxxxxxxxxxxxxxxx"
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

### 2. Logout
**Endpoint:** `POST /api/logout`

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

## 📦 PRODUCTS

### 3. Get All Products
**Endpoint:** `GET /api/products`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters (Optional):**
- `category` - Filter by category (food/drink/snack)

**Example:**
```
GET /api/products?category=food
```

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
    "first_page_url": "http://127.0.0.1:8000/api/products?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/products?page=1",
    "next_page_url": null,
    "path": "http://127.0.0.1:8000/api/products",
    "per_page": 10,
    "prev_page_url": null,
    "to": 10,
    "total": 10
  }
}
```

---

### 4. Get Product Detail
**Endpoint:** `GET /api/products/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Example:**
```
GET /api/products/1
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
    "photo": "1234567890.jpg",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
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

### 5. Create Product
**Endpoint:** `POST /api/products`

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
  "category": "food"
}
```

**Untuk Upload Photo (gunakan multipart/form-data):**
```
name: Nasi Goreng
description: Nasi goreng spesial
price: 15000
stock: 100
category: food
photo: [file]
```

**Validation Rules:**
- `name`: required, string, max 255
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
    "photo": "1234567890.jpg",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

---

### 6. Update Product
**Endpoint:** `PUT /api/products/{id}`

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
  "category": "food"
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
    "description": "Deskripsi baru",
    "price": 20000,
    "stock": 150,
    "category": "food",
    "photo": "1234567890.jpg",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T10:30:00.000000Z"
  }
}
```

---

### 7. Delete Product
**Endpoint:** `DELETE /api/products/{id}`

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

---

## 💰 TRANSACTIONS

### 8. Get All Transactions
**Endpoint:** `GET /api/transactions`

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
        "updated_at": "2024-01-01T00:00:00.000000Z",
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
              "category": "food"
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
              "price": 12000,
              "category": "food"
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

### 9. Get Transaction Detail
**Endpoint:** `GET /api/transactions/{id}`

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
    "updated_at": "2024-01-01T00:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "admin@kasir.com",
      "role": "admin"
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
          "description": "Nasi goreng spesial",
          "price": 15000,
          "stock": 98,
          "category": "food",
          "photo": "1234567890.jpg"
        }
      }
    ]
  }
}
```

---

### 10. Create Transaction
**Endpoint:** `POST /api/transactions`

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

**Validation Rules:**
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
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z",
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
          "price": 15000
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
          "price": 12000
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
  "message": "Transaction Failed: Insufficient stock",
  "data": null
}
```

**⚠️ CATATAN PENTING:**
- Stok produk akan otomatis berkurang sesuai qty yang dibeli
- Transaksi tidak bisa diupdate atau dihapus setelah dibuat
- Pastikan stok produk mencukupi sebelum membuat transaksi

---

## 👤 PROFILE

### 11. Get Profile
**Endpoint:** `GET /api/profile`

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
    "email_verified_at": null,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

---

### 12. Update Profile
**Endpoint:** `PUT /api/profile`

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
  "password": "newpassword123"
}
```

**Validation Rules:**
- `name`: required, string, max 255
- `email`: required, email, unique (kecuali email sendiri)
- `password`: optional, string, min 8

**Response Success (200):**
```json
{
  "success": true,
  "message": "Profile Updated",
  "data": {
    "id": 1,
    "name": "Admin Updated",
    "email": "admin@kasir.com",
    "role": "admin",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T10:30:00.000000Z"
  }
}
```

---

## ⚠️ ERROR RESPONSES

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Product Not Found",
  "data": null
}
```

### 422 Validation Error
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

### 500 Internal Server Error
```json
{
  "success": false,
  "message": "Transaction Failed: Error message",
  "data": null
}
```

---

## 🔑 AUTHENTICATION FLOW

1. **Login** untuk mendapatkan token:
```
POST /api/login
Body: {"email": "admin@kasir.com", "password": "password"}
```

2. **Simpan token** dari response

3. **Gunakan token** di semua request berikutnya:
```
Authorization: Bearer {token}
```

4. **Logout** untuk menghapus token:
```
POST /api/logout
Header: Authorization: Bearer {token}
```

---

## 📝 CATATAN PENTING

1. **Base URL**: `http://127.0.0.1:8000/api` (development)
2. **Authentication**: Semua endpoint kecuali `/login` memerlukan token Bearer
3. **Content-Type**: `application/json` (kecuali upload file)
4. **File Upload**: Gunakan `multipart/form-data` untuk upload foto
5. **Pagination**: Default 10 items per page
6. **Category**: Hanya boleh `food`, `drink`, atau `snack`
7. **Stock Management**: Stok otomatis berkurang saat transaksi
8. **Token**: Token tidak expired kecuali di-logout

---

## 🔐 KREDENSIAL DEFAULT

```
Email: admin@kasir.com
Password: password
Role: admin
```

---

## 📱 CONTOH PENGGUNAAN DI APLIKASI

### JavaScript/Fetch
```javascript
// Login
const login = async () => {
  const response = await fetch('http://127.0.0.1:8000/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      email: 'admin@kasir.com',
      password: 'password'
    })
  });
  const data = await response.json();
  const token = data.data.token;
  localStorage.setItem('token', token);
};

// Get Products
const getProducts = async () => {
  const token = localStorage.getItem('token');
  const response = await fetch('http://127.0.0.1:8000/api/products', {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    }
  });
  const data = await response.json();
  return data.data;
};

// Create Transaction
const createTransaction = async (transactionData) => {
  const token = localStorage.getItem('token');
  const response = await fetch('http://127.0.0.1:8000/api/transactions', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(transactionData)
  });
  const data = await response.json();
  return data;
};
```

### Axios
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json',
  }
});

// Interceptor untuk menambahkan token
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Login
const login = async (email, password) => {
  const response = await api.post('/login', { email, password });
  localStorage.setItem('token', response.data.data.token);
  return response.data;
};

// Get Products
const getProducts = async () => {
  const response = await api.get('/products');
  return response.data;
};

// Create Transaction
const createTransaction = async (data) => {
  const response = await api.post('/transactions', data);
  return response.data;
};
```

---

## 🚀 READY TO USE!

API sudah siap digunakan untuk aplikasi Anda. Pastikan server Laravel berjalan dengan:

```bash
php artisan serve
```

Server akan berjalan di: `http://127.0.0.1:8000`
