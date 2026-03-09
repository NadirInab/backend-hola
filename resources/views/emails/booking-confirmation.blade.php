<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .booking-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .booking-details h2 {
            margin-top: 0;
            color: #495057;
            font-size: 18px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .detail-value {
            color: #007bff;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmation</h1>
            <p>Your excursion booking has been confirmed!</p>
        </div>

        <div class="status-confirmed">
            ✓ STATUS: CONFIRMED
        </div>

        <div class="booking-details">
            <h2>Booking Details</h2>

            <div class="detail-row">
                <span class="detail-label">Booking Reference:</span>
                <span class="detail-value">#{{ $booking->id }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Customer Name:</span>
                <span class="detail-value">{{ $booking->customer_name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Excursion:</span>
                <span class="detail-value">{{ $booking->excursion->title }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span class="detail-value">{{ $booking->booking_date->format('l, F j, Y') }}</span>
            </div>

            @if($booking->excursion->pickup_times && count($booking->excursion->pickup_times) > 0)
            <div class="detail-row">
                <span class="detail-label">Pickup Times:</span>
                <span class="detail-value">{{ implode(', ', $booking->excursion->pickup_times) }}</span>
            </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">Number of Travelers:</span>
                <span class="detail-value">{{ $booking->number_of_travelers }} ({{ $booking->number_of_adults }} adults{{ $booking->number_of_children > 0 ? ', ' . $booking->number_of_children . ' children' : '' }})</span>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for choosing our service!</p>
            <p>If you have any questions, please contact us.</p>
        </div>
    </div>
</body>
</html>