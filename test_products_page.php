<?php

// Quick test script to check if products page loads without errors
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Http\Request::create('/products', 'GET')
);

echo "Status Code: " . $response->getStatusCode() . PHP_EOL;
echo "Content Length: " . strlen($response->getContent()) . " bytes" . PHP_EOL;

// Check for errors in response
if ($response->getStatusCode() === 200) {
    echo "✓ Page loads successfully!" . PHP_EOL;
    
    // Check if filters are in the response
    $content = $response->getContent();
    if (strpos($content, 'productFilters()') !== false) {
        echo "✓ Alpine.js filter function found!" . PHP_EOL;
    }
    if (strpos($content, 'Brands') !== false) {
        echo "✓ Brand filter section found!" . PHP_EOL;
    }
    if (strpos($content, 'Search products') !== false) {
        echo "✓ Search input found!" . PHP_EOL;
    }
} else {
    echo "✗ Error: Status code is not 200" . PHP_EOL;
    echo substr($response->getContent(), 0, 500) . PHP_EOL;
}

$kernel->terminate($request, $response);
