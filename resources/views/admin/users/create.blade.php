@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New User</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">User Account Details</h6>
                    <p class="text-secondary mb-3">Provision a new distributor or client account within the system.</p>

                    <form class="forms-sample" action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    id="name" placeholder="e.g. John Doe" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" placeholder="john@example.com" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="••••••••" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="roleSelector" class="form-label">Account Role</label>
                                <select name="role" id="roleSelector"
                                    class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="distributor" {{ old('role') === 'distributor' ? 'selected' : '' }}>
                                        Distributor</option>
                                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-4" id="parentSelectorRow">
                            <div class="col-md-6 mb-3">
                                <label for="parent_id" class="form-label">Affiliated Distributor</label>
                                <select name="parent_id" id="parent_id" class="form-select">
                                    <option value="">None (Top-Level/Super Admin)</option>
                                    @foreach($distributors as $dist)
                                        <option value="{{ $dist->id }}" {{ old('parent_id') == $dist->id ? 'selected' : '' }}>
                                            {{ $dist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="quotaCol">
                                <label for="license_quota" class="form-label">Allocated License Quota</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-lucide="zap" class="icon-sm"></i></span>
                                    <input type="number" name="license_quota" id="license_quota" class="form-control"
                                        placeholder="0" value="{{ old('license_quota', 0) }}">
                                </div>
                                <div class="form-text">Licenses the distributor can generate.</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2 text-white">
                                <i class="btn-icon-prepend" data-lucide="user-plus"></i>
                                Register User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            'use strict';
            $('#roleSelector').on('change', function () {
                if ($(this).val() === 'client') {
                    $('#quotaCol').fadeOut();
                } else {
                    $('#quotaCol').fadeIn();
                }
            });
            $('#roleSelector').trigger('change');
        });
    </script>
@endsection