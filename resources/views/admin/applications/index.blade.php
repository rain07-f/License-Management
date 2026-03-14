@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">Applications</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-secondary text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Applications</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.applications.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i data-lucide="plus" class="me-1 icon-sm"></i> Create Application
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="py-3">Slug</th>
                            <th class="py-3 text-center">VersionsCount</th>
                            <th class="py-3 text-muted">Created At</th>
                            <th class="px-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="applicationsTableBody">
                        @forelse ($applications as $app)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-primary">{{ $app->name }}</div>
                                    <small class="text-secondary d-block mt-1">{{ Str::limit($app->description, 50) }}</small>
                                </td>
                                <td class="py-3 font-monospace small">
                                    {{ $app->slug }}
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-primary rounded-pill px-3">{{ $app->versions_count }}</span>
                                </td>
                                <td class="py-3 text-muted">
                                    {{ $app->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle shadow-none border-0"
                                            type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.applications.show', $app) }}"><i
                                                        data-lucide="eye" class="me-2 icon-sm opacity-50"></i> View / Manage Versions</a></li>
                                            <li><a class="dropdown-item py-2" href="{{ route('admin.applications.edit', $app) }}"><i
                                                        data-lucide="edit-2" class="me-2 icon-sm opacity-50"></i> Edit App</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.applications.destroy', $app) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this application? This deletes all versions too.');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"><i
                                                            data-lucide="trash-2" class="me-2 icon-sm opacity-50"></i> Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted mb-2"><i data-lucide="box" class="icon-lg opacity-50"></i></div>
                                    <h6 class="fw-semibold">No Applications Found</h6>
                                    <p class="small mb-0">Get started by creating your first application.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
