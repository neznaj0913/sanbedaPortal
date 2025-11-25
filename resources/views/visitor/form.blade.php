<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Form | San Beda Portal</title>

  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/forgot.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/visitor.css') }}">
</head>

<body 
style="background: url('{{ asset('src/bg.jpg') }}') no-repeat center center fixed; background-size: cover; filter: brightness(0.85);">

<header>
    <img src="{{ asset('src/Sanbeda-svd.svg') }}" alt="San Beda Logo" class="header-logo">
    <span class="header-text">San Beda College Alabang</span>
</header>


@if(!session('access_granted'))
<div class="overlay" id="accessOverlay">
    <div class="overlay-content">
        <h3>Enter Access Code</h3>

        @if($errors->any())
            <div class="alert error">{{ $errors->first('code') }}</div>
        @endif

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
@endif


<div class="visitor-container {{ !session('access_granted') ? 'blurred' : '' }}">
    <div class="logo-container"></div> 

    <h2>GATE-PASS FORM</h2>

 
    @if(session('success'))
        <div class="alert success" id="successMessage">
            {{ session('success') }} <br><br>
            <strong>Gate Pass No:</strong> {{ session('gatepass_no') }} <br>
            <strong>Time In:</strong> {{ session('time_in') }}
        </div>
    @endif

    
    <form id="visitorForm" action="{{ route('visitor.store') }}" method="POST" class="form-landscape">
        @csrf

       
        <div class="form-section left">
            <h3 class="section-title">Visitor Information</h3>

            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required>
            </div>

            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" name="department" id="department" value="{{ old('department') }}" required>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="setOtherDay">
                <label for="setOtherDay">Set appointment on another day?</label>
            </div>

            <div id="appointmentSection" class="form-group">
                <label for="appointment_time">🕓 Appointment Date & Time</label>
                <input type="datetime-local" id="appointment_time" name="appointment_time">
                <small>Select your visit date & time (if not today).</small>
            </div>
        </div>

        {{-- === RIGHT SIDE === --}}
        <div class="form-section right">
            <h3 class="section-title">Company & Visit Details</h3>

            <div class="form-group">
                <label for="company_affiliation">Company / Affiliation:</label>
                <input type="text" name="company_affiliation" id="company_affiliation" value="{{ old('company_affiliation') }}">
            </div>

            <div class="form-group">
                <label for="contact_person">Contact Person:</label>
                <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" required>
            </div>

            <div class="form-group">
                <label for="contact_info">Contact Person’s Email:</label>
                <input type="email" name="contact_info" id="contact_info" value="{{ old('contact_info') }}">
            </div>

           <div class="form-group">
            <label for="purpose">Purpose of Visit:</label>

            <select name="purpose" id="purpose" required>
                <option value="">-- Select Purpose --</option>
                <option value="Meeting" Meeting </option>
                <option value="Delivery">Delivery</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Interview">Interview</option>
                <option value="Official Business">Official Business</option>
                <option value="Other">Other</option>
            </select>
        </div>

          
            <div class="form-group" id="otherPurposeGroup" style="display: none;">
                <label for="other_purpose">Please specify:</label>
                <input type="text" name="other_purpose" id="other_purpose" placeholder="Enter specific purpose">
            </div>

           
            <div class="form-submit">
                <button type="submit" class="submit-btn">Submit</button>
            </div>
        </div>
    </form>
</div>


<script src="{{ asset('js/visitor.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const successMsg = document.getElementById("successMessage");
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.opacity = "0";
            successMsg.style.transition = "opacity 0.5s ease";
            setTimeout(() => successMsg.remove(), 500);
        }, 4000);
    }

    const overlay = document.getElementById('accessOverlay');
    if (overlay) {
        document.querySelector('.visitor-container').classList.add('blurred');
    }
});
</script>

</body>
</html>
