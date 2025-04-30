@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-1">{{ $project->name }}</h1>
            <p class="text-muted mb-0">Project #{{ $project->project_id }} • Created {{ $project->created_at->format('M d, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('projects.edit', $project->project_id) }}" class="btn btn-primary">
                <i class="fa fa-edit me-1"></i> Edit Project
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="projectActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="projectActionsDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('sprints.index', $project->project_id) }}">
                            <i class="fa fa-running me-2 text-success"></i> Manage Sprints
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('projects.members', $project->project_id) }}">
                            <i class="fa fa-users me-2 text-info"></i> Manage Members
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('projects.destroy', $project->project_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this project?');">
                                <i class="fa fa-trash me-2"></i> Delete Project
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8 order-lg-2">
            <!-- Current Sprint Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-semibold text-gray-900 mb-1">Current Sprint</h5>
                            <p class="text-muted small mb-0">Active sprint progress and details</p>
                        </div>
                        @if($currentSprint)
                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Active</span>
                        @endif
                    </div>
                    
                    @if($currentSprint)
                        <h5 class="fw-semibold mb-3">{{ $currentSprint->goal }}</h5>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fa fa-calendar-alt text-primary me-2"></i>
                            <span>{{ $currentSprint->start_date->format('M d, Y') }} - {{ $currentSprint->end_date->format('M d, Y') }}</span>
                        </div>
                        
                        @php
                            $totalDays = $currentSprint->start_date->diffInDays($currentSprint->end_date);
                            $passedDays = $currentSprint->start_date->diffInDays(now());
                            $percentage = $totalDays > 0 ? min(($passedDays / $totalDays) * 100, 100) : 0;
                            $daysLeft = max(0, $currentSprint->end_date->diffInDays(now()));
                        @endphp
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small fw-medium">Sprint Progress</div>
                                <div class="small">{{ round($percentage) }}%</div>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="small text-muted mt-2">{{ $daysLeft }} days remaining</div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('sprints.show', $currentSprint->sprint_id) }}" class="btn btn-primary">
                                <i class="fa fa-eye me-1"></i> View Sprint Details
                            </a>
                            <a href="{{ route('tasks.index', $currentSprint->sprint_id) }}" class="btn btn-outline-primary">
                                <i class="fa fa-tasks me-1"></i> View Tasks
                            </a>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <div class="empty-state-icon mb-4">
                                <i class="fa fa-running fs-1 text-muted opacity-25"></i>
                            </div>
                            <h6 class="fw-medium mb-2">No Active Sprint</h6>
                            <p class="text-muted mb-4">This project doesn't have an active sprint yet.</p>
                            <a href="{{ route('sprints.create', $project->project_id) }}" class="btn btn-primary">
                                <i class="fa fa-plus me-1"></i> Create Sprint
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Team Members Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-semibold text-gray-900 mb-1">Team Members</h5>
                            <p class="text-muted small mb-0">People working on this project</p>
                        </div>
                        <a href="{{ route('projects.members', $project->project_id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fa fa-user-plus me-1"></i> Add Members
                        </a>
                    </div>
                    
                    @if($members->count() > 0)
                        <div class="row g-3">
                            @foreach($members->take(6) as $member)
                                <div class="col-md-6 col-lg-4">
                                    <div class="member-card p-3 rounded-3 border">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3 bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px; font-weight: 600;">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <div class="member-info">
                                                <h6 class="mb-0 fw-semibold">{{ $member->name }}</h6>
                                                <p class="small text-muted mb-0">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2 pt-2 border-top">
                                            <span class="badge bg-{{ $member->role === 'admin' ? 'danger' : ($member->role === 'lead' ? 'warning' : 'primary') }}-subtle text-{{ $member->role === 'admin' ? 'danger' : ($member->role === 'lead' ? 'warning' : 'primary') }} px-3 py-1 rounded-pill">
                                                {{ ucfirst($member->role) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($members->count() > 6)
                            <div class="text-center mt-4">
                                <a href="{{ route('projects.members', $project->project_id) }}" class="btn btn-outline-primary rounded-pill">
                                    View All {{ $members->count() }} Members
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="empty-state text-center py-4">
                            <div class="empty-state-icon mb-3">
                                <i class="fa fa-users fs-1 text-muted opacity-25"></i>
                            </div>
                            <p class="text-muted mb-3">No members assigned to this project yet.</p>
                            <a href="{{ route('projects.members', $project->project_id) }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="fa fa-plus me-1"></i> Add Members
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 order-lg-1">
            <!-- Project Info Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold text-gray-900 mb-3">Project Information</h5>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="small text-muted mb-1">Timeline</div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-calendar me-2 text-primary"></i>
                            @if($project->start_date && $project->end_date)
                                <span>{{ $project->start_date->format('M d, Y') }} - {{ $project->end_date->format('M d, Y') }}</span>
                            @elseif($project->start_date)
                                <span>Started on {{ $project->start_date->format('M d, Y') }}</span>
                            @elseif($project->end_date)
                                <span>Due by {{ $project->end_date->format('M d, Y') }}</span>
                            @else
                                <span class="text-muted">No dates specified</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="small text-muted mb-1">Last Updated</div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-clock me-2 text-primary"></i>
                            <span>{{ $project->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="small text-muted mb-1">Created By</div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-user me-2 text-primary"></i>
                            <span>{{ $project->created_by ?? 'Unknown' }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="small text-muted mb-1">Project ID</div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-hashtag me-2 text-primary"></i>
                            <span>{{ $project->project_id }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold text-gray-900 mb-3">Description</h5>
                    <p class="mb-0">{{ $project->description ?: 'No description provided for this project.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Sprints Table -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-semibold text-gray-900 mb-1">Project Sprints</h5>
                    <p class="text-muted small mb-0">All sprints for this project</p>
                </div>
                <a href="{{ route('sprints.create', $project->project_id) }}" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> New Sprint
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">Goal</th>
                            <th scope="col">Timeline</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tasks</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sprints as $sprint)
                            <tr>
                                <td class="fw-medium">{{ $sprint->goal }}</td>
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
                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $status }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark rounded-pill me-2">{{ $sprint->tasks->count() }}</span>
                                        <div class="progress flex-grow-1 rounded-pill" style="height: 6px; width: 80px;">
                                            @php
                                                $completedTasks = $sprint->tasks->where('status', 'done')->count();
                                                $totalTasks = $sprint->tasks->count();
                                                $taskPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $taskPercentage }}%" aria-valuenow="{{ $taskPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="View Sprint">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tasks.index', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-success rounded-circle ms-1" title="View Tasks">
                                        <i class="fa fa-tasks"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary rounded-circle ms-1" title="More Actions">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fa fa-running fs-1 text-muted opacity-25"></i>
                                    </div>
                                    <p class="mb-1 text-muted">No sprints created for this project yet</p>
                                    <a href="{{ route('sprints.create', $project->project_id) }}" class="btn btn-sm btn-primary rounded-pill mt-3">
                                        <i class="fa fa-plus me-1"></i> Create First Sprint
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($sprints->count() > 10)
                <div class="text-center mt-4">
                    <a href="{{ route('sprints.index', $project->project_id) }}" class="btn btn-outline-primary rounded-pill px-4">
                        View All Sprints
                    </a>
                </div>
            @endif
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
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .card {
        border-radius: 12px;
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
    
    .member-card {
        transition: all 0.2s ease;
        background-color: white;
    }
    
    .member-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    
    .rounded-pill {
        border-radius: 50rem;
    }
</style>
@endsection