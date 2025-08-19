<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice['unique_order_id'] }}</title>
    <style>
     /* General Styles */
     * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        #logo-imgs {
            height: 100px;
            width: 510px;
            padding: 5px;
        }
        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        /* Header Styles */
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info p {
            margin: 5px 0;
            color: #666;
        }
        /* Customer Info */
        .customer-info {
            margin-bottom: 30px;
        }
        .customer-info p {
            margin: 5px 0;
        }
        /* Invoice Details */
        .invoice-details {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f1f1f1;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        /* Footer */
        .invoice-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
        /* Responsive Design */
        @media screen and (max-width: 768px) {
            #logo-imgs {
                height: 68px;
                width: 330px;
                padding: 5px;
            }
            .invoice-container {
                padding: 15px;
            }
            .company-info {
                font-size: 14px;
            }
            .customer-info p, .invoice-details p {
                font-size: 14px;
            }
            table {
                font-size: 14px;
            }
            th, td {
                padding: 8px;
            }
        }
        @media screen and (max-width: 480px) {
            h1, h2 {
                font-size: 18px;
            }
            .company-info p {
                font-size: 12px;
            }
            .customer-info p {
                font-size: 12px;
            }
            table {
                font-size: 12px;
            }
            th, td {
                padding: 6px;
            }
            .invoice-footer {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
<section class="py-5">
    <div class="invoice-container">
        <!-- Header -->
        @php
        $setting = \App\Models\Setting::find(1);
    @endphp
        <header class="invoice-header">
            <div class="company-info">

                <h1>{{$setting->site_title}}</h1>
                <p>{{$setting->address}}</p>
                <p>Phone: {{$setting->phone}} | Email: {{$setting->email}}</p>
            </div>
        </header>

        <!-- Customer Info -->
        <section class="customer-info">
            <h2>Customer Details</h2>
            <p><strong>Name:</strong> {{ $invoice['name'] }}</p>
            <p><strong>Phone:</strong>+91 {{ $invoice['phone'] }}</p>
            <p><strong>Address:</strong> {{ $invoice['address'] }}, {{ $invoice['city'] }}, {{ $invoice['state'] }}, {{ $invoice['country'] }} - {{ $invoice['zip'] }}</p>
            <p><strong>Order ID:</strong> {{ $invoice['unique_order_id'] }}</p>
            <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($invoice['date'])->format('d-M-Y') }}</p>
        </section>

        <!-- Order Details -->
        <section class="invoice-details">
            <h2>Order Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Title</th>
                        <th>SKU</th>
                        <th>Unit Price (INR)</th>
                        <th>Quantity</th>
                        <th>Total Price (INR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice['products'] as $product)
                        <tr>
                            <td>{{ $product->title }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->qty }}</td>
                            <td>{{ number_format($product->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="4">Subtotal</td>
                        <td>{{ number_format($invoice['sub_total'], 2) }}</td>
                    </tr>
                    @if($invoice['discount_price'] > 0)
                        <tr class="total-row">
                            <td colspan="4">Discount</td>
                            <td>-{{ number_format($invoice['discount_price'], 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="4">Shipping Charge</td>
                        <td>{{ number_format($invoice['shipping_charge'], 2) }}</td>
                    </tr>
                   
                    <tr class="total-row">
                        <td colspan="4"><strong>Net Total</strong></td>
                        <td><strong>{{ number_format($invoice['gtotal'], 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Footer -->
        <footer class="invoice-footer">
            <p>Thank you for shopping with us! If you have any questions, please contact us at {{$setting->email}}</p><br>
            <p>Note- This is not a Tax Invoice</p>
        </footer>
    </div>
</section>
</body>
</html>
