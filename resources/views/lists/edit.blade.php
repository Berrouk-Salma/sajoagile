@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit List</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('lists.show', $list->id_list) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Edit List</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('lists.update', $list->id_list) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nid" class="form-label">List Name/ID</label>
                            <input type="text" class="form-control @error('nid') is-invalid @enderror" id="nid" name="nid" value="{{ old('nid', $list->nid) }}" required>
                            @error('nid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $list->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Update List
                            </button>
                            <a href="{{ route('lists.show', $list->id_list) }}" class="btn btn-secondary">
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
