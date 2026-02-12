@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="fw-bold mb-0">Developer Documentation</h5>
            <p class="text-muted small">Integrate the License System into your WordPress plugins or PHP applications.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">API Endpoints</h6>
                    <div class="mb-4">
                        <label class="small text-muted d-block mb-1">Base URL</label>
                        <code class="bg-light p-2 rounded d-block mb-2">{{ url('/api/v1') }}</code>
                        <div class="alert alert-warning border-0 small p-2 mb-0">
                            <strong>Security:</strong> All requests must include the <code>X-API-Key</code> header.
                        </div>
                    </div>

                    <ul class="nav flex-column gap-2 small fw-medium">
                        <li><a href="#activate" class="text-decoration-none"><i class="fa fa-plug me-2"></i> License
                                Activation</a></li>
                        <li><a href="#validate" class="text-decoration-none"><i class="fa fa-check-circle me-2"></i> License
                                Validation</a></li>
                        <li><a href="#deactivate" class="text-decoration-none"><i class="fa fa-times-circle me-2"></i>
                                License Deactivation</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Activation Section -->
            <section id="activate" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-primary-dark">License Activation</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <p class="small text-muted">Use this endpoint to register a domain for a license key.</p>
                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-light">POST /activate</code>
                    </div>

                    <h6 class="small fw-bold mb-2">Request Parameters</h6>
                    <table class="table table-sm small mb-4">
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

                    <h6 class="small fw-bold mb-2">PHP Implementation Example</h6>
                    <pre class="bg-light p-3 rounded-8 small"><code>function activate_license($key, $domain) {
                $response = wp_remote_post('{{ url('/api/v1/activate') }}', [
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
            </section>

            <!-- Validation Section -->
            <section id="validate" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-primary-dark">License Validation</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <p class="small text-muted">Verify if the current domain is still authorized.</p>
                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-light">POST /validate</code>
                    </div>

                    <h6 class="small fw-bold mb-2">PHP Implementation Example</h6>
                    <pre class="bg-light p-3 rounded-8 small"><code>function validate_license($key, $domain) {
                $response = wp_remote_post('{{ url('/api/v1/validate') }}', [
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
                    // License is OK
                    return true;
                }

                return false;
            }</code></pre>
                </div>
            </section>

            <!-- Deactivation Section -->
            <section id="deactivate" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-primary-dark">License Deactivation</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <p class="small text-muted">Remove a domain registration from a license.</p>
                    <div class="bg-dark rounded p-3 mb-4">
                        <code class="text-light">POST /deactivate</code>
                    </div>

                    <div class="alert alert-info border-0 rounded-8 small">
                        <i class="fa fa-info-circle me-1"></i> Use this when the user uninstalls your application to free up
                        a slot.
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection