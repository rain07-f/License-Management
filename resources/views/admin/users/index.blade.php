@extends('layouts.admin')

@section('content')
    <div class="row mb-4 align-items-center g-3">
        <div class="col-12 col-lg-4 text-center text-lg-start">
            <h5 class="fw-bold mb-0">User Management</h5>
        </div>
        <div class="col-12 col-md-8 col-lg-4">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="input-group shadow-sm rounded-pill">
                    <input type="text" name="search" class="form-control rounded-pill-start border-0 ps-4"
                        placeholder="Search by name or email..." value="{{ request('search') }}">
                    <button class="btn btn-white rounded-pill-end border-0 pe-4" type="submit">
                        <i data-lucide="search" class="text-muted icon-sm"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-4 col-lg-4 text-center text-lg-end">
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal"
                data-bs-target="#quickAddUserModal">
                <i data-lucide="plus" class="me-1 icon-sm"></i> Quick Add
            </button>
            <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm ms-2">
                <i data-lucide="user-plus" class="me-1 icon-sm"></i> Full Form
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">User</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Parent</th>
                            <th class="py-3">Quota</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @foreach($users as $user)
                            <tr id="userRow-{{ $user->id }}">
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle text-primary-dark rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span
                                        class="badge {{ $user->isSuperAdmin() ? 'bg-danger-subtle text-danger' : ($user->isDistributor() ? 'badge-suspended' : 'badge-active') }} text-capitalize">
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </td>
                                <td class="py-3 text-muted">
                                    {{ $user->parent->name ?? 'None' }}
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-dark text-white fw-bold rounded-pill px-3">
                                        {{ $user->license_quota }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span
                                        class="badge {{ $user->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} text-capitalize">
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle shadow-none border-0"
                                            type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.users.edit', $user) }}"><i
                                                        data-lucide="edit-2" class="me-2 icon-sm opacity-50"></i> Edit</a></li>
                                            @if($user->isDistributor())
                                                <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#quotaModal{{ $user->id }}"><i data-lucide="plus"
                                                            class="me-2 icon-sm opacity-50"></i> Add Quota</a></li>
                                            @endif
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form class="userDeleteForm" action="{{ route('admin.users.destroy', $user) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"><i
                                                            data-lucide="trash-2" class="me-2 icon-sm opacity-50"></i>
                                                        Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    @if($user->isDistributor())
                                        <!-- Quota Modal -->
                                        <div class="modal fade" id="quotaModal{{ $user->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <form class="userQuotaForm" action="{{ route('admin.users.quota', $user) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-header border-0 bg-light">
                                                            <h5 class="modal-title fw-bold">Add Quota to {{ $user->name }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-medium">License Count to
                                                                    Add</label>
                                                                <input type="number" name="quota" class="form-control"
                                                                    placeholder="e.g. 100" required min="1">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 p-4 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-4"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-primary rounded-pill px-4 shadow-sm">Add
                                                                Quota</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Quick Add User Modal -->
    <div class="modal fade" id="quickAddUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="quickAddUserForm" action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 bg-dark">
                        <h5 class="modal-title fw-bold">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-medium">Role</label>
                                <select name="role" id="quickRoleSelector" class="form-select" required>
                                    <option value="distributor">Distributor</option>
                                    <option value="client">Client</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="quickParentCol">
                                <label class="form-label small fw-medium">Affiliated Distributor</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">None</option>
                                    @foreach($distributors as $dist)
                                        <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3" id="quickQuotaCol">
                            <label class="form-label small fw-medium">Allocated License Quota</label>
                            <input type="number" name="license_quota" class="form-control" value="0" min="0">
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Helper to build row HTML
                function buildUserRow(user) {
                    const roleBadgeClass = user.role === 'super_admin' ? 'bg-danger-subtle text-danger' : (user.role === 'distributor' ? 'badge-suspended' : 'badge-active');
                    const roleLabel = user.role.replace('_', ' ');
                    const parentName = user.parent ? user.parent.name : 'None';
                    const statusBadgeClass = user.status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';

                    let quotaAction = '';
                    if (user.role === 'distributor') {
                        quotaAction = `
                                                <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#quotaModal${user.id}"><i data-lucide="plus"
                                                            class="me-2 icon-sm opacity-50"></i> Add Quota</a></li>
                                            `;
                    }

                    return `
                                            <tr id="userRow-${user.id}">
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary-subtle text-primary-dark rounded-circle d-flex align-items-center justify-content-center me-3"
                                                            style="width: 40px; height: 40px;">
                                                            ${user.name.charAt(0)}
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 fw-semibold">${user.name}</h6>
                                                            <small class="text-muted">${user.email}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <span class="badge ${roleBadgeClass} text-capitalize">${roleLabel}</span>
                                                </td>
                                                <td class="py-3 text-muted">${parentName}</td>
                                                <td class="py-3">
                                                    <span class="badge bg-dark text-white fw-bold rounded-pill px-3">${user.license_quota}</span>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <span class="badge ${statusBadgeClass} text-capitalize">${user.status}</span>
                                                </td>
                                                <td class="px-4 py-3 text-end">
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle shadow-none border-0"
                                                            type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                                            <li><a class="dropdown-item py-2" href="/admin/users/${user.id}/edit"><i
                                                                        data-lucide="edit-2" class="me-2 icon-sm opacity-50"></i> Edit</a></li>
                                                            ${quotaAction}
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form class="userDeleteForm" action="/admin/users/${user.id}" method="POST">
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
                $(document).on('submit', '#quickAddUserForm', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    let btn = form.find('button[type=submit]');
                    let modal = bootstrap.Modal.getInstance(document.getElementById('quickAddUserModal'));

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
                                const newRow = buildUserRow(res.data);
                                $('#usersTableBody').prepend(newRow);
                                if (window.lucide) lucide.createIcons();
                            }
                        },
                        error: function (xhr) {
                            alert('Failed to create user. Check email uniqueness.');
                        },
                        complete: function () {
                            btn.prop('disabled', false).text('Create User');
                        }
                    });
                });

                // AJAX Quota
                $(document).on('submit', '.userQuotaForm', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    let row = $(`#userRow-${form.prop('action').split('/').pop()}`);
                    let modal = form.closest('.modal');
                    let bootstrapModal = bootstrap.Modal.getInstance(modal[0]);

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        success: function (res) {
                            if (res.success) {
                                bootstrapModal.hide();
                                const updatedRow = buildUserRow(res.data);
                                $(`#userRow-${res.data.id}`).replaceWith(updatedRow);
                                if (window.lucide) lucide.createIcons();
                            }
                        }
                    });
                });

                // AJAX Delete
                $(document).on('submit', '.userDeleteForm', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    let row = form.closest('tr');

                    if (confirm('Are you sure you want to delete this user?')) {
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

                // Role selector logic for quick add
                $('#quickRoleSelector').on('change', function () {
                    if ($(this).val() === 'client') {
                        $('#quickQuotaCol').hide();
                    } else {
                        $('#quickQuotaCol').show();
                    }
                });
            });
        </script>
    @endpush
@endsection