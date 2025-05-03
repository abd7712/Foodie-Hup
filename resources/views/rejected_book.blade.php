<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 40px;">

<div style="max-width: 600px; margin: auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

    <h2 style="color: #e63946;">Reservation Rejected</h2>

    <p style="font-size: 16px; color: #333;">Hello <strong>{{ $user_name }}</strong>,</p>

    <p style="font-size: 16px; color: #333;">
        We're sorry to let you know that your reservation request has been <strong style="color: #e63946;">rejected</strong>.
    </p>

    <div style="margin-top: 20px; background-color: #f1f1f1; padding: 15px; border-radius: 8px;">
        <h4 style="margin: 0 0 10px 0; color: #1d3557;">Your Reservation Details:</h4>
        <ul style="list-style-type: none; padding: 0; color: #333;">
            <li><strong>Date:</strong> {{ $date }}</li>
            <li><strong>Time:</strong> {{ $start }} - {{ $end }}</li>
        </ul>
    </div>

    <p style="margin-top: 20px; font-size: 16px;"><strong>Reason for rejection:</strong> {{ $rejected_reason }}</p>

    @if($conflict_books)
        <div style="margin-top: 20px;">
            <h4 style="color: #1d3557;">Conflicting Reservations:</h4>
            <p style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; color: #333;">
                {!! nl2br(e($conflict_books)) !!}
            </p>
        </div>
    @else
        <div style="margin-top: 20px;">
            <p style="color: #555;">There were no conflicting reservations within the exact times of your booking.</p>
        </div>
    @endif

    @if($conflict_books_buffer_formatted)
        <div style="margin-top: 20px;">
            <h4 style="color: #1d3557;">Time Buffer Conflicts (30 mins rule):</h4>
            <p style="background-color: #fff3cd; padding: 15px; border-radius: 8px; color: #856404;">
                {!! nl2br(e($conflict_books_buffer_formatted)) !!}
            </p>
        </div>
    @else
        <div style="margin-top: 20px;">
            <p style="color: #555;">There were no conflicts found due to the 30-minute buffer rule.</p>
        </div>
    @endif

    <p style="margin-top: 30px; font-size: 14px; color: #555;">
        You can try submitting a new reservation with a different time or reach out to us for more help.
    </p>

    <p style="margin-top: 30px; font-size: 14px; color: #aaa;">Thank you for understanding.<br><strong>— Your Restaurant Team</strong></p>

</div>

</body>
</html>
