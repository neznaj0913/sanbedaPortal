<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visitor Arrival Notification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Visitor Arrival Notification</h2>
    <p>Hello,</p>
    <p>A visitor has arrived:</p>

    <ul>
        <li><strong>Name:</strong> {{ $name }}</li>
        <li><strong>Department:</strong> {{ $department }}</li>
        <li><strong>Purpose:</strong> {{ $purpose }}</li>
        <li><strong>Gate Pass No:</strong> {{ $gatepass_no }}</li>
        <li><strong>Time In:</strong> {{ \Carbon\Carbon::parse($time_in)->format('h:i A') }}</li>
    </ul>

    <p>Thank you,<br>San Beda Visitor Management System</p>
</body>
</html>
