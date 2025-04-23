@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Teams</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('teams.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-plus"></i> New Team
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($teams as $team)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ $team->name }}</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $team->team_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $team->team_id }}">
                                <li><a class="dropdown-item" href="{{ route('teams.show', $team->team_id) }}"><i class="fa fa-eye me-2"></i> View</a></li>
                                <li><a class="dropdown-item" href="{{ route('teams.edit', $team->team_id) }}"><i class="fa fa-edit me-2"></i> Edit</a></li>
                                <li><a class="dropdown-item" href="{{ route('teams.members', $team->team_id) }}"><i class="fa fa-users me-2"></i> Manage Members</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('teams.destroy', $team->team_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this team?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="fa fa-trash me-2"></i> Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong>Members:</strong> {{ $team->members->count() }}
                        </p>
                        <p class="card-text">
                            <strong>Projects:</strong> {{ $team->projects->count() }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teams.show', $team->team_id) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-eye me-1"></i> View
                            </a>
                            <a href="{{ route('teams.members', $team->team_id) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-users me-1"></i> Members
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i> No teams found. Create a new team to get started.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
