@extends('layouts.admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">Edit Application</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-secondary text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.applications.index') }}" class="text-secondary text-decoration-none">Applications</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">{{ $application->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.applications.update', $application) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Application Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-pill px-3" required 
                            value="{{ old('name', $application->name) }}" placeholder="e.g. SEO Booster">
                        @error('name') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Application Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control rounded-pill px-3" required 
                            value="{{ old('slug', $application->slug) }}" placeholder="e.g. seo-booster">
                        <small class="text-muted d-block mt-1">Unique identifier used in API endpoints.</small>
                        @error('slug') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Description</label>
                    <textarea name="description" class="form-control rounded-4 p-3" rows="4" 
                        placeholder="Brief application description">{{ old('description', $application->description) }}</textarea>
                    @error('description') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-light rounded-pill px-4 me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">Update Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
