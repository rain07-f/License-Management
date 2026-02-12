@extends('layouts.admin')

@section('content')
    <div class="row mb-4 align-items-center g-3">
        <div class="col-12 col-lg-4 text-center text-lg-start">
            <h5 class="fw-bold mb-0">Activated Domains</h5>
            <p class="text-muted small mb-0">Monitoring all domains currently using your licenses.</p>
        </div>
        <div class="col-12 col-md-8 col-lg-4 mx-auto">
            <form action="{{ route('admin.domains.index') }}" method="GET">
                <div class="input-group shadow-sm rounded-pill">
                    <input type="text" name="search" class="form-control rounded-pill-start border-0 ps-4"
                        placeholder="Search by domain or license..." value="{{ request('search') }}">
                    <button class="btn btn-white rounded-pill-end border-0 pe-4" type="submit">
                        <i class="fa fa-search text-muted"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Domain Name</th>
                            <th class="py-3">License Key (Hash)</th>
                            <th class="py-3">Owner</th>
                            <th class="py-3">Activated At</th>
                            <th class="py-3">Last Check</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($domains as $domain)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-globe text-accent-blue me-2"></i>
                                        <span class="fw-semibold">{{ $domain->domain_name }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <code
                                        class="small text-primary-dark">{{ substr($domain->license->license_key_hash, 0, 10) }}...</code>
                                </td>
                                <td class="py-3">
                                    <span class="small text-muted">{{ $domain->license->owner->name }}</span>
                                </td>
                                <td class="py-3 small text-muted">
                                    {{ $domain->activated_at ? $domain->activated_at->diffForHumans() : 'N/A' }}
                                </td>
                                <td class="py-3 small text-muted">
                                    {{ $domain->last_check_at ? $domain->last_check_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="badge bg-success-subtle text-success text-capitalize px-3">
                                        {{ $domain->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <form action="{{ route('admin.domains.destroy', $domain) }}" method="POST"
                                        onsubmit="return confirm('Deactivate this domain?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm"
                                            title="Deactivate">
                                            <i class="fa fa-times text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $domains->links() }}
            </div>
        </div>
    </div>
@endsection