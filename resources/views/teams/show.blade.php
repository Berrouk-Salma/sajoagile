@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ $team->name }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('teams.edit', $team->team_id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{ route('teams.members', $team->team_id) }}" class="btn btn-sm btn-outline-success">
                    <i class="fa fa-users"></i> Manage Members
                </a>
                <form action="{{ route('teams.destroy', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this team?');">
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
                    <h5 class="card-title mb-0">Team Information</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>Team ID:</strong> {{ $team->team_id }}
                    </p>
                    <p class="card-text">
                        <strong>Created:</strong> {{ $team->created_at->format('M d, Y') }}
                    </p>
                    <p class="card-text">
                        <strong>Last Updated:</strong> {{ $team->updated_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Team Members ({{ $members->count() }})</h5>
                    <a href="{{ route('teams.members', $team->team_id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-user-plus"></i> Add Members
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                    <tr>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $member->role === 'admin' ? 'danger' : ($member->role === 'lead' ? 'warning' : 'primary') }}">
                                                {{ ucfirst($member->role) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No members found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Team Projects ({{ $projects->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($projects->count() > 0)
                        <div class="row">
                            @foreach($projects as $project)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $project->name }}</h5>
                                            <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-primary">{{ $project->members->count() }} Members</span>
                                                <span class="badge bg-success">{{ $project->sprints->count() }} Sprints</span>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('projects.show', $project->project_id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i> View Project
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i> This team is not assigned to any projects yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
