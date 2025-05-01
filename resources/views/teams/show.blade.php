@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-1">{{ $team->name }}</h1>
            <p class="text-muted mb-0">Team #{{ $team->team_id }} • Created {{ $team->created_at->format('M d, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('teams.members', $team->team_id) }}" class="btn btn-primary">
                <i class="fa fa-user-plus me-1"></i> Add Members
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="teamActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="teamActionsDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('teams.edit', $team->team_id) }}">
                            <i class="fa fa-edit me-2 text-primary"></i> Edit Team
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('teams.members', $team->team_id) }}">
                            <i class="fa fa-users me-2 text-success"></i> Manage Members
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('teams.destroy', $team->team_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this team?')">
                                <i class="fa fa-trash me-2"></i> Delete Team
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <!-- Team Stats and Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-semibold text-gray-900 mb-0">Overview</h5>
                        <div class="team-icon bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded-3 text-center" style="background-color: rgba(59, 130, 246, 0.07);">
                                <h3 class="fw-bold text-primary mb-1">{{ $members->count() }}</h3>
                                <p class="text-muted small mb-0">Members</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 text-center" style="background-color: rgba(16, 185, 129, 0.07);">
                                <h3 class="fw-bold text-success mb-1">{{ $projects->count() }}</h3>
                                <p class="text-muted small mb-0">Projects</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="small text-muted mb-1">Team ID</div>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-hashtag me-2 text-primary"></i>
                                <span>{{ $team->team_id }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="small text-muted mb-1">Created</div>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-calendar me-2 text-primary"></i>
                                <span>{{ $team->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="small text-muted mb-1">Last Updated</div>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-clock me-2 text-primary"></i>
                                <span>{{ $team->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Team Members Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="fw-semibold text-gray-900 mb-0">
                        <i class="fa fa-users me-2 text-primary"></i> Team Members
                    </h5>
                    <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">{{ $members->count() }} total</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Member</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3 bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 36px; height: 36px; font-weight: 600;">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <span class="fw-medium">{{ $member->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $member->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $member->role === 'admin' ? 'danger' : ($member->role === 'lead' ? 'warning' : 'primary') }}-subtle text-{{ $member->role === 'admin' ? 'danger' : ($member->role === 'lead' ? 'warning' : 'primary') }} px-3 py-1 rounded-pill">
                                                {{ ucfirst($member->role) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle" title="View Member">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="empty-state-icon mb-3">
                                                <i class="fa fa-users fs-1 text-muted opacity-25"></i>
                                            </div>
                                            <p class="mb-1 text-muted">No members found</p>
                                            <a href="{{ route('teams.members', $team->team_id) }}" class="btn btn-sm btn-primary rounded-pill mt-3">
                                                <i class="fa fa-user-plus me-1"></i> Add First Member
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($members->count() > 0)
                    <div class="text-center py-3">
                        <a href="{{ route('teams.members', $team->team_id) }}" class="btn btn-outline-primary rounded-pill">
                            <i class="fa fa-users me-1"></i> Manage Members
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Team Projects Section -->
    <div class="card shadow-sm border-0 mt-2">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="fw-semibold text-gray-900 mb-0">
                <i class="fa fa-project-diagram me-2 text-primary"></i> Team Projects
            </h5>
            <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">{{ $projects->count() }} total</span>
        </div>
        <div class="card-body p-4">
            @if($projects->count() > 0)
                <div class="row g-4">
                    @foreach($projects as $project)
                        <div class="col-md-6 col-lg-4">
                            <div class="project-card card border-0 shadow-sm h-100 transition">
                                <div class="card-body p-4">
                                    <h5 class="fw-semibold mb-3">{{ $project->name }}</h5>
                                    <p class="text-muted mb-4">{{ Str::limit($project->description, 100) ?: 'No description provided.' }}</p>
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">
                                            <i class="fa fa-users me-1"></i> {{ $project->members->count() }}
                                        </span>
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                            <i class="fa fa-running me-1"></i> {{ $project->sprints->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0 p-3">
                                    <a href="{{ route('projects.show', $project->project_id) }}" class="btn btn-sm btn-primary rounded-pill w-100">
                                        <i class="fa fa-eye me-1"></i> View Project
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-3">
                        <i class="fa fa-project-diagram fs-1 text-muted opacity-25"></i>
                    </div>
                    <h6 class="fw-medium mb-2">No Projects Yet</h6>
                    <p class="text-muted mb-4">This team hasn't been assigned to any projects yet.</p>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fa fa-plus me-1"></i> Create a Project
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #4e46e5;
        --secondary-color: #6b7280;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
    }
    
    body {
        background-color: #f9fafb;
        color: #111827;
        font-family: 'Inter', sans-serif;
    }
    
    .text-gray-900 {
        color: #111827;
    }
    
    .bg-primary-subtle {
        background-color: rgba(78, 70, 229, 0.1);
    }
    
    .bg-success-subtle {
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    .bg-warning-subtle {
        background-color: rgba(245, 158, 11, 0.1);
    }
    
    .bg-danger-subtle {
        background-color: rgba(239, 68, 68, 0.1);
    }
    
    .bg-info-subtle {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .text-primary {
        color: var(--primary-color) !important;
    }
    
    .text-success {
        color: var(--success-color) !important;
    }
    
    .text-warning {
        color: var(--warning-color) !important;
    }
    
    .text-danger {
        color: var(--danger-color) !important;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }
    
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .card {
        border-radius: 12px;
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
    }
    
    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(78, 70, 229, 0.03);
    }
    
    .badge {
        font-weight: 500;
    }
    
    .rounded-pill {
        border-radius: 50rem;
    }
    
    .project-card {
        transition: all 0.2s ease;
    }
    
    .project-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    }
</style>
@endsection