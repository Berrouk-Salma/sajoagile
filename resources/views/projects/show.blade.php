@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ $project->name }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('projects.edit', $project->project_id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{ route('sprints.index', $project->project_id) }}" class="btn btn-sm btn-outline-success">
                    <i class="fa fa-running"></i> Sprints
                </a>
                <a href="{{ route('projects.members', $project->project_id) }}" class="btn btn-sm btn-outline-info">
                    <i class="fa fa-users"></i> Members
                </a>
                <form action="{{ route('projects.destroy', $project->project_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this project?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
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
                    <h5 class="card-title mb-0">Project Information</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>Project ID:</strong> {{ $project->project_id }}
                    </p>
                    <p class="card-text">
                        <strong>Created:</strong> {{ $project->created_at->format('M d, Y') }}
                    </p>
                    <p class="card-text">
                        <strong>Last Updated:</strong> {{ $project->updated_at->format('M d, Y') }}
                    </p>
                    <p class="card-text">
                        <strong>Start Date:</strong> {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}
                    </p>
                    <p class="card-text">
                        <strong>End Date:</strong> {{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}
                    </p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $project->description ?: 'No description provided.' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Sprint</h5>
                </div>
                <div class="card-body">
                    @if($currentSprint)
                        <h5>{{ $currentSprint->goal }}</h5>
                        <p>
                            <strong>Duration:</strong> {{ $currentSprint->start_date->format('M d, Y') }} - {{ $currentSprint->end_date->format('M d, Y') }}
                        </p>
                        
                        <div class="progress mb-3" style="height: 20px;">
                            @php
                                $totalDays = $currentSprint->start_date->diffInDays($currentSprint->end_date);
                                $passedDays = $currentSprint->start_date->diffInDays(now());
                                $percentage = $totalDays > 0 ? min(($passedDays / $totalDays) * 100, 100) : 0;
                            @endphp
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ round($percentage) }}%</div>
                        </div>
                        
                        <div class="text-center mb-3">
                            <a href="{{ route('sprints.show', $currentSprint->sprint_id) }}" class="btn btn-primary">
                                <i class="fa fa-eye"></i> View Sprint
                            </a>
                            <a href="{{ route('tasks.index', $currentSprint->sprint_id) }}" class="btn btn-success">
                                <i class="fa fa-tasks"></i> View Tasks
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i> No active sprint for this project.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('sprints.create', $project->project_id) }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create Sprint
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Team Members ({{ $members->count() }})</h5>
                    <a href="{{ route('projects.members', $project->project_id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-user-plus"></i> Add Members
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($members->take(6) as $member)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <div class="avatar mb-3 bg-primary text-white rounded-circle d-flex justify-content-center align-items-center mx-auto" style="width: 50px; height: 50px;">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <h6 class="mb-1">{{ $member->name }}</h6>
                                        <p class="small text-muted mb-2">{{ $member->email }}</p>
                                        <span class="badge bg-{{ $member->role === 'admin' ? 'danger' : ($member->role === 'lead' ? 'warning' : 'primary') }}">
                                            {{ ucfirst($member->role) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle me-2"></i> No members assigned to this project.
                                </div>
                            </div>
                        @endforelse
                    </div>
                    
                    @if($members->count() > 6)
                        <div class="text-center mt-3">
                            <a href="{{ route('projects.members', $project->project_id) }}" class="btn btn-outline-primary">
                                View All Members
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Project Sprints ({{ $sprints->count() }})</h5>
                    <a href="{{ route('sprints.create', $project->project_id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-plus"></i> New Sprint
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Goal</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Tasks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sprints as $sprint)
                                    <tr>
                                        <td>{{ $sprint->goal }}</td>
                                        <td>{{ $sprint->start_date->format('M d, Y') }}</td>
                                        <td>{{ $sprint->end_date->format('M d, Y') }}</td>
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
                                            <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('tasks.index', $sprint->sprint_id) }}" class="btn btn-sm btn-success">
                                                <i class="fa fa-tasks"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No sprints created for this project yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('sprints.index', $project->project_id) }}" class="btn btn-outline-primary">
                        View All Sprints
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
