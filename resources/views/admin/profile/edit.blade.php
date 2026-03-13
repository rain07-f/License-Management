@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">User Profile</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-header border-bottom-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <img id="profile-avatar-preview" class="wd-70 rounded-circle border border-primary border-4"
                                src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4B49AC&color=fff&size=100' }}"
                                alt="profile">
                            <span
                                class="h4 ms-3 fw-bolder tx-20">{{ $user->full_name ?? $user->name }}</span>
                        </div>
                        <div class="d-none d-md-block">
                            <input type="file" id="avatar-input" class="d-none" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center p-3 rounded-bottom">
                    <ul class="d-flex align-items-center justify-content-around w-100 list-unstyled mb-0">
                        <li class="d-flex align-items-center">
                            <i data-lucide="mail" class="icon-md me-2 text-primary"></i>
                            <span class="tx-12 fw-bolder">{{ $user->email }}</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i data-lucide="shield" class="icon-md me-2 text-primary"></i>
                            <span
                                class="badge bg-primary-subtle text-primary text-uppercase">{{ str_replace('_', ' ', $user->role) }}</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i data-lucide="calendar" class="icon-md me-2 text-primary"></i>
                            <span class="tx-12 text-muted">Member since {{ $user->created_at->format('M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row profile-body">
        <!-- left wrapper start -->
        <div class="d-none d-md-block col-md-4 col-xl-3 left-wrapper">
            <div class="card rounded">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="card-title mb-0">About</h6>
                    </div>
                    <p>{{ $user->bio ?? 'No bio information available.' }}</p>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Role:</label>
                        <p class="text-secondary">{{ ucfirst($user->role) }}</p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Joined:</label>
                        <p class="text-secondary">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Lives:</label>
                        <p class="text-secondary">{{ $user->address ?? 'Not specified' }}</p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Email:</label>
                        <p class="text-secondary">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- left wrapper end -->
        <!-- middle wrapper start -->
        <div class="col-md-8 col-xl-9 middle-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-header bg-dark border-bottom-0">
                            <ul class="nav nav-tabs nav-tabs-line" id="lineTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-line-tab" data-bs-toggle="tab" href="#personal-info"
                                        role="tab">Identity Modules</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-line-tab" data-bs-toggle="tab" href="#security-settings"
                                        role="tab">Security Layer</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content mt-3" id="lineTabContent">
                                <div class="tab-pane fade show active" id="personal-info" role="tabpanel">
                                    <form id="profile-form" method="POST">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Username</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ $user->name }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Full Name</label>
                                                <input type="text" name="full_name" class="form-control"
                                                    value="{{ $user->full_name }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Email Address</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ $user->email }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Phone Number</label>
                                                <input type="text" name="phone" class="form-control"
                                                    value="{{ $user->phone }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Organization / Company</label>
                                            <input type="text" name="company" class="form-control"
                                                value="{{ $user->company }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Address / Location</label>
                                            <textarea name="address" class="form-control"
                                                rows="2">{{ $user->address }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Biography</label>
                                            <textarea name="bio" class="form-control" rows="3">{{ $user->bio }}</textarea>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary btn-icon-text text-white">
                                                <i class="btn-icon-prepend" data-lucide="save"></i> Update Profile
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="security-settings" role="tabpanel">
                                    <form id="security-form" method="POST">
                                        @csrf
                                        <div class="alert alert-fill-warning d-flex align-items-center mb-4 border-0">
                                            <i data-lucide="shield" class="icon-sm me-2"></i>
                                            <span>Update your password to enhance account security.</span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <div class="input-group">
                                                <input type="password" name="current_password" id="current_password" class="form-control">
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                                    <i data-lucide="eye" class="icon-sm"></i>
                                                </button>
                                            </div>
                                            <div class="error-current_password text-danger tx-12 mt-1"></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">New Password</label>
                                                <div class="input-group">
                                                    <input type="password" name="password" id="new_password" class="form-control" onkeyup="checkPasswordStrength()">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                                        <i data-lucide="eye" class="icon-sm"></i>
                                                    </button>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="progress mb-1" style="height: 5px;">
                                                        <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                                    </div>
                                                    <div class="tx-11 fw-bold text-uppercase">
                                                        STRENGTH: <span id="password-strength">-</span>
                                                    </div>
                                                </div>
                                                <div class="error-password text-danger tx-12 mt-1"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Confirm New Password</label>
                                                <div class="input-group">
                                                    <input type="password" name="password_confirmation" id="new_password_confirmation" class="form-control">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                                        <i data-lucide="eye" class="icon-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 p-3 bg-light rounded border">
                                            <label class="tx-11 fw-bolder mb-2 text-uppercase text-muted d-block">Password Requirements:</label>
                                            <ul class="tx-12 text-muted mb-0 ps-3">
                                                <li id="req-length">• Minimum 8 characters</li>
                                                <li id="req-upper">• At least 1 uppercase letter</li>
                                                <li id="req-lower">• At least 1 lowercase letter</li>
                                                <li id="req-number">• At least 1 number</li>
                                                <li id="req-symbol">• At least 1 special character</li>
                                            </ul>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-danger btn-icon-text">
                                                <i class="btn-icon-prepend" data-lucide="lock"></i> Update Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar Modal -->
    <div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Refine Identity Visual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="cropper-container" style="max-height: 500px; background: #000;">
                        <img id="cropper-image" src="" style="max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abort</button>
                    <button type="button" id="save-avatar-btn" class="btn btn-primary fw-bold">Commit Visual Change</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
@endpush

@push('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global Utility Functions for Security Layer
        (function() {
            window.togglePassword = function(fieldId) {
                const field = document.getElementById(fieldId);
                if (!field) return;
                
                field.type = field.type === "password" ? "text" : "password";
                
                // Update Icon
                const btn = field.parentElement.querySelector('button');
                const icon = btn ? btn.querySelector('i') : null;
                if (icon) {
                    icon.setAttribute('data-lucide', field.type === "password" ? 'eye' : 'eye-off');
                    if (window.lucide) window.lucide.createIcons();
                }
            };

            window.checkPasswordStrength = function() {
                const passwordInput = document.getElementById("new_password");
                const strengthLabel = document.getElementById("password-strength");
                const bar = document.getElementById("password-strength-bar");
                
                if (!passwordInput || !strengthLabel) return;
                
                const password = passwordInput.value;
                if (!password) {
                    strengthLabel.innerText = "-";
                    if (bar) {
                        bar.style.width = '0%';
                        bar.className = 'progress-bar';
                    }
                    return;
                }

                let score = 0;
                if (password.length >= 8) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[a-z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[^A-Za-z0-9]/.test(password)) score++;

                let strength = "Weak";
                let barClass = 'progress-bar bg-danger';
                let barWidth = Math.max(5, (score * 20)) + '%';
                
                if (score >= 4) {
                    strength = "Strong";
                    barClass = 'progress-bar bg-success';
                    barWidth = '100%';
                } else if (score >= 2) {
                    strength = "Medium";
                    barClass = 'progress-bar bg-warning';
                }

                strengthLabel.innerText = strength;
                if (bar) {
                    bar.className = barClass;
                    bar.style.width = barWidth;
                }

                // Update requirement indicators
                const updateReq = (id, met) => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.className = met ? 'text-success' : 'text-muted';
                    }
                };

                updateReq('req-length', password.length >= 8);
                updateReq('req-upper', /[A-Z]/.test(password));
                updateReq('req-lower', /[a-z]/.test(password));
                updateReq('req-number', /[0-9]/.test(password));
                updateReq('req-symbol', /[^A-Za-z0-9]/.test(password));
            };
        })();

        $(function () {
            'use strict';
            let cropper;
            const avatarInput = $('#avatar-input');
            const avatarModal = new bootstrap.Modal(document.getElementById('avatarModal'));
            const cropperImage = document.getElementById('cropper-image');

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

            document.getElementById('avatarModal').addEventListener('shown.bs.modal', function () {
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    autoCropArea: 1,
                });
            });

            document.getElementById('avatarModal').addEventListener('hidden.bs.modal', function () {
                if (cropper) { cropper.destroy(); cropper = null; }
                avatarInput.val('');
            });

            $('#save-avatar-btn').on('click', function () {
                if (!cropper) return;
                const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
                canvas.toBlob(function (blob) {
                    const formData = new FormData();
                    formData.append('avatar', blob, 'avatar.png');
                    formData.append('_token', '{{ csrf_token() }}');
                    $('#save-avatar-btn').prop('disabled', true).text('Encrypting...');

                    $.ajax({
                        url: "{{ route('admin.profile.avatar') }}",
                        method: "POST", data: formData, processData: false, contentType: false,
                        success: function (response) {
                            $('#profile-avatar-preview').attr('src', response.avatar_url);
                            avatarModal.hide();
                            Swal.fire({ icon: 'success', title: 'Updated', timer: 2000, showConfirmButton: false });
                        },
                        complete: function () { $('#save-avatar-btn').prop('disabled', false).text('Commit Visual Change'); }
                    });
                });
            });

            $(document).on('submit', '#profile-form, #security-form', function (e) {
                e.preventDefault();
                const form = $(this);
                const btn = form.find('button[type="submit"]');
                const isSecurity = form.attr('id') === 'security-form';
                
                // Clear errors
                form.find('.text-danger').text('');
                
                btn.prop('disabled', true).text('Synchronizing...');
                $.ajax({
                    url: "{{ route('admin.profile.update') }}",
                    method: "POST", data: form.serialize(),
                    success: function (response) {
                        if (response.redirect) {
                            Swal.fire({ 
                                icon: 'success', 
                                title: 'Security Updated', 
                                text: response.message, 
                                showConfirmButton: true 
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            Swal.fire({ icon: 'success', title: 'System Synced', text: response.message, timer: 2000, showConfirmButton: false });
                        }
                        if (isSecurity) form[0].reset();
                    },
                    error: function (xhr) {
                        let errorMsg = 'Failure.';
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            // Map errors to specific fields if they exist
                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                $(`.error-${field}`).text(messages[0]);
                            });
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({ icon: 'error', title: 'Error', html: errorMsg });
                    },
                    complete: function () { 
                        btn.prop('disabled', false).text(isSecurity ? 'Update Password' : 'Update Profile'); 
                    }
                });
            });

            // Forms are handled via AJAX
        });
    </script>
@endpush