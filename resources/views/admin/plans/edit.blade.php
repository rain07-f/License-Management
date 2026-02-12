@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="fw-bold mb-0">Edit Plan: {{ $plan->name }}</h5>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.plans.index') }}" class="btn btn-light rounded-pill px-4 border shadow-sm">
                <i class="fa fa-arrow-left me-1 opacity-50"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.plans.update', $plan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Plan Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $plan->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="3" required>{{ old('description', $plan->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Price ($)</label>
                                <input type="number" step="0.01" name="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price', $plan->price) }}" required>
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Duration (Days)</label>
                                <input type="number" name="duration_days"
                                    class="form-control @error('duration_days') is-invalid @enderror"
                                    value="{{ old('duration_days', $plan->duration_days) }}" required>
                                @error('duration_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Domain Limit</label>
                                <input type="number" name="domain_limit"
                                    class="form-control @error('domain_limit') is-invalid @enderror"
                                    value="{{ old('domain_limit', $plan->domain_limit) }}" required>
                                @error('domain_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="text-end mt-5 pt-3 border-top">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm fw-semibold">
                                Update Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection