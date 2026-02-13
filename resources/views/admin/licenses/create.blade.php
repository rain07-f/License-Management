@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.licenses.index') }}">License Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Generate License</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-7 grid-margin stretch-card mx-auto">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Provision New License</h6>
                    <p class="text-secondary mb-4">Select a subscription plan and optionally assign it to a client account.
                    </p>

                    @if(auth()->user()->isDistributor())
                        <div class="alert alert-fill-info d-flex align-items-center mb-4 border-0 shadow-sm">
                            <i data-lucide="info" class="icon-sm me-2"></i>
                            <div>Your remaining quota: <span
                                    class="fw-bolder fs-5 ms-1">{{ auth()->user()->license_quota }}</span> licenses</div>
                        </div>
                    @endif

                    <form class="forms-sample" action="{{ route('admin.licenses.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="plan_id" class="form-label fw-bold">Select Subscription Plan</label>
                            <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror"
                                required>
                                <option value="" disabled selected>Choose a plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} — ${{ number_format($plan->price, 2) }}
                                        ({{ $plan->duration_days }} days, {{ $plan->domain_limit }} domains)
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="owner_id" class="form-label fw-bold">Client Assignment <small
                                    class="text-muted">(Optional)</small></label>
                            <select name="owner_id" id="owner_id" class="form-select">
                                <option value="">Keep for self (owned by you)</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('owner_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text mt-2 text-secondary">Licenses transferred to clients will be deducted from
                                your quota immediately.</div>
                        </div>

                        <div class="bg-light-subtle border p-3 rounded-3 mb-5 d-flex align-items-center opacity-75">
                            <i data-lucide="shield-check" class="text-primary me-2 icon-sm"></i>
                            <span class="tx-11 fw-medium text-secondary">System will generate a cryptographically secure key
                                and encrypt it using SHA256 protocols.</span>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-icon-text py-2 shadow-sm text-white">
                                <i class="btn-icon-prepend" data-lucide="zap"></i>
                                Generate & Activate License
                            </button>
                            <a href="{{ route('admin.licenses.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection