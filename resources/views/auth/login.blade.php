@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-card bg-white shadow-sm rounded-3 p-4 p-md-5">
                <h2 class="fw-bold mb-4 text-center">{{ __('Welcome back') }}</h2>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-medium">{{ __('Email address') }}</label>
                        <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label fw-medium mb-0">{{ __('Password') }}</label>
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            {{ __('Sign in') }}
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <p class="mb-0 text-secondary">
                            {{ __('Don\'t have an account?') }} 
                            <a href="{{ route('register') }}" class="text-decoration-none fw-medium">
                                {{ __('Sign up') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .login-card {
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .form-control {
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
    }
    
    .form-control:focus {
        border-color: #4e46e5;
        box-shadow: 0 0 0 0.25rem rgba(78, 70, 229, 0.1);
    }
    
    .btn-primary {
        background-color: #4e46e5;
        border-color: #4e46e5;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
    }
    
    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }
    
    .text-primary {
        color: #4e46e5 !important;
    }
    
    a {
        color: #4e46e5;
    }
    
    .form-check-input:checked {
        background-color: #4e46e5;
        border-color: #4e46e5;
    }
</style>
@endsection