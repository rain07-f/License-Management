@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
        </div>

    </div>

    @if(auth()->user()->isSuperAdmin())
        <div class="row">
            <div class="col-12 col-xl-12 stretch-card">
                <div class="row flex-grow-1">
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Total Users</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $stats['total_users'] }}</h3>
                                        <div class="d-flex align-items-baseline">
                                            <p class="text-success">
                                                <span>+3.3%</span>
                                                <i data-lucide="arrow-up" class="icon-sm mb-1"></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7">
                                        <div id="usersChart" class="mt-md-3 mt-xl-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Distributors</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $stats['total_distributors'] }}</h3>
                                        <div class="d-flex align-items-baseline">
                                            <p class="text-danger">
                                                <span>-2.8%</span>
                                                <i data-lucide="arrow-down" class="icon-sm mb-1"></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7">
                                        <div id="distributorsChart" class="mt-md-3 mt-xl-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Total Licenses</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $stats['total_licenses'] }}</h3>
                                        <div class="d-flex align-items-baseline">
                                            <p class="text-success">
                                                <span>+1.5%</span>
                                                <i data-lucide="arrow-up" class="icon-sm mb-1"></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7">
                                        <div id="licensesChart" class="mt-md-3 mt-xl-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Active Licenses</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $stats['active_licenses'] }}</h3>
                                        <div class="d-flex align-items-baseline">
                                            <p class="text-success">
                                                <span>+5.2%</span>
                                                <i data-lucide="arrow-up" class="icon-sm mb-1"></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7">
                                        <div id="activeLicensesChart" class="mt-md-3 mt-xl-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- row -->

        <div class="row">
            <div class="col-12 col-xl-12 grid-margin stretch-card">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                            <h6 class="card-title mb-0">Monthly License Generation</h6>
                        </div>
                        <div class="row align-items-start">
                            <div class="col-md-7">
                                <p class="text-secondary tx-13 mb-3 mb-md-0">Tracking the growth of license issuance across all
                                    distributors and clients.</p>
                            </div>
                        </div>
                        <div id="licenseGenerationChart"></div>
                    </div>
                </div>
            </div>
        </div> <!-- row -->

        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <h6 class="card-title mb-0">Critical Stats</h6>
                        </div>
                        <div class="d-flex align-items-center border-bottom py-3">
                            <div
                                class="wd-50 ht-50 bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i data-lucide="alert-triangle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-body mb-1">Expired Licenses</h6>
                                <h4 class="text-danger fw-bolder">{{ $stats['expired_licenses'] }}</h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center py-3">
                            <div
                                class="wd-50 ht-50 bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i data-lucide="globe"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-body mb-1">Active Activations</h6>
                                <h4 class="text-info fw-bolder">{{ $stats['active_activations'] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->isDistributor())
        <div class="row">
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3">Owned Licenses</h6>
                        <div class="d-flex align-items-center">
                            <div
                                class="wd-40 ht-40 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i data-lucide="key"></i>
                            </div>
                            <h2 class="mb-0 fw-bolder">{{ $stats['total_licenses'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card border-primary border-start border-4">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3">Remaining Quota</h6>
                        <div class="d-flex align-items-center">
                            <div
                                class="wd-40 ht-40 bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i data-lucide="zap"></i>
                            </div>
                            <h2 class="mb-0 fw-bolder text-info">{{ $stats['remaining_quota'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3">Active Licenses</h6>
                        <div class="d-flex align-items-center">
                            <div
                                class="wd-40 ht-40 bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i data-lucide="check-circle"></i>
                            </div>
                            <h2 class="mb-0 fw-bolder text-success">{{ $stats['active_licenses'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title text-muted mb-3">Expired Licenses</h6>
                        <div class="d-flex align-items-center">
                            <div
                                class="wd-40 ht-40 bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i data-lucide="clock"></i>
                            </div>
                            <h2 class="mb-0 fw-bolder text-danger">{{ $stats['expired_licenses'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
    <script>
        $(function () {
            'use strict';

            if ($('#dashboardDate').length) {
                flatpickr("#dashboardDate", {
                    wrap: true,
                    defaultDate: "today"
                });
            }

            @if(auth()->user()->isSuperAdmin())
                var colors = {
                    primary: "#6571ff",
                    secondary: "#7987a1",
                    success: "#05a34a",
                    info: "#66d1d1",
                    warning: "#fbbc06",
                    danger: "#ff3366",
                    light: "#e9ecef",
                    dark: "#060c17",
                    muted: "#7987a1",
                    gridBorder: "rgba(77, 77, 77, .15)",
                    bodyColor: "#000",
                    cardBg: "#fff"
                };

                // License Generation Chart (ApexCharts)
                if ($('#licenseGenerationChart').length) {
                    var options = {
                        chart: {
                            type: "line",
                            height: '400',
                            parentHeightOffset: 0,
                            foreColor: colors.muted,
                            toolbar: {
                                show: false
                            },
                            stacked: true,
                        },
                        theme: {
                            mode: 'light'
                        },
                        tooltip: {
                            theme: 'light'
                        },
                        colors: [colors.primary, colors.danger, colors.warning],
                        grid: {
                            padding: {
                                bottom: -4
                            },
                            borderColor: colors.gridBorder,
                            xaxis: {
                                lines: {
                                    show: true
                                }
                            }
                        },
                        series: [{
                            name: 'Generated',
                            data: {!! json_encode($stats['monthly_stats']->pluck('count')) !!}
                        }],
                        xaxis: {
                            type: 'category',
                            categories: {!! json_encode($stats['monthly_stats']->pluck('month')) !!},
                            lines: {
                                show: true
                            },
                            axisBorder: {
                                color: colors.gridBorder,
                            },
                            axisTicks: {
                                color: colors.gridBorder,
                            },
                        },
                        yaxis: {
                            labels: {
                                offsetX: 0
                            }
                        },
                        markers: {
                            size: 0,
                        },
                        legend: {
                            show: true,
                            position: "top",
                            horizontalAlign: 'left',
                            containerMargin: {
                                top: 30
                            }
                        },
                        stroke: {
                            width: 3,
                            curve: "smooth",
                            lineCap: "round"
                        },
                    };
                    var chart = new ApexCharts(document.querySelector("#licenseGenerationChart"), options);
                    chart.render();
                }

                // Mini Sparklines
                function createSparkline(selector, data, color) {
                    var options = {
                        series: [{ data: data }],
                        chart: { type: 'line', width: 100, height: 35, sparkline: { enabled: true } },
                        colors: [color],
                        stroke: { width: 2, curve: 'smooth' },
                        tooltip: { enabled: false }
                    };
                    new ApexCharts(document.querySelector(selector), options).render();
                }

                createSparkline("#usersChart", [10, 15, 8, 25], colors.primary);
                createSparkline("#distributorsChart", [12, 11, 14, 10], colors.danger);
                createSparkline("#licensesChart", [20, 30, 45, 40], colors.success);
                createSparkline("#activeLicensesChart", [15, 25, 35, 38], colors.info);

            @endif
            });
    </script>
@endsection