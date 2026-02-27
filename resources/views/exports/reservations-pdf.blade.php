<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations Report - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        .container {
            max-width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #1a1a2e;
        }
        .header h1 {
            color: #1a1a2e;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .report-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f7f4;
            border-radius: 8px;
        }
        .report-info p {
            margin: 5px 0;
        }
        .report-info strong {
            color: #1a1a2e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #1a1a2e;
            color: #fff;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #f0f0f0;
        }
        .status-booked {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        .status-checked_in {
            background: #dcfce7;
            color: #16a34a;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        .status-checked_out {
            background: #f3f4f6;
            color: #4b5563;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #f8f7f4;
            border-radius: 8px;
        }
        .summary h3 {
            color: #1a1a2e;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        .summary-item {
            text-align: center;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e0ddd6;
        }
        .summary-item .value {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .summary-item .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 10px;
        }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .container {
                padding: 0;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏨 Hotel Reservation System</h1>
            <p>Reservations Report</p>
        </div>

        <div class="report-info">
            <p><strong>Report Generated:</strong> {{ now()->format('F d, Y \a\t H:i:s') }}</p>
            <p><strong>Total Reservations:</strong> {{ $reservations->count() }}</p>
        </div>

        @if($reservations->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Guest Name</th>
                    <th>Email</th>
                    <th>Room</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Paid</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                <tr>
                    <td>#{{ str_pad($reservation->reservation_id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $reservation->guest->full_name }}</td>
                    <td>{{ $reservation->guest->email }}</td>
                    <td>{{ $reservation->room->room_number }} ({{ $reservation->room->roomType->type_name }})</td>
                    <td>{{ $reservation->check_in->format('M d, Y') }}</td>
                    <td>{{ $reservation->check_out->format('M d, Y') }}</td>
                    <td class="text-right">${{ number_format($reservation->total_price, 2) }}</td>
                    <td class="text-right">${{ number_format($reservation->payments->sum('amount'), 2) }}</td>
                    <td>
                        <span class="status-{{ $reservation->status }}">
                            @if($reservation->status === 'checked_in')
                                Checked In
                            @elseif($reservation->status === 'checked_out')
                                Checked Out
                            @else
                                {{ ucfirst($reservation->status) }}
                            @endif
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <h3>Summary</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="value">{{ $reservations->count() }}</div>
                    <div class="label">Total Reservations</div>
                </div>
                <div class="summary-item">
                    <div class="value">${{ number_format($reservations->sum('total_price'), 2) }}</div>
                    <div class="label">Total Revenue</div>
                </div>
                <div class="summary-item">
                    <div class="value">${{ number_format($reservations->sum(fn($r) => $r->payments->sum('amount')), 2) }}</div>
                    <div class="label">Total Collected</div>
                </div>
                <div class="summary-item">
                    <div class="value">{{ $reservations->where('status', 'checked_in')->count() }}</div>
                    <div class="label">Currently Staying</div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center" style="padding: 40px; color: #666;">
            <p>No reservations found matching the criteria.</p>
        </div>
        @endif

        <div class="footer">
            <p>This report was generated by the Hotel Reservation System.</p>
            <p>© {{ date('Y') }} All rights reserved.</p>
        </div>
    </div>

    <script>
        // Auto-print when loaded
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
