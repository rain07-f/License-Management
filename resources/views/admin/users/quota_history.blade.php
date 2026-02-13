@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quota Allocation History</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="card-title mb-1">Quota Allocation History</h6>
                            <p class="text-secondary tx-13">Audit trail for all license quota modifications across the
                                system.</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-icon-text btn-sm">
                            <i class="btn-icon-prepend" data-lucide="layout-dashboard"></i>
                            Dashboard
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Recipient (Distributor)</th>
                                    <th>Authorized By</th>
                                    <th class="text-center">Allocation</th>
                                    <th class="text-center">Previous</th>
                                    <th class="text-center">New Quota</th>
                                    <th>Ref/Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-medium text-dark">{{ $log->created_at->format('M d, Y') }}</span>
                                                <small class="text-secondary">{{ $log->created_at->format('H:i:s') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-primary">{{ $log->user->name }}</span>
                                                <small class="text-secondary">{{ $log->user->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-primary-subtle text-primary border-primary border">{{ $log->admin->name ?? 'System' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bolder tx-14">+{{ $log->amount }}</span>
                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ $log->previous_quota }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark text-white fw-bold">{{ $log->current_quota }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary small tx-12 fst-italic">{{ $log->note ?: 'No notes provided' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-secondary opacity-50">
                                                <i data-lucide="inbox" class="icon-lg mb-2"></i>
                                                <p>No quota allocation history found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection