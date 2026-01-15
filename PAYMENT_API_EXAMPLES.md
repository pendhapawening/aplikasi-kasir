# 💳 CONTOH PENGGUNAAN API PEMBAYARAN

## 🎯 SKENARIO LENGKAP

---

## 1️⃣ PEMBAYARAN TUNAI (CASH)

### Contoh Request:
```bash
curl -X POST http://127.0.0.1:8000/api/transactions \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
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
  }'
```

### Response:
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
    "tripay_reference": null,
    "tripay_merchant_ref": null,
    "tripay_payment_method": null,
    "tripay_payment_name": null,
    "tripay_checkout_url": null,
    "paid_at": "2024-01-13T10:30:00.000000Z",
    "created_at": "2024-01-13T10:30:00.000000Z",
    "updated_at": "2024-01-13T10:30:00.000000Z",
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
}
```

---

## 2️⃣ PEMBAYARAN ONLINE (TRIPAY)

### Step 1: Get Payment Channels

```bash
curl -X GET http://127.0.0.1:8000/api/payment/channels \
  -H "Authorization: Bearer YOUR_TOKEN"
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
    }
  ]
}
```

### Step 2: Create Online Transaction

```bash
curl -X POST http://127.0.0.1:8000/api/transactions \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
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
  }'
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

### Step 3: Check Payment Status

```bash
curl -X GET http://127.0.0.1:8000/api/payment/status/2 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Response (Pending):**
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

**Response (Paid):**
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

## 3️⃣ FILTER TRANSAKSI

### Filter by Payment Status:
```bash
curl -X GET "http://127.0.0.1:8000/api/transactions?payment_status=paid" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Filter by Payment Type:
```bash
curl -X GET "http://127.0.0.1:8000/api/transactions?payment_type=cash" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Filter Kombinasi:
```bash
curl -X GET "http://127.0.0.1:8000/api/transactions?payment_status=pending&payment_type=online" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 4️⃣ JAVASCRIPT/FETCH EXAMPLES

### Get Payment Channels:
```javascript
async function getPaymentChannels() {
  const response = await fetch('http://127.0.0.1:8000/api/payment/channels', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  
  const result = await response.json();
  
  if (result.success) {
    console.log('Available channels:', result.data);
    return result.data;
  }
}
```

### Create Cash Transaction:
```javascript
async function createCashTransaction(items, totalPrice, paidAmount) {
  const response = await fetch('http://127.0.0.1:8000/api/transactions', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      payment_type: 'cash',
      payment_method: 'cash',
      total_price: totalPrice,
      paid_amount: paidAmount,
      change: paidAmount - totalPrice,
      items: items
    })
  });
  
  const result = await response.json();
  
  if (result.success) {
    console.log('Transaction created:', result.data);
    alert('Pembayaran tunai berhasil!');
  }
}
```

### Create Online Transaction:
```javascript
async function createOnlineTransaction(items, totalPrice, paymentMethod, phone) {
  const response = await fetch('http://127.0.0.1:8000/api/transactions', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      payment_type: 'online',
      tripay_method: paymentMethod, // 'BRIVA', 'QRIS', etc
      customer_phone: phone,
      total_price: totalPrice,
      items: items
    })
  });
  
  const result = await response.json();
  
  if (result.success) {
    console.log('Transaction created:', result.data);
    
    // Open checkout URL in new tab
    window.open(result.data.tripay_checkout_url, '_blank');
    
    // Start polling payment status
    pollPaymentStatus(result.data.id);
  }
}
```

### Poll Payment Status:
```javascript
function pollPaymentStatus(transactionId) {
  const interval = setInterval(async () => {
    const response = await fetch(`http://127.0.0.1:8000/api/payment/status/${transactionId}`, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });
    
    const result = await response.json();
    
    if (result.success) {
      console.log('Payment status:', result.data.payment_status);
      
      if (result.data.payment_status === 'paid') {
        clearInterval(interval);
        alert('Pembayaran berhasil!');
        // Redirect or update UI
        window.location.href = '/success';
      } else if (result.data.payment_status === 'expired' || result.data.payment_status === 'failed') {
        clearInterval(interval);
        alert('Pembayaran gagal atau kadaluarsa');
        // Redirect or update UI
        window.location.href = '/failed';
      }
    }
  }, 5000); // Check every 5 seconds
  
  // Stop polling after 30 minutes
  setTimeout(() => {
    clearInterval(interval);
  }, 30 * 60 * 1000);
}
```

---

## 5️⃣ REACT EXAMPLE

```jsx
import { useState, useEffect } from 'react';

function PaymentPage() {
  const [paymentChannels, setPaymentChannels] = useState([]);
  const [selectedMethod, setSelectedMethod] = useState('');
  const [cart, setCart] = useState([]);
  const [totalPrice, setTotalPrice] = useState(0);
  
  useEffect(() => {
    fetchPaymentChannels();
  }, []);
  
  const fetchPaymentChannels = async () => {
    const response = await fetch('http://127.0.0.1:8000/api/payment/channels', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const result = await response.json();
    if (result.success) {
      setPaymentChannels(result.data);
    }
  };
  
  const handleCashPayment = async (paidAmount) => {
    const response = await fetch('http://127.0.0.1:8000/api/transactions', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        payment_type: 'cash',
        payment_method: 'cash',
        total_price: totalPrice,
        paid_amount: paidAmount,
        change: paidAmount - totalPrice,
        items: cart
      })
    });
    
    const result = await response.json();
    if (result.success) {
      alert('Pembayaran tunai berhasil!');
      // Clear cart, redirect, etc
    }
  };
  
  const handleOnlinePayment = async () => {
    const response = await fetch('http://127.0.0.1:8000/api/transactions', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        payment_type: 'online',
        tripay_method: selectedMethod,
        customer_phone: '08123456789',
        total_price: totalPrice,
        items: cart
      })
    });
    
    const result = await response.json();
    if (result.success) {
      window.open(result.data.tripay_checkout_url, '_blank');
      // Start polling
      pollPaymentStatus(result.data.id);
    }
  };
  
  return (
    <div>
      <h2>Pilih Metode Pembayaran</h2>
      
      <button onClick={() => handleCashPayment(100000)}>
        Bayar Tunai
      </button>
      
      <select onChange={(e) => setSelectedMethod(e.target.value)}>
        <option value="">Pilih Metode Online</option>
        {paymentChannels.map(channel => (
          <option key={channel.code} value={channel.code}>
            {channel.name}
          </option>
        ))}
      </select>
      
      <button onClick={handleOnlinePayment} disabled={!selectedMethod}>
        Bayar Online
      </button>
    </div>
  );
}
```

---

## 6️⃣ POSTMAN COLLECTION

### Import ke Postman:

```json
{
  "info": {
    "name": "Kasir API - Payment",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Get Payment Channels",
      "request": {
        "method": "GET",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}"
          }
        ],
        "url": {
          "raw": "{{base_url}}/api/payment/channels",
          "host": ["{{base_url}}"],
          "path": ["api", "payment", "channels"]
        }
      }
    },
    {
      "name": "Create Cash Transaction",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}"
          },
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"payment_type\": \"cash\",\n  \"payment_method\": \"cash\",\n  \"total_price\": 50000,\n  \"paid_amount\": 100000,\n  \"change\": 50000,\n  \"items\": [\n    {\n      \"id\": 1,\n      \"qty\": 2,\n      \"price\": 15000\n    }\n  ]\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/transactions",
          "host": ["{{base_url}}"],
          "path": ["api", "transactions"]
        }
      }
    },
    {
      "name": "Create Online Transaction",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}"
          },
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n  \"payment_type\": \"online\",\n  \"tripay_method\": \"BRIVA\",\n  \"customer_phone\": \"08123456789\",\n  \"total_price\": 50000,\n  \"items\": [\n    {\n      \"id\": 1,\n      \"qty\": 2,\n      \"price\": 15000\n    }\n  ]\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/transactions",
          "host": ["{{base_url}}"],
          "path": ["api", "transactions"]
        }
      }
    },
    {
      "name": "Check Payment Status",
      "request": {
        "method": "GET",
        "header": [
          {
            "key": "Authorization",
            "value": "Bearer {{token}}"
          }
        ],
        "url": {
          "raw": "{{base_url}}/api/payment/status/2",
          "host": ["{{base_url}}"],
          "path": ["api", "payment", "status", "2"]
        }
      }
    }
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "http://127.0.0.1:8000"
    },
    {
      "key": "token",
      "value": "YOUR_TOKEN_HERE"
    }
  ]
}
```

---

## ✅ CHECKLIST TESTING

- [ ] Login dan dapatkan token
- [ ] Get payment channels
- [ ] Create cash transaction
- [ ] Verify cash transaction status (should be "paid")
- [ ] Create online transaction dengan BRIVA
- [ ] Verify online transaction status (should be "pending")
- [ ] Open checkout URL
- [ ] Simulate payment (di Tripay sandbox)
- [ ] Check status lagi (should be "paid")
- [ ] Filter transactions by payment_type
- [ ] Filter transactions by payment_status

---

## 🎉 SELESAI!

Semua contoh API sudah lengkap dan siap digunakan!
