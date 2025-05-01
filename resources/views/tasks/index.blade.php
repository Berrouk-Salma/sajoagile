@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-1">Tasks</h1>
            <p class="text-muted mb-0">Sprint: <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="text-decoration-none">{{ $sprint->goal }}</a></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tasks.create', $sprint->sprint_id) }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> New Task
            </a>
            <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Back to Sprint
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white p-0 border-0">
            <ul class="nav nav-tabs" id="taskTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-3" id="kanban-tab" data-bs-toggle="tab" data-bs-target="#kanban" type="button" role="tab" aria-controls="kanban" aria-selected="true">
                        <i class="fa fa-columns me-2"></i> Kanban View
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-3" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab" aria-controls="list" aria-selected="false">
                        <i class="fa fa-list me-2"></i> List View
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="taskTabsContent">
                <div class="tab-pane fade show active p-3" id="kanban" role="tabpanel" aria-labelledby="kanban-tab">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="board-column todo-column rounded-3">
                                <div class="column-header d-flex justify-content-between align-items-center p-3 rounded-top">
                                    <h6 class="mb-0 fw-semibold d-flex align-items-center">
                                        <span class="status-dot bg-danger me-2"></span>
                                        To Do
                                    </h6>
                                    <span class="badge bg-light text-dark">{{ $tasksByStatus['todo']->count() }}</span>
                                </div>
                                <div class="column-body p-2 kanban-column">
                                    @foreach($tasksByStatus['todo'] as $task)
                                        <div class="task-card card border-0 shadow-sm mb-2">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">
                                                        <a href="{{ route('tasks.show', $task->task_id) }}" class="text-decoration-none text-dark stretched-link">{{ $task->title }}</a>
                                                    </h6>
                                                    <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-2 py-1 rounded-pill">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </div>
                                                @if($task->assignedUser)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 24px; height: 24px; font-weight: 600; font-size: 12px;">
                                                            {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                        </div>
                                                        <span class="small text-muted">{{ $task->assignedUser->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer bg-white p-2 border-top">
                                                <div class="d-flex justify-content-between task-actions">
                                                    <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" title="Move to In Progress">
                                                            <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Task">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
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
                                <div class="column-body p-2 kanban-column">
                                    @foreach($tasksByStatus['in_progress'] as $task)
                                        <div class="task-card card border-0 shadow-sm mb-2">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">
                                                        <a href="{{ route('tasks.show', $task->task_id) }}" class="text-decoration-none text-dark stretched-link">{{ $task->title }}</a>
                                                    </h6>
                                                    <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-2 py-1 rounded-pill">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </div>
                                                @if($task->assignedUser)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 24px; height: 24px; font-weight: 600; font-size: 12px;">
                                                            {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                        </div>
                                                        <span class="small text-muted">{{ $task->assignedUser->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer bg-white p-2 border-top">
                                                <div class="d-flex justify-content-between task-actions">
                                                    <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="todo">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" title="Move back to To Do">
                                                            <i class="fa fa-arrow-left"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Task">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="review">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" title="Move to Review">
                                                            <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </form>
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
                                <div class="column-body p-2 kanban-column">
                                    @foreach($tasksByStatus['review'] as $task)
                                        <div class="task-card card border-0 shadow-sm mb-2">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">
                                                        <a href="{{ route('tasks.show', $task->task_id) }}" class="text-decoration-none text-dark stretched-link">{{ $task->title }}</a>
                                                    </h6>
                                                    <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-2 py-1 rounded-pill">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </div>
                                                @if($task->assignedUser)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 24px; height: 24px; font-weight: 600; font-size: 12px;">
                                                            {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                        </div>
                                                        <span class="small text-muted">{{ $task->assignedUser->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer bg-white p-2 border-top">
                                                <div class="d-flex justify-content-between task-actions">
                                                    <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" title="Move back to In Progress">
                                                            <i class="fa fa-arrow-left"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Task">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="done">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" title="Move to Done">
                                                            <i class="fa fa-arrow-right"></i>
                                                        </button>
                                                    </form>
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
                                <div class="column-body p-2 kanban-column">
                                    @foreach($tasksByStatus['done'] as $task)
                                        <div class="task-card card border-0 shadow-sm mb-2">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0">
                                                        <a href="{{ route('tasks.show', $task->task_id) }}" class="text-decoration-none text-dark stretched-link">{{ $task->title }}</a>
                                                    </h6>
                                                    <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-2 py-1 rounded-pill">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </div>
                                                @if($task->assignedUser)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 24px; height: 24px; font-weight: 600; font-size: 12px;">
                                                            {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                        </div>
                                                        <span class="small text-muted">{{ $task->assignedUser->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer bg-white p-2 border-top">
                                                <div class="d-flex justify-content-between task-actions">
                                                    <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="review">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" title="Move back to Review">
                                                            <i class="fa fa-arrow-left"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Task">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
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
                
                <div class="tab-pane fade p-3" id="list" role="tabpanel" aria-labelledby="list-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Assigned To</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td class="fw-medium">
                                            <a href="{{ route('tasks.show', $task->task_id) }}" class="text-decoration-none text-gray-900">{{ $task->title }}</a>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $task->status === 'todo' ? 'danger' : ($task->status === 'in_progress' ? 'warning' : ($task->status === 'review' ? 'info' : 'success')) }}-subtle text-{{ $task->status === 'todo' ? 'danger' : ($task->status === 'in_progress' ? 'warning' : ($task->status === 'review' ? 'info' : 'success')) }} px-2 py-1 rounded-pill">
                                                {{ ucwords(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-2 py-1 rounded-pill">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($task->assignedUser)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 24px; height: 24px; font-weight: 600; font-size: 12px;">
                                                        {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                    </div>
                                                    {{ $task->assignedUser->name }}
                                                </div>
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="{{ route('tasks.show', $task->task_id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="View Task">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-info rounded-circle" title="Edit Task">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('tasks.destroy', $task->task_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Are you sure you want to delete this task?')" title="Delete Task">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="empty-state-icon mb-3">
                                                <i class="fa fa-tasks fs-1 text-muted opacity-25"></i>
                                            </div>
                                            <p class="mb-1 text-muted">No tasks found</p>
                                            <a href="{{ route('tasks.create', $sprint->sprint_id) }}" class="btn btn-sm btn-primary rounded-pill mt-3">
                                                <i class="fa fa-plus me-1"></i> Create First Task
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
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .card {
        border-radius: 12px;
    }
    
    .nav-tabs {
        border-bottom: 0;
    }
    
    .nav-tabs .nav-link {
        border: none;
        font-weight: 500;
        color: #6b7280;
    }
    
    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        background-color: transparent;
    }
    
    .board-column {
        background-color: #f4f5f7;
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
        transition: all 0.2s ease;
        border-radius: 8px;
        background-color: white;
    }
    
    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.06) !important;
    }
    
    .card-footer {
        border-bottom-left-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }
    
    .kanban-column {
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
    
    /* Hide link underline on hover for task titles*/
    .stretched-link:hover {
        text-decoration: none !important;
    }
    
    /* Task actions only visible on hover */
    .task-actions {
        opacity: 0.6;
        transition: opacity 0.2s ease;
    }
    
    .task-card:hover .task-actions {
        opacity: 1;
    }
</style>
@endsection