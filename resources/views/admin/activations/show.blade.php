@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.activations.index') }}">License Activations</a></li>
            <li class="breadcrumb-item active" aria-current="page">Activation Details</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-4">
            <div class="card grid-margin">
                <div class="card-body">
                    <h6 class="card-title">Activation Info</h6>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Domain:</label>
                        <p class="text-primary fw-semibold">{{ $activation->domain }}</p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Device UID:</label>
                        <p class="text-muted small font-monospace text-break">{{ $activation->device_uid }}</p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Status:</label>
                        <p>
                            @if($activation->status === 'active')
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Revoked</span>
                            @endif
                        </p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Activated At:</label>
                        <p class="text-muted">{{ $activation->activated_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    @if($activation->revoked_at)
                        <div class="mt-3">
                            <label class="tx-11 fw-bolder mb-0 text-uppercase">Revoked At:</label>
                            <p class="text-danger">{{ $activation->revoked_at->format('M d, Y H:i:s') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-primary">License Reference</h6>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">License Key Hash:</label>
                        <p class="text-muted small">{{ $activation->license->license_key_hash }}</p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Plan:</label>
                        <p class="fw-medium">{{ $activation->license->plan->name }}</p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Owner:</label>
                        <p>{{ $activation->license->owner->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.licenses.show', $activation->license) }}"
                            class="btn btn-primary btn-xs w-100">
                            Go to License Page
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Activity History (Pair Specific)</h6>
                    <div id="content">
                        <ul class="timeline">
                            @forelse($activation->license->logs as $log)
                                <li class="event">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-primary">{{ ucfirst($log->action) }}</span>
                                        <small class="text-muted">{{ $log->created_at->format('M d, Y H:i:s') }}</small>
                                    </div>
                                    <p class="tx-13 mb-1">
                                        Action performed by <span class="fw-semibold">{{ $log->user->name ?? 'System' }}</span>
                                    </p>
                                    <p class="text-secondary small">
                                        <i data-lucide="monitor" class="icon-xs me-1"></i> IP: {{ $log->ip_address ?? 'N/A' }}
                                    </p>
                                </li>
                            @empty
                                <div class="text-center py-5">
                                    <i data-lucide="info" class="icon-lg text-secondary mb-2"></i>
                                    <p class="text-secondary">No activity logs found for this pair.</p>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-styles')
    <style>
        .timeline {
            border-left: 2px solid #e9ecef;
            padding-left: 20px;
            list-style: none;
        }

        .timeline .event {
            position: relative;
            padding-bottom: 25px;
        }

        .timeline .event::before {
            content: "";
            position: absolute;
            left: -27px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #6571ff;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #6571ff;
        }
    </style>
@endpush