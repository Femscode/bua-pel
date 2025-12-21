@extends('admin.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/user-change-password.css') }}" />
@endsection

@section('breadcrumb')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button"><div class="solar-breadcrumb-item">Home</div></button>
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper"><div class="solar-breadcrumb-item">Admin</div></div>
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper"><p class="solar-breadcrumb-current">Change Password</p></div>
</div>
@endsection

@section('content')
<div class="dashboard2" style="width:100%">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="top">
        <div class="div-wrapper">
            <h2 class="text-wrapper-4">Change Password</h2>
        </div>
    </div>

    <form class="password-form" method="POST" action="{{ route('admin.change-password.update') }}">
        @csrf
        <div class="frame-2">
            <label for="current-password" class="label-email-address">Current Password</label>
            <input type="password" id="current-password" name="current_password" class="input" placeholder="Enter current password" required>
        </div>

        <div class="frame-2">
            <label for="new-password" class="label-email-address">New Password</label>
            <input type="password" id="new-password" name="password" class="input" placeholder="Enter new password" required>
        </div>

        <div class="frame-2">
            <label for="confirm-password" class="label-email-address">Confirm Password</label>
            <input type="password" id="confirm-password" name="password_confirmation" class="input" placeholder="Re-enter new password" required>
        </div>

        <button type="submit" class="request">
            <div class="request-quote">Save Password</div>
        </button>
    </form>
</div>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    font-size: 14px;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.alert li {
    margin-bottom: 5px;
}

.password-form {
    max-width: 500px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.frame-2 {
    margin-bottom: 20px;
}

.label-email-address {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.input:focus {
    outline: none;
    border-color: #003177;
    box-shadow: 0 0 0 2px rgba(0, 49, 119, 0.1);
}

.request {
    background: #003177;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
    margin-top: 20px;
}

.request:hover {
    background: #002a5c;
}

.request-quote {
    font-weight: 500;
}

@media (max-width: 768px) {
    .password-form {
        padding: 20px;
        margin: 10px;
    }
}
</style>
@endsection