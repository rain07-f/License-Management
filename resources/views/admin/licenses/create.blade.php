@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="fw-bold mb-0">Generate New License</h5>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.licenses.index') }}" class="btn btn-light rounded-pill px-4 border shadow-sm">
                <i class="fa fa-arrow-left me-1 opacity-50"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm border-top border-primary border-4">
                <div class="card-body p-4 p-md-5">
                    @if(auth()->user()->isDistributor())
                        <div class="alert alert-info border-0 rounded-8 small mb-4">
                            <i class="fa fa-info-circle me-2"></i> Your remaining quota:
                            <strong>{{ auth()->user()->license_quota }}</strong> licenses.
                        </div>
                    @endif

                    <form action="{{ route('admin.licenses.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Select Product/Plan</label>
                            <select name="plan_id"
                                class="form-select form-select-lg rounded-8 @error('plan_id') is-invalid @enderror"
                                required>
                                <option value="" disabled selected>Choose a plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->price }}
                                        ({{ $plan->duration_days }} Days, {{ $plan->domain_limit }} Domains)</option>
                                @endforeach
                            </select>
                            @error('plan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Assign to Client (Optional)</label>
                            <select name="owner_id" class="form-select rounded-8">
                                <option value="">Keep for myself (Distributor)</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">If you select a client, the license will be owned by them
                                immediately.</small>
                        </div>

                        <div class="bg-light p-3 rounded-8 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-shield-alt text-primary-dark me-2"></i>
                                <span class="small fw-medium">Secured SHA256 hashing will be applied.</span>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 shadow-sm fw-bold w-100">
                                <i class="fa fa-bolt me-2"></i> Generate & Active Key
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection