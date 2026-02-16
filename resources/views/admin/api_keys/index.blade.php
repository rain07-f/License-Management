@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">API Key Management</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="card-title mb-0">API Keys</h6>
                        <button type="button" class="btn btn-primary btn-icon-text" data-bs-toggle="modal"
                            data-bs-target="#generateKeyModal">
                            <i class="btn-icon-prepend" data-lucide="plus"></i>
                            Generate New API Key
                        </button>
                    </div>

                    @if(Session::has('api_key'))
                        <div class="alert alert-warning border-start border-4 border-warning shadow-sm mb-4">
                            <div class="d-flex">
                                <div class="py-1"><i data-lucide="alert-triangle" class="text-warning me-2"></i></div>
                                <div>
                                    <p class="fw-bold text-dark mb-1">New API Key Generated!</p>
                                    <p class="text-dark small mb-2">Copy this key now. For security purposes, we will
                                        <strong>never show it again</strong>.
                                    </p>
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light fw-mono font-monospace" id="newApiKey"
                                            value="{{ session('api_key') }}" readonly>
                                        <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard()">
                                            <i data-lucide="copy" class="me-1" style="width: 14px; height: 14px;"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Fingerprint (Last 8 of Hash)</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($apiKeys as $key)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-primary">{{ $key->name }}</div>
                                        </td>
                                        <td>
                                            <code
                                                class="text-muted small">{{ substr($key->key_hash, 0, 8) }}****{{ substr($key->key_hash, -8) }}</code>
                                        </td>
                                        <td>
                                            @if($key->is_active)
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $key->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle shadow-none border-0"
                                                    type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                                    @if($key->is_active && Auth::user()->isSuperAdmin())
                                                        <li>
                                                            <button type="button" class="dropdown-item py-2 reveal-key-btn" data-id="{{ $key->id }}">
                                                                <i data-lucide="eye" class="me-2 icon-sm opacity-50"></i> Reveal Key
                                                            </button>
                                                        </li>
                                                    @endif

                                                    @if($key->is_active)
                                                        <li>
                                                            <form action="{{ route('admin.api-keys.destroy', $key) }}" method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="dropdown-item py-2 text-warning">
                                                                    <i data-lucide="shield-off" class="me-2 icon-sm opacity-50"></i> Deactivate
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form action="{{ route('admin.api-keys.activate', $key) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item py-2 text-success">
                                                                    <i data-lucide="shield-check" class="me-2 icon-sm opacity-50"></i> Reactivate
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    <li><hr class="dropdown-divider"></li>
                                                    
                                                    <li>
                                                        <form action="{{ route('admin.api-keys.permanent-delete', $key) }}" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to permanently delete this API key? This action cannot be undone.')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="dropdown-item py-2 text-danger">
                                                                <i data-lucide="trash-2" class="me-2 icon-sm opacity-50"></i> Delete Permanently
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
                        {{ $apiKeys->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Key Modal -->
    <div class="modal fade" id="generateKeyModal" tabindex="-1" aria-labelledby="generateKeyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.api-keys.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="generateKeyModalLabel">Generate New API Key</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Key Name (e.g. Master App, WordPress Plugin)</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="Enter a descriptive name">
                        </div>
                        <div class="alert alert-info small">
                            <strong>Note:</strong> API keys are hashed before storage. You will only see the plain key once
                            after generation.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Generate Key</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reveal Key Modal -->
    <div class="modal fade" id="revealKeyModal" tabindex="-1" aria-labelledby="revealKeyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="revealKeyModalLabel">Reveal API Key</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        <i data-lucide="info" class="me-1" style="width: 14px; height: 14px;"></i>
                        For security, this key will be hidden automatically in <span id="revealTimer">30</span> seconds.
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light fw-mono font-monospace" id="revealedApiKey"
                            readonly>
                        <button class="btn btn-outline-primary" type="button" id="copyRevealedKeyBtn">
                            <i data-lucide="copy" class="me-1" style="width: 14px; height: 14px;"></i> Copy
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const revealButtons = document.querySelectorAll('.reveal-key-btn');
            const revealModal = new bootstrap.Modal(document.getElementById('revealKeyModal'));
            const revealedInput = document.getElementById('revealedApiKey');
            const timerSpan = document.getElementById('revealTimer');
            const copyBtn = document.getElementById('copyRevealedKeyBtn');
            let countdownInterval;

            revealButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const keyId = this.dataset.id;

                    fetch(`/admin/api-keys/${keyId}/reveal`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                revealedInput.value = data.key;
                                revealModal.show();
                                startCountdown();
                            } else {
                                alert(data.message || 'Failed to reveal key');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while revealing the key');
                        });
                });
            });

            function startCountdown() {
                clearInterval(countdownInterval);
                let seconds = 30;
                timerSpan.textContent = seconds;

                countdownInterval = setInterval(() => {
                    seconds--;
                    timerSpan.textContent = seconds;
                    if (seconds <= 0) {
                        clearInterval(countdownInterval);
                        revealModal.hide();
                        revealedInput.value = '';
                    }
                }, 1000);
            }

            copyBtn.addEventListener('click', function () {
                revealedInput.select();
                revealedInput.setSelectionRange(0, 99999);
                document.execCommand("copy");

                const originalContent = this.innerHTML;
                this.innerHTML = '<i data-lucide="check" class="me-1" style="width: 14px; height: 14px;"></i> Copied!';
                this.classList.replace('btn-outline-primary', 'btn-success');

                setTimeout(() => {
                    this.innerHTML = originalContent;
                    this.classList.replace('btn-success', 'btn-outline-primary');
                    if (window.lucide) lucide.createIcons();
                }, 2000);
            });

            document.getElementById('revealKeyModal').addEventListener('hidden.bs.modal', function () {
                clearInterval(countdownInterval);
                revealedInput.value = '';
            });
        });

        function copyToClipboard() {
            var copyText = document.getElementById("newApiKey");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("API Key copied to clipboard!");
        }
    </script>
@endsection