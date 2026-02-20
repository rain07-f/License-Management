@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Activated Domains</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="card-title">Activated Domains</h6>
                            <p class="text-secondary tx-13">Monitoring all domains currently using your licenses.</p>
                        </div>
                        <div>
                            <form action="{{ route('admin.domains.index') }}" method="GET" class="search-form">
                                <div class="input-group wd-250">
                                    <span class="input-group-text bg-transparent border-primary">
                                        <i data-lucide="search" class="icon-sm text-primary"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-primary"
                                        placeholder="Search domain..." value="{{ request('search') }}">
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Domain Name</th>
                                    <th>License Key (Hash)</th>
                                    <th>Owner</th>
                                    <th>Activated At</th>
                                    <th>Last Check</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($domains as $domain)
                                    <tr>
                                        <td class="fw-semibold">
                                            <div class="d-flex align-items-center">
                                                <i data-lucide="globe" class="icon-sm text-primary me-2"></i>
                                                {{ $domain->domain_name }}
                                            </div>
                                        </td>
                                        <td>
                                            <code
                                                class="tx-12 text-primary">{{ substr($domain->license->license_key_hash, 0, 10) }}...</code>
                                        </td>
                                        <td>
                                            <span class="text-secondary small">{{ $domain->license->owner->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary small">
                                                {{ $domain->activated_at ? $domain->activated_at->diffForHumans() : 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-secondary small">
                                                {{ $domain->last_check_at ? $domain->last_check_at->diffForHumans() : 'Never' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success px-2 py-1">
                                                {{ ucfirst($domain->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <form class="domainDeactivateForm"
                                                action="{{ route('admin.domains.destroy', $domain) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-icon btn-xs"
                                                    title="Deactivate">
                                                    <i data-lucide="x-circle"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-secondary">
                                                <i data-lucide="info" class="icon-lg mb-2"></i>
                                                <p>No activated domains found matching your criteria.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $domains->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('custom-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // AJAX Deactivate
                $(document).on('submit', '.domainDeactivateForm', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    let row = form.closest('tr');

                    if (confirm('Are you sure you want to deactivate this domain?')) {
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