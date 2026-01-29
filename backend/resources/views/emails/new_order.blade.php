<html>
    <head>
        <title>New Order Confirmation - Dulyaana Bathik</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f9f9f9;
                color: #333;
                margin: 0;
                padding: 0;
            }
            .email-container {
                max-width: 600px;
                margin: 20px auto;
                background: #ffffff;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            .email-header {
                background-color: #4CAF50;
                color: #ffffff;
                text-align: center;
                padding: 20px;
            }
            .email-body {
                padding: 20px;
                line-height: 1.6;
            }
            .email-footer {
                text-align: center;
                padding: 10px;
                background-color: #f1f1f1;
                font-size: 12px;
                color: #666;
            }
            .button {
                display: inline-block;
                padding: 10px 20px;
                margin-top: 20px;
                background-color: #4CAF50;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
            .button:hover {
                background-color: #45a049;
            }
            a.button {
                text-decoration: none;
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">
                <h1>New Order Confirmation - Dulyaana Bathik</h1>
                <h3>Thank you for your order!</h3>
            </div>
            <div class="email-body">
                <h2>Hello, {{ $name }}!</h2>
                <p>
                    Thank you for your order with Dulyaana Bathik!<br>
                    We are thrilled to have you as a valued customer and can't wait for you to experience the beauty of our handcrafted Batik sarees.
                </p>
                <p>
                    Your order number is <strong>{{ $orderNumber }}</strong>. We are currently processing your order and will notify you once it has been shipped.
                </p>
                <p>
                    In the meantime, feel free to explore our latest collections and discover more exquisite designs that you might love.
                </p>
                <p style="text-align: center;">
                    <a href="{{ $actionUrl }}" class="button">🛍️ Explore Collections</a>
                    <a href="{{ $orderDetailsUrl }}" class="button">📦 View Order Details</a>
                </p>
                <p>
                    If you have any questions or need help, our support team is always here for you.
                </p>
                <p>
                    Warm regards,<br>
                    The Dulyaana Bathik Team
                </p>
            </div>
            <div class="email-footer">
                <p>&copy; {{ $year }} Dulyaana Bathik. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>