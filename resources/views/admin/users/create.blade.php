@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New User</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="card-title">Add New User</h6>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-icon-text btn-sm">
                            <i class="btn-icon-prepend" data-lucide="arrow-left"></i>
                            Back to List
                        </a>
                    </div>

                    <form class="forms-sample" action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    id="name" placeholder="John Doe" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" placeholder="john@example.com" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="••••••••" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="roleSelector" class="form-label">Role</label>
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
                            <div class="col-md-6">
                                <label for="parent_id" class="form-label">Parent (Distributor)</label>
                                <select name="parent_id" id="parent_id" class="form-select">
                                    <option value="">None (Super Admin)</option>
                                    @foreach($distributors as $dist)
                                        <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="quotaCol">
                                <label for="license_quota" class="form-label">Initial License Quota</label>
                                <input type="number" name="license_quota" id="license_quota" class="form-control"
                                    placeholder="0" value="{{ old('license_quota', 0) }}">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary me-2">Create User</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
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
                    $('#quotaCol').hide();
                } else {
                    $('#quotaCol').show();
                }
            });
            $('#roleSelector').trigger('change');
        });
    </script>
@endsection