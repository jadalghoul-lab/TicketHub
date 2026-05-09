<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Support Request</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="background-color: #f8fafc; border-bottom: 3px solid #4f46e5; padding: 20px; text-align: center;">
        <h2 style="color: #1e293b; margin: 0;">New Support Request</h2>
    </div>

    <div style="padding: 30px; border: 1px solid #e2e8f0; border-top: none;">
        <p style="margin-bottom: 20px;"><strong>Topic:</strong> {{ $subject }}</p>
        
        <p style="margin-bottom: 5px;"><strong>From:</strong></p>
        <p style="margin-top: 0; margin-bottom: 20px;">
            {{ $name }}<br>
            <a href="mailto:{{ $email }}" style="color: #4f46e5;">{{ $email }}</a>
        </p>

        <p style="margin-bottom: 5px;"><strong>Message:</strong></p>
        <div style="background-color: #f1f5f9; padding: 15px; border-radius: 5px; white-space: pre-wrap;">{{ $messageContent }}</div>
    </div>

    <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #64748b;">
        <p>This email was sent from the TicketHub Support Form.</p>
    </div>

</body>
</html>
