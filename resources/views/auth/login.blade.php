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
        <p class="solar-breadcrumb-current">Login</p>
    </div>
</div>

<div class="main-section">
    <img class="sign-up-bro" src="{{ url('homepage/images/login.png') }}" alt="Sign up illustration" />

    <div class="form-container">
        <div class="form-header">
            <h2 class="heading-register">Login</h2>
            <p class="join-to-us">LOGIN TO YOUR DASHBOARD</p>
        </div>

        <form class="register-form" method="POST" action="{{ route('login') }}">@csrf
       
           
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="example@gmail.com" value="{{ old('email') }}" required />
            </div>

            <div class="form-group password-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="********" required />
                <span class="toggle-password" onclick="togglePassword('password')">&#128065;</span>
            </div>

            <input type="submit" class="btn-register" value="LOGIN">

            <p class="form-footer">
                DON'T HAVE ACCOUNT? <a href="{{ route('register') }}">REGISTER</a>
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

    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                html: '{!! addslashes(implode("<br>", $errors->all())) !!}',
                confirmButtonColor: '#d33'
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{!! addslashes(session('success')) !!}',
                confirmButtonColor: '#28a745'
            });
        @endif
    });
</script>
@endsection