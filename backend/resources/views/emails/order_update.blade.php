<html>
    <head>
        <title>Order Status Update - Dulyaana Bathik</title>
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
                <h1>Order Status Update - Dulyaana Bathik</h1>
                <h3>Your Order Status Has Been Updated</h3>
            </div>
            <div class="email-body">
                <h2>Hello, {{ $name }}!</h2>
                <p>
                    We wanted to inform you that the status of your order with Dulyaana Bathik has been updated.
                </p>
                <p>
                    <strong>Order Number:</strong> {{ $orderNumber }}<br>
                    <strong>Order Status:</strong> {{ ucfirst($order->status) }}<br>
                    <strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}<br>
                    <strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}
                </p>
                @if($order->courier_name)
                <p>
                    <strong>Courier Name:</strong> {{ $order->courier_name }}
                </p>
                @endif
                @if($order->courier_tracking_no)
                <p>
                    <strong>Courier Tracking Number:</strong> {{ $order->courier_tracking_no }}
                </p>
                @endif
                @if($order->admin_note)
                <p>
                    <strong>Admin Note:</strong> {{ $order->admin_note }}
                </p>
                @endif
                <p>
                    If you have any questions or need further assistance, please don't hesitate to contact our support team.
                </p>
                <p style="text-align: center;">
                    <a href="{{ $actionUrl }}" class="button">🛍️ Explore Collections</a>
                    <a href="{{ $orderDetailsUrl }}" class="button">📦 View Order Details</a>
                </p>
                <p>
                    Thank you for choosing Dulyaana Bathik!<br>
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