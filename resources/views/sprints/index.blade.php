@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-1">Sprints</h1>
            <p class="text-muted mb-0">Project: <a href="{{ route('projects.show', $project->project_id) }}" class="text-decoration-none">{{ $project->name }}</a></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sprints.create', $project->project_id) }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> New Sprint
            </a>
            <a href="{{ route('projects.show', $project->project_id) }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back to Project
            </a>
        </div>
    </div>

    @if($currentSprint)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-lg-4 current-sprint-banner" style="background-color: rgba(16, 185, 129, 0.07);">
                    <div class="p-4 h-100 d-flex flex-column justify-content-center">
                        <div class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3 align-self-start">Active Sprint</div>
                        <h3 class="fw-bold mb-3">{{ $currentSprint->goal }}</h3>
                        <div class="d-flex align-items-center mb-4">
                            <i class="fa fa-calendar-alt me-2 text-success"></i>
                            <span>{{ $currentSprint->start_date->format('M d') }} - {{ $currentSprint->end_date->format('M d, Y') }}</span>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small fw-medium">Sprint Progress</div>
                                @php
                                    $totalDays = $currentSprint->start_date->diffInDays($currentSprint->end_date);
                                    $passedDays = $currentSprint->start_date->diffInDays(now());
                                    $percentage = $totalDays > 0 ? min(($passedDays / $totalDays) * 100, 100) : 0;
                                    $daysLeft = max(0, $currentSprint->end_date->diffInDays(now()));
                                @endphp
                                <div class="small">{{ round($percentage) }}%</div>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="small text-muted mt-2">{{ $daysLeft }} days remaining</div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('sprints.show', $currentSprint->sprint_id) }}" class="btn btn-success">
                                <i class="fa fa-eye me-1"></i> View Details
                            </a>
                            <a href="{{ route('tasks.index', $currentSprint->sprint_id) }}" class="btn btn-outline-success">
                                <i class="fa fa-tasks me-1"></i> View Tasks
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="p-4">
                        <h5 class="fw-semibold mb-4">Sprint Overview</h5>
                        <div class="row g-4">
                            <div class="col-6 col-md-3">
                                <div class="d-flex flex-column align-items-center p-3 rounded-3" style="background-color: rgba(99, 102, 241, 0.07);">
                                    <h3 class="fw-bold text-primary m-0">{{ $currentSprint->tasks->count() }}</h3>
                                    <p class="text-muted small mb-0">Total Tasks</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex flex-column align-items-center p-3 rounded-3" style="background-color: rgba(245, 158, 11, 0.07);">
                                    <h3 class="fw-bold text-warning m-0">{{ $currentSprint->tasks->where('status', 'in_progress')->count() }}</h3>
                                    <p class="text-muted small mb-0">In Progress</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex flex-column align-items-center p-3 rounded-3" style="background-color: rgba(59, 130, 246, 0.07);">
                                    <h3 class="fw-bold text-info m-0">{{ $currentSprint->tasks->where('status', 'review')->count() }}</h3>
                                    <p class="text-muted small mb-0">In Review</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex flex-column align-items-center p-3 rounded-3" style="background-color: rgba(16, 185, 129, 0.07);">
                                    <h3 class="fw-bold text-success m-0">{{ $currentSprint->tasks->where('status', 'done')->count() }}</h3>
                                    <p class="text-muted small mb-0">Completed</p>
                                </div>
                            </div>
                        </div>
                        
                        @php
                            $todoCount = $currentSprint->tasks->where('status', 'todo')->count();
                            $inProgressCount = $currentSprint->tasks->where('status', 'in_progress')->count();
                            $reviewCount = $currentSprint->tasks->where('status', 'review')->count();
                            $doneCount = $currentSprint->tasks->where('status', 'done')->count();
                            $totalTasks = $currentSprint->tasks->count();
                        @endphp
                        
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small fw-medium">Tasks Completion</div>
                                <div class="small">{{ $doneCount }}/{{ $totalTasks }}</div>
                            </div>
                            <div class="progress rounded-pill" style="height: 12px;">
                                @if($totalTasks > 0)
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($todoCount / $totalTasks) * 100 }}%" title="To Do"></div>
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($inProgressCount / $totalTasks) * 100 }}%" title="In Progress"></div>
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($reviewCount / $totalTasks) * 100 }}%" title="Review"></div>
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($doneCount / $totalTasks) * 100 }}%" title="Done"></div>
                                @else
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="d-flex align-items-center small">
                                    <div class="me-1 rounded-circle bg-danger" style="width: 8px; height: 8px;"></div>
                                    <span class="text-muted">To Do</span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <div class="me-1 rounded-circle bg-warning" style="width: 8px; height: 8px;"></div>
                                    <span class="text-muted">In Progress</span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <div class="me-1 rounded-circle bg-info" style="width: 8px; height: 8px;"></div>
                                    <span class="text-muted">Review</span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <div class="me-1 rounded-circle bg-success" style="width: 8px; height: 8px;"></div>
                                    <span class="text-muted">Done</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="fw-semibold text-gray-900 mb-0">All Sprints</h5>
            @if($sprints->count() > 0)
            <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">{{ $sprints->count() }} total</span>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Sprint Goal</th>
                            <th>Timeline</th>
                            <th>Status</th>
                            <th>Tasks</th>
                            <th>Progress</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sprints as $sprint)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $sprint->goal }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-calendar-alt text-muted me-2"></i>
                                        <span>{{ $sprint->start_date->format('M d') }} - {{ $sprint->end_date->format('M d, Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $now = now();
                                        $status = 'Upcoming';
                                        $badgeClass = 'bg-secondary-subtle text-secondary';
                                        
                                        if ($now->between($sprint->start_date, $sprint->end_date)) {
                                            $status = 'Active';
                                            $badgeClass = 'bg-success-subtle text-success';
                                        } elseif ($now->gt($sprint->end_date)) {
                                            $status = 'Completed';
                                            $badgeClass = 'bg-primary-subtle text-primary';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-1 rounded-pill">{{ $status }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill">{{ $sprint->tasks->count() }}</span>
                                </td>
                                <td>
                                    @php
                                        $totalTasks = $sprint->tasks->count();
                                        $completedTasks = $sprint->tasks->where('status', 'done')->count();
                                        $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1 rounded-pill" style="height: 6px; width: 100px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="small text-muted">{{ $completedTasks }}/{{ $totalTasks }}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="View Sprint">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tasks.index', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-success rounded-circle" title="View Tasks">
                                            <i class="fa fa-tasks"></i>
                                        </a>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="More Actions">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('sprints.edit', $sprint->sprint_id) }}">
                                                        <i class="fa fa-edit me-2 text-primary"></i> Edit Sprint
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('sprints.destroy', $sprint->sprint_id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this sprint?')">
                                                            <i class="fa fa-trash me-2"></i> Delete Sprint
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fa fa-running fs-1 text-muted opacity-25"></i>
                                    </div>
                                    <p class="mb-1 text-muted">No sprints found for this project</p>
                                    <a href="{{ route('sprints.create', $project->project_id) }}" class="btn btn-sm btn-primary rounded-pill mt-3">
                                        <i class="fa fa-plus me-1"></i> Create First Sprint
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
    
    .bg-primary-subtle {
        background-color: rgba(78, 70, 229, 0.1);
    }
    
    .bg-success-subtle {
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    .bg-warning-subtle {
        background-color: rgba(245, 158, 11, 0.1);
    }
    
    .bg-danger-subtle {
        background-color: rgba(239, 68, 68, 0.1);
    }
    
    .bg-info-subtle {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .bg-secondary-subtle {
        background-color: rgba(107, 114, 128, 0.1);
    }
    
    .text-primary {
        color: var(--primary-color) !important;
    }
    
    .text-success {
        color: var(--success-color) !important;
    }
    
    .text-warning {
        color: var(--warning-color) !important;
    }
    
    .text-danger {
        color: var(--danger-color) !important;
    }
    
    .text-info {
        color: var(--info-color) !important;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }
    
    .btn-success {
        background-color: var(--success-color);
        border-color: var(--success-color);
    }
    
    .btn-success:hover {
        background-color: #059669;
        border-color: #059669;
    }
    
    .btn-outline-success {
        color: var(--success-color);
        border-color: var(--success-color);
    }
    
    .btn-outline-success:hover {
        background-color: var(--success-color);
        border-color: var(--success-color);
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
    }
    
    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(78, 70, 229, 0.03);
    }
    
    .badge {
        font-weight: 500;
    }
    
    .rounded-pill {
        border-radius: 50rem;
    }
    
    @media (max-width: 991.98px) {
        .current-sprint-banner {
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
    }
</style>
@endsection