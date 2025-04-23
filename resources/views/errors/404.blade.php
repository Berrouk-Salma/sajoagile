@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body py-5">
                    <h1 class="display-1 text-muted mb-4">404</h1>
                    <h2 class="mb-4">Page Not Found</h2>
                    <p class="lead mb-4">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/') }}" class="btn btn-primary me-2">
                            <i class="fa fa-home me-2"></i> Go Home
                        </a>
                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left me-2"></i> Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
