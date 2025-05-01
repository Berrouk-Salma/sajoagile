@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-1">User Management</h1>
            <p class="text-muted mb-0">Manage all system users and their roles</p>
        </div>
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-primary">
                <i class="fa fa-user-plus me-1"></i> Add New User
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userActionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-filter me-1"></i> Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userActionDropdown">
                    <li><a class="dropdown-item" href="#">All Users</a></li>
                    <li><a class="dropdown-item" href="#">Admins</a></li>
                    <li><a class="dropdown-item" href="#">Team Leads</a></li>
                    <li><a class="dropdown-item" href="#">Members</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="fw-semibold text-gray-900 mb-0">
                <i class="fa fa-users me-2 text-primary"></i> All Users
            </h5>
            <div class="d-flex align-items-center gap-3">
                <div class="search-container">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fa fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search users..." id="userSearch">
                    </div>
                </div>
                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">{{ count($users) }} total</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="usersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3 bg-primary-subtle text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 36px; height: 36px; font-weight: 600;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-medium d-block">{{ $user->name }}</span>
                                            <span class="small text-muted">ID: {{ $user->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'lead' ? 'warning' : 'primary') }}-subtle text-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'lead' ? 'warning' : 'primary') }} px-3 py-1 rounded-pill">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit User">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if($user->id !== Auth::id())
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="More Actions">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fa fa-eye me-2 text-primary"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fa fa-key me-2 text-warning"></i> Change Role
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                                <i class="fa fa-trash me-2"></i> Delete User
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fa fa-users fs-1 text-muted opacity-25"></i>
                                    </div>
                                    <p class="mb-1 text-muted">No users found</p>
                                    <a href="#" class="btn btn-sm btn-primary rounded-pill mt-3">
                                        <i class="fa fa-user-plus me-1"></i> Add New User
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <nav aria-label="User pagination">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
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
    
    .table-hover tbody tr:hover {
        background-color: rgba(78, 70, 229, 0.03);
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
    }
    
    .search-container .input-group-text,
    .search-container .form-control {
        background-color: #f9fafb;
        border-color: #e5e7eb;
    }
    
    .search-container .form-control:focus {
        background-color: #fff;
        box-shadow: none;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(78, 70, 229, 0.15);
    }
    
    .pagination .page-link {
        color: var(--primary-color);
        border-color: #e5e7eb;
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .rounded-pill {
        border-radius: 50rem;
    }
</style>

<script>
// Simple client-side search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tr');
    
    searchInput.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        
        // Skip the header row (index 0)
        for (let i = 1; i < rows.length; i++) {
            let found = false;
            const cells = rows[i].getElementsByTagName('td');
            
            // Search through each cell in the row
            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent.toLowerCase();
                if (cellText.indexOf(searchValue) > -1) {
                    found = true;
                    break;
                }
            }
            
            rows[i].style.display = found ? '' : 'none';
        }
    });
});
</script>
@endsection