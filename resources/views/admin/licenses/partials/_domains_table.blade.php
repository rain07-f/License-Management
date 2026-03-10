<div id="domainsTableContainer">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="tx-11 text-uppercase">Domain Name</th>
                    <th class="tx-11 text-uppercase">Activated At</th>
                    <th class="text-end tx-11 text-uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($domains as $domain)
                    <tr>
                        <td class="fw-semibold tx-13">{{ $domain->domain_name }}</td>
                        <td class="tx-12 text-muted">{{ $domain->activated_at->format('M d, Y H:i') }}</td>
                        <td class="text-end">
                            <form class="deactivate-domain-form" action="{{ route('admin.domains.destroy', $domain) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-xs py-1 px-2" title="Deactivate">
                                    <i data-lucide="minus-circle" class="icon-xs me-1"></i> Deactivate
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted tx-12">No active domains found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($domains->hasPages())
        <div class="mt-3 mb-2" id="domainsPagination">
            {{ $domains->onEachSide(1)->links() }}
        </div>
    @endif
</div>
