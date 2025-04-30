@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h2 class="text-center mb-4 fw-bold">{{ __('Create your account') }}</h2>
                    <p class="text-center text-muted mb-4">Join thousands of teams using our platform to manage their projects.</p>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">{{ __('Full Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your full name">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email address">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Choose a secure password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-medium">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label text-muted" for="terms">
                                    {{ __('I agree to the') }} <a href="#" class="text-decoration-none">Terms of Service</a> {{ __('and') }} <a href="#" class="text-decoration-none">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-medium">
                                {{ __('Create Account') }}
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0 text-muted">
                                {{ __('Already have an account?') }} <a href="{{ route('login') }}" class="text-decoration-none fw-medium">{{ __('Log in') }}</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-primary {
        background-color: #4e46e5;
        border-color: #4e46e5;
        border-radius: 6px;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
    }
    
    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }
    
    .form-control {
        border-radius: 6px;
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
    }
    
    .form-control:focus {
        border-color: #4e46e5;
        box-shadow: 0 0 0 0.25rem rgba(78, 70, 229, 0.15);
    }
    
    .form-check-input:checked {
        background-color: #4e46e5;
        border-color: #4e46e5;
    }
    
    .card {
        border-radius: 12px;
    }
</style>
@endsection