<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Completed</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 40px;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        
        <h2 style="color: #27ae60; margin-top: 0;">🎉 Booking Completed</h2>

        <p style="font-size: 16px; color: #333;">Hello <strong>{{ $user_name }}</strong>,</p>

        <p style="font-size: 16px; color: #555; line-height: 1.6;">
            {{ $custom_message }}
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="https://your-website.com/account/invoices" style="background-color: #3498db; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                View Your Invoice
            </a>
        </div>

        <p style="font-size: 14px; color: #999; line-height: 1.5;">
            Thank you for booking with us!<br>
            — Your Restaurant Team
        </p>
    </div>

</body>
</html>
