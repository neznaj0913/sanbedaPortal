<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Access Code | San Beda Portal</title>
    <link rel="stylesheet" href="{{ asset('css/visitor.css') }}">
</head>
<body>
    <!-- 🔐 Access Code Overlay -->
    <div class="overlay">
        <div class="overlay-content">
            <h3>Enter Access Code</h3>

            {{-- ✅ Error Message --}}
            @if($errors->any())
                <div class="alert error">
                    {{ $errors->first('code') }}
                </div>
            @endif

            {{-- ✅ Form --}}
            <form action="{{ route('accesscode.verify') }}" method="POST">
                @csrf
                <input 
                    type="text" 
                    name="code" 
                    id="code" 
                    placeholder="Enter your access code"
                    required
                >
                <button type="submit">Enter</button>
            </form>
        </div>
    </div>

    {{-- Optional Script for Animation or Redirection --}}
    <script>
        // Example: smooth fade out when form submitted
        const form = document.querySelector('form');
        form.addEventListener('submit', () => {
            document.querySelector('.overlay').style.opacity = '0.6';
        });
    </script>
</body>
</html>
