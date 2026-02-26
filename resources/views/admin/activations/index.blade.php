@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">License Activations</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="card-title">License Activations</h6>
                            <p class="text-secondary tx-13">Monitoring all active and revoked license pairs across your
                                system.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.activations.index') }}" method="GET" class="d-flex gap-2">
                                <select name="status" class="form-select wd-120" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>Revoked
                                    </option>
                                </select>
                                <div class="input-group wd-250">
                                    <span class="input-group-text bg-transparent border-primary">
                                        <i data-lucide="search" class="icon-sm text-primary"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-primary"
                                        placeholder="Search domain or UID..." value="{{ request('search') }}">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th>Device UID</th>
                                    <th>License Info</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Activated At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activations as $activation)
                                    <tr class="{{ $activation->status === 'revoked' ? 'bg-light opacity-75' : '' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i data-lucide="globe" class="icon-sm text-primary me-2"></i>
                                                <span class="fw-semibold">{{ $activation->domain }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-secondary font-monospace">
                                                {{ substr($activation->device_uid, 0, 16) }}...
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-medium text-primary">{{ $activation->license->plan->name }}</span>
                                                <code
                                                    class="tx-11">{{ substr($activation->license->license_key_hash, 0, 12) }}...</code>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary small">{{ $activation->license->owner->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if($activation->status === 'active')
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Revoked</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-secondary small">
                                                {{ $activation->activated_at->format('M d, Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($activation->status === 'active')
                                                <form class="activationRevokeForm"
                                                    action="{{ route('admin.activations.destroy', $activation) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-icon btn-xs"
                                                        title="Revoke Activation">
                                                        <i data-lucide="x-circle"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small">Locked</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-secondary">
                                                <i data-lucide="info" class="icon-lg mb-2"></i>
                                                <p>No license activations found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $activations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // AJAX Revoke
                $(document).on('submit', '.activationRevokeForm', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    let row = form.closest('tr');

                    if (confirm('Are you sure you want to revoke this activation? This will PERMANENTLY lock this domain and device for this license.')) {
                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function (res) {
                                if (res.success) {
                                    location.reload(); // Reload to show revoked state
                                }
                            },
                            error: function (xhr) {
                                alert(xhr.responseJSON?.message || 'Error occurred');
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection