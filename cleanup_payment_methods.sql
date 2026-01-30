-- Cleanup Payment Method Prefixes
-- This script removes the type prefix from payment_method field in transactions table
-- Run this with: php artisan db:sql < cleanup_payment_methods.sql

UPDATE transactions 
SET payment_method = REPLACE(payment_method, 'bank_transfer: ', '')
WHERE payment_method LIKE 'bank_transfer:%';

UPDATE transactions 
SET payment_method = REPLACE(payment_method, 'e_wallet: ', '')
WHERE payment_method LIKE 'e_wallet:%';

UPDATE transactions 
SET payment_method = REPLACE(payment_method, 'qris: ', '')
WHERE payment_method LIKE 'qris:%';

-- Verify the changes
SELECT id, payment_method, transaction_type, created_at 
FROM transactions 
WHERE payment_method IS NOT NULL 
ORDER BY created_at DESC 
LIMIT 10;
