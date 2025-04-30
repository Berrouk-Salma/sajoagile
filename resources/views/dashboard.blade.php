@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <h1 class="h3 fw-bold text-gray-900">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> New Project
                </a>
                <a href="{{ route('teams.create') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-plus"></i> New Team
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stats-icon bg-primary-subtle rounded-circle p-3">
                            <i class="fa fa-project-diagram text-primary fs-4"></i>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill">Projects</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ count($projects) }}</h3>
                    <p class="text-muted small mb-0">Total active projects</p>
                </div>
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-4">
                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-link text-primary p-0 fw-medium">
                        View All Projects <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stats-icon bg-success-subtle rounded-circle p-3">
                            <i class="fa fa-users text-success fs-4"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill">Teams</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ count($teams) }}</h3>
                    <p class="text-muted small mb-0">Across all projects</p>
                </div>
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-4">
                    <a href="{{ route('teams.index') }}" class="btn btn-sm btn-link text-success p-0 fw-medium">
                        View All Teams <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stats-icon bg-warning-subtle rounded-circle p-3">
                            <i class="fa fa-tasks text-warning fs-4"></i>
                        </div>
                        <span class="badge bg-warning-subtle text-warning rounded-pill">Tasks</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ count($tasks) }}</h3>
                    <p class="text-muted small mb-0">Assigned to you</p>
                </div>
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-4">
                    <a href="#yourTasks" class="btn btn-sm btn-link text-warning p-0 fw-medium">
                        View Your Tasks <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stats-icon bg-danger-subtle rounded-circle p-3">
                            <i class="fa fa-bell text-danger fs-4"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill">Alerts</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ count($notifications) }}</h3>
                    <p class="text-muted small mb-0">Unread notifications</p>
                </div>
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-4">
                    <button class="btn btn-sm btn-link text-danger p-0 fw-medium">
                        View All Notifications <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title fw-semibold text-gray-900 mb-0">
                        <i class="fa fa-chart-pie me-2 text-primary"></i> Task Distribution
                    </h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="progress rounded-pill" style="height: 12px;">
                        @if(array_sum($taskStatistics) > 0)
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($taskStatistics['todo'] / array_sum($taskStatistics)) * 100 }}%" 
                                title="Todo: {{ $taskStatistics['todo'] }}" data-bs-toggle="tooltip">
                            </div>
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($taskStatistics['in_progress'] / array_sum($taskStatistics)) * 100 }}%" 
                                title="In Progress: {{ $taskStatistics['in_progress'] }}" data-bs-toggle="tooltip">
                            </div>
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($taskStatistics['review'] / array_sum($taskStatistics)) * 100 }}%" 
                                title="Review: {{ $taskStatistics['review'] }}" data-bs-toggle="tooltip">
                            </div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($taskStatistics['done'] / array_sum($taskStatistics)) * 100 }}%" 
                                title="Done: {{ $taskStatistics['done'] }}" data-bs-toggle="tooltip">
                            </div>
                        @else
                            <div class="progress-bar bg-secondary opacity-25" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        @endif
                    </div>
                    <div class="row mt-4">
                        <div class="col-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2 rounded-circle bg-danger" style="width: 10px; height: 10px;"></div>
                                <span class="small text-gray-600">Todo</span>
                            </div>
                            <h5 class="fw-bold mb-0">{{ $taskStatistics['todo'] }}</h5>
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2 rounded-circle bg-warning" style="width: 10px; height: 10px;"></div>
                                <span class="small text-gray-600">In Progress</span>
                            </div>
                            <h5 class="fw-bold mb-0">{{ $taskStatistics['in_progress'] }}</h5>
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2 rounded-circle bg-info" style="width: 10px; height: 10px;"></div>
                                <span class="small text-gray-600">Review</span>
                            </div>
                            <h5 class="fw-bold mb-0">{{ $taskStatistics['review'] }}</h5>
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2 rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                                <span class="small text-gray-600">Done</span>
                            </div>
                            <h5 class="fw-bold mb-0">{{ $taskStatistics['done'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title fw-semibold text-gray-900 mb-0">
                        <i class="fa fa-bell me-2 text-primary"></i> Recent Notifications
                    </h5>
                    @if(count($notifications) > 5)
                    <span class="badge bg-primary-subtle text-primary rounded-pill">{{ count($notifications) - 5 }} more</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="notification-list">
                        @forelse($notifications->take(5) as $notification)
                            <div class="notification-item d-flex align-items-start p-3 border-bottom">
                                <div class="me-3">
                                    @switch($notification->type)
                                        @case('info')
                                            <div class="notification-icon bg-info-subtle text-info rounded-circle">
                                                <i class="fa fa-info"></i>
                                            </div>
                                            @break
                                        @case('success')
                                            <div class="notification-icon bg-success-subtle text-success rounded-circle">
                                                <i class="fa fa-check"></i>
                                            </div>
                                            @break
                                        @case('warning')
                                            <div class="notification-icon bg-warning-subtle text-warning rounded-circle">
                                                <i class="fa fa-exclamation"></i>
                                            </div>
                                            @break
                                        @case('error')
                                            <div class="notification-icon bg-danger-subtle text-danger rounded-circle">
                                                <i class="fa fa-times"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="notification-icon bg-primary-subtle text-primary rounded-circle">
                                                <i class="fa fa-bell"></i>
                                            </div>
                                    @endswitch
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1">{{ $notification->message }}</p>
                                    <p class="text-muted small mb-0">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="empty-state-icon mb-3">
                                    <i class="fa fa-bell-slash fs-1 text-muted opacity-25"></i>
                                </div>
                                <p class="text-muted">No notifications yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if(count($notifications) > 0)
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-4">View All</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4" id="yourTasks">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title fw-semibold text-gray-900 mb-0">
                        <i class="fa fa-tasks me-2 text-primary"></i> Your Tasks
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-filter me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#">All Tasks</a></li>
                            <li><a class="dropdown-item" href="#">High Priority</a></li>
                            <li><a class="dropdown-item" href="#">Due This Week</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="ps-4">Task</th>
                                    <th scope="col">Project</th>
                                    <th scope="col">Sprint</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Priority</th>
                                    <th scope="col" class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td class="ps-4 fw-medium">{{ $task->title }}</td>
                                        <td>
                                            @if($task->sprint && $task->sprint->project)
                                                <a href="{{ route('projects.show', $task->sprint->project->project_id) }}" class="text-decoration-none">
                                                    {{ $task->sprint->project->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->sprint)
                                                <a href="{{ route('sprints.show', $task->sprint->sprint_id) }}" class="text-decoration-none">
                                                    {{ $task->sprint->goal }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($task->status)
                                                @case('todo')
                                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">Todo</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">In Progress</span>
                                                    @break
                                                @case('review')
                                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">Review</span>
                                                    @break
                                                @case('done')
                                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Done</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Unknown</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($task->priority)
                                                @case('low')
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2 bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                                                        <span>Low</span>
                                                    </div>
                                                    @break
                                                @case('medium')
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2 bg-info rounded-circle" style="width: 8px; height: 8px;"></div>
                                                        <span>Medium</span>
                                                    </div>
                                                    @break
                                                @case('high')
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2 bg-warning rounded-circle" style="width: 8px; height: 8px;"></div>
                                                        <span>High</span>
                                                    </div>
                                                    @break
                                                @case('urgent')
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2 bg-danger rounded-circle" style="width: 8px; height: 8px;"></div>
                                                        <span>Urgent</span>
                                                    </div>
                                                    @break
                                                @default
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2 bg-secondary rounded-circle" style="width: 8px; height: 8px;"></div>
                                                        <span>Unknown</span>
                                                    </div>
                                            @endswitch
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('tasks.show', $task->task_id) }}" class="btn btn-sm btn-outline-primary rounded-circle">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-secondary rounded-circle ms-1">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state-icon mb-3">
                                                <i class="fa fa-clipboard-list fs-1 text-muted opacity-25"></i>
                                            </div>
                                            <p class="mb-1 text-muted">No tasks assigned to you</p>
                                            <a href="#" class="btn btn-sm btn-primary rounded-pill mt-3">Create New Task</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title fw-semibold text-gray-900 mb-0">
                        <i class="fa fa-project-diagram me-2 text-primary"></i> Recent Projects
                    </h5>
                    @if(count($projects) > 5)
                    <span class="badge bg-primary-subtle text-primary rounded-pill">{{ count($projects) - 5 }} more</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($projects->sortByDesc('created_at')->take(5) as $project)
                            <div class="list-group-item border-0 border-bottom py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('projects.show', $project->project_id) }}" class="fw-medium text-decoration-none">
                                            {{ $project->name }}
                                        </a>
                                        <p class="text-muted small mb-0">Created {{ $project->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary-subtle text-primary rounded-pill">
                                            {{ $project->members->count() }} {{ Str::plural('member', $project->members->count()) }}
                                        </span>
                                        <span class="badge bg-success-subtle text-success rounded-pill ms-1">
                                            {{ $project->sprints->count() }} {{ Str::plural('sprint', $project->sprints->count()) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="empty-state-icon mb-3">
                                    <i class="fa fa-folder-open fs-1 text-muted opacity-25"></i>
                                </div>
                                <p class="text-muted">No projects yet</p>
                                <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Create Project</a>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if(count($projects) > 0)
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">View All Projects</a>
                </div>
                @endif
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title fw-semibold text-gray-900 mb-0">
                        <i class="fa fa-users me-2 text-primary"></i> Your Teams
                    </h5>
                    @if(count($teams) > 5)
                    <span class="badge bg-primary-subtle text-primary rounded-pill">{{ count($teams) - 5 }} more</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($teams as $team)
                            <div class="list-group-item border-0 border-bottom py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('teams.show', $team->team_id) }}" class="fw-medium text-decoration-none">
                                            {{ $team->name }}
                                        </a>
                                        <p class="text-muted small mb-0">{{ $team->description ?? 'No description' }}</p>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary-subtle text-primary rounded-pill">
                                            {{ $team->members->count() }} {{ Str::plural('member', $team->members->count()) }}
                                        </span>
                                        <span class="badge bg-success-subtle text-success rounded-pill ms-1">
                                            {{ $team->projects->count() }} {{ Str::plural('project', $team->projects->count()) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="empty-state-icon mb-3">
                                    <i class="fa fa-user-friends fs-1 text-muted opacity-25"></i>
                                </div>
                                <p class="text-muted">No teams yet</p>
                                <a href="{{ route('teams.create') }}" class="btn btn-sm btn-primary rounded-pill px-4 mt-2">Create Team</a>
                            </div>
                        @endforelse
                    </div>
                </div>
                @if(count($teams) > 0)
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="{{ route('teams.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">View All Teams</a>
                </div>
                @endif
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
    
    .text-gray-600 {
        color: #4b5563;
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
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .stats-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
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
    
    .notification-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .card {
        border-radius: 8px;
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
</style>
@endsection