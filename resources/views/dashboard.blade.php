@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('projects.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-plus"></i> New Project
                </a>
                <a href="{{ route('teams.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-plus"></i> New Team
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-primary h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-project-diagram me-2"></i> Your Projects
                    </h5>
                </div>
                <div class="card-body">
                    <h1 class="display-4 text-center">{{ count($projects) }}</h1>
                </div>
                <div class="card-footer">
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-primary btn-sm w-100">View All Projects</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-success h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-users me-2"></i> Your Teams
                    </h5>
                </div>
                <div class="card-body">
                    <h1 class="display-4 text-center">{{ count($teams) }}</h1>
                </div>
                <div class="card-footer">
                    <a href="{{ route('teams.index') }}" class="btn btn-outline-success btn-sm w-100">View All Teams</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-warning h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-tasks me-2"></i> Your Tasks
                    </h5>
                </div>
                <div class="card-body">
                    <h1 class="display-4 text-center">{{ count($tasks) }}</h1>
                </div>
                <div class="card-footer">
                    <a href="#yourTasks" class="btn btn-outline-warning btn-sm w-100">View Your Tasks</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card border-danger h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-bell me-2"></i> Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <h1 class="display-4 text-center">{{ count($notifications) }}</h1>
                </div>
                <div class="card-footer">
                    <button class="btn btn-outline-danger btn-sm w-100">View All Notifications</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-chart-pie me-2"></i> Task Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div class="progress" style="height: 30px;">
                        @if(array_sum($taskStatistics) > 0)
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($taskStatistics['todo'] / array_sum($taskStatistics)) * 100 }}%" 
                                title="Todo: {{ $taskStatistics['todo'] }}" data-bs-toggle="tooltip">
                                {{ $taskStatistics['todo'] }}
                            </div>
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($taskStatistics['in_progress'] / array_sum($taskStatistics)) * 100 }}%" 
                                title="In Progress: {{ $taskStatistics['in_progress'] }}" data-bs-toggle="tooltip">
                                {{ $taskStatistics['in_progress'] }}
                            </div>
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($taskStatistics['review'] / array_sum($taskStatistics)) * 100 }}%" 
                                title="Review: {{ $taskStatistics['review'] }}" data-bs-toggle="tooltip">
                                {{ $taskStatistics['review'] }}
                            </div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($taskStatistics['done'] / array_sum($taskStatistics)) * 100 }}%" 
                                title="Done: {{ $taskStatistics['done'] }}" data-bs-toggle="tooltip">
                                {{ $taskStatistics['done'] }}
                            </div>
                        @else
                            <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">No tasks</div>
                        @endif
                    </div>
                    <div class="row mt-3">
                        <div class="col-3 text-center">
                            <span class="badge bg-danger d-block p-2">Todo</span>
                            <h5 class="mt-2">{{ $taskStatistics['todo'] }}</h5>
                        </div>
                        <div class="col-3 text-center">
                            <span class="badge bg-warning d-block p-2">In Progress</span>
                            <h5 class="mt-2">{{ $taskStatistics['in_progress'] }}</h5>
                        </div>
                        <div class="col-3 text-center">
                            <span class="badge bg-info d-block p-2">Review</span>
                            <h5 class="mt-2">{{ $taskStatistics['review'] }}</h5>
                        </div>
                        <div class="col-3 text-center">
                            <span class="badge bg-success d-block p-2">Done</span>
                            <h5 class="mt-2">{{ $taskStatistics['done'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-bell me-2"></i> Recent Notifications
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($notifications->take(5) as $notification)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    @switch($notification->type)
                                        @case('info')
                                            <span class="badge bg-info rounded-pill me-2"><i class="fa fa-info-circle"></i></span>
                                            @break
                                        @case('success')
                                            <span class="badge bg-success rounded-pill me-2"><i class="fa fa-check-circle"></i></span>
                                            @break
                                        @case('warning')
                                            <span class="badge bg-warning rounded-pill me-2"><i class="fa fa-exclamation-triangle"></i></span>
                                            @break
                                        @case('error')
                                            <span class="badge bg-danger rounded-pill me-2"><i class="fa fa-exclamation-circle"></i></span>
                                            @break
                                        @default
                                            <span class="badge bg-primary rounded-pill me-2"><i class="fa fa-bell"></i></span>
                                    @endswitch
                                    {{ $notification->message }}
                                </div>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-center">No notifications</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="#" class="btn btn-outline-primary btn-sm">View All Notifications</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header" id="yourTasks">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-tasks me-2"></i> Your Tasks
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Task</th>
                                    <th scope="col">Project</th>
                                    <th scope="col">Sprint</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Priority</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>{{ $task->title }}</td>
                                        <td>
                                            @if($task->sprint && $task->sprint->project)
                                                <a href="{{ route('projects.show', $task->sprint->project->project_id) }}">
                                                    {{ $task->sprint->project->name }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->sprint)
                                                <a href="{{ route('sprints.show', $task->sprint->sprint_id) }}">
                                                    {{ $task->sprint->goal }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @switch($task->status)
                                                @case('todo')
                                                    <span class="badge bg-danger">Todo</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge bg-warning">In Progress</span>
                                                    @break
                                                @case('review')
                                                    <span class="badge bg-info">Review</span>
                                                    @break
                                                @case('done')
                                                    <span class="badge bg-success">Done</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">Unknown</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($task->priority)
                                                @case('low')
                                                    <span class="badge bg-success">Low</span>
                                                    @break
                                                @case('medium')
                                                    <span class="badge bg-info">Medium</span>
                                                    @break
                                                @case('high')
                                                    <span class="badge bg-warning">High</span>
                                                    @break
                                                @case('urgent')
                                                    <span class="badge bg-danger">Urgent</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">Unknown</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ route('tasks.show', $task->task_id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No tasks assigned to you</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-project-diagram me-2"></i> Recent Projects
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($projects->sortByDesc('created_at')->take(5) as $project)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('projects.show', $project->project_id) }}">
                                    {{ $project->name }}
                                </a>
                                <div>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $project->members->count() }} {{ Str::plural('member', $project->members->count()) }}
                                    </span>
                                    <span class="badge bg-success rounded-pill ms-1">
                                        {{ $project->sprints->count() }} {{ Str::plural('sprint', $project->sprints->count()) }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center">No projects yet</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-primary btn-sm">View All Projects</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-users me-2"></i> Your Teams
                    </h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($teams as $team)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('teams.show', $team->team_id) }}">
                                    {{ $team->name }}
                                </a>
                                <div>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $team->members->count() }} {{ Str::plural('member', $team->members->count()) }}
                                    </span>
                                    <span class="badge bg-success rounded-pill ms-1">
                                        {{ $team->projects->count() }} {{ Str::plural('project', $team->projects->count()) }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center">No teams yet</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('teams.index') }}" class="btn btn-outline-primary btn-sm">View All Teams</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
