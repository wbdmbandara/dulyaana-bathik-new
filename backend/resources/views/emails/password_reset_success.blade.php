<html>
    <head>
        <title>Password Reset Successful</title>
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
                <h3>Password Reset Successful</h3>
            </div>
            <div class="email-body">
                <h2>Hello, {{ $name }}!</h2>
                <p>
                    Your password for your Dulyaana Bathik account has been successfully reset.
                </p>
                <p>
                    You can now log in to your account using your new password.
                </p>
                <a href="{{ $loginUrl }}" class="button">Log In</a>
                <p>
                    If you did not request this password reset, please contact our support team immediately to secure your account.
                </p>
                <p>
                    If you have any questions, feel free to reach out to our support team.
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
