@extends('layouts.admin')

@section('content')
    <div class="row mb-4 align-items-center g-3">
        <div class="col-12 col-lg-4 text-center text-lg-start">
            <h5 class="fw-bold mb-0">License Management</h5>
        </div>
        <div class="col-12 col-md-8 col-lg-4">
            <form action="{{ route('admin.licenses.index') }}" method="GET">
                <div class="input-group shadow-sm rounded-pill">
                    <input type="text" name="search" class="form-control rounded-pill-start border-0 ps-4"
                        placeholder="Search by hash or owner..." value="{{ request('search') }}">
                    <button class="btn btn-white rounded-pill-end border-0 pe-4" type="submit">
                        <i data-lucide="search" class="text-muted icon-sm"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-4 col-lg-4 text-center text-lg-end">
            <a href="{{ route('admin.licenses.export') }}" class="btn btn-light rounded-pill px-4 border shadow-sm me-2">
                <i data-lucide="download" class="me-1 icon-sm"></i> Export
            </a>
            @if(auth()->user()->role !== 'client')
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#quickGenerateModal">
                    <i data-lucide="zap" class="me-1 icon-sm"></i> Generate
                </button>
                
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">License Key Display</th>
                            <th class="py-3">Owner</th>
                            <th class="py-3">Plan</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Domains</th>
                            <th class="py-3">Expires At</th>
                            <th class="px-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="licensesTableBody">
                        @foreach($licenses as $license)
                            <tr id="licenseRow-{{ $license->id }}">
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.licenses.show', $license) }}" class="text-decoration-none">
                                        <span class="font-monospace fw-medium text-primary-dark small"
                                            title="{{ $license->license_key_display }}">
                                            {{ substr($license->license_key_display, 0, 12) }}...
                                        </span>
                                    </a>
                                </td>
                                <td class="py-3">
                                    <h6 class="mb-0 fw-semibold">{{ $license->owner->name }}</h6>
                                    <small class="text-muted">{{ $license->owner->role }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="fw-bold">{{ $license->plan->name }}</span>
                                </td>
                                <td class="py-3">
                                    @php
                                        $statusClass = [
                                            'active' => 'badge-active',
                                            'expired' => 'badge-expired',
                                            'suspended' => 'badge-suspended',
                                            'revoked' => 'bg-secondary text-white',
                                        ][$license->status] ?? 'bg-light text-dark';
                                    @endphp
                                    <span class="badge {{ $statusClass }} text-capitalize px-3">
                                        {{ $license->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-dark text-white fw-bold rounded-pill px-3">
                                        {{ $license->domains()->count() }} / {{ $license->max_domains }}
                                    </span>
                                </td>
                                <td class="py-3 text-muted">
                                    {{ $license->expires_at ? $license->expires_at->format('M d, Y') : 'Never' }}
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle shadow-none border-0"
                                            type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                            @if($license->status === 'active')
                                                <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#assignModal{{ $license->id }}"><i
                                                            data-lucide="user-plus" class="me-2 icon-sm opacity-50"></i> Transfer Ownership</a></li>
                                                <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#renewModal{{ $license->id }}"><i
                                                            data-lucide="refresh-cw" class="me-2 icon-sm opacity-50"></i> Change Plan</a></li>
                                                <li>
                                                    <form class="licenseFormRevoke" action="{{ route('admin.licenses.revoke', $license) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item py-2 text-danger"><i
                                                                data-lucide="slash" class="me-2 icon-sm opacity-50"></i> Revoke License</button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.licenses.show', $license) }}"><i
                                                        data-lucide="list" class="me-2 icon-sm opacity-50"></i> View Logs</a></li>
                                        </ul>
                                    </div>

                                    <!-- Renew Modal -->
                                    <div class="modal fade" id="renewModal{{ $license->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <form class="licenseFormRenew" action="{{ route('admin.licenses.renew', $license) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header border-0 bg-dark">
                                                        <h5 class="modal-title fw-bold">Change License Plan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4 text-start">
                                                        <p class="text-muted small mb-4">Select a new plan for this license. The expiration will be extended based on the new plan.</p>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-medium">Select Plan</label>
                                                            <select name="plan_id" class="form-select rounded-8" required>
                                                                @foreach($plans as $plan)
                                                                    <option value="{{ $plan->id }}" {{ $license->plan_id == $plan->id ? 'selected' : '' }}>
                                                                        {{ $plan->name }} ({{ $plan->duration_days }} Days - {{ $plan->domain_limit }} Domains)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 p-4 pt-0">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Renew Now</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $licenses->links() }}
            </div>
        </div>
    </div>

    <!-- Quick Generate Modal -->
    <div class="modal fade" id="quickGenerateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="quickGenerateForm" action="{{ route('admin.licenses.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 bg-dark">
                        <h5 class="modal-title fw-bold">Generate License</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Select Plan</label>
                            <select name="plan_id" class="form-select" required>
                                <option value="">Select a plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} (${{ $plan->price }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Assign to Client (Optional)</label>
                            <select name="owner_id" class="form-select">
                                <option value="">Generate for Myself</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Result Modal -->
    <div class="modal fade" id="licenseResultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-success-subtle">
                    <h5 class="modal-title fw-bold text-success">License Generated!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="text-muted mb-3">Please copy your license key now. For security, it won't be shown again in full.</p>
                    <div class="bg-light p-3 rounded-lg border mb-3">
                        <code class="fs-4 fw-bold text-dark" id="newLicenseKey"></code>
                    </div>
                    <button type="button" class="btn btn-primary rounded-pill px-4" id="copyLicenseKey">
                        <i data-lucide="copy" class="me-2 icon-sm"></i> Copy to Clipboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Helper to build row HTML
            function buildLicenseRow(license) {
                const statusClasses = {
                    'active': 'badge-active',
                    'expired': 'badge-expired',
                    'suspended': 'badge-suspended',
                    'revoked': 'bg-secondary text-white'
                };
                const statusClass = statusClasses[license.status] || 'bg-light text-dark';
                const ownerName = license.owner ? license.owner.name : 'Unknown';
                const ownerRole = license.owner ? license.owner.role : '';
                const planName = license.plan ? license.plan.name : 'Unknown';
                const expiresAt = license.expires_at ? new Date(license.expires_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) : 'Never';
                const domainsCount = license.domains ? license.domains.length : 0;
                
                let actions = '';
                if (license.status === 'active') {
                    actions = `
                        <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                data-bs-target="#assignModal${license.id}"><i
                                    data-lucide="user-plus" class="me-2 icon-sm opacity-50"></i> Transfer Ownership</a></li>
                        <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                data-bs-target="#renewModal${license.id}"><i
                                    data-lucide="refresh-cw" class="me-2 icon-sm opacity-50"></i> Change Plan</a></li>
                        <li>
                            <form class="licenseFormRevoke" action="/admin/licenses/${license.id}/revoke" method="POST">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <button type="submit" class="dropdown-item py-2 text-danger"><i
                                        data-lucide="slash" class="me-2 icon-sm opacity-50"></i> Revoke License</button>
                            </form>
                        </li>
                    `;
                }

                return `
                    <tr id="licenseRow-${license.id}">
                        <td class="px-4 py-3">
                            <a href="/admin/licenses/${license.id}" class="text-decoration-none">
                                <span class="font-monospace fw-medium text-primary-dark small">
                                    ${license.license_key_hash.substr(0, 12)}...
                                </span>
                            </a>
                        </td>
                        <td class="py-3">
                            <h6 class="mb-0 fw-semibold">${ownerName}</h6>
                            <small class="text-muted">${ownerRole}</small>
                        </td>
                        <td class="py-3">
                            <span class="fw-bold">${planName}</span>
                        </td>
                        <td class="py-3">
                            <span class="badge ${statusClass} text-capitalize px-3">${license.status}</span>
                        </td>
                        <td class="py-3 text-center">
                            <span class="badge bg-dark text-white fw-bold rounded-pill px-3">
                                ${domainsCount} / ${license.max_domains || license.plan.domain_limit}
                            </span>
                        </td>
                        <td class="py-3 text-muted">${expiresAt}</td>
                        <td class="px-4 py-3 text-end">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle shadow-none border-0"
                                    type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                    ${actions}
                                    <li><a class="dropdown-item py-2" href="#"><i data-lucide="list" class="me-2 icon-sm opacity-50"></i> View Logs</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `;
            }

            // AJAX Generate
            $(document).on('submit', '#quickGenerateForm', function(e) {
                e.preventDefault();
                let form = $(this);
                let btn = form.find('button[type=submit]');
                let modal = bootstrap.Modal.getInstance(document.getElementById('quickGenerateModal'));

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    beforeSend: function() {
                        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Generating...');
                    },
                    success: function(res) {
                        if(res.success) {
                            form[0].reset();
                            modal.hide();
                            
                            // Prepend row
                            const newRow = buildLicenseRow(res.data);
                            $('#licensesTableBody').prepend(newRow);
                            if (window.lucide) lucide.createIcons();

                            // Show result
                            $('#newLicenseKey').text(res.display_key);
                            const resultModal = new bootstrap.Modal(document.getElementById('licenseResultModal'));
                            resultModal.show();
                        }
                    },
                    error: function(xhr) {
                        alert('Failed: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Generate');
                    }
                });
            });

            // AJAX Transfer
            $(document).on('submit', '.licenseFormTransfer', function(e) {
                e.preventDefault();
                let form = $(this);
                let modal = form.closest('.modal');
                let bootstrapModal = bootstrap.Modal.getInstance(modal[0]);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if(res.success) {
                            bootstrapModal.hide();
                            const updatedRow = buildLicenseRow(res.data);
                            $(`#licenseRow-${res.data.id}`).replaceWith(updatedRow);
                            if (window.lucide) lucide.createIcons();
                        }
                    }
                });
            });

            // AJAX Revoke (same as before)
            $(document).on('submit', '.licenseFormRevoke', function(e) {
                e.preventDefault();
                let form = $(this);
                let row = form.closest('tr');

                if(confirm('Are you sure you want to revoke this license?')) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        success: function(res) {
                            if(res.success) {
                                const updatedRow = buildLicenseRow(res.data);
                                row.replaceWith(updatedRow);
                                if (window.lucide) lucide.createIcons();
                            }
                        }
                    });
                }
            });

            // AJAX Renew/Change Plan
            $(document).on('submit', '.licenseFormRenew', function(e) {
                e.preventDefault();
                let form = $(this);
                let modal = form.closest('.modal');
                let bootstrapModal = bootstrap.Modal.getInstance(modal[0]);
                let btn = form.find('button[type=submit]');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    beforeSend: function() {
                        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Updating...');
                    },
                    success: function(res) {
                        if(res.success) {
                            bootstrapModal.hide();
                            const updatedRow = buildLicenseRow(res.data);
                            $(`#licenseRow-${res.data.id}`).replaceWith(updatedRow);
                            if (window.lucide) lucide.createIcons();
                        }
                    },
                    error: function(xhr) {
                        alert('Failed: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Renew Now');
                    }
                });
            });

            // Copy logic
            $('#copyLicenseKey').on('click', function() {
                const key = $('#newLicenseKey').text();
                navigator.clipboard.writeText(key).then(() => {
                    $(this).html('<i data-lucide="check" class="me-2 icon-sm"></i> Copied!').removeClass('btn-primary').addClass('btn-success');
                    if (window.lucide) lucide.createIcons();
                    setTimeout(() => {
                        $(this).html('<i data-lucide="copy" class="me-2 icon-sm"></i> Copy to Clipboard').removeClass('btn-success').addClass('btn-primary');
                        if (window.lucide) lucide.createIcons();
                    }, 2000);
                });
            });
        });
    </script>
    @endpush
@endsection