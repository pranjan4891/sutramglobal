<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header img {
            max-height: 90px;
        }
        .content {
            padding: 20px;
        }
        .content strong {
            font-weight: 600;
        }
        .content p {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px auto;
            color: #fff;
            text-decoration: none;
            background-color: #007bff;
            border-radius: 4px;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            padding: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://sutramglobal.com/public/web/images/SutramBlack.png" alt="Company Logo">
        </div>
        <div class="content">
            <p>Dear <strong>{{ $data['userinfo']['name'] }}</strong>,</p>
            <p>Thank you for shopping with <strong>sutramglobal</strong>. We have successfully received your order. Below are the details:</p>
            <hr>
            <p><strong>Order Number:</strong> {{ $data['order']->unique_order_id }}</p>
            <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($data['order']->date)->format('d M Y') }}</p>
            <p><strong>Product(s):</strong></p>
            <ol>
                @foreach ($data['item'] as $item)
                    <li>
                        <strong>{{ $item->title }}</strong> (Qty: {{ $item->qty }},
                        @if($item->color )
                        Color: {{ $item->color }},
                        @endif
                        Size: {{ $item->size }}) - ₹{{ number_format($item->total_price, 2) }}
                    </li>
                @endforeach
            </ol>
            <p><strong>Total Amount:</strong> ₹{{ number_format($data['order']->gtotal, 2) }}</p>
            <p><strong>Delivery Address:</strong></p>
            <p>{{ $data['order']->address }}, {{ $data['order']->city }}, {{ $data['order']->state }}, {{ $data['order']->country }} - {{ $data['order']->zip }}</p>
            <hr>
            <p>Thank you for choosing <strong>sutramglobal</strong>. We hope to see you again soon!</p>
            <p>Warm Regards,</p>
            <p><strong>sutramglobal</strong></p>
            <p><i>sutramglobal.com</i></p>
            <p><i>sutramglobal@gmail.com</i></p>
            <p><i>+91 8299314643</i></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} sutramglobal. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
