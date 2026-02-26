@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Developer Documentation</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-4">
            <div class="card grid-margin stretch-card sticky-top" style="top: 80px;">
                <div class="card-body">
                    <h6 class="card-title">API Endpoints</h6>
                    <div class="mb-4">
                        <label class="tx-11 fw-bolder mb-1 text-uppercase text-muted d-block">Base URL</label>
                        <div class="bg-dark p-2 rounded">
                            <code class="tx-12 text-primary">{{ url('/api/v1') }}</code>
                        </div>
                    </div>
                    <div class="alert alert-warning border-0 d-flex align-items-center mb-4">
                        <i data-lucide="shield-alert" class="icon-md me-2"></i>
                        <span class="tx-12">All requests must include the <code>X-API-Key</code> header.</span>
                    </div>

                    <ul class="nav flex-column ps-0">
                        <li class="nav-item">
                            <a href="#activate" class="nav-link text-body d-flex align-items-center px-0">
                                <i data-lucide="plug" class="icon-sm me-2"></i>
                                <span>Pair Activation</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#validate" class="nav-link text-body d-flex align-items-center px-0">
                                <i data-lucide="check-circle" class="icon-sm me-2"></i>
                                <span>Pair Validation</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#revoke" class="nav-link text-body d-flex align-items-center px-0">
                                <i data-lucide="x-circle" class="icon-sm me-2"></i>
                                <span>Pair Revocation</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="alert alert-fill-info mb-4 d-flex align-items-center">
                <i data-lucide="info" class="icon-md me-3"></i>
                <div>
                    <h6 class="fw-bolder mb-1">Dynamic Quota Policy</h6>
                    <p class="tx-12 mb-0">Activations are counted dynamically based on <strong>Active</strong> pairs.
                        Revoking an activation frees up the slot for a new domain or device. Revoked pairs can be
                        re-activated without consuming additional permanent slots.</p>
                </div>
            </div>

            <!-- Activation Section -->
            <section id="activate" class="card grid-margin stretch-card">
                <div class="card-body">
                    <h6 class="card-title text-primary">Pair Activation</h6>
                    <p class="text-secondary tx-13 mb-3">Bind a specific domain and device UID to a license key. This
                        consumes 1 activation slot.</p>

                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-white text-uppercase">POST</code> <code
                            class="text-primary-light ms-2">/license-pair/activate</code>
                    </div>

                    <h6 class="tx-14 fw-bolder mb-2">Request Parameters</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th style="width: 15%;">Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>license_key</code></td>
                                    <td>String</td>
                                    <td>The plain license key (e.g. <code>LIC07-XXXX-...</code>).</td>
                                </tr>
                                <tr>
                                    <td><code>domain</code></td>
                                    <td>String</td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">Optional</span><br>
                                        The domain name. If omitted, detected via <code>Referer</code> or
                                        <code>Origin</code> headers.
                                    </td>
                                </tr>
                                <tr>
                                    <td><code>device_uid</code></td>
                                    <td>String</td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">Optional</span><br>
                                        Unique hardware identifier. If omitted, a deterministic fingerprint is generated
                                        from <code>IP + UA</code>.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="tx-14 fw-bolder mb-2">PHP Implementation Example</h6>
                    <div class="bg-dark p-3 rounded">
                        <pre class="mb-0"><code>function activate_license($key, $domain, $device_uid) {
                $response = wp_remote_post('{{ url('/api/v1/license-pair/activate') }}', [
                    'headers' => [
                        'X-API-Key' => 'YOUR_SECRET_KEY'
                    ],
                    'body' => [
                        'license_key' => $key,
                        'domain'      => $domain,
                        'device_uid'  => $device_uid
                    ]
                ]);

                if (is_wp_error($response)) return false;

                $body = json_decode(wp_remote_retrieve_body($response), true);
                return $body['success'] ?? false;
            }</code></pre>
                    </div>
                </div>
            </section>

            <!-- Validation Section -->
            <section id="validate" class="card grid-margin stretch-card">
                <div class="card-body">
                    <h6 class="card-title text-primary">Pair Validation</h6>
                    <p class="text-secondary tx-13 mb-3">Verify if the specific Domain + Device UID pair is currently active
                        for a license.</p>

                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-white text-uppercase">POST</code> <code
                            class="text-primary-light ms-2">/license-pair/validate</code>
                    </div>

                    <h6 class="tx-14 fw-bolder mb-2">PHP Implementation Example</h6>
                    <div class="bg-dark p-3 rounded">
                        <pre class="mb-0"><code>function validate_license($key, $domain, $device_uid) {
                $response = wp_remote_post('{{ url('/api/v1/license-pair/validate') }}', [
                    'headers' => [
                        'X-API-Key' => 'YOUR_SECRET_KEY'
                    ],
                    'body' => [
                        'license_key' => $key,
                        'domain'      => $domain,
                        'device_uid'  => $device_uid
                    ]
                ]);

                $body = json_decode(wp_remote_retrieve_body($response), true);

                return (isset($body['success']) && $body['success']);
            }</code></pre>
                    </div>
                </div>
            </section>

            <!-- Deactivation Section -->
            <section id="revoke" class="card grid-margin stretch-card">
                <div class="card-body">
                    <h6 class="card-title text-primary">Pair Revocation</h6>
                    <p class="text-secondary tx-13 mb-3">Remotely revoke an active pair. **Note**: This frees up 1
                        activation slot on the license, allowing for new activations.</p>

                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-white text-uppercase">POST</code> <code
                            class="text-primary-light ms-2">/license-pair/revoke</code>
                    </div>

                    <div class="alert alert-fill-info d-flex align-items-center">
                        <i data-lucide="info" class="icon-md me-2"></i>
                        <span class="tx-12">Use this when a user cancels their subscription or manually unbinds from your
                            app settings.</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection