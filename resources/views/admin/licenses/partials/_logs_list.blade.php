<div id="logsListContainer">
    <div class="activity-history-scroll" style="height: 500px;">
        <div class="list-group list-group-flush">
            @forelse($logs as $log)
                <div class="list-group-item px-0 py-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="badge bg-dark text-white rounded-pill px-2 tx-10">{{ $log->action }}</span>
                        <small class="text-muted tx-10">{{ $log->created_at->format('M d, H:i') }}</small>
                    </div>
                    <p class="tx-12 mb-0 fw-medium">{{ $log->domain ?? '-' }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted tx-11">{{ $log->user->name ?? 'System' }}</small>
                        <code class="tx-10 text-muted">{{ $log->ip_address ?? '-' }}</code>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <i data-lucide="info" class="icon-md text-muted mb-2"></i>
                    <p class="tx-12 text-muted">No activity recorded.</p>
                </div>
            @endforelse
        </div>
    </div>
    @if($logs->hasPages())
        <div class="mt-3 py-2 border-top d-flex justify-content-between align-items-center tx-11 text-muted">
            <span>Page <b class="text-secondary">{{ $logs->currentPage() }}</b> of {{ $logs->lastPage() }}</span>
            <div class="d-flex gap-1">
                @if($logs->onFirstPage())
                    <button class="btn btn-icon btn-xs opacity-25" disabled>
                        <i data-lucide="chevron-left" class="icon-xs"></i>
                    </button>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" class="btn btn-icon btn-outline-secondary btn-xs">
                        <i data-lucide="chevron-left" class="icon-xs"></i>
                    </a>
                @endif

                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" class="btn btn-icon btn-outline-secondary btn-xs">
                        <i data-lucide="chevron-right" class="icon-xs"></i>
                    </a>
                @else
                    <button class="btn btn-icon btn-xs opacity-25" disabled>
                        <i data-lucide="chevron-right" class="icon-xs"></i>
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
