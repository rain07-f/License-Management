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

                    <!-- Filters -->
                    <div class="mb-4">
                        <form action="{{ route('admin.logs.index') }}" method="GET" class="row g-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label tx-11 fw-bold text-uppercase text-secondary">Action</label>
                                <select name="action" class="form-select form-select-sm shadow-none">
                                    <option value="">All Actions</option>
                                    @php
                                        $actions = ['generate', 'activate', 'validate', 'renew', 'revoke', 'deactivate', 'reactivate', 'transfer'];
                                    @endphp
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                            {{ ucfirst($action) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label tx-11 fw-bold text-uppercase text-secondary">Date From</label>
                                <div class="input-group datepicker-wrap">
                                    <span class="input-group-text bg-transparent border-end-0 py-1 px-2 cursor-pointer" data-toggle>
                                        <i data-lucide="calendar" class="icon-sm text-secondary"></i>
                                    </span>
                                    <input type="text" name="date_from" id="date_from" 
                                        class="form-control form-control-sm shadow-none border-start-0 ps-0" 
                                        data-input
                                        placeholder="YYYY-MM-DD" 
                                        value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label tx-11 fw-bold text-uppercase text-secondary">Date To</label>
                                <div class="input-group datepicker-wrap">
                                    <span class="input-group-text bg-transparent border-end-0 py-1 px-2 cursor-pointer" data-toggle>
                                        <i data-lucide="calendar" class="icon-sm text-secondary"></i>
                                    </span>
                                    <input type="text" name="date_to" id="date_to" 
                                        class="form-control form-control-sm shadow-none border-start-0 ps-0" 
                                        data-input
                                        placeholder="YYYY-MM-DD" 
                                        value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1 px-3">
                                    <i data-lucide="filter" class="icon-sm me-1"></i> Filter
                                </button>
                                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                                    <i data-lucide="refresh-cw" class="icon-sm me-1"></i> Reset
                                </a>
                            </div>
                        </form>
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
                                                <a href="{{ route('admin.logs.index', ['date_from' => $log->created_at->format('Y-m-d'), 'date_to' => $log->created_at->format('Y-m-d')]) }}" 
                                                   class="fw-medium tx-12 text-light hover-primary" 
                                                   title="Filter by this date">
                                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                                </a>
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

@push('custom-scripts')
    <script>
        $(function() {
            'use strict';

            if ($('.datepicker-wrap').length) {
                $('.datepicker-wrap').each(function() {
                    flatpickr(this, {
                        wrap: true,
                        dateFormat: "Y-m-d",
                        allowInput: true,
                        disableMobile: "true"
                    });
                });
            }
        });
    </script>
@endpush