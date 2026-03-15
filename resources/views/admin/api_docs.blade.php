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
                                <span>License Activation</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#validate" class="nav-link text-body d-flex align-items-center px-0">
                                <i data-lucide="check-circle" class="icon-sm me-2"></i>
                                <span>License Validation</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#deactivate" class="nav-link text-body d-flex align-items-center px-0">
                                <i data-lucide="x-circle" class="icon-sm me-2"></i>
                                <span>License Deactivation</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#update-check" class="nav-link text-body d-flex align-items-center px-0">
                                <i data-lucide="refresh-cw" class="icon-sm me-2"></i>
                                <span>Update Check</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#download" class="nav-link text-body d-flex align-items-center px-0">
                                <i data-lucide="download" class="icon-sm me-2"></i>
                                <span>Download Package</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Activation Section -->
            <section id="activate" class="card grid-margin stretch-card">
                <div class="card-body">
                    <h6 class="card-title text-primary">License Activation</h6>
                    <p class="text-secondary tx-13 mb-3">Use this endpoint to register a domain for a license key.</p>

                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-white">POST /license/activate</code>
                    </div>

                    <h6 class="tx-14 fw-bolder mb-2">Request Parameters</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>license_key</code></td>
                                    <td>The plain license key provided to the user.</td>
                                </tr>
                                <tr>
                                    <td><code>domain</code></td>
                                    <td>The domain name (e.g. <code>example.com</code>).</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="tx-14 fw-bolder mb-2">PHP Implementation Example</h6>
                    <div class="bg-dark p-3 rounded text-white">
                        <pre class="mb-0"><code>function activate_license($key, $domain) {
                                    $response = wp_remote_post('{{ url('/api/v1/license/activate') }}', [
                                        'headers' => [
                                            'X-API-Key' => 'YOUR_SECRET_KEY'
                                        ],
                                        'body' => [
                                            'license_key' => $key,
                                            'domain'      => $domain
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
                    <h6 class="card-title text-primary">License Validation</h6>
                    <p class="text-secondary tx-13 mb-3">Verify if the current domain is still authorized.</p>

                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-white">POST /license/validate</code> <span class="text-muted small ms-2">(or
                            <code>/license/active</code>)</span>
                    </div>

                    <h6 class="tx-14 fw-bolder mb-2">PHP Implementation Example</h6>
                    <div class="bg-dark p-3 rounded text-white">
                        <pre class="mb-0"><code>function validate_license($key, $domain) {
                                    $response = wp_remote_post('{{ url('/api/v1/license/validate') }}', [
                                        'headers' => [
                                            'X-API-Key' => 'YOUR_SECRET_KEY'
                                        ],
                                        'body' => [
                                            'license_key' => $key,
                                            'domain'      => $domain
                                        ]
                                    ]);

                                    $body = json_decode(wp_remote_retrieve_body($response), true);

                                    if (isset($body['success']) && $body['success']) {
                                        return true;
                                    }

                                    return false;
                                }</code></pre>
                    </div>
                </div>
            </section>

            <!-- Deactivation Section -->
            <section id="deactivate" class="card grid-margin stretch-card">
                <div class="card-body">
                    <h6 class="card-title text-primary">License Deactivation</h6>
                    <p class="text-secondary tx-13 mb-3">Remove a domain registration from a license.</p>

                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-white">POST /license/deactivate</code>
                    </div>

                    <div class="alert alert-fill-info d-flex align-items-center">
                        <i data-lucide="info" class="icon-md me-2"></i>
                        <span class="tx-12">Use this when the user uninstalls your application to free up a slot.</span>
                    </div>
                </div>
            </section>

            <!-- Update Check Section -->
            <section id="update-check" class="card grid-margin stretch-card">
                <div class="card-body">
                    <h6 class="card-title text-primary">Update Check</h6>
                    <p class="text-secondary tx-13 mb-3">Check if a newer version of the application is available.</p>

                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-white">GET /license/update-check</code>
                    </div>

                    <h6 class="tx-14 fw-bolder mb-2">Request Parameters</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><code>license_key</code></td><td>The user's license key.</td></tr>
                                <tr><td><code>domain</code></td><td>The current domain.</td></tr>
                                <tr><td><code>application_slug</code></td><td>The slug of your application (e.g. <code>my-plugin</code>).</td></tr>
                                <tr><td><code>current_version</code></td><td>The currently installed version (e.g. <code>1.0.0</code>).</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="tx-14 fw-bolder mb-2">Success Response</h6>
                    <div class="bg-dark p-3 rounded text-white mb-4">
                        <pre class="mb-0"><code>{
    "success": true,
    "update_available": true,
    "new_version": "1.2.0",
    "download_url": "https://...",
    "release_notes": "Added new features..."
}</code></pre>
                    </div>
                </div>
            </section>

            <!-- Download Section -->
            <section id="download" class="card grid-margin stretch-card">
                <div class="card-body">
                    <h6 class="card-title text-primary">Download Package</h6>
                    <p class="text-secondary tx-13 mb-3">Download the latest update package securely.</p>

                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-white">GET /license/download</code>
                    </div>

                    <div class="alert alert-fill-warning d-flex align-items-center">
                        <i data-lucide="help-circle" class="icon-md me-2"></i>
                        <span class="tx-12">This endpoint requires the same parameters as Update Check + <code>version</code>.</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection