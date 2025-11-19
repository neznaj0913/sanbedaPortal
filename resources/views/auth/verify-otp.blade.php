<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify OTP</title>
</head>
<body>
    <h2>Verify OTP</h2>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('verify-otp') }}">
        @csrf
        <label>Enter the OTP sent to your email:</label><br>
        <input type="text" name="otp" maxlength="6" required><br><br>

        <button type="submit">Verify OTP</button>
    </form>
</body>
</html>
