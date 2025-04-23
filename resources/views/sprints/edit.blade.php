@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Sprint</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Sprint
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Edit Sprint Details for {{ $project->name }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sprints.update', $sprint->sprint_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="goal" class="form-label">Sprint Goal</label>
                            <input type="text" class="form-control @error('goal') is-invalid @enderror" id="goal" name="goal" value="{{ old('goal', $sprint->goal) }}" required>
                            @error('goal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $sprint->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $sprint->end_date->format('Y-m-d')) }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Update Sprint
                            </button>
                            <a href="{{ route('sprints.show', $sprint->sprint_id) }}" class="btn btn-secondary">
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
