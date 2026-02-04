<html>
    <head>
        <title>Reset Your Password</title>
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
                <h1>Dulyaana Bathik</h1>
                <h3>Password Reset Request</h3>
            </div>
            <div class="email-body">
                <h2>Hello, {{ $name }}!</h2>
                <p>
                    We received a request to reset your password for your Dulyaana Bathik account.
                </p>
                <p>
                    If you made this request, click the button below to reset your password. This link will expire in 24 hours for security reasons.
                </p>
                <p>
                    <a href="{{ $resetLink }}" class="button">Reset Password</a>
                </p>
                <p>
                    If you did not request a password reset, please ignore this email. Your password will remain unchanged.
                </p>
                <p>
                    If you have any questions, contact our support team.
                </p>
                <p>
                    Best regards,<br>
                    The Dulyaana Bathik Team
                </p>
            </div>
            <div class="email-footer">
                <p>&copy; {{ $year }} Dulyaana Bathik. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
