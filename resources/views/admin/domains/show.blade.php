@extends('layouts.admin')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.domains.index') }}">Activated Domains</a></li>
            <li class="breadcrumb-item active" aria-current="page">Domain Detail</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Domain: {{ $domain->domain_name }}</h4>
                <div>
                    <a href="{{ route('admin.domains.index') }}" class="btn btn-outline-secondary">
                        <i data-lucide="arrow-left" class="icon-sm me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Domain Information</h6>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="ps-0 fw-bold border-0">Domain Name</td>
                                    <td class="text-secondary border-0 text-end">{{ $domain->domain_name }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">License Key (Hash)</td>
                                    <td class="text-secondary text-end">
                                        <code class="tx-12">{{ $domain->license->license_key_hash }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">Owner</td>
                                    <td class="text-secondary text-end">{{ $domain->license->owner->name }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">Status</td>
                                    <td class="text-end">
                                        <span class="badge {{ $domain->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-2 py-1">
                                            {{ ucfirst($domain->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">Activated At</td>
                                    <td class="text-secondary text-end">
                                        {{ $domain->activated_at ? $domain->activated_at->format('Y-m-d H:i:s') : 'N/A' }}
                                        <br>
                                        <small class="text-muted">({{ $domain->activated_at ? $domain->activated_at->diffForHumans() : '' }})</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">Last Check</td>
                                    <td class="text-secondary text-end">
                                        {{ $domain->last_check_at ? $domain->last_check_at->format('Y-m-d H:i:s') : 'Never' }}
                                        <br>
                                        <small class="text-muted">({{ $domain->last_check_at ? $domain->last_check_at->diffForHumans() : '' }})</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">Last IP</td>
                                    <td class="text-secondary text-end">{{ $domain->last_ip ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">License Information</h6>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="ps-0 fw-bold border-0">License ID</td>
                                    <td class="text-secondary border-0 text-end">#{{ $domain->license->id }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">Plan</td>
                                    <td class="text-secondary text-end">
                                        <span class="badge bg-primary-subtle text-primary">{{ $domain->license->plan->name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">Max Activations</td>
                                    <td class="text-secondary text-end font-monospace">{{ $domain->license->max_domains }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">Current Activations</td>
                                    <td class="text-secondary text-end font-monospace">{{ $domain->license->domains()->count() }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-0 fw-bold">Expires At</td>
                                    <td class="text-secondary text-end">
                                        {{ $domain->license->expires_at ? $domain->license->expires_at->format('Y-m-d') : 'Never' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.licenses.show', $domain->license_id) }}" class="btn btn-primary w-100">
                            <i data-lucide="external-link" class="icon-sm me-1"></i> Manage License
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Domain Activity Timeline</h6>
                    <div class="timeline-wrapper pt-3">
                        <ul class="timeline">
                            @forelse($logs as $log)
                                <li class="event" data-date="{{ $log->created_at->format('Y-m-d H:i:s') }}">
                                    <h3>{{ str_replace('_', ' ', ucfirst($log->event_type)) }}</h3>
                                    <p>{{ $log->message }}</p>
                                    @if($log->ip_address)
                                        <small class="text-muted">IP: {{ $log->ip_address }}</small>
                                    @endif
                                </li>
                            @empty
                                <div class="text-center py-5">
                                    <i data-lucide="info" class="icon-lg text-secondary mb-2"></i>
                                    <p class="text-secondary">No activity logs recorded for this domain.</p>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-styles')
<style>
    .timeline {
        position: relative;
        padding: 20px 0 20px 140px;
        list-style: none;
        max-width: 95%;
        margin: 0 auto;
    }

    .timeline:before {
        content: '';
        position: absolute;
        left: 120px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: rgba(114, 124, 245, 0.3);
    }

    .timeline .event {
        position: relative;
        margin-bottom: 40px;
        padding-left: 20px;
    }

    .timeline .event:last-of-type {
        margin-bottom: 0;
    }

    .timeline .event:before {
        content: attr(data-date);
        position: absolute;
        left: -140px;
        width: 110px;
        text-align: right;
        font-weight: 500;
        font-size: 0.8rem;
        color: #6c757d;
        top: 2px;
    }

    .timeline .event:after {
        content: "";
        position: absolute;
        left: -5px;
        top: 4px;
        width: 10px;
        height: 10px;
        background: #727cf5;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 3px rgba(114, 124, 245, 0.2);
        z-index: 1;
    }

    @media (max-width: 767px) {
        .timeline {
            padding-left: 0;
        }
        .timeline:before {
            left: 10px;
        }
        .timeline .event {
            padding-left: 30px;
        }
        .timeline .event:before {
            position: relative;
            left: 0;
            width: auto;
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 0.75rem;
        }
        .timeline .event:after {
            left: 5px;
            top: 25px;
        }
    }

    .timeline .event h3 {
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 4px;
        color: var(--bs-heading-color);
    }

    .timeline .event p {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 2px;
    }

    .timeline .event small {
        display: block;
        font-size: 0.8rem;
        color: #adb5bd;
    }
</style>
@endpush
