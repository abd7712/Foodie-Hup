<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Already Verified</title>
    <style>
        body {
            background-color: #f2f4f8;
            font-family: 'Helvetica Neue', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .verification-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        }

        .header {
            background-color: #ff6b6b;
            padding: 30px;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
        }

        .content {
            padding: 30px;
            text-align: center;
        }

        .content h2 {
            color: #222;
            margin-bottom: 10px;
            font-size: 22px;
        }

        .content p {
            line-height: 1.6;
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }

        .button {
            background-color: #ff6b6b;
            color: white;
            padding: 14px 28px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
            display: inline-block;
        }

        .button:hover {
            background-color: #ff3b3b;
        }

        .footer {
            padding: 20px;
            font-size: 14px;
            color: #888;
            text-align: center;
            background-color: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="verification-wrapper">
        <div class="header">
            <h1>✅ Account Already Verified</h1>
        </div>
        <div class="content">
            <h2>You're All Set!</h2>
            <p>Your account has already been verified.<br>You can now go ahead and log in or continue enjoying Foodie Hub.</p>
        </div>
        <div class="footer">
            Need help? Just reply to our support or visit the help center.<br>
            &copy; {{ date('Y') }} Foodie Hub. All rights reserved.
        </div>
    </div>
</body>
</html>
