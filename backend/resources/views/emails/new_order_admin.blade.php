<html>
    <head>
        <title>New Order Received - Dulyaana Bathik</title>
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
                background-color: #FF5722;
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
                background-color: #FF5722;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
            .button:hover {
                background-color: #E64A19;
            }
            a.button {
                text-decoration: none;
                color: white;
            }
            .order-details {
                margin-top: 20px;
                border-top: 1px solid #ddd;
                padding-top: 20px;
            }
            .order-details table {
                width: 100%;
                border-collapse: collapse;
            }
            .order-details th, .order-details td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            .order-details th {
                background-color: #f9f9f9;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">
                <h1>New Order Received - Dulyaana Bathik</h1>
                <h3>Admin Notification</h3>
            </div>
            <div class="email-body">
                <h2>Hello Admin!</h2>
                <p>
                    A new order has been placed on Dulyaana Bathik.
                </p>
                <p>
                    <strong>Customer:</strong> {{ $customer }}<br>
                    <strong>Order Number:</strong> {{ $orderNumber }}
                </p>
                <div class="order-details">
                    <h3>Order Details:</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p><strong>Total:</strong> {{ $order->final_amount }}</p>
                </div>
                <p style="text-align: center;">
                    <a href="{{ $orderDetailsUrl }}" class="button">📦 View Order Details</a>
                </p>
                <p>
                    Please process this order promptly.
                </p>
                <p>
                    Best regards,<br>
                    Dulyaana Bathik System
                </p>
            </div>
            <div class="email-footer">
                <p>&copy; {{ $year }} Dulyaana Bathik. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>