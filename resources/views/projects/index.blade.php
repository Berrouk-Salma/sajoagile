@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Projects</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('projects.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-plus"></i> New Project
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($projects as $project)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ $project->name }}</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $project->project_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $project->project_id }}">
                                <li><a class="dropdown-item" href="{{ route('projects.show', $project->project_id) }}"><i class="fa fa-eye me-2"></i> View</a></li>
                                <li><a class="dropdown-item" href="{{ route('projects.edit', $project->project_id) }}"><i class="fa fa-edit me-2"></i> Edit</a></li>
                                <li><a class="dropdown-item" href="{{ route('projects.members', $project->project_id) }}"><i class="fa fa-users me-2"></i> Manage Members</a></li>
                                <li><a class="dropdown-item" href="{{ route('sprints.index', $project->project_id) }}"><i class="fa fa-running me-2"></i> Sprints</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('projects.destroy', $project->project_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="fa fa-trash me-2"></i> Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                        <div class="mb-2">
                            <strong>Start Date:</strong> {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}
                        </div>
                        <div class="mb-2">
                            <strong>End Date:</strong> {{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}
                        </div>
                        <div class="mb-2">
                            <strong>Members:</strong> {{ $project->members->count() }}
                        </div>
                        <div>
                            <strong>Sprints:</strong> {{ $project->sprints->count() }}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('projects.show', $project->project_id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-eye me-1"></i> View
                            </a>
                            <a href="{{ route('sprints.index', $project->project_id) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-running me-1"></i> Sprints
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i> No projects found. Create a new project to get started.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
