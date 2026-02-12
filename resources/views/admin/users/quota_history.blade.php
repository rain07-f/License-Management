@extends('layouts.admin')

@section('content')
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h5 class="fw-bold mb-0">Quota Allocation History</h5>
            <p class="text-muted small mb-0">Track all license quota changes across the system.</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light rounded-pill px-4 border shadow-sm">
                <i class="fa fa-arrow-left me-1 opacity-50"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="py-3">Distributor</th>
                            <th class="py-3">Added By</th>
                            <th class="py-3 text-center">Amount</th>
                            <th class="py-3 text-center">Previous</th>
                            <th class="py-3 text-center">New Quota</th>
                            <th class="px-4 py-3">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="px-4 py-3 small text-muted">
                                    {{ $log->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="py-3">
                                    <h6 class="mb-0 fw-semibold">{{ $log->user->name }}</h6>
                                    <small class="text-muted">{{ $log->user->email }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="small fw-medium">{{ $log->admin->name ?? 'System' }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-success-light text-success rounded-pill px-3">
                                        +{{ $log->amount }}
                                    </span>
                                </td>
                                <td class="py-3 text-center text-muted small">
                                    {{ $log->previous_quota }}
                                </td>
                                <td class="py-3 text-center fw-bold text-primary-dark">
                                    {{ $log->current_quota }}
                                </td>
                                <td class="px-4 py-3 small text-muted">
                                    {{ $log->note }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-5 text-center text-muted">
                                    No quota history found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection