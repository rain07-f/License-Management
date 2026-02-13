@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Plan Management</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="card-title mb-0">Subscription Plans</h6>
                        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary btn-icon-text">
                            <i class="btn-icon-prepend" data-lucide="plus"></i>
                            Create New Plan
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Domain Limit</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plans as $plan)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-primary">{{ $plan->name }}</div>
                                            <small class="text-secondary d-block mt-1">{{ $plan->description }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bolder fs-5 text-dark">${{ number_format($plan->price, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info">{{ $plan->duration_days }} Days</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">{{ $plan->domain_limit }}
                                                Domains</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('admin.plans.edit', $plan) }}"
                                                    class="btn btn-outline-info btn-icon btn-sm" title="Edit">
                                                    <i data-lucide="edit-2"></i>
                                                </a>
                                                <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST"
                                                    class="d-inline" onsubmit="return confirm('Delete this plan?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-icon btn-sm"
                                                        title="Delete">
                                                        <i data-lucide="trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $plans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection