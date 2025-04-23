@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Sprint: {{ $sprint->goal }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('sprints.index', $project->project_id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Sprints
                </a>
                <a href="{{ route('sprints.edit', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{ route('tasks.create', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-success">
                    <i class="fa fa-plus"></i> Add Task
                </a>
                <form action="{{ route('sprints.destroy', $sprint->sprint_id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this sprint?')">
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
                    <h5 class="card-title mb-0">Sprint Information</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>Project:</strong> <a href="{{ route('projects.show', $project->project_id) }}">{{ $project->name }}</a>
                    </p>
                    <p class="card-text">
                        <strong>Start Date:</strong> {{ $sprint->start_date->format('M d, Y') }}
                    </p>
                    <p class="card-text">
                        <strong>End Date:</strong> {{ $sprint->end_date->format('M d, Y') }}
                    </p>
                    <p class="card-text">
                        <strong>Duration:</strong> {{ $sprint->start_date->diffInDays($sprint->end_date) + 1 }} days
                    </p>
                    <p class="card-text">
                        <strong>Status:</strong> 
                        @php
                            $now = now();
                            $status = 'Upcoming';
                            $badgeClass = 'bg-secondary';
                            
                            if ($now->between($sprint->start_date, $sprint->end_date)) {
                                $status = 'Active';
                                $badgeClass = 'bg-success';
                            } elseif ($now->gt($sprint->end_date)) {
                                $status = 'Completed';
                                $badgeClass = 'bg-primary';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                    </p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Progress</h5>
                </div>
                <div class="card-body">
                    @php
                        // Calculate sprint timeline progress
                        $totalDays = $sprint->start_date->diffInDays($sprint->end_date);
                        $passedDays = $sprint->start_date->diffInDays(now());
                        $timelinePercentage = $totalDays > 0 ? min(($passedDays / $totalDays) * 100, 100) : 0;
                        
                        // Calculate task completion progress
                        $totalTasks = $tasks->count();
                        $completedTasks = $tasks->where('status', 'done')->count();
                        $taskPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                    @endphp
                    
                    <h6 class="mb-2">Timeline Progress</h6>
                    <div class="progress mb-3" style="height: 15px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $timelinePercentage }}%" aria-valuenow="{{ $timelinePercentage }}" aria-valuemin="0" aria-valuemax="100">{{ round($timelinePercentage) }}%</div>
                    </div>
                    
                    <h6 class="mb-2">Task Completion</h6>
                    <div class="progress mb-3" style="height: 15px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $taskPercentage }}%" aria-valuenow="{{ $taskPercentage }}" aria-valuemin="0" aria-valuemax="100">{{ round($taskPercentage) }}%</div>
                    </div>
                    
                    <div class="text-center">
                        <h4>{{ $completedTasks }}/{{ $totalTasks }}</h4>
                        <p class="text-muted">Tasks Completed</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Sprint Tasks</h5>
                    <a href="{{ route('tasks.create', $sprint->sprint_id) }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i> Add Task
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-light mb-3">
                                <div class="card-header bg-danger text-white">To Do</div>
                                <div class="card-body">
                                    @foreach($tasksByStatus['todo'] as $task)
                                        <div class="card mb-2 task-card" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->task_id }}">
                                            <div class="card-body p-2">
                                                <h6 class="card-title">{{ $task->title }}</h6>
                                                <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                                @if($task->assignedUser)
                                                    <div class="mt-1 small text-muted">
                                                        Assigned to: {{ $task->assignedUser->name }}
                                                    </div>
                                                @endif
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
                                <div class="card-header bg-warning text-dark">In Progress</div>
                                <div class="card-body">
                                    @foreach($tasksByStatus['in_progress'] as $task)
                                        <div class="card mb-2 task-card" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->task_id }}">
                                            <div class="card-body p-2">
                                                <h6 class="card-title">{{ $task->title }}</h6>
                                                <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                                @if($task->assignedUser)
                                                    <div class="mt-1 small text-muted">
                                                        Assigned to: {{ $task->assignedUser->name }}
                                                    </div>
                                                @endif
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
                                <div class="card-header bg-info text-white">Review</div>
                                <div class="card-body">
                                    @foreach($tasksByStatus['review'] as $task)
                                        <div class="card mb-2 task-card" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->task_id }}">
                                            <div class="card-body p-2">
                                                <h6 class="card-title">{{ $task->title }}</h6>
                                                <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                                @if($task->assignedUser)
                                                    <div class="mt-1 small text-muted">
                                                        Assigned to: {{ $task->assignedUser->name }}
                                                    </div>
                                                @endif
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
                                <div class="card-header bg-success text-white">Done</div>
                                <div class="card-body">
                                    @foreach($tasksByStatus['done'] as $task)
                                        <div class="card mb-2 task-card" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->task_id }}">
                                            <div class="card-body p-2">
                                                <h6 class="card-title">{{ $task->title }}</h6>
                                                <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                                @if($task->assignedUser)
                                                    <div class="mt-1 small text-muted">
                                                        Assigned to: {{ $task->assignedUser->name }}
                                                    </div>
                                                @endif
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
                <div class="card-footer text-center">
                    <a href="{{ route('tasks.index', $sprint->sprint_id) }}" class="btn btn-outline-primary">
                        <i class="fa fa-list"></i> View All Tasks
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Modals -->
@foreach($tasks as $task)
    <div class="modal fade" id="taskModal{{ $task->task_id }}" tabindex="-1" aria-labelledby="taskModalLabel{{ $task->task_id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel{{ $task->task_id }}">{{ $task->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge bg-{{ $task->status === 'todo' ? 'danger' : ($task->status === 'in_progress' ? 'warning' : ($task->status === 'review' ? 'info' : 'success')) }} p-2">
                            Status: {{ ucwords(str_replace('_', ' ', $task->status)) }}
                        </span>
                        <span class="badge bg-{{ $task->priority === 'low' ? 'success' : ($task->priority === 'medium' ? 'info' : ($task->priority === 'high' ? 'warning' : 'danger')) }} p-2">
                            Priority: {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Assigned to:</strong> 
                        @if($task->assignedUser)
                            {{ $task->assignedUser->name }}
                        @else
                            <span class="text-muted">Not assigned</span>
                        @endif
                    </div>
                    
                    <form action="{{ route('tasks.status.update', $task->task_id) }}" method="POST" class="mb-3">
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
                <div class="modal-footer">
                    <a href="{{ route('tasks.show', $task->task_id) }}" class="btn btn-primary">View Details</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
