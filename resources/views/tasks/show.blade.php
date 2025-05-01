@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-1">{{ $task->title }}</h1>
            <p class="text-muted mb-0">
                <span>Task in sprint: </span>
                <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="text-decoration-none">{{ $sprint->goal }}</a>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-primary">
                <i class="fa fa-edit me-1"></i> Edit Task
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="taskActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="taskActionsDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('tasks.index', $sprint->sprint_id) }}">
                            <i class="fa fa-arrow-left me-2 text-primary"></i> Back to Tasks
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('tasks.destroy', $task->task_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                                <i class="fa fa-trash me-2"></i> Delete Task
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8 order-lg-2">
            <!-- Comments Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="fw-semibold text-gray-900 mb-0">
                        <i class="fa fa-comments me-2 text-primary"></i> Comments
                    </h5>
                    <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">{{ count($comments) }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="comments-list mb-4">
                        @forelse($comments as $comment)
                            <div class="comment-item mb-4">
                                <div class="d-flex">
                                    <div class="avatar-circle me-3 bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px; font-weight: 600;">
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    </div>
                                    <div class="comment-content flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="fw-semibold">{{ $comment->user->name }}</span>
                                                <span class="text-muted small ms-2">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="comment-body p-3 rounded-3 bg-light">
                                            {{ $comment->content }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state text-center py-4">
                                <div class="empty-state-icon mb-3">
                                    <i class="fa fa-comments fs-1 text-muted opacity-25"></i>
                                </div>
                                <p class="text-muted mb-0">No comments yet. Start the conversation!</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="comments-form">
                        <h6 class="fw-semibold mb-3">Add a comment</h6>
                        <form action="{{ route('tasks.comments.add', $task->task_id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="3" placeholder="Write your comment here..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane me-1"></i> Post Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 order-lg-1">
            <!-- Task Details Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold text-gray-900 mb-4">Task Details</h5>
                    
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-{{ $task->status === 'todo' ? 'danger' : ($task->status === 'in_progress' ? 'warning' : ($task->status === 'review' ? 'info' : 'success')) }}-subtle text-{{ $task->status === 'todo' ? 'danger' : ($task->status === 'in_progress' ? 'warning' : ($task->status === 'review' ? 'info' : 'success')) }} px-3 py-2 rounded-pill">
                            {{ ucwords(str_replace('_', ' ', $task->status)) }}
                        </span>
                        <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}-subtle text-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} px-3 py-2 rounded-pill">
                            {{ ucfirst($task->priority) }} Priority
                        </span>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="small text-muted mb-1">Project</div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-project-diagram me-2 text-primary"></i>
                            <a href="{{ route('projects.show', $project->project_id) }}" class="text-decoration-none">{{ $project->name }}</a>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="small text-muted mb-1">Sprint</div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-running me-2 text-primary"></i>
                            <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="text-decoration-none">{{ $sprint->goal }}</a>
                        </div>
                    </div>
                    
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="small text-muted mb-1">Assigned to</div>
                        <div class="d-flex align-items-center">
                            @if($assignedUser)
                                <div class="avatar-circle me-2 bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 32px; height: 32px; font-weight: 600;">
                                    {{ strtoupper(substr($assignedUser->name, 0, 1)) }}
                                </div>
                                <span>{{ $assignedUser->name }}</span>
                            @else
                                <i class="fa fa-user me-2 text-muted"></i>
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3">Update Status</h6>
                        <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="d-flex align-items-center gap-2">
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
                    
                    <div>
                        <h6 class="fw-semibold mb-3">Assign Task</h6>
                        <form action="{{ route('tasks.assign', $task->task_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="d-flex align-items-center gap-2">
                                <select class="form-select" id="assigned_to" name="assigned_to">
                                    <option value="">-- Select User --</option>
                                    @foreach($project->members as $member)
                                        <option value="{{ $member->id }}" {{ $task->assigned_to == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" type="submit">Assign</button>
                            </div>
                        </form>
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
    
    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
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
    
    .form-control {
        border-radius: 8px;
        padding: 0.625rem 1rem;
        border-color: #e5e7eb;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(78, 70, 229, 0.15);
    }
    
    .comment-body {
        background-color: #f9fafb;
        border-radius: 0 12px 12px 12px !important;
    }
    
    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .rounded-pill {
        border-radius: 50rem;
    }
</style>
@endsection