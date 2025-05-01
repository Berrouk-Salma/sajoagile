@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <h1 class="h3 fw-bold text-gray-900">Create New Sprint</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('sprints.index', $project->project_id) }}" class="btn btn-outline-secondary rounded-pill">
                <i class="fa fa-arrow-left me-1"></i> Back to Sprints
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-4 pb-2">
                        <div class="d-flex align-items-center mb-3">
                            <div class="project-icon bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 48px; height: 48px;">
                                <i class="fa fa-project-diagram fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-semibold mb-1">Sprint for {{ $project->name }}</h5>
                                <p class="text-muted small mb-0">Define the goal and timeline for this sprint</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('sprints.store', $project->project_id) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="goal" class="form-label fw-medium">Sprint Goal</label>
                            <input type="text" class="form-control form-control-lg @error('goal') is-invalid @enderror" id="goal" name="goal" value="{{ old('goal') }}" placeholder="What do you aim to accomplish in this sprint?" required>
                            @error('goal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted small mt-2">
                                <i class="fa fa-info-circle me-1"></i> A clear sprint goal helps the team understand what they're working towards
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="start_date" class="form-label fw-medium">Start Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fa fa-calendar-alt text-muted"></i>
                                    </span>
                                    <input type="date" class="form-control form-control-lg border-start-0 ps-0 @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') ?? now()->format('Y-m-d') }}" required>
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
                                    <input type="date" class="form-control form-control-lg border-start-0 ps-0 @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') ?? now()->addDays(14)->format('Y-m-d') }}" required>
                                </div>
                                @error('end_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="suggested-dates mb-4">
                            <div class="d-flex justify-content-between">
                                <div class="small text-muted">Suggested sprint durations:</div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setDuration(7)">1 week</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setDuration(14)">2 weeks</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setDuration(21)">3 weeks</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="setDuration(30)">1 month</button>
                            </div>
                        </div>

                        <input type="hidden" name="project_id" value="{{ $project->project_id }}">

                        <div class="mt-5 d-flex flex-column flex-sm-row gap-3">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fa fa-plus-circle me-2"></i> Create Sprint
                            </button>
                            <a href="{{ route('sprints.index', $project->project_id) }}" class="btn btn-light btn-lg px-4">
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

<script>
    function setDuration(days) {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        // If start date is empty, set it to today
        if (!startDate.value) {
            const today = new Date();
            startDate.value = today.toISOString().split('T')[0];
        }
        
        // Calculate end date based on start date + duration
        const start = new Date(startDate.value);
        const end = new Date(start);
        end.setDate(start.getDate() + days);
        
        // Format as YYYY-MM-DD for input
        endDate.value = end.toISOString().split('T')[0];
    }
    
    // Initialize with suggested dates when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('start_date');
        if (!startDate.value) {
            const today = new Date();
            startDate.value = today.toISOString().split('T')[0];
            setDuration(14); // Default to 2 weeks
        }
    });
</script>
@endsection