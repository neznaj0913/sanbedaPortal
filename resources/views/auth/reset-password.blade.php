<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/forgot.css') }}">
</head>
<body>
      <body style="background: url('{{ asset('src/bg.jpg') }}') no-repeat center center fixed; background-size: cover; filter: brightness(0.85);">

    <header>
        <img src="{{ asset('src/Sanbeda-svd.svg') }}" alt="San Beda Logo" class="header-logo">
        <span class="header-text">San Beda College Alabang</span>
    </header>

    <div class="auth-container">
        <h2>Reset Password</h2>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
            @error('email') <p class="error">{{ $message }}</p> @enderror

            <input type="password" name="password" placeholder="New Password" required>
            @error('password') <p class="error">{{ $message }}</p> @enderror

            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

            <button type="submit">Reset Password</button>
        </form>

        <a href="{{ route('login.view') }}">Back to Login</a>
    </div>
</body>
</html>
