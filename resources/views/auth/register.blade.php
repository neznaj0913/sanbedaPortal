<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <script src="{{ asset('js/register.js') }}"></script>

<head>
    <title>Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>
<body>
    <body style="background: url('{{ asset('src/bg.jpg') }}') no-repeat center center fixed; background-size: cover; filter: brightness(0.85);">

    <header>
        <img src="{{ asset('src/Sanbeda-svd.svg') }}" alt="San Beda Logo" class="header-logo">
        <span class="header-text">San Beda College Alabang</span>
    </header>


<form id="registerForm" method="POST" action="{{ route('register.submit') }}">
    @csrf
<div class="form-wrapper">
    <h2>Register</h2>
    <label>Username:</label>
    <input type="text" name="username" required>

    <label>First Name:</label>
    <input type="text" name="firstname" required>

    <label>Last Name:</label>
    <input type="text" name="lastname" required>

    <label>San Beda Email (@sanbeda-alabang.edu.ph only):</label>
    <input type="email" id="email" name="email" required>
    <button type="button" id="sendOtpBtn">Send OTP</button>

    <div id="otpSection" style="display:none;">
        <label>Enter OTP:</label>
        <input type="text" id="otpInput" name="otp" required>
        <button type="button" id="verifyOtpBtn">Verify OTP</button>
        <span id="otpStatus"></span>
    </div>

    <label>Password:</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required>
                <span class="toggle-password" data-target="password">👁</span>
            </div>

            <label>Confirm Password:</label>
            <div class="password-wrapper">
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <span class="toggle-password" data-target="password_confirmation">👁</span>
            </div>
    <br>
    <button type="submit" id="registerBtn" disabled>Register</button>
</form>
<div class="login-link">
    Already have an account? 
    <a href="{{ route('login.view') }}">Login here</a>
</div>

@include('layouts.footer-auth')



</body>
</html>
