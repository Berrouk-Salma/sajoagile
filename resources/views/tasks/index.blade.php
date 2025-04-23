@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tasks: {{ $sprint->goal }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Sprint
                </a>
                <a href="{{ route('tasks.create', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-plus"></i> New Task
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="taskTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="kanban-tab" data-bs-toggle="tab" data-bs-target="#kanban" type="button" role="tab" aria-controls="kanban" aria-selected="true">
                                <i class="fa fa-columns me-1"></i> Kanban View
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab" aria-controls="list" aria-selected="false">
                                <i class="fa fa-list me-1"></i> List View
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="taskTabsContent">
                        <div class="tab-pane fade show active" id="kanban" role="tabpanel" aria-labelledby="kanban-tab">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-light mb-3">
                                        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                                            <span>To Do</span>
                                            <span class="badge bg-light text-dark">{{ $tasksByStatus['todo']->count() }}</span>
                                        </div>
                                        <div class="card-body kanban-column">
                                            @foreach($tasksByStatus['todo'] as $task)
                                                <div class="card mb-2 task-card">
                                                    <div class="card-body p-2">
                                                        <h6 class="card-title">
                                                            <a href="{{ route('tasks.show', $task->task_id) }}">{{ $task->title }}</a>
                                                        </h6>
                                                        <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                        @if($task->assignedUser)
                                                            <div class="mt-1 small text-muted">
                                                                Assigned to: {{ $task->assignedUser->name }}
                                                            </div>
                                                        @endif
                                                        <div class="mt-2 d-flex justify-content-between">
                                                            <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="in_progress">
                                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                                    <i class="fa fa-arrow-right"></i>
                                                                </button>
                                                            </form>
                                                            <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if($tasksByStatus['todo']->isEmpty())
                                                <div class="text-center text-muted py-3">
                                                    <i class="fa fa-check-circle"></i> No tasks
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="card bg-light mb-3">
                                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                            <span>In Progress</span>
                                            <span class="badge bg-light text-dark">{{ $tasksByStatus['in_progress']->count() }}</span>
                                        </div>
                                        <div class="card-body kanban-column">
                                            @foreach($tasksByStatus['in_progress'] as $task)
                                                <div class="card mb-2 task-card">
                                                    <div class="card-body p-2">
                                                        <h6 class="card-title">
                                                            <a href="{{ route('tasks.show', $task->task_id) }}">{{ $task->title }}</a>
                                                        </h6>
                                                        <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                        @if($task->assignedUser)
                                                            <div class="mt-1 small text-muted">
                                                                Assigned to: {{ $task->assignedUser->name }}
                                                            </div>
                                                        @endif
                                                        <div class="mt-2 d-flex justify-content-between">
                                                            <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="todo">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                    <i class="fa fa-arrow-left"></i>
                                                                </button>
                                                            </form>
                                                            <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="review">
                                                                <button type="submit" class="btn btn-sm btn-outline-info">
                                                                    <i class="fa fa-arrow-right"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if($tasksByStatus['in_progress']->isEmpty())
                                                <div class="text-center text-muted py-3">
                                                    <i class="fa fa-check-circle"></i> No tasks
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="card bg-light mb-3">
                                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                            <span>Review</span>
                                            <span class="badge bg-light text-dark">{{ $tasksByStatus['review']->count() }}</span>
                                        </div>
                                        <div class="card-body kanban-column">
                                            @foreach($tasksByStatus['review'] as $task)
                                                <div class="card mb-2 task-card">
                                                    <div class="card-body p-2">
                                                        <h6 class="card-title">
                                                            <a href="{{ route('tasks.show', $task->task_id) }}">{{ $task->title }}</a>
                                                        </h6>
                                                        <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                        @if($task->assignedUser)
                                                            <div class="mt-1 small text-muted">
                                                                Assigned to: {{ $task->assignedUser->name }}
                                                            </div>
                                                        @endif
                                                        <div class="mt-2 d-flex justify-content-between">
                                                            <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="in_progress">
                                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                                    <i class="fa fa-arrow-left"></i>
                                                                </button>
                                                            </form>
                                                            <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="done">
                                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                                    <i class="fa fa-arrow-right"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if($tasksByStatus['review']->isEmpty())
                                                <div class="text-center text-muted py-3">
                                                    <i class="fa fa-check-circle"></i> No tasks
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="card bg-light mb-3">
                                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                            <span>Done</span>
                                            <span class="badge bg-light text-dark">{{ $tasksByStatus['done']->count() }}</span>
                                        </div>
                                        <div class="card-body kanban-column">
                                            @foreach($tasksByStatus['done'] as $task)
                                                <div class="card mb-2 task-card">
                                                    <div class="card-body p-2">
                                                        <h6 class="card-title">
                                                            <a href="{{ route('tasks.show', $task->task_id) }}">{{ $task->title }}</a>
                                                        </h6>
                                                        <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                        @if($task->assignedUser)
                                                            <div class="mt-1 small text-muted">
                                                                Assigned to: {{ $task->assignedUser->name }}
                                                            </div>
                                                        @endif
                                                        <div class="mt-2 d-flex justify-content-between">
                                                            <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="review">
                                                                <button type="submit" class="btn btn-sm btn-outline-info">
                                                                    <i class="fa fa-arrow-left"></i>
                                                                </button>
                                                            </form>
                                                            <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if($tasksByStatus['done']->isEmpty())
                                                <div class="text-center text-muted py-3">
                                                    <i class="fa fa-check-circle"></i> No tasks
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="list-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Assigned To</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tasks as $task)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('tasks.show', $task->task_id) }}">{{ $task->title }}</a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $task->status === 'todo' ? 'danger' : ($task->status === 'in_progress' ? 'warning' : ($task->status === 'review' ? 'info' : 'success')) }}">
                                                        {{ ucwords(str_replace('_', ' ', $task->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($task->assignedUser)
                                                        {{ $task->assignedUser->name }}
                                                    @else
                                                        <span class="text-muted">Not assigned</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('tasks.show', $task->task_id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('tasks.edit', $task->task_id) }}" class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('tasks.destroy', $task->task_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No tasks found</td>
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
    </div>
</div>
@endsection
