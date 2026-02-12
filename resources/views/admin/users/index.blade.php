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
                        <i class="fa fa-search text-muted"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-4 col-lg-4 text-center text-lg-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fa fa-plus me-1"></i> Add User
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
                    <tbody>
                        @foreach($users as $user)
                            <tr>
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
                                <td class="py-3 fw-bold text-primary-dark">
                                    {{ $user->license_quota }}
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
                                            type="button" data-bs-toggle="dropdown">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.users.edit', $user) }}"><i
                                                        class="fa fa-edit me-2 opacity-50"></i> Edit</a></li>
                                            @if($user->isDistributor())
                                                <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#quotaModal{{ $user->id }}"><i
                                                            class="fa fa-plus me-2 opacity-50"></i> Add Quota</a></li>
                                            @endif
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    onsubmit="return confirm('Delete this user?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"><i
                                                            class="fa fa-trash me-2 opacity-50"></i> Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    @if($user->isDistributor())
                                        <!-- Quota Modal -->
                                        <div class="modal fade" id="quotaModal{{ $user->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <form action="{{ route('admin.users.quota', $user) }}" method="POST">
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
@endsection