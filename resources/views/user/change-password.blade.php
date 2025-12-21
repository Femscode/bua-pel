@extends('user.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/user-change-password.css') }}" />
@endsection

@section('breadcrumb')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button"><div class="solar-breadcrumb-item">Home</div></button>
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper"><div class="solar-breadcrumb-item">Account</div></div>
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper"><p class="solar-breadcrumb-current">Change Password</p></div>
</div>
@endsection

@section('content')
<section class="password-section">
    <h2>Change Password</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form class="admin-form" method="POST" action="{{ route('user.change-password.update') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-input" placeholder="Enter current password" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter new password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Re-enter new password" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Save Password
                </button>
            </div>
        </form>
    </div>
</section>




@endsection

@section('script')

@endsection