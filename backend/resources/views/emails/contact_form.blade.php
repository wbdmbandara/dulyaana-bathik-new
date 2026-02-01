<html>
    <head>
        <title>New Contact Form Submission - Dulyaana Bathik</title>
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
                background-color: #2563eb;
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
                background-color: #2563eb;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
            .button:hover {
                background-color: #1d4ed8;
            }
            a.button {
                text-decoration: none;
                color: white;
            }
            .info-box {
                background-color: #f8f9fa;
                border-left: 4px solid #2563eb;
                padding: 15px;
                margin: 20px 0;
                border-radius: 4px;
            }
            .info-row {
                margin: 10px 0;
                padding: 8px 0;
                border-bottom: 1px solid #e0e0e0;
            }
            .info-row:last-child {
                border-bottom: none;
            }
            .info-label {
                font-weight: bold;
                color: #2563eb;
                display: inline-block;
                min-width: 100px;
            }
            .info-value {
                color: #333;
            }
            .message-box {
                background-color: #fff;
                border: 1px solid #e0e0e0;
                padding: 15px;
                margin: 20px 0;
                border-radius: 4px;
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            .alert-badge {
                display: inline-block;
                background-color: #ef4444;
                color: white;
                padding: 4px 12px;
                border-radius: 12px;
                font-size: 12px;
                font-weight: bold;
                margin-left: 10px;
            }
        </style>
    </head>
    <body>
        <div class="email-container">            
            <div class="email-header">
                <h1>📧 New Contact Form Submission</h1>
                <h3>Dulyaana Bathik Website</h3>
            </div>
            <div class="email-body">
                <h2>Hello Admin,</h2>
                <p>
                    You have received a new message through the contact form on your website.
                    <span class="alert-badge">NEW</span>
                </p>

                <div class="info-box">
                    <h3 style="margin-top: 0; color: #2563eb;">📋 Contact Details</h3>
                    
                    <div class="info-row">
                        <span class="info-label">👤 Name:</span>
                        <span class="info-value">{{ $name }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">📧 Email:</span>
                        <span class="info-value">
                            <a href="mailto:{{ $email }}" style="color: #2563eb; text-decoration: none;">{{ $email }}</a>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">📌 Subject:</span>
                        <span class="info-value">{{ $subject }}</span>
                    </div>
                </div>

                <h3 style="color: #2563eb;">💬 Message</h3>
                <div class="message-box">{{ $customerMessage }}</div>

                <p style="margin-top: 30px;">
                    <strong>Quick Actions:</strong>
                </p>
                <p>
                    <a href="mailto:{{ $email }}?subject=Re: {{ $subject }}" class="button">
                        ✉️ Reply to {{ $name }}
                    </a>
                </p>

                <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; color: #666; font-size: 14px;">
                    <strong>📅 Received:</strong> {{ date('F j, Y \a\t g:i A', strtotime('+5 hours 30 minutes')) }}<br>
                    <strong>🌐 Source:</strong> Contact Form - Dulyaana Bathik Website
                </p>

                <p style="color: #999; font-size: 12px; margin-top: 20px;">
                    💡 <em>Tip: Respond to customer inquiries within 24 hours for the best customer experience.</em>
                </p>
            </div>
            <div class="email-footer">
                <p>&copy; {{ $year }} Dulyaana Bathik. All rights reserved.</p>
                <p style="margin-top: 5px;">This is an automated notification from your website contact form.</p>
            </div>
        </div>
    </body>
</html>