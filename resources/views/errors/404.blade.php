@extends('layouts.auth')

@section('content')
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center">
            <img src="{{ asset('assets/images/others/404.svg') }}" class="img-fluid mb-2" alt="404"
                style="max-width: 300px;">
            <h1 class="fw-bolder mb-2 mt-2 display-1 text-secondary">404</h1>
            <h4 class="mb-2">Page Not Found</h4>
            <h6 class="text-secondary mb-3 text-center">Oops!! The page you were looking for doesn't exist.</h6>
            <a href="{{ url('/') }}" class="btn btn-primary btn-icon-text">
                <i class="btn-icon-prepend" data-lucide="arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
@endsection