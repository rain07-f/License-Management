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
                    <h6 class="card-title mb-4">Activity History (Pair Specific)</h6>
                    <div class="mt-2">
                        <div class="activity-timeline">
                            @forelse($activation->license->logs as $log)
                                <div class="timeline-item">
                                    <div class="timeline-indicator">
                                        <div class="dot {{ $log->action === 'revoke_pair' ? 'bg-danger' : 'bg-primary' }}">
                                        </div>
                                        <div class="line"></div>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span
                                                class="badge {{ $log->action === 'revoke_pair' ? 'bg-danger' : 'bg-primary' }} bg-opacity-10 text-{{ $log->action === 'revoke_pair' ? 'danger' : 'primary' }} text-uppercase tx-10 fw-bolder px-2 py-1">
                                                {{ str_replace('_', ' ', $log->action) }}
                                            </span>
                                            <span
                                                class="text-secondary tx-11">{{ $log->created_at->format('M d, Y H:i:s') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <div class="avatar avatar-xs">
                                                <div class="avatar-title bg-light text-secondary rounded-circle">
                                                    {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                                </div>
                                            </div>
                                            <p class="tx-13 mb-0">
                                                Performed by <span
                                                    class="fw-bold text-body">{{ $log->user->name ?? 'System' }}</span>
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-center text-secondary tx-12 ps-1">
                                            <i data-lucide="monitor" class="icon-xs me-2"></i>
                                            <span>IP: {{ $log->ip_address ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i data-lucide="info" class="icon-lg text-secondary mb-2"></i>
                                    <p class="text-secondary">No activity logs found for this pair.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-styles')
    <style>
        .activity-timeline {
            padding-left: 10px;
        }

        .timeline-item {
            display: flex;
            gap: 20px;
            padding-bottom: 30px;
            position: relative;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 12px;
        }

        .timeline-indicator .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            z-index: 2;
            box-shadow: 0 0 0 3px rgba(101, 113, 255, 0.2);
        }

        .timeline-indicator .dot.bg-danger {
            box-shadow: 0 0 0 3px rgba(255, 51, 102, 0.2);
        }

        .timeline-indicator .line {
            flex-grow: 1;
            width: 2px;
            background-color: #e9ecef;
            margin-top: 5px;
            margin-bottom: -5px;
        }

        .timeline-item:last-child .timeline-indicator .line {
            display: none;
        }

        .timeline-content {
            flex-grow: 1;
            padding: 15px;
            background-color: rgba(248, 249, 250, 0.5);
            border-radius: 8px;
            border: 1px solid #f1f2f4;
            transition: all 0.2s ease;
        }

        .timeline-content:hover {
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .avatar-xs {
            width: 24px;
            height: 24px;
            font-size: 10px;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
    </style>
@endpush