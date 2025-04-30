@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <h1 class="h3 fw-bold text-gray-900">Create New Project</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fa fa-arrow-left me-1"></i> Back to Projects
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-4 pb-2">
                        <h5 class="fw-semibold mb-1">Project Details</h5>
                        <p class="text-muted small">Fill in the information below to create your new project</p>
                    </div>
                    
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">Project Name</label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter project name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-medium">Description</label>
                            <textarea class="form-control form-control-lg @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Describe your project and its goals">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="start_date" class="form-label fw-medium">Start Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-calendar-alt text-muted"></i>
                                    </span>
                                    <input type="date" class="form-control form-control-lg border-start-0 ps-0 @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                </div>
                                @error('start_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label fw-medium">End Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-calendar-alt text-muted"></i>
                                    </span>
                                    <input type="date" class="form-control form-control-lg border-start-0 ps-0 @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                </div>
                                @error('end_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="team_id" class="form-label fw-medium">Assign to Team</label>
                            <select class="form-select form-select-lg @error('team_id') is-invalid @enderror" id="team_id" name="team_id">
                                <option value="">-- Select Team --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->team_id }}" {{ old('team_id') == $team->team_id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted small mt-2">
                                <i class="fa fa-info-circle me-1"></i> If you select a team, all team members will be added to this project
                            </div>
                            @error('team_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-5 d-flex flex-column flex-sm-row gap-3">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fa fa-plus-circle me-2"></i> Create Project
                            </button>
                            <a href="{{ route('projects.index') }}" class="btn btn-light btn-lg px-4">
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
    
    .form-control, .form-select, .input-group-text {
        border-radius: 8px;
        border-color: #e5e7eb;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e46e5;
        box-shadow: 0 0 0 0.25rem rgba(78, 70, 229, 0.15);
    }
    
    .input-group-text {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    .form-control-lg, .form-select-lg, .input-group-text {
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
</style>
@endsection