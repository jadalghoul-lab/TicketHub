<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket - {{ $ticket->ticket_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .ticket-box {
            border: 2px dashed #ccc;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #4f46e5;
            font-size: 28px;
        }
        .header h2 {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 20px;
        }
        .content {
            display: table;
            width: 100%;
        }
        .details {
            display: table-cell;
            width: 70%;
            vertical-align: top;
        }
        .qr-code {
            display: table-cell;
            width: 30%;
            vertical-align: top;
            text-align: right;
        }
        .detail-item {
            margin-bottom: 15px;
        }
        .detail-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .detail-value {
            font-size: 18px;
            font-weight: bold;
            color: #222;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <div class="ticket-box">
        <div class="header">
            <h1>TicketHub</h1>
            <h2>{{ $ticket->ticketType->event->title }}</h2>
        </div>

        <div class="content">
            <div class="details">
                <div class="detail-item">
                    <div class="detail-label">Attendee Name</div>
                    <div class="detail-value">{{ $ticket->order->user->name }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Date & Time</div>
                    <div class="detail-value">{{ $ticket->ticketType->event->start_date->format('l, F j, Y \a\t H:i') }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Location</div>
                    <div class="detail-value">{{ $ticket->ticketType->event->venue_name ?? 'TBA' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Ticket Type</div>
                    <div class="detail-value">{{ $ticket->ticketType->name }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Ticket Code</div>
                    <div class="detail-value" style="font-family: monospace;">{{ $ticket->ticket_number }}</div>
                </div>
            </div>

            <div class="qr-code">
                @php
                    $qrCodeService = app(\App\Services\TicketService::class);
                    // generateQrCode returns SVG, we can convert it to base64 or just output
                    $svg = $qrCodeService->generateQrCode($ticket);
                    // DomPDF handles basic SVG
                @endphp
                {!! $svg !!}
            </div>
        </div>

        <div class="footer">
            Please present this ticket at the entrance. Valid for one entry only.
        </div>
    </div>

</body>
</html>
