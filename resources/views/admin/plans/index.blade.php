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
                                        <td class="px-4 py-3 text-end">
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle shadow-none border-0"
                                                    type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                                    <li>
                                                        <a class="dropdown-item py-2"
                                                            href="{{ route('admin.plans.edit', $plan) }}">
                                                            <i data-lucide="edit-2" class="me-2 icon-sm opacity-50"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="dropdown-item py-2 text-danger">
                                                                <i data-lucide="trash-2" class="me-2 icon-sm opacity-50"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
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