<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
   <link rel="stylesheet" href="{{ asset('css/forgot.css') }}">

</head>
<body>
    <div class="auth-container">
        <h2>Forgot Password</h2>
        <p>Enter your email to receive a password reset link.</p>

        {{-- Status Alert --}}
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            @error('email') 
                <p class="error">{{ $message }}</p> 
            @enderror
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>

        <a href="{{ route('login.submit') }}" class="forgot-link">Back to Login</a>
    </div>
</body>
</html>
