@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">List: {{ $list->nid }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                @if($projects->isNotEmpty())
                    <a href="{{ route('lists.index', $projects->first()->project_id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Lists
                    </a>
                @endif
                <a href="{{ route('lists.edit', $list->id_list) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <form action="{{ route('lists.destroy', $list->id_list) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this list?')">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">List Information</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>List ID:</strong> {{ $list->id_list }}
                    </p>
                    <p class="card-text">
                        <strong>Name/ID:</strong> {{ $list->nid }}
                    </p>
                    <p class="card-text">
                        <strong>Description:</strong> {{ $list->description ?: 'No description provided.' }}
                    </p>
                    <p class="card-text">
                        <strong>Created:</strong> {{ $list->created_at->format('M d, Y') }}
                    </p>
                    <p class="card-text">
                        <strong>Last Updated:</strong> {{ $list->updated_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Associated Projects</h5>
                </div>
                <div class="card-body">
                    @if($projects->isNotEmpty())
                        <ul class="list-group">
                            @foreach($projects as $project)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ route('projects.show', $project->project_id) }}">{{ $project->name }}</a>
                                    <form action="{{ route('projects.lists.remove', ['projectId' => $project->project_id, 'listId' => $list->id_list]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this list from the project?')">
                                            <i class="fa fa-unlink"></i> Remove
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i> This list is not associated with any projects.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
