@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">{{ $application->name }} Versions</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.applications.index') }}" class="text-secondary text-decoration-none">Applications</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">{{ $application->name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadVersionModal">
                <i data-lucide="upload-cloud" class="me-1 icon-sm"></i> Upload Version
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Version</th>
                            <th class="py-3">Release Notes</th>
                            <th class="py-3 text-center">Requirements</th>
                            <th class="py-3 text-center">File Size</th>
                            <th class="py-3 text-muted">Uploaded At</th>
                            <th class="px-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($application->versions as $version)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 fs-6">{{ $version->version }}</span>
                                </td>
                                <td class="py-3">
                                    <small class="text-secondary text-wrap" style="max-width: 300px; display: inline-block;">{{ Str::limit($version->release_notes, 100) }}</small>
                                </td>
                                <td class="py-3 text-center">
                                    @if($version->min_php_version)
                                        <span class="badge bg-light text-dark mb-1">PHP: {{ $version->min_php_version }}+</span><br>
                                    @endif
                                    @if($version->min_wp_version)
                                        <span class="badge bg-light text-dark">WP: {{ $version->min_wp_version }}+</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center text-muted small">
                                    {{ round($version->file_size / 1048576, 2) }} MB
                                </td>
                                <td class="py-3 text-muted">
                                    {{ $version->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <form action="{{ route('admin.application-versions.destroy', $version) }}" method="POST"
                                        onsubmit="return confirm('Delete this version? This action cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-none">
                                            <i data-lucide="trash-2" class="me-1 icon-sm"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-2"><i data-lucide="upload-cloud" class="icon-lg opacity-50"></i></div>
                                    <h6 class="fw-semibold">No Versions Uploaded</h6>
                                    <p class="small mb-0">Click the upload button to add your first release.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadVersionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.application-versions.store') }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <input type="hidden" name="application_id" value="{{ $application->id }}">
            <div class="modal-header border-0 bg-dark rounded-top-4 pb-0">
                <h5 class="modal-title fw-bold text-white mb-3">Upload New Version</h5>
                <button type="button" class="btn-close btn-close-white mb-3" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-4">
                <div class="mb-3">
                    <label class="form-label fw-medium">Version <span class="text-danger">*</span></label>
                    <input type="text" name="version" class="form-control rounded-pill px-3" required 
                           placeholder="e.g. 1.3.0" />
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Upload Package (.zip) <span class="text-danger">*</span></label>
                    <input type="file" name="package" accept=".zip" class="form-control rounded-pill px-3" required />
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Release Notes</label>
                    <textarea name="release_notes" class="form-control rounded-4 p-3" rows="3" 
                              placeholder="Changes, fixes, new features..."></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label fw-medium">Min PHP Version (Optional)</label>
                        <input type="text" name="min_php_version" class="form-control rounded-pill px-3" placeholder="e.g. 8.1" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Min WP Version (Optional)</label>
                        <input type="text" name="min_wp_version" class="form-control rounded-pill px-3" placeholder="e.g. 6.4" />
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 p-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Upload</button>
            </div>
        </form>
    </div>
</div>
@endsection
