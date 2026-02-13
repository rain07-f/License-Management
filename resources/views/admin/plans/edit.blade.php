@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.plans.index') }}">Plan Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Plan</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Edit Plan: {{ $plan->name }}</h6>
                    <p class="text-secondary mb-3">Update the subscription plan's attributes and constraints.</p>

                    <form class="forms-sample" action="{{ route('admin.plans.update', $plan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="planName" class="form-label">Plan Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="planName" value="{{ old('name', $plan->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="planDescription" class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                id="planDescription" rows="3"
                                required>{{ old('description', $plan->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="planPrice" class="form-label">Price ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="price"
                                        class="form-control @error('price') is-invalid @enderror" id="planPrice"
                                        value="{{ old('price', $plan->price) }}" required>
                                </div>
                                @error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="planDuration" class="form-label">Duration (Days)</label>
                                <input type="number" name="duration_days"
                                    class="form-control @error('duration_days') is-invalid @enderror" id="planDuration"
                                    value="{{ old('duration_days', $plan->duration_days) }}" required>
                                @error('duration_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="planDomainLimit" class="form-label">Domain Limit</label>
                                <input type="number" name="domain_limit"
                                    class="form-control @error('domain_limit') is-invalid @enderror" id="planDomainLimit"
                                    value="{{ old('domain_limit', $plan->domain_limit) }}" required>
                                @error('domain_limit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2 text-white">
                                <i class="btn-icon-prepend" data-lucide="save"></i>
                                Update Plan
                            </button>
                            <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection