@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage Project Members: {{ $project->name }}</h1>
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
                    <h5 class="card-title mb-0">Current Project Members</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
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
                                        <td>
                                            @if($members->count() > 1)
                                                <form action="{{ route('projects.members.remove', ['id' => $project->project_id, 'userId' => $member->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this member?')">
                                                        <i class="fa fa-user-minus"></i> Remove
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-danger" disabled title="Cannot remove the last member">
                                                    <i class="fa fa-user-minus"></i> Remove
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No members found</td>
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
                    <h5 class="card-title mb-0">Add Project Member</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.members.add', $project->project_id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Select User</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">-- Select User --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-user-plus"></i> Add to Project
                            </button>
                        </div>
                    </form>

                    @if($availableUsers->isEmpty())
                        <div class="alert alert-info mt-3">
                            <i class="fa fa-info-circle me-2"></i> No more users available to add to this project.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
