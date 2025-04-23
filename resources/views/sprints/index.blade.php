@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Sprints: {{ $project->name }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('projects.show', $project->project_id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Project
                </a>
                <a href="{{ route('sprints.create', $project->project_id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-plus"></i> New Sprint
                </a>
            </div>
        </div>
    </div>

    @if($currentSprint)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Current Sprint: {{ $currentSprint->goal }}</h5>
                    <span class="badge bg-light text-dark">{{ $currentSprint->start_date->format('M d') }} - {{ $currentSprint->end_date->format('M d, Y') }}</span>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 20px;">
                        @php
                            $totalDays = $currentSprint->start_date->diffInDays($currentSprint->end_date);
                            $passedDays = $currentSprint->start_date->diffInDays(now());
                            $percentage = $totalDays > 0 ? min(($passedDays / $totalDays) * 100, 100) : 0;
                        @endphp
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ round($percentage) }}%</div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h2 class="display-4">{{ $currentSprint->tasks->count() }}</h2>
                            <p class="text-muted">Total Tasks</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="display-4">{{ $currentSprint->tasks->where('status', 'in_progress')->count() }}</h2>
                            <p class="text-muted">In Progress</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="display-4">{{ $currentSprint->tasks->where('status', 'review')->count() }}</h2>
                            <p class="text-muted">In Review</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="display-4">{{ $currentSprint->tasks->where('status', 'done')->count() }}</h2>
                            <p class="text-muted">Completed</p>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('sprints.show', $currentSprint->sprint_id) }}" class="btn btn-primary me-2">
                            <i class="fa fa-eye"></i> View Details
                        </a>
                        <a href="{{ route('tasks.index', $currentSprint->sprint_id) }}" class="btn btn-success">
                            <i class="fa fa-tasks"></i> View Tasks
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Sprints</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Goal</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Tasks</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sprints as $sprint)
                                    <tr>
                                        <td>{{ $sprint->goal }}</td>
                                        <td>{{ $sprint->start_date->format('M d') }} - {{ $sprint->end_date->format('M d, Y') }}</td>
                                        <td>
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
                                        </td>
                                        <td>{{ $sprint->tasks->count() }}</td>
                                        <td>
                                            @php
                                                $totalTasks = $sprint->tasks->count();
                                                $completedTasks = $sprint->tasks->where('status', 'done')->count();
                                                $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                                            @endphp
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <small class="text-muted">{{ $completedTasks }}/{{ $totalTasks }} tasks completed</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('sprints.edit', $sprint->sprint_id) }}" class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('tasks.index', $sprint->sprint_id) }}" class="btn btn-sm btn-success">
                                                <i class="fa fa-tasks"></i>
                                            </a>
                                            <form action="{{ route('sprints.destroy', $sprint->sprint_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this sprint?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No sprints found for this project</td>
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
@endsection
