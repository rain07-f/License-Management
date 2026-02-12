@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">System Logs</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="card-title">System Logs</h6>
                            <p class="text-secondary tx-13">Audit trail for all license and domain actions.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                    <th>License (Hash)</th>
                                    <th>User</th>
                                    <th>Domain</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-medium tx-12">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                                                <span
                                                    class="text-secondary tx-11">{{ $log->created_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $actionBadge = [
                                                    'generate' => 'bg-primary',
                                                    'activate' => 'bg-success',
                                                    'validate' => 'bg-info',
                                                    'revoke' => 'bg-danger',
                                                    'deactivate' => 'bg-warning',
                                                ][$log->action] ?? 'bg-secondary';
                                            @endphp
                                            <span class="badge {{ $actionBadge }} text-capitalize px-2 py-1">
                                                {{ $log->action }}
                                            </span>
                                        </td>
                                        <td>
                                            <code
                                                class="tx-11 text-primary">{{ substr($log->license->license_key_hash, 0, 10) }}...</code>
                                        </td>
                                        <td>
                                            <span class="tx-12">{{ $log->user->name ?? 'System/API' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-medium tx-12">{{ $log->domain ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-secondary font-monospace tx-11">{{ $log->ip_address ?? '-' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-secondary">
                                                <i data-lucide="activity" class="icon-lg mb-2"></i>
                                                <p>No activity logs found.</p>
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