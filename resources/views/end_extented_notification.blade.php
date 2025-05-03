<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation Ended</title>
</head>
<body style="margin: 0; padding: 40px; background-color: #f2f2f2; font-family: 'Segoe UI', sans-serif;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        
        <h2 style="color: #2c3e50; margin-bottom: 20px;">Hello {{ $user_name }},</h2>

        <p style="font-size: 16px; color: #333333; line-height: 1.6;">
            {{ $custom_message }}
        </p>

        <div style="margin-top: 25px; padding: 20px; background-color: #ecf0f1; border-left: 5px solid #3498db; border-radius: 8px;">
            <p style="margin: 0; font-size: 15px; color: #555555;">
                You can now view or download your invoice from your account dashboard.
            </p>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/user/invoices') }}" style="display: inline-block; padding: 12px 24px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 15px;">
                View Invoice
            </a>
        </div>

        <p style="margin-top: 40px; font-size: 13px; color: #888888; text-align: center;">
            Thank you for visiting us.<br><strong>— The Restaurant Team</strong>
        </p>
    </div>

</body>
</html>
