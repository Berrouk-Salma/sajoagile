@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage Project Teams: {{ $project->name }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('projects.show', $project->project_id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Project
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Current Project Teams</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Members</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projectTeams as $team)
                                    <tr>
                                        <td>{{ $team->name }}</td>
                                        <td>{{ $team->members->count() }}</td>
                                        <td>
                                            <a href="{{ route('teams.show', $team->team_id) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            <form action="{{ route('projects.teams.remove', ['id' => $project->project_id, 'teamId' => $team->team_id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this team?')">
                                                    <i class="fa fa-minus-circle"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No teams assigned to this project</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add Team to Project</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.teams.add', $project->project_id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="team_id" class="form-label">Select Team</label>
                            <select class="form-select @error('team_id') is-invalid @enderror" id="team_id" name="team_id" required>
                                <option value="">-- Select Team --</option>
                                @foreach($availableTeams as $team)
                                    <option value="{{ $team->team_id }}">{{ $team->name }} ({{ $team->members->count() }} members)</option>
                                @endforeach
                            </select>
                            @error('team_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text mb-3">
                            <i class="fa fa-info-circle me-1"></i> Adding a team will also add all team members to the project.
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add to Project
                            </button>
                        </div>
                    </form>

                    @if($availableTeams->isEmpty())
                        <div class="alert alert-info mt-3">
                            <i class="fa fa-info-circle me-2"></i> No more teams available to add to this project.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
