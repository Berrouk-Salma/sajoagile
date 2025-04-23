@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Task: {{ $task->title }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('tasks.index', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Tasks
                </a>
                <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <form action="{{ route('tasks.destroy', $task->task_id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Task Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-{{ $task->status === 'todo' ? 'danger' : ($task->status === 'in_progress' ? 'warning' : ($task->status === 'review' ? 'info' : 'success')) }} p-2">
                            Status: {{ ucwords(str_replace('_', ' ', $task->status)) }}
                        </span>
                        <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} p-2">
                            Priority: {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    
                    <p class="card-text">
                        <strong>Sprint:</strong> <a href="{{ route('sprints.show', $sprint->sprint_id) }}">{{ $sprint->goal }}</a>
                    </p>
                    <p class="card-text">
                        <strong>Project:</strong> <a href="{{ route('projects.show', $project->project_id) }}">{{ $project->name }}</a>
                    </p>
                    <p class="card-text">
                        <strong>Assigned to:</strong> 
                        @if($assignedUser)
                            {{ $assignedUser->name }}
                        @else
                            <span class="text-muted">Not assigned</span>
                        @endif
                    </p>
                </div>
                <div class="card-footer">
                    <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="input-group">
                            <label class="input-group-text" for="status">Update Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Review</option>
                                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            <button class="btn btn-outline-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Assign Task</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tasks.assign', $task->task_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="input-group">
                            <label class="input-group-text" for="assigned_to">Assign To</label>
                            <select class="form-select" id="assigned_to" name="assigned_to">
                                <option value="">-- Select User --</option>
                                @foreach($project->members as $member)
                                    <option value="{{ $member->id }}" {{ $task->assigned_to == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary" type="submit">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Comments</h5>
                </div>
                <div class="card-body">
                    <div class="comments-list">
                        @forelse($comments as $comment)
                            <div class="card mb-3">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                        <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    {{ $comment->content }}
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i> No comments yet. Be the first to add a comment!
                            </div>
                        @endforelse
                    </div>

                    <div class="comments-form mt-4">
                        <form action="{{ route('tasks.comments.add', $task->task_id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="content" class="form-label">Add Comment</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="3" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane me-1"></i> Post Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
