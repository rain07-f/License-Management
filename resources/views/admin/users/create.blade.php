@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="fw-bold mb-0">Add New User</h5>
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
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Full Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="John Doe" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="john@example.com" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                                    required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Role</label>
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
                                <label class="form-label fw-semibold small">Parent (Distributor)</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">None (Super Admin)</option>
                                    @foreach($distributors as $dist)
                                        <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="quotaCol">
                                <label class="form-label fw-semibold small">Initial License Quota</label>
                                <input type="number" name="license_quota" class="form-control" placeholder="0"
                                    value="{{ old('license_quota', 0) }}">
                            </div>
                        </div>

                        <div class="text-end mt-5 pt-3 border-top">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm fw-semibold">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#roleSelector').change(function () {
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