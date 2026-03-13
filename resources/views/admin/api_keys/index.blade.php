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
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                        <div>
                            <h6 class="card-title mb-0">API Keys</h6>
                            <p class="text-muted small d-block d-md-none mt-1">Manage your access tokens</p>
                        </div>
                        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm w-100 w-md-auto" data-bs-toggle="modal"
                            data-bs-target="#generateKeyModal">
                            <i class="btn-icon-prepend" data-lucide="plus"></i>
                            Generate New API Key
                        </button>
                    </div>

                    <div id="apiKeyAlertArea"></div>

                    @if(Session::has('api_key'))
                        <div id="sessionApiKeyAlert" class="alert alert-warning border-start border-4 border-warning shadow-sm mb-4">
                            <div class="d-flex">
                                <div class="py-1"><i data-lucide="alert-triangle" class="text-warning me-2"></i></div>
                                <div class="flex-grow-1">
                                    <p class="fw-bold text-dark mb-1">New API Key Generated!</p>
                                    <p class="text-dark small mb-2">Copy this key now. For security purposes, we will
                                        <strong>never show it again</strong>.
                                    </p>
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light fw-mono font-monospace" id="newApiKey"
                                            value="{{ session('api_key') }}" readonly>
                                        <button class="btn btn-outline-primary" type="button" id="copySessionKeyBtn">
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
                            <tbody id="apiKeysTableBody">
                                @foreach($apiKeys as $key)
                                    <tr id="apiKeyRow-{{ $key->id }}">
                                        <td data-label="Name">
                                            <div class="fw-bold text-primary">{{ $key->name }}</div>
                                        </td>
                                        <td data-label="Fingerprint">
                                            <code
                                                class="text-muted small">{{ substr($key->key_hash, 0, 8) }}****{{ substr($key->key_hash, -8) }}</code>
                                        </td>
                                        <td data-label="Status">
                                            @if($key->is_active)
                                                <span class="badge bg-success-subtle text-success">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td data-label="Created At">
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
                                                            <form class="apiKeyFormAction" action="{{ route('admin.api-keys.destroy', $key) }}" method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="dropdown-item py-2 text-warning">
                                                                    <i data-lucide="shield-off" class="me-2 icon-sm opacity-50"></i> Deactivate
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form class="apiKeyFormAction" action="{{ route('admin.api-keys.activate', $key) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item py-2 text-success">
                                                                    <i data-lucide="shield-check" class="me-2 icon-sm opacity-50"></i> Reactivate
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    <li><hr class="dropdown-divider"></li>
                                                    
                                                    <li>
                                                        <form class="apiKeyFormDelete" action="{{ route('admin.api-keys.permanent-delete', $key) }}" method="POST">
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
                <form id="createApiKeyForm" action="{{ route('admin.api-keys.store') }}" method="POST">
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
                        <input type="text" class="form-control bg-dark fw-mono font-monospace" id="revealedApiKey"
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

@endsection

@section('scripts')
    <script>
<<<<<<< HEAD
        (function ($) {

            "use strict";

            if (typeof jQuery === "undefined") {
                console.error("jQuery not loaded");
                return;
            }

            async function copyToClipboard(inputId, btnEl) {
                const input = document.getElementById(inputId);
                if (!input || !input.value) return;

                try {
                    await navigator.clipboard.writeText(input.value);
                    
                    const originalContent = btnEl.innerHTML;
                    const originalClass = btnEl.className;
                    
                    btnEl.innerHTML = '<i data-lucide="check" class="me-1" style="width: 14px; height: 14px;"></i> Copied!';
                    btnEl.classList.remove('btn-outline-primary');
                    btnEl.classList.add('btn-success');
                    
                    if (window.lucide) lucide.createIcons();

                    setTimeout(() => {
                        btnEl.innerHTML = originalContent;
                        btnEl.className = originalClass;
                        if (window.lucide) lucide.createIcons();
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy: ', err);
                    alert('Failed to copy to clipboard');
                }
            }

            $(function() {
                // Session Copy
                const sessionCopyBtn = document.getElementById('copySessionKeyBtn');
                if (sessionCopyBtn) {
                    sessionCopyBtn.addEventListener('click', function() {
                        copyToClipboard('newApiKey', this);
                    });
                }

                // Helper to build row HTML
=======
        function copyToClipboard(id) {
            var copyText = document.getElementById(id);
            if (!copyText) return;
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("API Key copied to clipboard!");
        }

        document.addEventListener('DOMContentLoaded', function () {
            "use strict";
            const $ = window.jQuery;
            if (!$) {
                console.error("jQuery is required but not loaded properly.");
                return;
            }

            // Helper to build row HTML
>>>>>>> 1b5aae3 (fix-reveal-key)
                function buildApiKeyRow(key, isSuperAdmin) {
                    const fingerprint = key.key_hash.substring(0, 8) + '****' + key.key_hash.substring(key.key_hash.length - 8);
                    const statusBadge = key.is_active 
                        ? '<span class="badge bg-success-subtle text-success">Active</span>' 
                        : '<span class="badge bg-danger-subtle text-danger">Inactive</span>';
                    
                    const createdAt = new Date(key.created_at).toLocaleString('en-US', {
                        month: 'short', day: '2-digit', year: 'numeric', 
                        hour: '2-digit', minute: '2-digit', hour12: false
                    }).replace(',', '');

                    let revealButton = '';
                    if (key.is_active && isSuperAdmin) {
                        revealButton = `
                            <li>
                                <button type="button" class="dropdown-item py-2 reveal-key-btn" data-id="${key.id}">
                                    <i data-lucide="eye" class="me-2 icon-sm opacity-50"></i> Reveal Key
                                </button>
                            </li>
                        `;
                    }

                    const statusAction = key.is_active
                        ? `
                            <li>
                                <form class="apiKeyFormAction" action="/admin/api-keys/${key.id}" method="POST">
                                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="dropdown-item py-2 text-warning">
                                        <i data-lucide="shield-off" class="me-2 icon-sm opacity-50"></i> Deactivate
                                    </button>
                                </form>
                            </li>
                        `
                        : `
                            <li>
                                <form class="apiKeyFormAction" action="/admin/api-keys/${key.id}/activate" method="POST">
                                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                    <button type="submit" class="dropdown-item py-2 text-success">
                                        <i data-lucide="shield-check" class="me-2 icon-sm opacity-50"></i> Reactivate
                                    </button>
                                </form>
                            </li>
                        `;

                    return `
                        <tr id="apiKeyRow-${key.id}">
                            <td data-label="Name"><div class="fw-bold text-primary">${key.name}</div></td>
                            <td data-label="Fingerprint"><code class="text-muted small">${fingerprint}</code></td>
                            <td data-label="Status">${statusBadge}</td>
                            <td data-label="Created At">${createdAt}</td>
                            <td class="px-4 py-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle shadow-none border-0"
                                        type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                        ${revealButton}
                                        ${statusAction}
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form class="apiKeyFormDelete" action="/admin/api-keys/${key.id}/permanent" method="POST">
                                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="dropdown-item py-2 text-danger">
                                                    <i data-lucide="trash-2" class="me-2 icon-sm opacity-50"></i> Delete Permanently
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                }

                // AJAX Create
                $(document).off('submit', '#createApiKeyForm').on('submit', '#createApiKeyForm', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let btn = form.find('button[type=submit]');
                    let modal = bootstrap.Modal.getInstance(document.getElementById('generateKeyModal'));

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        beforeSend: function() {
                            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Generating...');
                        },
                        success: function(res) {
                            if(res.success) {
                                form[0].reset();
                                modal.hide();
                                $('#sessionApiKeyAlert').remove();
                                
                                const alertHtml = `
                                    <div class="alert alert-warning border-start border-4 border-warning shadow-sm mb-4">
                                        <div class="d-flex">
                                            <div class="py-1"><i data-lucide="alert-triangle" class="text-warning me-2"></i></div>
<<<<<<< HEAD
                                            <div class="flex-grow-1">
=======
                                            <div>
>>>>>>> 1b5aae3 (fix-reveal-key)
                                                <p class="fw-bold text-dark mb-1">New API Key Generated!</p>
                                                <p class="text-dark small mb-2">Copy this key now. For security purposes, we will <strong>never show it again</strong>.</p>
                                                <div class="input-group">
                                                    <input type="text" class="form-control bg-light fw-mono font-monospace" id="dynamicApiKey" value="${res.api_key}" readonly>
<<<<<<< HEAD
                                                    <button class="btn btn-outline-primary" type="button" id="copyDynamicKeyBtn">
=======
                                                    <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('dynamicApiKey')">
>>>>>>> 1b5aae3 (fix-reveal-key)
                                                        <i data-lucide="copy" class="me-1" style="width: 14px; height: 14px;"></i> Copy
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('#apiKeyAlertArea').html(alertHtml);
                                
<<<<<<< HEAD
                                document.getElementById('copyDynamicKeyBtn').addEventListener('click', function() {
                                    copyToClipboard('dynamicApiKey', this);
                                });

=======
>>>>>>> 1b5aae3 (fix-reveal-key)
                                const newRow = buildApiKeyRow(res.data, {{ Auth::user()->isSuperAdmin() ? 'true' : 'false' }});
                                $('#apiKeysTableBody').prepend(newRow);
                                if (window.lucide) lucide.createIcons();
                            }
                        },
                        error: function(xhr) {
                            alert('Failed to generate API Key');
                            console.error(xhr.responseText);
                        },
                        complete: function() {
                            btn.prop('disabled', false).text('Generate Key');
                        }
                    });
                });

                // AJAX Status Toggle
                $(document).off('submit', '.apiKeyFormAction').on('submit', '.apiKeyFormAction', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let row = form.closest('tr');

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        success: function(res) {
                            if(res.success) {
                                const updatedRow = buildApiKeyRow(res.data, {{ Auth::user()->isSuperAdmin() ? 'true' : 'false' }});
                                row.replaceWith(updatedRow);
                                if (window.lucide) lucide.createIcons();
                            }
                        }
                    });
                });

                // AJAX Delete
                $(document).off('submit', '.apiKeyFormDelete').on('submit', '.apiKeyFormDelete', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let row = form.closest('tr');

                    if(confirm('Are you sure you want to permanently delete this API key? This action cannot be undone.')) {
                        $.ajax({
                            url: form.attr('action'),
                            method: 'POST',
                            data: form.serialize(),
                            success: function(res) {
                                if(res.success) {
                                    row.fadeOut(300, function() { $(this).remove(); });
                                }
                            }
                        });
                    }
                });

<<<<<<< HEAD
                // Reveal logic
                const revealModalEl = document.getElementById('revealKeyModal');
                const revealModal = bootstrap.Modal.getOrCreateInstance(revealModalEl);
                const revealedInput = document.getElementById('revealedApiKey');
                const timerSpan = document.getElementById('revealTimer');
                const copyBtn = document.getElementById('copyRevealedKeyBtn');
                let countdownInterval = null;

                $(document).on('click', '.reveal-key-btn', function () {
                    const btn = $(this);
                    const keyId = btn.data('id');
                    if (!keyId) return;

                    btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/api-keys/${keyId}/reveal`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data && data.success) {
                                revealedInput.value = data.key || '';
                                revealModal.show();
                                startCountdown(30);
                            } else {
                                alert(data.message || 'Failed to reveal key');
                            }
                        },
                        error: function () {
                            alert('Server error while revealing key');
                        },
                        complete: function () {
                            btn.prop('disabled', false);
                        }
                    });
                });

                function startCountdown(seconds = 30) {
                    clearInterval(countdownInterval);
                    let timeLeft = seconds;
                    timerSpan.textContent = timeLeft;

                    countdownInterval = setInterval(() => {
                        timeLeft--;
                        timerSpan.textContent = timeLeft;
                        if (timeLeft <= 0) {
                            clearInterval(countdownInterval);
                            revealedInput.value = '';
                            revealModal.hide();
                        }
                    }, 1000);
                }

               if (copyBtn) {
                    copyBtn.addEventListener('click', function () {
                        copyToClipboard('revealedApiKey', this);
                    });
                }

                revealModalEl.addEventListener('hidden.bs.modal', function(){
                    clearInterval(countdownInterval);
                    revealedInput.value = '';
                    timerSpan.textContent = 30;
                });
            });
        })(jQuery);
=======
                // Reveal logic (existing with minor adjustments for delegation)
                const revealModalElement = document.getElementById('revealKeyModal');
                if (revealModalElement) {
                    const revealModal = new bootstrap.Modal(revealModalElement);
                    const revealedInput = document.getElementById('revealedApiKey');
                    const timerSpan = document.getElementById('revealTimer');
                    const copyBtn = document.getElementById('copyRevealedKeyBtn');
                    let countdownInterval;

                    $(document).on('click', '.reveal-key-btn', function () {
                        const keyId = this.dataset.id;
                        $.ajax({
                            url: `/admin/api-keys/${keyId}/reveal`,
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function(data) {
                                if (data.success) {
                                    revealedInput.value = data.key;
                                    revealModal.show();
                                    startCountdown();
                                } else {
                                    alert(data.message || 'Failed to reveal key');
                                }
                            }
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

                    if (copyBtn) {
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
                    }

                    revealModalElement.addEventListener('hidden.bs.modal', function () {
                        clearInterval(countdownInterval);
                        revealedInput.value = '';
                    });
                }
            });
>>>>>>> 1b5aae3 (fix-reveal-key)
    </script>
    <style>
        @media (max-width: 767.98px) {
            .table-responsive {
                overflow-x: visible !important;
                border: 0;
            }
            .table thead {
                display: none;
            }
            .table tr {
                display: block;
                margin-bottom: 1.25rem;
                border: 1px solid var(--bs-border-color);
                border-radius: 0.75rem;
                padding: 1.25rem;
                background: rgba(var(--bs-dark-rgb), 0.15);
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            }
            .table td {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                border: 0;
                padding: 0.6rem 0;
                text-align: left;
                width: 100%;
            }
            .table td:before {
                content: attr(data-label);
                font-weight: 700;
                text-transform: uppercase;
                font-size: 0.65rem;
                letter-spacing: 0.8px;
                color: var(--bs-primary);
                margin-bottom: 0.25rem;
                opacity: 0.8;
            }
            .table td:last-child {
                border-top: 1px solid var(--bs-border-color);
                padding-top: 1rem;
                margin-top: 0.5rem;
                display: block;
                text-align: right;
            }
            .table td:last-child:before {
                display: none;
            }
        }
    </style>
@endsection