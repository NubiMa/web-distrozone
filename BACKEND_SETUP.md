## DistroZone Backend - Setup Instructions

Backend setup is complete! Follow these steps to get the system running:

### 1. Register Middleware

Add the following middleware aliases to your `bootstrap/app.php` or appropriate configuration file:

```php
'admin' => \App\Http\Middleware\AdminMiddleware::class,
'kasir' => \App\Http\Middleware\KasirMiddleware::class,
'customer' => \App\Http\Middleware\CustomerMiddleware::class,
'operational.hours' => \App\Http\Middleware\OperationalHoursMiddleware::class,
```

### 2. Run Migrations and Seeders

```bash
# Fresh migration with seeders
php artisan migrate:fresh --seed

# Or run them separately
php artisan migrate
php artisan db:seed
```

### 3. Create Storage Link

```bash
php artisan storage:link
```

### 4. Default User Accounts

After seeding, you'll have these accounts:

-   **Admin**: admin@distrozone.com / admin123
-   **Kasir 1**: budi@distrozone.com / kasir123
-   **Kasir 2**: siti@distrozone.com / kasir123
-   **Customer**: customer@test.com / customer123

### 5. Test the API

**Login Example:**

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@distrozone.com","password":"admin123"}'
```

**Get Products (Public):**

```bash
curl http://localhost:8000/api/products
```

### 6. API Endpoints Overview

#### Public (No Auth)

-   `POST /api/register` - Customer registration
-   `POST /api/login` - Login (all roles)
-   `GET /api/products` - Browse products
-   `GET /api/products/{id}` - Product details
-   `GET /api/shipping/destinations` - Available destinations
-   `POST /api/shipping/calculate` - Calculate shipping

#### Admin Routes (require admin role)

-   Employee CRUD: `/api/admin/employees`
-   Product CRUD: `/api/admin/products`
-   Reports: `/api/admin/reports/*`
-   Settings: `/api/admin/settings/*`

#### Kasir Routes (require kasir role)

-   Products: `/api/kasir/products`
-   Transactions: `/api/kasir/transactions`
-   Order Verification: `/api/kasir/orders/{id}/verify`
-   Reports: `/api/kasir/reports/*`

#### Customer Routes (require customer role + operational hours)

-   Orders: `/api/customer/orders`
-   Product browsing: `/api/customer/products`

### 7. Important Notes

✅ **No Free Shipping**: All online orders are charged shipping costs based on destination and weight (1kg = 3 shirts max)

✅ **Operational Hours**:

-   Online: 10:00 - 17:00 daily
-   Offline: 10:00 - 20:00 (closed Monday)

✅ **Role-Based Access**: All routes are protected by role-specific middleware

✅ **Stock Management**: Stock is automatically decreased on transaction/order creation

✅ **Payment Verification**: Online orders require Kasir approval before processing

### 8. Next Steps

-   Test all endpoints with Postman or similar tool
-   Build frontend views based on designSystem.txt
-   Implement guest routes for homepage, catalog, about pages
-   Add authentication pages (login/register)
