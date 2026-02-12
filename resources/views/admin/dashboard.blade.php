@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        @if(auth()->user()->isSuperAdmin())
            <!-- Super Admin Widgets -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card accent-main">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Users</h6>
                            <h3 class="fw-bold mb-0 text-primary-dark">{{ $stats['total_users'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card accent-info">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Distributors</h6>
                            <h3 class="fw-bold mb-0 text-accent-blue">{{ $stats['total_distributors'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card accent-main">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Licenses</h6>
                            <h3 class="fw-bold mb-0 text-primary-dark">{{ $stats['total_licenses'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card accent-info">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Active Licenses</h6>
                            <h3 class="fw-bold mb-0 text-accent-blue">{{ $stats['active_licenses'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <span>Monthly License Generation</span>
                        </div>
                        <div class="card-body">
                            <canvas id="licenseChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card accent-warning">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-3 bg-danger-subtle text-danger rounded-circle me-3">
                                    <i class="fa fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Expired Licenses</h6>
                                    <h3 class="fw-bold mb-0 text-danger-red">{{ $stats['expired_licenses'] }}</h3>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <div class="p-3 bg-info-subtle text-info rounded-circle me-3">
                                    <i class="fa fa-globe fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Active Domains</h6>
                                    <h3 class="fw-bold mb-0 text-accent-blue">{{ $stats['active_domains'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->isDistributor())
            <!-- Distributor Widgets -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card accent-main">
                        <div class="card-body text-center py-4">
                            <h6 class="text-muted mb-2">Owned Licenses</h6>
                            <h2 class="fw-bold mb-0 text-primary-dark">{{ $stats['total_licenses'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card accent-info">
                        <div class="card-body text-center py-4">
                            <h6 class="text-muted mb-2">Remaining Quota</h6>
                            <h2 class="fw-bold mb-0 text-accent-blue">{{ $stats['remaining_quota'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card accent-main">
                        <div class="card-body text-center py-4">
                            <h6 class="text-muted mb-2">Active Licenses</h6>
                            <h2 class="fw-bold mb-0 text-secondary-purple">{{ $stats['active_licenses'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card accent-warning">
                        <div class="card-body text-center py-4">
                            <h6 class="text-muted mb-2">Expired Licenses</h6>
                            <h2 class="fw-bold mb-0 text-danger-red">{{ $stats['expired_licenses'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        @if(auth()->user()->isSuperAdmin())
            const ctx = document.getElementById('licenseChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($stats['monthly_stats']->pluck('month')) !!},
                    datasets: [{
                        label: 'Licenses Generated',
                        data: {!! json_encode($stats['monthly_stats']->pluck('count')) !!},
                        backgroundColor: 'rgba(75, 73, 172, 0.1)',
                        borderColor: '#4B49AC',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#4B49AC',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        @endif
    </script>
@endsection