<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visitor Arrival Notification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    
    <h2 style="color:#b30000;">Visitor Arrival Notification</h2>

    <p>Hello,</p>

    <p>You have a visitor currently waiting for you. Below are the visitor details:</p>

    <ul>
        <li><strong>Name:</strong> {{ $name }}</li>
        <li><strong>Department:</strong> {{ $department }}</li>
        <li><strong>Purpose:</strong> {{ $purpose }}</li>
        <li><strong>Gate Pass No:</strong> {{ $gatepass_no }}</li>
        <li><strong>Time In:</strong> {{ \Carbon\Carbon::parse($time_in)->format('h:i A') }}</li>
    </ul>

    <p>Please attend to your visitor at your earliest convenience.</p>

    <p>Thank you,<br>
    <strong>SBCA Security</strong></p>

</body>
</html>
