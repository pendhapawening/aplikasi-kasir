# Panduan Testing API Kasir

## Kredensial Login
- **Email**: admin@kasir.com
- **Password**: password

## Cara Testing Manual dengan Postman/Insomnia

### 1. Login
**POST** `http://127.0.0.1:8000/api/login`

Headers:
```
Content-Type: application/json
```

Body (JSON):
```json
{
  "email": "admin@kasir.com",
  "password": "password"
}
```

Response Success:
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

**PENTING**: Copy token dari response untuk digunakan di request selanjutnya!

---

### 2. Get All Products
**GET** `http://127.0.0.1:8000/api/products`

Headers:
```
Authorization: Bearer {TOKEN_DARI_LOGIN}
Content-Type: application/json
```

Response Success:
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
        "photo": null
      }
    ]
  }
}
```

---

### 3. Get Product Detail
**GET** `http://127.0.0.1:8000/api/products/1`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

---

### 4. Create Product
**POST** `http://127.0.0.1:8000/api/products`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

Body (JSON):
```json
{
  "name": "Produk Baru",
  "description": "Deskripsi produk",
  "price": 25000,
  "stock": 50,
  "category": "food"
}
```

Response Success:
```json
{
  "success": true,
  "message": "Product Created Successfully",
  "data": {
    "id": 11,
    "name": "Produk Baru",
    "price": 25000,
    "stock": 50
  }
}
```

---

### 5. Update Product
**PUT** `http://127.0.0.1:8000/api/products/11`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

Body (JSON):
```json
{
  "name": "Produk Updated",
  "description": "Deskripsi baru",
  "price": 30000,
  "stock": 75,
  "category": "drink"
}
```

---

### 6. Delete Product
**DELETE** `http://127.0.0.1:8000/api/products/11`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

Response Success:
```json
{
  "success": true,
  "message": "Product Deleted Successfully",
  "data": null
}
```

---

### 7. Create Transaction
**POST** `http://127.0.0.1:8000/api/transactions`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

Body (JSON):
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

Response Success:
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

---

### 8. Get All Transactions
**GET** `http://127.0.0.1:8000/api/transactions`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

---

### 9. Get Transaction Detail
**GET** `http://127.0.0.1:8000/api/transactions/1`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

---

### 10. Get Profile
**GET** `http://127.0.0.1:8000/api/profile`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

Response Success:
```json
{
  "success": true,
  "message": "User Profile",
  "data": {
    "id": 1,
    "name": "Admin",
    "email": "admin@kasir.com",
    "role": "admin"
  }
}
```

---

### 11. Update Profile
**PUT** `http://127.0.0.1:8000/api/profile`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

Body (JSON):
```json
{
  "name": "Admin Updated",
  "email": "admin@kasir.com",
  "password": "newpassword123"
}
```

---

### 12. Logout
**POST** `http://127.0.0.1:8000/api/logout`

Headers:
```
Authorization: Bearer {TOKEN}
Content-Type: application/json
```

Response Success:
```json
{
  "success": true,
  "message": "Logout Success",
  "data": null
}
```

---

## Testing Error Cases

### 1. Login dengan Password Salah
**POST** `http://127.0.0.1:8000/api/login`

Body:
```json
{
  "email": "admin@kasir.com",
  "password": "wrongpassword"
}
```

Response (401):
```json
{
  "success": false,
  "message": "Invalid Credentials",
  "data": null
}
```

---

### 2. Access Endpoint Tanpa Token
**GET** `http://127.0.0.1:8000/api/products`

(Tanpa header Authorization)

Response (401):
```json
{
  "message": "Unauthenticated."
}
```

---

### 3. Product Not Found
**GET** `http://127.0.0.1:8000/api/products/9999`

Headers:
```
Authorization: Bearer {TOKEN}
```

Response (404):
```json
{
  "success": false,
  "message": "Product Not Found",
  "data": null
}
```

---

## Catatan Penting

1. **Token Expiration**: Token tidak akan expired kecuali di-logout
2. **Stock Management**: Saat membuat transaksi, stok produk akan otomatis berkurang
3. **Photo Upload**: Untuk upload foto produk, gunakan `multipart/form-data` dengan field `photo`
4. **Pagination**: List products dan transactions menggunakan pagination (10 items per page)
5. **Category**: Kategori produk hanya boleh: `food`, `drink`, atau `snack`

---

## Struktur Database

### Users
- id
- name
- email
- password
- role (admin/cashier)

### Products
- id
- name
- description
- price
- stock
- category
- photo

### Transactions
- id
- user_id
- total_price
- paid_amount
- change
- payment_method

### Transaction Items
- id
- transaction_id
- product_id
- qty
- price
- subtotal
