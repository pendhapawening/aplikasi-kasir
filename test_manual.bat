@echo off
echo === TEST API KASIR ===
echo.

echo [1] Testing LOGIN (Kredensial Benar)
curl -X POST http://127.0.0.1:8000/api/login -H "Content-Type: application/json" -d "{\"email\":\"admin@kasir.com\",\"password\":\"password\"}"
echo.
echo.

echo [2] Testing LOGIN (Kredensial Salah)
curl -X POST http://127.0.0.1:8000/api/login -H "Content-Type: application/json" -d "{\"email\":\"admin@kasir.com\",\"password\":\"wrong\"}"
echo.
echo.

echo [3] Testing GET Products (Tanpa Token) - Harus Error 401
curl -X GET http://127.0.0.1:8000/api/products
echo.
echo.

echo Silakan copy token dari response login di atas, lalu jalankan:
echo curl -X GET http://127.0.0.1:8000/api/products -H "Authorization: Bearer YOUR_TOKEN"
echo.

pause
