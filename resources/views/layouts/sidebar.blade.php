<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary' : '' }}" href="{{ route('dashboard') }}">
            <i class="fa fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('projects.*') ? 'active bg-primary' : '' }}" href="{{ route('projects.index') }}">
            <i class="fa fa-project-diagram me-2"></i> Projects
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-white {{ request()->routeIs('teams.*') ? 'active bg-primary' : '' }}" href="{{ route('teams.index') }}">
            <i class="fa fa-users me-2"></i> Teams
        </a>
    </li>
    
    @if(request()->routeIs('projects.show') || request()->routeIs('sprints.*') || request()->routeIs('tasks.*'))
        @php
            $projectId = null;
            
            if(request()->routeIs('projects.show')) {
                $projectId = $project->project_id ?? null;
            } elseif(request()->routeIs('sprints.*') && isset($sprint)) {
                $projectId = $project->project_id ?? null;
            } elseif(request()->routeIs('tasks.*') && isset($task)) {
                $projectId = $project->project_id ?? null;
            }
        @endphp
        
        @if($projectId)
            <li class="nav-item mt-4">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Current Project</span>
                </h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('projects.show') && request()->route('id') == $projectId ? 'active bg-primary' : '' }}" href="{{ route('projects.show', $projectId) }}">
                    <i class="fa fa-info-circle me-2"></i> Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('sprints.index') && request()->route('projectId') == $projectId ? 'active bg-primary' : '' }}" href="{{ route('sprints.index', $projectId) }}">
                    <i class="fa fa-running me-2"></i> Sprints
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('projects.members') && request()->route('id') == $projectId ? 'active bg-primary' : '' }}" href="{{ route('projects.members', $projectId) }}">
                    <i class="fa fa-user-friends me-2"></i> Members
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('projects.teams') && request()->route('id') == $projectId ? 'active bg-primary' : '' }}" href="{{ route('projects.teams', $projectId) }}">
                    <i class="fa fa-users me-2"></i> Teams
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('lists.index') && request()->route('projectId') == $projectId ? 'active bg-primary' : '' }}" href="{{ route('lists.index', $projectId) }}">
                    <i class="fa fa-list me-2"></i> Lists
                </a>
            </li>
        @endif
    @endif
    
    @if(request()->routeIs('sprints.show') || (request()->routeIs('tasks.*') && isset($sprint)))
        @php
            $sprintId = null;
            
            if(request()->routeIs('sprints.show')) {
                $sprintId = $sprint->sprint_id ?? null;
            } elseif(request()->routeIs('tasks.*') && isset($sprint)) {
                $sprintId = $sprint->sprint_id ?? null;
            }
        @endphp
        
        @if($sprintId)
            <li class="nav-item mt-4">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Current Sprint</span>
                </h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('sprints.show') && request()->route('id') == $sprintId ? 'active bg-primary' : '' }}" href="{{ route('sprints.show', $sprintId) }}">
                    <i class="fa fa-info-circle me-2"></i> Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('tasks.index') && request()->route('sprintId') == $sprintId ? 'active bg-primary' : '' }}" href="{{ route('tasks.index', $sprintId) }}">
                    <i class="fa fa-tasks me-2"></i> Tasks
                </a>
            </li>
        @endif
    @endif
</ul>
