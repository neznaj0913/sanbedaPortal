<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot.css') }}"> 
    <title>San Beda Portal | Login</title>
</head>

<body style="background: url('{{ asset('src/bg.jpg') }}') no-repeat center center fixed; background-size: cover; filter: brightness(0.85);">

    <header>
        <img src="{{ asset('src/Sanbeda-svd.svg') }}" alt="San Beda Logo" class="header-logo">
        <span class="header-text">San Beda College Alabang</span>
    </header>

    <div class="login-container">
        <h2>Login</h2>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
            >

            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required>
                <span id="togglePassword" class="toggle-password">👁</span>
            </div>

            <button type="submit">Login</button>
        </form>

        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="#" class="forgot-link">Forgot your password?</a>

        <div class="register-link">
            Don’t have an account? 
            <a href="{{ route('register.form') }}">Register here</a>
        </div>
    </div>


    <div id="forgotPasswordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>

            <h2>Forgot Password</h2>
            <p>Enter your email to receive a password reset link.</p>

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
        </div>
    </div>

    @include('layouts.footer-auth')

    <script>
    document.addEventListener("DOMContentLoaded", () => {

        const modal = document.getElementById("forgotPasswordModal");
        const forgotLink = document.querySelector(".forgot-link");
        const closeBtn = modal.querySelector(".close");

        forgotLink.addEventListener("click", e => {
            e.preventDefault();
            modal.style.display = "block";
        });

        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        window.addEventListener("click", e => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });

        @if (session('status'))
            modal.style.display = "block";
        @endif


 
        const password = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", () => {
            const visible = password.type === "text";
            password.type = visible ? "password" : "text";
            togglePassword.textContent = visible ? "👁" : "⌣";
        });

    });
    </script>

</body>
</html>
