@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Create New List</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('lists.index', $project->project_id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Lists
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">List Details for {{ $project->name }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('lists.store', $project->project_id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nid" class="form-label">List Name/ID</label>
                            <input type="text" class="form-control @error('nid') is-invalid @enderror" id="nid" name="nid" value="{{ old('nid') }}" required>
                            @error('nid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Create List
                            </button>
                            <a href="{{ route('lists.index', $project->project_id) }}" class="btn btn-secondary">
                                <i class="fa fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
