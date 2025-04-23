@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Lists: {{ $project->name }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('projects.show', $project->project_id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Project
                </a>
                <a href="{{ route('lists.create', $project->project_id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-plus"></i> New List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($lists as $list)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ $list->nid }}</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $list->id_list }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $list->id_list }}">
                                <li><a class="dropdown-item" href="{{ route('lists.show', $list->id_list) }}"><i class="fa fa-eye me-2"></i> View</a></li>
                                <li><a class="dropdown-item" href="{{ route('lists.edit', $list->id_list) }}"><i class="fa fa-edit me-2"></i> Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('lists.destroy', $list->id_list) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this list?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="fa fa-trash me-2"></i> Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $list->description }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('lists.show', $list->id_list) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-eye me-1"></i> View
                        </a>
                        <a href="{{ route('lists.edit', $list->id_list) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i> No lists found for this project. Create a new list to get started.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
