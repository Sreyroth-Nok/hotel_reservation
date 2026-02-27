<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Cancelled</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f7f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #c9a961;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            color: #ffffff;
            margin: 10px 0 0;
            font-size: 14px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #1a1a2e;
            margin-bottom: 20px;
        }
        .status-badge {
            display: inline-block;
            background-color: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .reservation-details {
            background-color: #f8f7f4;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .reservation-details h2 {
            color: #1a1a2e;
            margin: 0 0 15px;
            font-size: 18px;
            border-bottom: 2px solid #c9a961;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0ddd6;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #6b7280;
            font-size: 14px;
        }
        .detail-value {
            color: #1a1a2e;
            font-weight: 600;
            font-size: 14px;
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f7f4;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e0ddd6;
        }
        .footer p {
            color: #6b7280;
            font-size: 12px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏨 Luxe Stay</h1>
            <p>Hotel Reservation System</p>
        </div>
        
        <div class="content">
            <p class="greeting">Dear {{ $reservation->guest->full_name }},</p>
            
            <span class="status-badge">✗ Reservation Cancelled</span>
            
            <p>We regret to inform you that your reservation has been cancelled. Below are the details of the cancelled reservation:</p>
            
            <div class="reservation-details">
                <h2>Cancelled Reservation Details</h2>
                <div class="detail-row">
                    <span class="detail-label">Reservation ID</span>
                    <span class="detail-value">#{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Room Number</span>
                    <span class="detail-value">{{ $reservation->room->room_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Room Type</span>
                    <span class="detail-value">{{ $reservation->room->roomType->type_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Original Check-In</span>
                    <span class="detail-value">{{ $reservation->check_in->format('F d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Original Check-Out</span>
                    <span class="detail-value">{{ $reservation->check_out->format('F d, Y') }}</span>
                </div>
            </div>
            
            <div class="info-box">
                <p><strong>Need to make a new reservation?</strong> Visit our website or contact us at reservations@luxestay.com to book your next stay.</p>
            </div>
            
            <p>If you have any questions or concerns regarding this cancellation, please don't hesitate to contact our front desk.</p>
            
            <p>We hope to have the opportunity to serve you in the future.</p>
            
            <p>Best regards,<br>
            <strong>The Luxe Stay Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this message.</p>
            <p>© {{ date('Y') }} Luxe Stay Hotel. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
