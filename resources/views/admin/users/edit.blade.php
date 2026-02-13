@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">User Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Modify User: {{ $user->name }}</h6>
                    <p class="text-secondary mb-3">Update account identity, communication, and operational status.</p>

                    <form class="forms-sample" action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">System Username</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    id="name" value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Display Name</label>
                                <input type="text" name="full_name"
                                    class="form-control @error('full_name') is-invalid @enderror" id="full_name"
                                    value="{{ old('full_name', $user->full_name) }}" placeholder="e.g. John Doe">
                                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Primary Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Contact Number</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 (555) 000-0000">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company" class="form-label">Organization / Affiliation</label>
                            <input type="text" name="company" class="form-control @error('company') is-invalid @enderror"
                                id="company" value="{{ old('company', $user->company) }}" placeholder="Company Name">
                            @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Reset Password <small class="text-muted">(Leave
                                        blank to keep current)</small></label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="••••••••">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Deployment Status</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror"
                                    required>
                                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2 text-white">
                                <i class="btn-icon-prepend" data-lucide="save"></i>
                                Save Changes
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