@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.licenses.index') }}">Licenses</a></li>
            <li class="breadcrumb-item active" aria-current="page">Generate New License</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="card-title">Generate New License</h6>
                        <a href="{{ route('admin.licenses.index') }}" class="btn btn-outline-primary btn-icon-text btn-sm">
                            <i class="btn-icon-prepend" data-lucide="arrow-left"></i>
                            Back to List
                        </a>
                    </div>

                    @if(auth()->user()->isDistributor())
                        <div class="alert alert-fill-info d-flex align-items-center mb-4">
                            <i data-lucide="info" class="icon-md me-2"></i>
                            <span>Your remaining quota: <strong>{{ auth()->user()->license_quota }}</strong> licenses.</span>
                        </div>
                    @endif

                    <form class="forms-sample" action="{{ route('admin.licenses.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="plan_id" class="form-label">Select Product/Plan</label>
                            <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror"
                                required>
                                <option value="" disabled selected>Choose a plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->price }}
                                        ({{ $plan->duration_days }} Days, {{ $plan->domain_limit }} Domains)</option>
                                @endforeach
                            </select>
                            @error('plan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="owner_id" class="form-label">Assign to Client (Optional)</label>
                            <select name="owner_id" id="owner_id" class="form-select">
                                <option value="">Keep for myself (Distributor)</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">If you select a client, the license will be owned by them
                                immediately.</div>
                        </div>

                        <div class="bg-light p-3 rounded mb-4 d-flex align-items-center">
                            <i data-lucide="shield-check" class="text-primary me-2"></i>
                            <span class="tx-12 fw-medium">Secured SHA256 hashing will be applied to the generated
                                key.</span>
                        </div>

                        <div class="text-center d-grid">
                            <button type="submit" class="btn btn-primary btn-icon-text">
                                <i class="btn-icon-prepend" data-lucide="zap"></i>
                                Generate & Active Key
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection