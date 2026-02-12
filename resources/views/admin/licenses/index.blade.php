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
                        <i class="fa fa-search text-muted"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-4 col-lg-4 text-center text-lg-end">
            <a href="{{ route('admin.licenses.export') }}" class="btn btn-light rounded-pill px-4 border shadow-sm me-2">
                <i class="fa fa-download me-1"></i> Export
            </a>
            @if(auth()->user()->role !== 'client')
                <a href="{{ route('admin.licenses.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fa fa-magic me-1"></i> Generate License
                </a>
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
                    <tbody>
                        @foreach($licenses as $license)
                            <tr>
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
                                    <span class="badge bg-light text-primary-dark border fw-bold rounded-pill px-3">
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
                                                            class="fa fa-user-plus me-2 opacity-50"></i> Transfer Ownership</a></li>
                                                <li>
                                                    <form action="{{ route('admin.licenses.revoke', $license) }}" method="POST"
                                                        onsubmit="return confirm('Revoke this license?')">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item py-2 text-danger"><i
                                                                class="fa fa-ban me-2 opacity-50"></i> Revoke License</button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li><a class="dropdown-item py-2" href="#"><i
                                                        class="fa fa-list me-2 opacity-50"></i> View Logs</a></li>
                                        </ul>
                                    </div>

                                    <!-- Assign Modal -->
                                    <div class="modal fade" id="assignModal{{ $license->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <form action="{{ route('admin.licenses.assign', $license) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header border-0 bg-light">
                                                        <h5 class="modal-title fw-bold">Transfer License Ownership</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4 text-start">
                                                        <p class="text-muted small mb-4">Transfer this license to one of your
                                                            sub-users (clients).</p>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-medium">Select Client</label>
                                                            <select name="client_id" class="form-select rounded-8" required>
                                                                @php
                                                                    $clients = auth()->user()->isSuperAdmin() ? \App\Models\User::where('role', 'client')->get() : \App\Models\User::where('parent_id', auth()->id())->where('role', 'client')->get();
                                                                @endphp
                                                                @foreach($clients as $client)
                                                                    <option value="{{ $client->id }}">{{ $client->name }}
                                                                        ({{ $client->email }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 p-4 pt-0">
                                                        <button type="button" class="btn btn-light rounded-pill px-4"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit"
                                                            class="btn btn-primary rounded-pill px-4 shadow-sm">Transfer</button>
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
@endsection