@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <h1 class="h3 fw-bold text-gray-900">Create New Team</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('teams.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fa fa-arrow-left me-1"></i> Back to Teams
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-4 pb-2">
                        <div class="d-flex align-items-center mb-3">
                            <div class="team-icon bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 48px; height: 48px;">
                                <i class="fa fa-users fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-1">Team Information</h5>
                                <p class="text-muted small mb-0">Create a new team to collaborate on projects</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('teams.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">Team Name</label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter team name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small mt-2">
                                <i class="fa fa-info-circle me-1"></i> Choose a descriptive name for your team
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-medium">Description (Optional)</label>
                            <textarea class="form-control form-control-lg @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Briefly describe your team's purpose">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-5 d-flex flex-column flex-sm-row gap-3">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fa fa-plus-circle me-2"></i> Create Team
                            </button>
                            <a href="{{ route('teams.index') }}" class="btn btn-light btn-lg px-4">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
         
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #4e46e5;
        --secondary-color: #6b7280;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
    }
    
    body {
        background-color: #f9fafb;
        color: #111827;
        font-family: 'Inter', sans-serif;
    }
    
    .text-gray-900 {
        color: #111827;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border-color: #e5e7eb;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e46e5;
        box-shadow: 0 0 0 0.25rem rgba(78, 70, 229, 0.15);
    }
    
    .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .card {
        border-radius: 12px;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
    }
    
    .rounded-pill {
        border-radius: 50rem;
    }
    
    .bg-primary-subtle {
        background-color: rgba(78, 70, 229, 0.1);
    }
    
    .bg-info-subtle {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .text-primary {
        color: var(--primary-color) !important;
    }
    
    .text-info {
        color: var(--info-color) !important;
    }
</style>
@endsection