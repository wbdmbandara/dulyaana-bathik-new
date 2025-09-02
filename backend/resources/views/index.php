<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unauthorized Access - Dulyaana Bathik</title>
    <meta name="description" content="Unauthorized access to Dulyaana Bathik">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">

    <!-- STYLES -->
    <style {csp-style-nonce}>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .lock-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        .lock-icon svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-code {
            font-size: 1.2rem;
            color: #e74c3c;
            font-weight: 600;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }

        .message {
            font-size: 1.1rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .go-back {
            margin-top: 1.5rem;
        }

        .go-back a {
            text-decoration: none;
            color: #fff;
            background: #3498db;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .go-back a:hover {
            background: #2980b9;
        }

        .brand {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .brand strong {
            color: #2c3e50;
            font-weight: 600;
        }

        .brand a,
        .brand a:visited{
            color: #764ba2;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .brand a:hover {
            color: #667eea;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .lock-icon {
                width: 60px;
                height: 60px;
            }
            
            .lock-icon svg {
                width: 30px;
                height: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="lock-icon">
            <svg viewBox="0 0 24 24">
                <path d="M18,8H17V6A5,5 0 0,0 12,1A5,5 0 0,0 7,6V8H6A2,2 0 0,0 4,10V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V10A2,2 0 0,0 18,8M12,3A3,3 0 0,1 15,6V8H9V6A3,3 0 0,1 12,3M8,10H16V20H8V10Z"/>
            </svg>
        </div>

        <h1>Access Denied</h1>
        
        <div class="error-code">HTTP 403 - Unauthorized</div>
        
        <div class="message">
            <strong>You do not have permission to access this resource.</strong><br>
            This is a protected resource that requires proper authentication.
        </div>

        <!-- Go Back Button -->
        <div class="go-back">
            <a href="http://localhost:3000" class="btn btn-secondary">← Go Back to Website</a>
        </div>

        <div class="brand">
            <strong><a href="http://localhost:3000">DULYAANA BATHIK</a> © <?php echo date('Y'); ?></strong> - All rights reserved.<br>
            Developed by <strong><a href="https://hyperflex.lk/" target="_blank">HYPERFLEX INNOVATION (PVT) LTD</a></strong>.
        </div>
    </div>

    <script {csp-script-nonce}>
        // Add some subtle animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.6s ease-out';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });

        // Prevent right-click context menu for security
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Disable F12, Ctrl+Shift+I, Ctrl+U
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
