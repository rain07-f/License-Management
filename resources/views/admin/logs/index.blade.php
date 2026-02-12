@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="fw-bold mb-0">System Logs</h5>
            <p class="text-muted small">Audit trail for all license and domain actions.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Timestamp</th>
                            <th class="py-3">Action</th>
                            <th class="py-3">License (Hash)</th>
                            <th class="py-3">User</th>
                            <th class="py-3">Domain</th>
                            <th class="px-4 py-3">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td class="px-4 py-3 small text-muted">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                    <br>
                                    <span class="x-small">{{ $log->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="py-3">
                                    @php
                                        $actionClass = [
                                            'generate' => 'bg-primary',
                                            'activate' => 'bg-success',
                                            'validate' => 'bg-info',
                                            'revoke' => 'bg-danger',
                                            'deactivate' => 'bg-warning',
                                        ][$log->action] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $actionClass }} text-capitalize px-3">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <code class="small">{{ substr($log->license->license_key_hash, 0, 10) }}...</code>
                                </td>
                                <td class="py-3 small">
                                    {{ $log->user->name ?? 'System/API' }}
                                </td>
                                <td class="py-3 small fw-medium">
                                    {{ $log->domain ?? '-' }}
                                </td>
                                <td class="px-4 py-3 small text-muted font-monospace">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection