<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation Confirmed</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:rgb(223, 159, 159); padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: auto; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="background-color:rgb(230, 19, 19); color: white; padding: 20px; text-align: center;">
            <h2 style="margin: 0;">🎉 Your Reservation is Confirmed!</h2>
        </div>
        <div style="padding: 30px;">
            <p style="font-size: 16px;">Hello <strong>{{ $user_name }}</strong>,</p>

            <p style="font-size: 16px;">We're excited to let you know that your reservation has been <strong style="color:rgb(191, 255, 0);">confirmed</strong>!</p>

            <table style="width: 100%; margin: 20px 0; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0;">📅 <strong>Date:</strong></td>
                    <td>{{ $date }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;">⏰ <strong>From:</strong></td>
                    <td>{{ $start }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;">⏰ <strong>To:</strong></td>
                    <td>{{ $end }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;">🍽️ <strong>Table #:</strong></td>
                    <td>{{ $table_id }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;">📍 <strong>Location:</strong></td>
                    <td>{{ ucfirst($location) }}</td>
                </tr>
            </table>

            <p style="font-size: 15px; line-height: 1.5;">{{ $custom_message }}</p>

            <p style="font-size: 15px;">We're looking forward to seeing you!</p>

        </div>
        <div style="background-color: #f0f0f0; padding: 15px; text-align: center; font-size: 12px; color: #888;">
            © {{ date('Y') }} Your Restaurant. All rights reserved.
        </div>
    </div>
</body>
</html>
