@extends('layouts.auth')

@section('content')
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-8 col-xl-6 mx-auto">
            <div class="card">
                <div class="row">
                    <div class="col-md-4 pe-md-0">
                        <div class="auth-side-wrapper"
                            style="background-image: url('https://via.placeholder.com/219x452');">
                            {{-- NobleUI usually has an image here --}}
                        </div>
                    </div>
                    <div class="col-md-8 ps-md-0">
                        <div class="auth-form-wrapper px-4 py-5">
                            <a href="#" class="noble-ui-logo d-block mb-2">License<span>Server</span></a>
                            <h5 class="text-secondary fw-normal mb-4">Welcome back! Log in to your account.</h5>

                            <div id="loginErrorArea">
                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i data-lucide="alert-circle" class="icon-sm me-2"></i>
                                        {{ $errors->first() }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                            </div>

                            <form class="forms-sample" id="loginForm" action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="userEmail" class="form-label text-secondary">Email address</label>
                                    <input type="email" name="email" class="form-control" id="userEmail" placeholder="Email"
                                        required value="{{ old('email') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="userPassword" class="form-label text-secondary">Password</label>
                                    <input type="password" name="password" class="form-control" id="userPassword"
                                        autocomplete="current-password" placeholder="Password" required>
                                </div>
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="authCheck">
                                    <label class="form-check-label text-secondary" for="authCheck">
                                        Remember me
                                    </label>
                                </div>
                                <div>
                                    <button type="submit"
                                        class="btn btn-primary me-2 mb-2 mb-md-0 text-white">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('custom-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $(document).on('submit', '#loginForm', function (e) {
                    e.preventDefault();
                    let form = $(this);
                    let btn = form.find('button[type=submit]');
                    let errorArea = $('#loginErrorArea');

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        beforeSend: function () {
                            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Logging in...');
                            errorArea.empty();
                        },
                        success: function (res) {
                            if (res.success) {
                                window.location.href = res.redirect;
                            }
                        },
                        error: function (xhr) {
                            btn.prop('disabled', false).text('Login');
                            let msg = 'An error occurred. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            errorArea.html(`
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i data-lucide="alert-circle" class="icon-sm me-2"></i>
                                        ${msg}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                `);
                            if (window.lucide) lucide.createIcons();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection

@push('custom-styles')
    <style>
        .auth-side-wrapper {
            width: 100%;
            height: 100%;
            background-size: cover;
        }

        .noble-ui-logo {
            font-weight: 900;
            font-size: 25px;
            letter-spacing: -1px;
            color: var(--bs-primary);
        }

        .noble-ui-logo span {
            color: var(--bs-secondary);
        }
    </style>
@endpush