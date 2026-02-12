@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-primary-dark mb-1">Modify System Entity</h2>
            <p class="text-muted mb-0">Adjusting parameters for {{ $user->name }}</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-pill px-4 border shadow-sm">
                <i class="fa fa-arrow-left me-1 opacity-50"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">System Username</label>
                                <input type="text" name="name"
                                    class="form-control rounded-12 p-3 bg-light border-0 @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Full Display Name</label>
                                <input type="text" name="full_name"
                                    class="form-control rounded-12 p-3 bg-light border-0 @error('full_name') is-invalid @enderror"
                                    value="{{ old('full_name', $user->full_name) }}">
                                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Primary Email Address</label>
                                <input type="email" name="email"
                                    class="form-control rounded-12 p-3 bg-light border-0 @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Mobile Communication</label>
                                <input type="text" name="phone"
                                    class="form-control rounded-12 p-3 bg-light border-0 @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Corporate Affiliation</label>
                                <input type="text" name="company"
                                    class="form-control rounded-12 p-3 bg-light border-0 @error('company') is-invalid @enderror"
                                    value="{{ old('company', $user->company) }}">
                                @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Security Token (Password)</label>
                        <input type="password" name="password"
                            class="form-control rounded-12 p-3 bg-light border-0 @error('password') is-invalid @enderror"
                            placeholder="••••••••">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Operational Status</label>
                        <select name="status"
                            class="form-select rounded-12 p-3 bg-light border-0 @error('status') is-invalid @enderror"
                            required>
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>
                                Active Deployment</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>
                                Suspended Protocol</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mt-5 pt-3 border-top">
                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm fw-semibold">
                    Update User
                </button>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>
@endsection