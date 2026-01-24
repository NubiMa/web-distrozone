<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $transaction->transaction_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .receipt {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        .receipt-info {
            margin-bottom: 20px;
        }

        .receipt-info table {
            width: 100%;
        }

        .receipt-info td {
            padding: 5px 0;
        }

        .receipt-info td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .items-table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }

        .items-table .item-name {
            font-weight: bold;
        }

        .items-table .item-details {
            font-size: 10px;
            color: #666;
        }

        .totals {
            margin-top: 20px;
            float: right;
            width: 300px;
        }

        .totals table {
            width: 100%;
        }

        .totals td {
            padding: 8px 10px;
        }

        .totals .total-label {
            text-align: right;
            font-weight: bold;
        }

        .totals .total-amount {
            text-align: right;
            width: 120px;
        }

        .totals .grand-total {
            border-top: 2px solid #333;
            border-bottom: 3px double #333;
            font-size: 14px;
            font-weight: bold;
        }

        .payment-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .status-paid {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .status-rejected {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>DISTROZONE</h1>
            <p>Official Receipt</p>
        </div>

        <!-- Receipt Information -->
        <div class="receipt-info">
            <table>
                <tr>
                    <td>Order Number:</td>
                    <td><strong>{{ $transaction->transaction_code }}</strong></td>
                </tr>
                <tr>
                    <td>Order Date:</td>
                    <td>{{ $transaction->created_at->format('d F Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td>Payment Status:</td>
                    <td>
                        <span class="payment-status status-{{ $transaction->payment_status }}">
                            {{ strtoupper($transaction->payment_status) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Payment Method:</td>
                    <td>{{ strtoupper($transaction->payment_method) }}</td>
                </tr>
            </table>
        </div>

        <!-- Shipping Information -->
        <div class="section-title">Shipping Information</div>
        <div class="receipt-info">
            <table>
                <tr>
                    <td>Recipient Name:</td>
                    <td>{{ $transaction->recipient_name }}</td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td>{{ $transaction->recipient_phone }}</td>
                </tr>
                <tr>
                    <td>Shipping Address:</td>
                    <td>{{ $transaction->shipping_address }}</td>
                </tr>
                <tr>
                    <td>Shipping Destination:</td>
                    <td>{{ $transaction->shipping_destination }}</td>
                </tr>
            </table>
        </div>

        <!-- Order Items -->
        <div class="section-title">Order Details</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Product</th>
                    <th style="width: 80px; text-align: center;">Qty</th>
                    <th style="width: 120px; text-align: right;">Unit Price</th>
                    <th style="width: 120px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $index => $detail)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <div class="item-name">{{ $detail->productVariant->product->name ?? 'Product' }}</div>
                            <div class="item-details">
                                {{ $detail->productVariant->color }} • Size {{ $detail->productVariant->size }}
                            </div>
                        </td>
                        <td style="text-align: center;">{{ $detail->quantity }}</td>
                        <td style="text-align: right;">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <table>
                <tr>
                    <td class="total-label">Subtotal:</td>
                    <td class="total-amount">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="total-label">Shipping ({{ $transaction->weight_kg }} kg):</td>
                    <td class="total-amount">Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</td>
                </tr>
                <tr class="grand-total">
                    <td class="total-label">TOTAL:</td>
                    <td class="total-amount">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="clear"></div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for shopping with DISTROZONE!</p>
            <p>This is a computer-generated receipt and does not require a signature.</p>
            <p>For any inquiries, please contact us through our customer service.</p>
        </div>
    </div>
</body>

</html>
