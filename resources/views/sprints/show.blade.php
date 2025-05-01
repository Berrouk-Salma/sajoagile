@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-1">{{ $sprint->goal }}</h1>
            <p class="text-muted mb-0">Sprint for <a href="{{ route('projects.show', $project->project_id) }}" class="text-decoration-none">{{ $project->name }}</a></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tasks.create', $sprint->sprint_id) }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Add Task
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sprintActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sprintActionsDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('sprints.edit', $sprint->sprint_id) }}">
                            <i class="fa fa-edit me-2 text-primary"></i> Edit Sprint
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('sprints.index', $project->project_id) }}">
                            <i class="fa fa-list me-2 text-info"></i> All Sprints
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
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="sprint-info card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold text-gray-900 mb-3">Sprint Details</h5>
                    
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
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Status</span>
                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $status }}</span>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="small text-muted mb-1">Timeline</div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-calendar me-2 text-primary"></i>
                            <span>{{ $sprint->start_date->format('M d') }} - {{ $sprint->end_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="small text-muted mb-1">Duration</div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-clock me-2 text-primary"></i>
                            <span>{{ $sprint->start_date->diffInDays($sprint->end_date) + 1 }} days</span>
                        </div>
                    </div>

                    @php
                        // Calculate sprint timeline progress
                        $totalDays = $sprint->start_date->diffInDays($sprint->end_date);
                        $passedDays = $sprint->start_date->diffInDays(now());
                        $timelinePercentage = $totalDays > 0 ? min(($passedDays / $totalDays) * 100, 100) : 0;
                        $daysLeft = max(0, $sprint->end_date->diffInDays(now()));
                        
                        // Calculate task completion progress
                        $totalTasks = $tasks->count();
                        $completedTasks = $tasks->where('status', 'done')->count();
                        $taskPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                    @endphp
                    
                    <h6 class="fw-semibold mt-4 mb-3">Sprint Progress</h6>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="small text-muted">Timeline</div>
                            <div class="small">{{ round($timelinePercentage) }}%</div>
                        </div>
                        <div class="progress rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $timelinePercentage }}%" aria-valuenow="{{ $timelinePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="small text-muted mt-2">{{ $daysLeft }} days remaining</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="small text-muted">Tasks Completed</div>
                            <div class="small fw-medium">{{ $completedTasks }}/{{ $totalTasks }}</div>
                        </div>
                        <div class="progress rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $taskPercentage }}%" aria-valuenow="{{ $taskPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="small text-muted mt-2">{{ round($taskPercentage) }}% complete</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <div class="board-wrapper card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-semibold text-gray-900 mb-0">Sprint Board</h5>
                        <a href="{{ route('tasks.index', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fa fa-list me-1"></i> List View
                        </a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 board-columns">
                        <div class="col-md-3">
                            <div class="board-column todo-column rounded-3">
                                <div class="column-header d-flex justify-content-between align-items-center p-3 rounded-top">
                                    <h6 class="mb-0 fw-semibold d-flex align-items-center">
                                        <span class="status-dot bg-danger me-2"></span>
                                        To Do
                                    </h6>
                                    <span class="badge bg-light text-dark">{{ $tasksByStatus['todo']->count() }}</span>
                                </div>
                                <div class="column-body p-2">
                                    @foreach($tasksByStatus['todo'] as $task)
                                        <div class="task-card card border-0 shadow-sm mb-2" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->task_id }}">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-2">{{ $task->title }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-2 py-1 rounded-pill">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                    @if($task->assignedUser)
                                                        <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 28px; height: 28px; font-weight: 600;">
                                                            {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($tasksByStatus['todo']->isEmpty())
                                        <div class="empty-state text-center p-4">
                                            <p class="text-muted small mb-0">No tasks</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="board-column in-progress-column rounded-3">
                                <div class="column-header d-flex justify-content-between align-items-center p-3 rounded-top">
                                    <h6 class="mb-0 fw-semibold d-flex align-items-center">
                                        <span class="status-dot bg-warning me-2"></span>
                                        In Progress
                                    </h6>
                                    <span class="badge bg-light text-dark">{{ $tasksByStatus['in_progress']->count() }}</span>
                                </div>
                                <div class="column-body p-2">
                                    @foreach($tasksByStatus['in_progress'] as $task)
                                        <div class="task-card card border-0 shadow-sm mb-2" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->task_id }}">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-2">{{ $task->title }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-2 py-1 rounded-pill">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                    @if($task->assignedUser)
                                                        <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 28px; height: 28px; font-weight: 600;">
                                                            {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($tasksByStatus['in_progress']->isEmpty())
                                        <div class="empty-state text-center p-4">
                                            <p class="text-muted small mb-0">No tasks</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="board-column review-column rounded-3">
                                <div class="column-header d-flex justify-content-between align-items-center p-3 rounded-top">
                                    <h6 class="mb-0 fw-semibold d-flex align-items-center">
                                        <span class="status-dot bg-info me-2"></span>
                                        Review
                                    </h6>
                                    <span class="badge bg-light text-dark">{{ $tasksByStatus['review']->count() }}</span>
                                </div>
                                <div class="column-body p-2">
                                    @foreach($tasksByStatus['review'] as $task)
                                        <div class="task-card card border-0 shadow-sm mb-2" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->task_id }}">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-2">{{ $task->title }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-2 py-1 rounded-pill">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                    @if($task->assignedUser)
                                                        <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 28px; height: 28px; font-weight: 600;">
                                                            {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($tasksByStatus['review']->isEmpty())
                                        <div class="empty-state text-center p-4">
                                            <p class="text-muted small mb-0">No tasks</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="board-column done-column rounded-3">
                                <div class="column-header d-flex justify-content-between align-items-center p-3 rounded-top">
                                    <h6 class="mb-0 fw-semibold d-flex align-items-center">
                                        <span class="status-dot bg-success me-2"></span>
                                        Done
                                    </h6>
                                    <span class="badge bg-light text-dark">{{ $tasksByStatus['done']->count() }}</span>
                                </div>
                                <div class="column-body p-2">
                                    @foreach($tasksByStatus['done'] as $task)
                                        <div class="task-card card border-0 shadow-sm mb-2" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->task_id }}">
                                            <div class="card-body p-3">
                                                <h6 class="card-title mb-2">{{ $task->title }}</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-2 py-1 rounded-pill">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                    @if($task->assignedUser)
                                                        <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 28px; height: 28px; font-weight: 600;">
                                                            {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($tasksByStatus['done']->isEmpty())
                                        <div class="empty-state text-center p-4">
                                            <p class="text-muted small mb-0">No tasks</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Modals -->
@foreach($tasks as $task)
    <div class="modal fade" id="taskModal{{ $task->task_id }}" tabindex="-1" aria-labelledby="taskModalLabel{{ $task->task_id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="taskModalLabel{{ $task->task_id }}">{{ $task->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-{{ $task->status === 'todo' ? 'danger' : ($task->status === 'in_progress' ? 'warning' : ($task->status === 'review' ? 'info' : 'success')) }}-subtle text-{{ $task->status === 'todo' ? 'danger' : ($task->status === 'in_progress' ? 'warning' : ($task->status === 'review' ? 'info' : 'success')) }} px-3 py-2 rounded-pill">
                            {{ ucwords(str_replace('_', ' ', $task->status)) }}
                        </span>
                        <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-3 py-2 rounded-pill">
                            {{ ucfirst($task->priority) }} Priority
                        </span>
                    </div>
                    
                    @if($task->description)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">Description</h6>
                            <p class="mb-0">{{ $task->description }}</p>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2">Assigned to</h6>
                        @if($task->assignedUser)
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2 bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 32px; height: 32px; font-weight: 600;">
                                    {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                </div>
                                <span>{{ $task->assignedUser->name }}</span>
                            </div>
                        @else
                            <p class="text-muted mb-0">Not assigned</p>
                        @endif
                    </div>
                    
                    <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PUT')
                        <h6 class="fw-semibold mb-2">Update Status</h6>
                        <div class="d-flex gap-2">
                            <select class="form-select" id="status" name="status">
                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Review</option>
                                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('tasks.show', $task->task_id) }}" class="btn btn-outline-primary">
                        <i class="fa fa-external-link-alt me-1"></i> Full Details
                    </a>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

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
    
    .card {
        border-radius: 12px;
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
    }
    
    .board-column {
        background-color: #f4f5f7;
        height: 100%;
        min-height: 400px;
    }
    
    .todo-column .column-header {
        background-color: rgba(239, 68, 68, 0.1);
    }
    
    .in-progress-column .column-header {
        background-color: rgba(245, 158, 11, 0.1);
    }
    
    .review-column .column-header {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .done-column .column-header {
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    .task-card {
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 8px;
        background-color: white;
    }
    
    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.06) !important;
    }
    
    .column-body {
        max-height: calc(100vh - 240px);
        overflow-y: auto;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .rounded-pill {
        border-radius: 50rem;
    }
    
    .modal-content {
        border-radius: 12px;
    }
</style>
@endsection