@extends('admin.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/settings.css') }}" />
@endsection

@section('breadcrumb')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button">
        <div class="solar-breadcrumb-item">Home</div>
    </button>
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper">
        <div class="solar-breadcrumb-item">Admin</div>
    </div>
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper">
        <p class="solar-breadcrumb-current">Settings</p>
    </div>
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

    <form class="settings-form" method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        <h1>Admin Settings</h1>
      
        <section class="personal-info">
            <h2>Personal Information</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="first-name">First Name <span class="required">*</span></label>
                    <input type="text" id="first-name" name="first_name" value="{{ old('first_name', explode(' ', auth()->user()->name)[0] ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name <span class="required">*</span></label>
                    <input type="text" id="last-name" name="last_name" value="{{ old('last_name', explode(' ', auth()->user()->name)[1] ?? '') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number (Optional)</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}">
            </div>
            <div class="form-group">
                <label for="address">Default Address</label>
                <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address ?? '') }}">
            </div>
        </section>
     
        <button type="submit" class="save-button">Save Changes</button>
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

.settings-form {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.settings-form h1 {
    color: #003177;
    margin-bottom: 30px;
    text-align: center;
}

.personal-info h2 {
    color: #333;
    margin-bottom: 20px;
    border-bottom: 2px solid #003177;
    padding-bottom: 10px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.required {
    color: #dc3545;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: #003177;
    box-shadow: 0 0 0 2px rgba(0, 49, 119, 0.1);
}

.save-button {
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

.save-button:hover {
    background: #002a5c;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .settings-form {
        padding: 20px;
        margin: 10px;
    }
}
</style>
@endsection