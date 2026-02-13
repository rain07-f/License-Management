@extends('layouts.auth')

@section('content')
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center">
            <img src="{{ asset('assets/images/others/500_light.svg') }}" class="img-fluid mb-2 d-dark-none" alt="500"
                style="max-width: 300px;">
            <h1 class="fw-bolder mb-2 mt-2 display-1 text-secondary">500</h1>
            <h4 class="mb-2">Internal Server Error</h4>
            <h6 class="text-secondary mb-3 text-center">Sorry, our server is having some issues. Please try again later.
            </h6>
            <a href="{{ url('/') }}" class="btn btn-primary btn-icon-text">
                <i class="btn-icon-prepend" data-lucide="arrow-left"></i>
                Return to Safety
            </a>
        </div>
    </div>
@endsection