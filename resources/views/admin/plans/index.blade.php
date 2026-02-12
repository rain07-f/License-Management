@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="fw-bold mb-0">Plan Management</h5>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.plans.create') }}" class="btn btn-primary rounded-pill">
                <i class="fa fa-plus me-1"></i> Create New Plan
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Plan Name</th>
                            <th class="py-3">Price</th>
                            <th class="py-3">Duration (Days)</th>
                            <th class="py-3">Domain Limit</th>
                            <th class="px-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                            <tr>
                                <td class="px-4 py-3">
                                    <h6 class="mb-0 fw-semibold text-primary-dark">{{ $plan->name }}</h6>
                                    <small class="text-muted d-block" style="max-width: 300px;">{{ $plan->description }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="fw-bold fs-5">${{ number_format($plan->price, 2) }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-info-subtle text-info px-3">{{ $plan->duration_days }} Days</span>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-light text-dark px-3 fw-medium">{{ $plan->domain_limit }}
                                        Domains</span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.plans.edit', $plan) }}"
                                            class="btn btn-light btn-sm rounded-circle shadow-sm"
                                            style="width: 32px; height: 32px; padding: 0; line-height: 32px;">
                                            <i class="fa fa-edit text-accent-blue"></i>
                                        </a>
                                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST"
                                            onsubmit="return confirm('Delete this plan?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm"
                                                style="width: 32px; height: 32px; padding: 0; line-height: 32px;">
                                                <i class="fa fa-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $plans->links() }}
            </div>
        </div>
    </div>
@endsection