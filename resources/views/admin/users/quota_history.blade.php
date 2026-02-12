@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quota Allocation History</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="card-title">Quota Allocation History</h6>
                            <p class="text-secondary tx-13">Track all license quota changes across the system.</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-icon-text btn-sm">
                                <i class="btn-icon-prepend" data-lucide="arrow-left"></i>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Distributor</th>
                                    <th>Added By</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Previous</th>
                                    <th class="text-center">New Quota</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            <span
                                                class="text-secondary small">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">{{ $log->user->name }}</span>
                                                <span class="text-secondary tx-11">{{ $log->user->email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-primary-subtle text-primary px-2">{{ $log->admin->name ?? 'System' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success fw-bolder">+{{ $log->amount }}</span>
                                        </td>
                                        <td class="text-center text-secondary small">
                                            {{ $log->previous_quota }}
                                        </td>
                                        <td class="text-center fw-bold text-primary">
                                            {{ $log->current_quota }}
                                        </td>
                                        <td>
                                            <span class="text-secondary small tx-12">{{ $log->note }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-secondary">
                                                <i data-lucide="inbox" class="icon-lg mb-2"></i>
                                                <p>No quota history logs found.</p>
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