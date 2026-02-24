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
                        <div>
                            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#quickAddPlanModal">
                                <i data-lucide="plus" class="me-1 icon-sm"></i> Quick Add
                            </button>
                            <a href="{{ route('admin.plans.create') }}"
                                class="btn btn-outline-primary rounded-pill px-4 shadow-sm ms-2">
                                <i data-lucide="zap" class="me-1 icon-sm"></i> Full Form
                            </a>
                        </div>
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
                            <tbody id="plansTableBody">
                                @foreach($plans as $plan)
                                    <tr id="planRow-{{ $plan->id }}">
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
                                                        <form class="planDeleteForm"
                                                            action="{{ route('admin.plans.destroy', $plan) }}" method="POST">
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

    <!-- Quick Add Plan Modal -->
    <div class="modal fade" id="quickAddPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="quickAddPlanForm" action="{{ route('admin.plans.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 bg-dark">
                        <h5 class="modal-title fw-bold">Add Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Plan Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Premium Monthly" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Brief description..."
                                required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-medium">Price ($)</label>
                                <input type="number" name="price" class="form-control" step="0.01" min="0"
                                    placeholder="0.00" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-medium">Duration (Days)</label>
                                <input type="number" name="duration_days" class="form-control" min="1" placeholder="30"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Domain Limit</label>
                            <input type="number" name="domain_limit" class="form-control" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Create Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Helper to build row HTML
                function buildPlanRow(plan) {
                    const formattedPrice = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(plan.price);

                    return `
                                    <tr id="planRow-${plan.id}">
                                        <td>
                                            <div class="fw-bold text-primary">${plan.name}</div>
                                            <small class="text-secondary d-block mt-1">${plan.description}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bolder fs-5 text-dark">${formattedPrice}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info">${plan.duration_days} Days</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">${plan.domain_limit} Domains</span>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle shadow-none border-0"
                                                    type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                                    <li><a class="dropdown-item py-2" href="/admin/plans/${plan.id}/edit"><i
                                                                data-lucide="edit-2" class="me-2 icon-sm opacity-50"></i> Edit</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form class="planDeleteForm" action="/admin/plans/${plan.id}" method="POST">
                                                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="dropdown-item py-2 text-danger"><i
                                                                    data-lucide="trash-2" class="me-2 icon-sm opacity-50"></i> Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                }

                // AJAX Create
                $(document).on('submit', '#quickAddPlanForm', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    let btn = form.find('button[type=submit]');
                    let modal = bootstrap.Modal.getInstance(document.getElementById('quickAddPlanModal'));

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        beforeSend: function () {
                            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Creating...');
                        },
                        success: function (res) {
                            if (res.success) {
                                form[0].reset();
                                modal.hide();
                                const newRow = buildPlanRow(res.data);
                                $('#plansTableBody').prepend(newRow);
                                if (window.lucide) lucide.createIcons();
                            }
                        },
                        complete: function () {
                            btn.prop('disabled', false).text('Create Plan');
                        }
                    });
                });

                // AJAX Delete
                $(document).on('submit', '.planDeleteForm', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    let row = form.closest('tr');

                    if (confirm('Are you sure you want to delete this plan?')) {
                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function (res) {
                                if (res.success) {
                                    row.fadeOut(300, function () { $(this).remove(); });
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection