<!DOCTYPE html>
<html>
<head>
    <title>Wishlist Notification</title>
    <!-- Bootstrap CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #000000;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-body {
            padding: 20px;
        }
        .email-body h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .email-body p {
            margin-bottom: 15px;
            color: #333333;
        }
        .email-footer {
            text-align: center;
            padding: 15px;
            background-color: #f1f1f1;
            font-size: 14px;
            color: #666666;
        }
        .btn-primary {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff !important;
            text-decoration: none;
            background-color: #000000;
            border-radius: 10px;
            border: 1px;
            text-align: center;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color:#f2f2f2;
            color: #000000 !important;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">

                <img src="https://sutramglobal.com/public/uploads/settings/1728368937_SutramWhite.png" alt="Logo" width="280" height="auto"
                    class="brand-image">

        </div>

        <!-- Body -->
        <div class="email-body">
            <h1>Hello, {{ $emailData['userName'] }}</h1>
            <p>We noticed that you have a product in your wishlist:</p>
            <ul>
                <li><b>Product Title:</b> {{ $emailData['productTitle'] }}</li>
                <li><b>Size:</b> {{ $emailData['productSize'] }}</li>
                <li><b>Color:</b> {{ $emailData['productColor'] }}</li>
                <li><b>Added On:</b> {{ $emailData['createdAt'] }}</li>
            </ul>
            <p>If you'd like to purchase this product, visit our website by clicking the button below:</p>
            <a href="https://sutramglobal.com/products-details/{{ $emailData['productSlug'] }}" class="btn btn-primary">View Product</a>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>Thank you for choosing {{ config('app.name') }}!</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
