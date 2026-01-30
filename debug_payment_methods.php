<?php

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$methods = Transaction::distinct()->pluck('payment_method')->toArray();

echo "Distinct Payment Methods:\n";
foreach ($methods as $method) {
    echo "- " . $method . "\n";
}
