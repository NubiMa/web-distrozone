# DistroZone API Testing Guide

## Base URL

```
http://localhost:8000/api
```

## Test User Accounts

-   **Admin**: admin@distrozone.com / admin123
-   **Kasir**: budi@distrozone.com / kasir123
-   **Customer**: customer@test.com / customer123

---

## 1. Authentication Tests

### 1.1 Login as Admin

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin@distrozone.com\",\"password\":\"admin123\"}"
```

**Expected Response:**

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin DistroZone",
            "email": "admin@distrozone.com",
            "role": "admin"
        },
        "token": "1|xxxxx..."
    }
}
```

### 1.2 Login as Kasir

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"budi@distrozone.com\",\"password\":\"kasir123\"}"
```

### 1.3 Login as Customer

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"customer@test.com\",\"password\":\"customer123\"}"
```

### 1.4 Register New Customer

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"Test Customer\",\"email\":\"test@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\",\"phone\":\"081234567890\",\"address\":\"Jakarta Selatan\"}"
```

### 1.5 Get Profile (Authenticated)

```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 1.6 Logout

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 2. Public Endpoints (No Auth Required)

### 2.1 Browse Products

```bash
curl -X GET "http://localhost:8000/api/products?page=1"
```

### 2.2 Search Products

```bash
curl -X GET "http://localhost:8000/api/products?search=kaos&brand=Nike"
```

### 2.3 Get Product Detail

```bash
curl -X GET "http://localhost:8000/api/products/1"
```

### 2.4 Get Shipping Destinations

```bash
curl -X GET http://localhost:8000/api/shipping/destinations
```

### 2.5 Calculate Shipping

```bash
curl -X POST http://localhost:8000/api/shipping/calculate \
  -H "Content-Type: application/json" \
  -d "{\"destination\":\"Jakarta\",\"quantity\":5}"
```

---

## 3. Admin Endpoints

> **Note**: Replace `YOUR_ADMIN_TOKEN` with the token from admin login

### 3.1 Employee Management

#### Create Employee

```bash
curl -X POST http://localhost:8000/api/admin/employees \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"John Doe\",\"email\":\"john@distrozone.com\",\"password\":\"password123\",\"nik\":\"3175012345670003\",\"phone\":\"081234567899\",\"address\":\"Jakarta\"}"
```

#### List Employees

```bash
curl -X GET "http://localhost:8000/api/admin/employees?page=1" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Search Employees

```bash
curl -X GET "http://localhost:8000/api/admin/employees?search=john" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Get Employee Detail

```bash
curl -X GET http://localhost:8000/api/admin/employees/1 \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Update Employee

```bash
curl -X PUT http://localhost:8000/api/admin/employees/1 \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"John Updated\",\"email\":\"john@distrozone.com\",\"nik\":\"3175012345670003\",\"phone\":\"081234567899\",\"address\":\"Jakarta Barat\"}"
```

#### Delete Employee

```bash
curl -X DELETE http://localhost:8000/api/admin/employees/1 \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 3.2 Product Management

#### Create Product

```bash
curl -X POST http://localhost:8000/api/admin/products \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"brand\":\"Nike\",\"type\":\"lengan pendek\",\"color\":\"Hitam\",\"size\":\"L\",\"selling_price\":150000,\"cost_price\":100000,\"stock\":50,\"description\":\"Kaos Nike hitam ukuran L\"}"
```

#### List Products

```bash
curl -X GET "http://localhost:8000/api/admin/products?page=1" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Filter Products

```bash
curl -X GET "http://localhost:8000/api/admin/products?brand=Nike&size=L&in_stock=1" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Get Product Statistics

```bash
curl -X GET http://localhost:8000/api/admin/products/statistics \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Update Product

```bash
curl -X PUT http://localhost:8000/api/admin/products/1 \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"brand\":\"Nike\",\"type\":\"lengan pendek\",\"color\":\"Hitam\",\"size\":\"L\",\"selling_price\":155000,\"cost_price\":100000,\"stock\":45}"
```

#### Delete Product

```bash
curl -X DELETE http://localhost:8000/api/admin/products/1 \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 3.3 Reports

#### Financial Report (All Cashiers)

```bash
curl -X GET "http://localhost:8000/api/admin/reports/financial?start_date=2024-01-01&end_date=2024-12-31" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Daily Sales Report

```bash
curl -X GET "http://localhost:8000/api/admin/reports/daily-sales?start_date=2024-01-01&end_date=2024-12-31" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Top Products

```bash
curl -X GET "http://localhost:8000/api/admin/reports/top-products?limit=10" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Cashier Performance

```bash
curl -X GET "http://localhost:8000/api/admin/reports/cashier-performance?start_date=2024-01-01&end_date=2024-12-31" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 3.4 Settings

#### Get All Settings

```bash
curl -X GET http://localhost:8000/api/admin/settings \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Get Operational Status

```bash
curl -X GET http://localhost:8000/api/admin/settings/operational-status \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Update Operational Hours

```bash
curl -X PUT http://localhost:8000/api/admin/settings/operational-hours \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"online_open_time\":\"09:00\",\"online_close_time\":\"18:00\"}"
```

---

## 4. Kasir Endpoints

> **Note**: Replace `YOUR_KASIR_TOKEN` with the token from kasir login

### 4.1 View Products

```bash
curl -X GET "http://localhost:8000/api/kasir/products?search=nike" \
  -H "Authorization: Bearer YOUR_KASIR_TOKEN"
```

### 4.2 Create POS Transaction

```bash
curl -X POST http://localhost:8000/api/kasir/transactions \
  -H "Authorization: Bearer YOUR_KASIR_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"payment_method\":\"tunai\",\"items\":[{\"product_id\":1,\"quantity\":2},{\"product_id\":2,\"quantity\":1}],\"notes\":\"Customer membayar cash\"}"
```

### 4.3 List Transactions

```bash
curl -X GET "http://localhost:8000/api/kasir/transactions?type=offline" \
  -H "Authorization: Bearer YOUR_KASIR_TOKEN"
```

### 4.4 Get Pending Online Orders

```bash
curl -X GET http://localhost:8000/api/kasir/orders/pending \
  -H "Authorization: Bearer YOUR_KASIR_TOKEN"
```

### 4.5 Verify Payment (Approve)

```bash
curl -X POST http://localhost:8000/api/kasir/orders/1/verify \
  -H "Authorization: Bearer YOUR_KASIR_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"action\":\"approve\",\"notes\":\"Payment verified\"}"
```

### 4.6 Verify Payment (Reject)

```bash
curl -X POST http://localhost:8000/api/kasir/orders/1/verify \
  -H "Authorization: Bearer YOUR_KASIR_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"action\":\"reject\",\"notes\":\"Invalid payment proof\"}"
```

### 4.7 Personal Financial Report

```bash
curl -X GET "http://localhost:8000/api/kasir/reports/financial?start_date=2024-01-01&end_date=2024-12-31" \
  -H "Authorization: Bearer YOUR_KASIR_TOKEN"
```

---

## 5. Customer Endpoints

> **Note**: Replace `YOUR_CUSTOMER_TOKEN` with the token from customer login
> **Important**: Customer endpoints are protected by operational hours (10:00-17:00 WIB)

### 5.1 Browse Products

```bash
curl -X GET "http://localhost:8000/api/customer/products?search=nike&size=L" \
  -H "Authorization: Bearer YOUR_CUSTOMER_TOKEN"
```

### 5.2 Get Product Detail

```bash
curl -X GET http://localhost:8000/api/customer/products/1 \
  -H "Authorization: Bearer YOUR_CUSTOMER_TOKEN"
```

### 5.3 Calculate Shipping

```bash
curl -X POST http://localhost:8000/api/customer/orders/shipping/calculate \
  -H "Authorization: Bearer YOUR_CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d "{\"destination\":\"Jakarta\",\"quantity\":5}"
```

**Expected Response (5 items = 2kg, Jakarta = Rp24,000/kg):**

```json
{
    "success": true,
    "message": "Shipping cost calculated successfully",
    "destination": "Jakarta",
    "quantity": 5,
    "weight_kg": 2,
    "rate_per_kg": 24000,
    "shipping_cost": 48000
}
```

### 5.4 Place Order (Requires multipart/form-data for payment proof)

```bash
# Note: This requires a file upload, easier to test with Postman
# Structure:
# POST /api/customer/orders
# Headers: Authorization: Bearer TOKEN
# Body (multipart/form-data):
# - items[0][product_id]: 1
# - items[0][quantity]: 3
# - payment_method: transfer
# - shipping_destination: Jakarta
# - shipping_address: Jl. Example No. 123
# - recipient_name: John Doe
# - recipient_phone: 081234567890
# - payment_proof: [image file]
```

### 5.5 List My Orders

```bash
curl -X GET "http://localhost:8000/api/customer/orders?status=pending" \
  -H "Authorization: Bearer YOUR_CUSTOMER_TOKEN"
```

### 5.6 Get Order Detail

```bash
curl -X GET http://localhost:8000/api/customer/orders/1 \
  -H "Authorization: Bearer YOUR_CUSTOMER_TOKEN"
```

---

## Testing Workflow Example

### Complete Order Flow Test:

1. **Login as Customer**
2. **Browse Products** to find items
3. **Calculate Shipping** for destination
4. **Place Order** with payment proof (use Postman for file upload)
5. **Login as Kasir**
6. **Check Pending Orders**
7. **Verify Payment** (approve or reject)
8. **Login as Admin**
9. **View Financial Reports** to see the transaction

---

## Important Notes

✅ **No Free Shipping**: All online orders calculate shipping: ceiling(quantity/3) × rate_per_kg
✅ **Operational Hours**: Customer endpoints only work 10:00-17:00 WIB
✅ **Stock Management**: Stock decreases automatically on transaction/order
✅ **Payment Verification**: Online orders need kasir approval

---

## Next Steps for Testing

1. **Use Postman/Insomnia** for easier testing with file uploads
2. **Import this as a collection** or use the curl commands
3. **Test the complete workflow** from order to verification
4. **Verify stock changes** after transactions
5. **Test operational hours** (should block customer orders outside 10:00-17:00)
