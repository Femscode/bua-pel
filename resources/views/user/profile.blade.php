@extends('user.master')

@section('breadcrumb')
<div class="breadcrumb">
    <div class="container">
        <nav>
            <a href="{{ route('home') }}">Home</a> > 
            <span>Account Info</span>
        </nav>
    </div>
</div>
@endsection

@section('content')
<section class="profile-section">
    <h2>Account Information</h2>
    
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
        <form method="POST" action="{{ route('user.profile.update') }}" class="admin-form">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="name" class="form-label">
                        Full Name <span class="required">*</span>
                    </label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">
                        Email Address <span class="required">*</span>
                    </label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="form-label">
                        Phone Number <span class="optional">(Optional)</span>
                    </label>
                    <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                </div>
                
                <div class="form-group full-width">
                    <label for="address" class="form-label">
                        Address <span class="optional">(Optional)</span>
                    </label>
                    <textarea id="address" name="address" class="form-input" rows="3">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</section>
@endsection