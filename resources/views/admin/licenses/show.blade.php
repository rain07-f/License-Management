@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.licenses.index') }}">Licenses</a></li>
            <li class="breadcrumb-item active" aria-current="page">License Details</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="card-title mb-0">License Information</h6>
                        <div class="d-flex gap-2">
                            @if(auth()->user()->role !== 'client')
                                <button class="btn btn-outline-primary btn-icon-text btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#renewModal">
                                    <i class="btn-icon-prepend" data-lucide="refresh-cw"></i>
                                    Renew License
                                </button>
                            @endif
                            <a href="{{ route('admin.licenses.index') }}"
                                class="btn btn-outline-secondary btn-icon-text btn-sm">
                                <i class="btn-icon-prepend" data-lucide="arrow-left"></i>
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="bg-light p-4 rounded mb-4 text-center">
                                <label class="tx-11 fw-bolder mb-2 text-uppercase text-muted d-block">License Key</label>
                                <h3 class="text-primary fw-bolder text-break">{{ $license->license_key_display }}</h3>
                                <div class="mt-2 text-center">
                                    @php
                                        $statusBadge = [
                                            'active' => 'bg-success',
                                            'expired' => 'bg-danger',
                                            'suspended' => 'bg-warning',
                                            'revoked' => 'bg-secondary',
                                        ][$license->status] ?? 'bg-info';
                                    @endphp
                                    <span
                                        class="badge {{ $statusBadge }} text-capitalize px-3 py-1">{{ $license->status }}</span>
                                </div>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-4">
                                    <label class="tx-11 fw-bolder mb-1 text-uppercase text-muted d-block">Plan</label>
                                    <span class="fw-bold">{{ $license->plan->name }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="tx-11 fw-bolder mb-1 text-uppercase text-muted d-block">Owner</label>
                                    <span class="fw-bold">{{ $license->owner->name }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="tx-11 fw-bolder mb-1 text-uppercase text-muted d-block">Generated
                                        By</label>
                                    <span class="fw-bold">{{ $license->generator->name }}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="tx-11 fw-bolder mb-1 text-uppercase text-muted d-block">Expires At</label>
                                    <span
                                        class="fw-bold {{ $license->expires_at && $license->expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                                        {{ $license->expires_at ? $license->expires_at->format('M d, Y') : 'Never' }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="tx-11 fw-bolder mb-1 text-uppercase text-muted d-block">Activation
                                        Status</label>
                                    <span class="fw-bold">{{ $license->domains->count() }} / {{ $license->max_domains }}
                                        Domains Used</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="tx-11 fw-bolder mb-1 text-uppercase text-muted d-block">Created At</label>
                                    <span
                                        class="fw-bold text-secondary tx-13">{{ $license->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <h6 class="card-title mt-4">Activity History</h6>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Timestamp</th>
                                            <th>Action</th>
                                            <th>User</th>
                                            <th>Domain</th>
                                            <th>IP Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($license->logs as $log)
                                            <tr>
                                                <td class="tx-12">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-light text-dark text-capitalize">{{ $log->action }}</span>
                                                </td>
                                                <td class="tx-12">{{ $log->user->name ?? 'System' }}</td>
                                                <td class="tx-12 fw-medium text-primary">{{ $log->domain ?? '-' }}</td>
                                                <td class="font-monospace tx-11">{{ $log->ip_address ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">No activity recorded.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <h6 class="card-title tx-13 mb-3 d-flex align-items-center">
                                        <i data-lucide="globe" class="icon-sm me-2 text-primary"></i>
                                        Activated Domains
                                    </h6>
                                    <div class="list-group list-group-flush">
                                        @forelse($license->domains as $domain)
                                            <div
                                                class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                                <div>
                                                    <p class="mb-0 fw-semibold tx-13">{{ $domain->domain_name }}</p>
                                                    <small
                                                        class="text-muted tx-11">{{ $domain->activated_at->format('M d, Y') }}</small>
                                                </div>
                                                <form action="{{ route('admin.domains.destroy', $domain) }}" method="POST"
                                                    onsubmit="return confirm('Deactivate this domain?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0"
                                                        title="Deactivate">
                                                        <i data-lucide="minus-circle" class="icon-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @empty
                                            <p class="tx-12 text-muted text-center py-3">No active domains found.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->role !== 'client' && $license->status !== 'revoked')
                                <div class="card border border-danger">
                                    <div class="card-body p-3">
                                        <h6 class="tx-12 fw-bolder text-danger mb-3 text-uppercase">Danger Zone</h6>
                                        <form action="{{ route('admin.licenses.revoke', $license) }}" method="POST"
                                            onsubmit="return confirm('REVOKE this license? This is permanent.')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger w-100 btn-sm">
                                                Revoke License
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->role !== 'client')
        <!-- Renew Modal -->
        <div class="modal fade" id="renewModal" tabindex="-1" aria-labelledby="renewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.licenses.renew', $license) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="renewModalLabel">Renew License</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="text-secondary tx-13 mb-4">Select a plan to extend the license expiration date. The quota
                                will be deducted if you are a distributor.</p>
                            <div class="mb-3">
                                <label for="plan_id" class="form-label">Select Plan</label>
                                <select name="plan_id" id="plan_id" class="form-select" required>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ $license->plan_id == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} ({{ $plan->duration_days }} Days - {{ $plan->domain_limit }} Domains)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Renew Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        $(function () {
            'use strict';
            // Modal and Luicide are handled by the main layout scripts
        });
    </script>
@endsection