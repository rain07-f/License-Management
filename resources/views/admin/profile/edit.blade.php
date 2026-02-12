@extends('layouts.admin')

@section('content')
    <div class="row mb-5 align-items-center">
        <div class="col-md-12">
            <h2 class="fw-bold text-primary-dark mb-1">Account Synthesis</h2>
            <p class="text-muted mb-0">Manage your administrative identity and security parameters.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-16 overflow-hidden">
                <div class="card-body p-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <div class="avatar-wrapper rounded-circle border-4 border-white shadow-sm overflow-hidden"
                            style="width: 150px; height: 150px; background: #f8f9fa;">
                            <img id="profile-avatar-preview"
                                src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4B49AC&color=fff&size=150' }}"
                                class="w-100 h-100 object-fit-cover shadow-sm" alt="Avatar">
                        </div>
                        <label for="avatar-input"
                            class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 shadow-sm border-2 border-white d-flex align-items-center justify-content-center"
                            style="width: 36px; height: 36px; cursor: pointer;">
                            <i class="fa fa-camera"></i>
                        </label>
                        <input type="file" id="avatar-input" class="d-none" accept="image/*">
                    </div>

                    <h4 class="fw-bold mb-1">{{ $user->full_name ?? $user->name }}</h4>
                    <div class="mb-3">
                        @php
                            $roleColors = [
                                'super_admin' => 'badge-soft-danger',
                                'distributor' => 'badge-soft-primary',
                                'client' => 'badge-soft-success'
                            ];
                        @endphp
                        <span
                            class="badge {{ $roleColors[$user->role] ?? 'badge-soft-dark' }} px-3 py-2 rounded-pill text-uppercase fw-bold letter-spacing-1 small">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </div>
                    <p class="text-muted small mb-0">Member since {{ $user->created_at->format('M d, Y') }}</p>
                    <p class="text-muted small">Last Activity:
                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
                </div>
                <div class="card-footer bg-light border-0 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape badge-soft-primary rounded-12 me-3 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Email Protocol</p>
                            <p class="fw-bold mb-0 small">{{ $user->email }}</p>
                        </div>
                    </div>
                    @if($user->phone)
                        <div class="d-flex align-items-center">
                            <div class="icon-shape badge-soft-success rounded-12 me-3 d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-0">Communication</p>
                                <p class="fw-bold mb-0 small">{{ $user->phone }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-16 overflow-hidden">
                <div class="card-header bg-white border-0 p-4">
                    <div class="nav nav-pills custom-pills" id="profile-tabs">
                        <button class="nav-link active rounded-pill px-4 py-2 me-2" data-bs-toggle="pill"
                            data-bs-target="#personal-info">
                            Identity Modules
                        </button>
                        <button class="nav-link rounded-pill px-4 py-2" data-bs-toggle="pill"
                            data-bs-target="#security-settings">
                            Security Layer
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- Personal Info -->
                        <div class="tab-pane fade show active" id="personal-info">
                            <form id="profile-form">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">System
                                            Username</label>
                                        <input type="text" name="name" class="form-control rounded-12 p-3 bg-light border-0"
                                            value="{{ $user->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Full Display
                                            Name</label>
                                        <input type="text" name="full_name"
                                            class="form-control rounded-12 p-3 bg-light border-0"
                                            value="{{ $user->full_name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Primary Email
                                            Address</label>
                                        <input type="email" name="email"
                                            class="form-control rounded-12 p-3 bg-light border-0" value="{{ $user->email }}"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Mobile
                                            Contact</label>
                                        <input type="text" name="phone"
                                            class="form-control rounded-12 p-3 bg-light border-0"
                                            value="{{ $user->phone }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Professional
                                            Affiliation</label>
                                        <input type="text" name="company"
                                            class="form-control rounded-12 p-3 bg-light border-0"
                                            value="{{ $user->company }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Geographic
                                            Infrastructure</label>
                                        <textarea name="address" class="form-control rounded-12 p-3 bg-light border-0"
                                            rows="3">{{ $user->address }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Professional
                                            Synopsis (Bio)</label>
                                        <textarea name="bio" class="form-control rounded-12 p-3 bg-light border-0"
                                            rows="4">{{ $user->bio }}</textarea>
                                    </div>
                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit"
                                            class="btn btn-primary rounded-pill px-5 py-3 shadow-lg border-0 fw-bold">
                                            Update Synchronously
                                            <i class="fa fa-sync ms-2 opacity-50"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Security -->
                        <div class="tab-pane fade" id="security-settings">
                            <form id="security-form">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div
                                            class="alert badge-soft-warning border-0 rounded-12 d-flex align-items-center p-4">
                                            <i class="fa fa-shield-alt fa-2x me-4 opacity-50"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">Cryptographic Security</h6>
                                                <p class="mb-0 small">Updating your password will regenerate active session
                                                    keys and require new authentication tokens.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Current Security
                                            Token (Password)</label>
                                        <input type="password" name="current_password"
                                            class="form-control rounded-12 p-3 bg-light border-0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">New Access
                                            Sequence</label>
                                        <input type="password" name="new_password"
                                            class="form-control rounded-12 p-3 bg-light border-0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Confirm New
                                            Sequence</label>
                                        <input type="password" name="new_password_confirmation"
                                            class="form-control rounded-12 p-3 bg-light border-0">
                                    </div>
                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit"
                                            class="btn btn-dark rounded-pill px-5 py-3 shadow-lg border-0 fw-bold">
                                            Fortify Security
                                            <i class="fa fa-lock ms-2 opacity-50"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar Modal -->
    <div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-20 overflow-hidden">
                <div class="modal-header bg-primary text-white border-0 p-4">
                    <h5 class="modal-title fw-bold">Refine Identity Visual</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="cropper-container" style="max-height: 500px; background: #000;">
                        <img id="cropper-image" src="" style="max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Abort</button>
                    <button type="button" id="save-avatar-btn" class="btn btn-primary rounded-pill px-4 fw-bold">Commit
                        Visual Change</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        .custom-pills .nav-link {
            color: #6c757d;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .custom-pills .nav-link.active {
            background-color: var(--primary-dark) !important;
            color: white !important;
            box-shadow: 0 5px 15px rgba(75, 73, 172, 0.3);
        }

        .form-control:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 3px rgba(75, 73, 172, 0.1);
            border: 1px solid var(--primary-dark) !important;
        }

        .avatar-wrapper {
            transition: transform 0.3s ease;
        }

        .position-relative:hover .avatar-wrapper {
            transform: scale(1.02);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            let cropper;
            const avatarInput = $('#avatar-input');
            const avatarModal = new bootstrap.Modal(document.getElementById('avatarModal'));
            const cropperImage = document.getElementById('cropper-image');

            // Handle File Input
            avatarInput.on('change', function (e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    const file = files[0];
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        cropperImage.src = e.target.result;
                        avatarModal.show();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Initialize Cropper when modal opens
            document.getElementById('avatarModal').addEventListener('shown.bs.modal', function () {
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            });

            // Destroy Cropper when modal closes
            document.getElementById('avatarModal').addEventListener('hidden.bs.modal', function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                avatarInput.val('');
            });

            // Save Avatar
            $('#save-avatar-btn').on('click', function () {
                if (!cropper) return;

                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                });

                canvas.toBlob(function (blob) {
                    const formData = new FormData();
                    formData.append('avatar', blob, 'avatar.png');
                    formData.append('_token', '{{ csrf_token() }}');

                    $('#save-avatar-btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Encrypting...');

                    $.ajax({
                        url: "{{ route('admin.profile.avatar') }}",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#profile-avatar-preview').attr('src', response.avatar_url);
                            avatarModal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Visual Matrix Updated',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Uplink Error',
                                text: xhr.responseJSON.message || 'Verification failure.'
                            });
                        },
                        complete: function () {
                            $('#save-avatar-btn').prop('disabled', false).text('Commit Visual Change');
                        }
                    });
                });
            });

            // Handle Profile Form
            $('#profile-form, #security-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const btn = form.find('button[type="submit"]');
                const originalText = btn.html();

                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Synchronizing...');

                $.ajax({
                    url: "{{ route('admin.profile.update') }}",
                    method: "POST",
                    data: form.serialize(),
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'System Synced',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        if (form.attr('id') === 'security-form') form[0].reset();
                    },
                    error: function (xhr) {
                        let errorMsg = xhr.responseJSON.message || 'Integration failure.';
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Verification Error',
                            html: errorMsg
                        });
                    },
                    complete: function () {
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
@endpush