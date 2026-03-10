@extends('layouts.admin')

@push('custom-styles')
    <style>
        .activity-history-scroll {
            height: 400px;
            overflow-y: auto;
            border-bottom: 1px solid var(--bs-border-color, #e9ecef);
        }

        .activity-history-scroll table {
            margin-bottom: 0;
        }

        .activity-history-scroll thead th {
            position: sticky;
            top: 0;
            background-color: var(--bs-card-bg, #040914);
            z-index: 10;
            box-shadow: 0 1px 0 var(--bs-border-color, #e9ecef);
        }
    </style>
@endpush

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
                            <div class="bg-dark p-4 rounded mb-4 text-center">
                                <label class="tx-11 fw-bolder mb-2 text-uppercase text-muted d-block">License Key</label>
                                <h4 class="text-primary fw-bolder text-break">{{ $license->license_key_display }}</h4>
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
                                    <span id="planNameDisplay" class="fw-bold">{{ $license->plan->name }}</span>
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
                                    <span id="expiresAtBadge"
                                        class="fw-bold {{ $license->expires_at && $license->expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                                        {{ $license->expires_at ? $license->expires_at->format('M d, Y') : 'Never' }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label class="tx-11 fw-bolder mb-1 text-uppercase text-muted d-block">Activation
                                        Status</label>
                                    <span id="domainCount" class="fw-bold">{{ $license->domains()->count() }} / {{ $license->max_domains }}
                                        Domains Used</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="tx-11 fw-bolder mb-1 text-uppercase text-muted d-block">Created At</label>
                                    <span
                                        class="fw-bold text-secondary tx-13">{{ $license->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <h6 class="card-title mt-4 d-flex align-items-center">
                                <i data-lucide="globe" class="icon-sm me-2 text-primary"></i>
                                Activated Domains
                            </h6>
                            <div id="domainsContainer">
                                @php
                                    $domains = $license->domains()->latest()->paginate(5);
                                @endphp
                                @include('admin.licenses.partials._domains_table', ['license' => $license, 'domains' => $domains])
                            </div>
                        </div>

                        <div class="col-md-3">
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
                            <div> &nbsp;</div>
                            <div class="card border mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title tx-13 mb-3 d-flex align-items-center">
                                        <i data-lucide="history" class="icon-sm me-2 text-primary"></i>
                                        Activity History
                                    </h6>
                                    <div class="activity-history-scroll" style="height: 350px;">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="tx-10 text-uppercase">Log Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($license->logs as $log)
                                                        <tr>
                                                            <td class="px-0 py-2 border-0">
                                                                <div class="d-flex justify-content-between mb-1">
                                                                    <span class="badge bg-dark-subtle text-white tx-9 px-2">{{ $log->action }}</span>
                                                                    <span class="tx-9 text-muted">{{ $log->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <p class="tx-11 mb-0"><span class="text-primary">{{ $log->domain ?? '-' }}</span></p>
                                                                <small class="text-muted tx-10">{{ $log->user->name ?? 'System' }} • {{ $log->ip_address ?? '-' }}</small>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td class="text-center py-3 text-muted tx-11">No activity recorded.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                    <form id="renewLicenseForm" action="{{ route('admin.licenses.renew', $license) }}" method="POST">
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
            
            // Handle AJAX Pagination for Domains
            $(document).on('click', '#domainsPagination a', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                loadDomains(url);
            });

            function loadDomains(url) {
                const container = $('#domainsContainer');
                container.css('opacity', '0.5');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (res) {
                        // Support both raw HTML (old) and JSON (new) for robustness
                        const html = typeof res === 'string' ? res : res.html;
                        container.html(html).css('opacity', '1');
                        
                        if (res.count !== undefined) {
                            $('#domainCount').text(`${res.count} / ${res.max} Domains Used`);
                        }
                        
                        if (window.lucide) lucide.createIcons();
                    },
                    error: function () {
                        container.css('opacity', '1');
                        alert('Failed to load domains.');
                    }
                });
            }

            // Handle AJAX Deactivation
            $(document).on('submit', '.deactivate-domain-form', function (e) {
                e.preventDefault();
                if (!confirm('Deactivate this domain?')) return;

                const form = $(this);
                const row = form.closest('tr');
                const btn = form.find('button');
                const originalHtml = btn.html();

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    beforeSend: function () {
                        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                    },
                    success: function (res) {
                        if (res.success) {
                            // Reload current page of domains to keep pagination accurate
                            const currentPageUrl = $('#domainsPagination .active .page-link').attr('href') || '{{ route("admin.licenses.domains", $license) }}';
                            loadDomains(currentPageUrl);
                            
                            // Also update the domain count in the UI if possible
                            // The easiest way is to refresh the page or update just that part
                            // For now, let's assume the user is okay with the table updating
                        }
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).html(originalHtml);
                        alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Failed to deactivate domain.'));
                    }
                });
            // Handle AJAX Renewal
            $(document).on('submit', '#renewLicenseForm', function (e) {
                e.preventDefault();
                const form = $(this);
                const btn = form.find('button[type="submit"]');
                const modalEl = document.getElementById('renewModal');
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    beforeSend: function () {
                        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Reviewing...');
                    },
                    success: function (res) {
                        if (res.success) {
                            modal.hide();
                            
                            // Update UI
                            const expiresAt = new Date(res.data.expires_at).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                            $('#expiresAtBadge').text(expiresAt).removeClass('text-danger').addClass('text-success');
                            $('#planNameDisplay').text(res.data.plan.name);
                            
                            // Reload domains and history to show the renewal action
                            loadDomains('{{ route("admin.licenses.domains", $license) }}');
                            
                            // Optional: Refresh the activity history if needed
                            // For simplicity, we can just reload the current page or partial
                            location.reload(); // Simple reload for history for now, or we could AJAX-ify it too
                        }
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).text('Renew Now');
                        alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Failed to renew license.'));
                    }
                });
            });
        });
    </script>
@endsection