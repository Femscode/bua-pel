@extends('frontend.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/auth.css') }}" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="solar-breadcrumb">
    <a href="/" class="solar-breadcrumb-button">
        <div class="solar-breadcrumb-item">Home</div>
</a>
    
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper">
        <p class="solar-breadcrumb-current">Register</p>
    </div>
</div>

<div class="main-section">
    <img class="sign-up-bro" src="{{ url('homepage/images/login.png') }}" alt="Sign up illustration" />

    <div class="form-container">
        <div class="form-header">
            <h2 class="heading-register">Register</h2>
            <p class="join-to-us">CREATE AN ACCOUNT</p>
        </div>

        <form class="register-form" method="POST" action="{{ route('register') }}" id="registerForm">@csrf
       
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" placeholder="John Doe" value="{{ old('name') }}" required />
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="example@gmail.com" value="{{ old('email') }}" required />
            </div>

            <div class="form-group password-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="********" required />
                <span class="toggle-password" onclick="togglePassword('password')">&#128065;</span>
            </div>

            <div class="form-group password-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" name="password_confirmation" id="confirm-password" placeholder="********" required />
                <span class="toggle-password" onclick="togglePassword('confirm-password')">&#128065;</span>
            </div>
            <div id="password-match-indicator" style="margin-top: 5px; font-size: 12px;"></div>

            <input type="submit" class="btn-register" value="REGISTER">

            <p class="form-footer">
                ALREADY USER? <a href="{{ route('login') }}">LOGIN</a>
            </p>
        </form>
    </div>
</div>


@endsection

@section('script')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const toggle = field.nextElementSibling;
        
        if (field.type === 'password') {
            field.type = 'text';
            toggle.innerHTML = '&#128064;';
        } else {
            field.type = 'password';
            toggle.innerHTML = '&#128065;';
        }
    }

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const indicator = document.getElementById('password-match-indicator');
        
        if (confirmPassword === '') {
            indicator.innerHTML = '';
            return;
        }
        
        if (password === confirmPassword) {
            indicator.innerHTML = '<span style="color: green;">✓ Passwords match</span>';
        } else {
            indicator.innerHTML = '<span style="color: red;">✗ Passwords do not match</span>';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirm-password');
        
        passwordField.addEventListener('input', checkPasswordMatch);
        confirmPasswordField.addEventListener('input', checkPasswordMatch);
        
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match. Please check and try again.',
                    confirmButtonColor: '#d33'
                });
                return false;
            }
        });
    });
</script>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Registration Failed',
            html: '{!! implode("<br>", array_map("addslashes", $errors->all())) !!}',
            confirmButtonColor: '#d33'
        });
    });
</script>
@endif

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#28a745'
        });
    });
</script>
@endif
@endsection