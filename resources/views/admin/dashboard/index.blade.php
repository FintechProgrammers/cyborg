@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-xl-6 col-md-12">
            <!-- card -->
            <div class="card card-h-100 bg-dark border-0">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-white mb-3 lh-1 d-block text-truncate">Total Users</span>
                            <h4 class="mb-3 text-white">
                                <span class="counter-value text-white" data-target="865.2">0</span>k
                            </h4>
                        </div>

                        <div class="col-6">
                            <div id="mini-chart1" data-colors='["#fff"]' class="apex-charts mb-2"></div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success">+20.9k</span>
                        <span class="ms-1  font-size-13 text-white">Since today</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-6 col-md-12">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Paid Users</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="6258">0</span>
                            </h4>
                        </div>
                        <div class="col-6">
                            <div id="mini-chart2" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                        </div>
                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-danger-subtle text-danger">-29 Unpaid</span>


                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100 bg-success border-0">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-white mb-3 lh-1 d-block text-truncate">Total circle income</span>
                            <h4 class="mb-3">
                                <span class="counter-value text-white" data-target="865.2">0</span>
                            </h4>
                        </div>


                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success">+20.9k</span>
                        <span class="ms-1 text-white font-size-13">Since today</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100 bg-body-tertiary border-0">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Kucoin api bind</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="6258">0</span>
                            </h4>
                        </div>

                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-danger-subtle text-danger">-29 Trades</span>
                        <span class="ms-1 text-muted font-size-13">Since last week</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col-->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100 bg-danger border-0">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-white mb-3 lh-1 d-block text-truncate">Users Balance</span>
                            <h4 class="mb-3 text-white">
                                $<span class="counter-value text-white" data-target="4.32">0</span>M
                            </h4>
                        </div>

                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-danger-subtle text-danger">+ $2.8k</span>
                        <span class="ms-1 text-white font-size-13">Since last week</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100 bg-primary border-0">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="mb-3 lh-1 d-block text-truncate text-white">Total Users Profit</span>
                            <h4 class="mb-3">
                                <span class="counter-value text-white" data-target="12.57">0</span>
                            </h4>
                        </div>

                    </div>
                    <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success">+2.95%</span>
                        <span class="ms-1 text-white font-size-13">Since last week</span>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div>
    <div class="row">
        <div class="col-xl-2 col-md-5">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Binance api bind</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="865.2">0</span>k
                            </h4>
                        </div>

                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-2 col-md-5">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Reward</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="865.2">0</span>k
                            </h4>
                        </div>

                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-2 col-md-5">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total support tickets</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="865.2">0</span>k
                            </h4>
                        </div>

                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-2 col-md-5">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Users in position</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="865.2">0</span>k
                            </h4>
                        </div>

                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-2 col-md-5">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Bots On</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="865.2">0</span>k
                            </h4>
                        </div>

                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
        <div class="col-xl-2 col-md-5">
            <!-- card -->
            <div class="card card-h-100">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-muted mb-3 lh-3 d-block text-truncate">Strategy sync</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="865.2">0</span>k
                            </h4>
                        </div>

                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

    </div>

    <div class="row">

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Deposit/Withdraw (30 days)</h4>
                </div>
                <div class="card-body">

                    <canvas id="lineChart" class="chartjs-chart"
                        data-colors='["rgba(81, 86, 190, 0.2)", "#5156be", "rgba(235, 239, 242, 0.2)", "#ebeff2"]'></canvas>

                </div>
            </div>
        </div> <!-- end col -->



        <!-- end col -->
        <div class="col-xl-5">
            <div class="row">

                <!-- end col -->

                <div class="col-xl-7">
                    <!-- card -->
                    <div class="card bg-primary text-white shadow-primary card-h-100">
                        <!-- card body -->
                        <div class="card-body p-0">
                            <div id="carouselExampleCaptions" class="carousel slide text-center widget-carousel"
                                data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="text-center p-4">
                                            <i class="mdi mdi-bitcoin widget-box-1-icon"></i>
                                            <div class="avatar-md m-auto">
                                                <span
                                                    class="avatar-title rounded-circle bg-light-subtle text-white font-size-24">
                                                    <i class="mdi mdi-currency-btc"></i>
                                                </span>
                                            </div>
                                            <h4 class="mt-3 lh-base fw-normal text-white"><b>Bitcoin</b> News</h4>
                                            <p class="text-white-50 font-size-13">Bitcoin prices fell sharply amid the
                                                global sell-off in equities. Negative news
                                                over the Bitcoin past week has dampened Bitcoin basics
                                                sentiment for bitcoin. </p>
                                            <button type="button" class="btn btn-light btn-sm">View details <i
                                                    class="mdi mdi-arrow-right ms-1"></i></button>
                                        </div>
                                    </div>
                                    <!-- end carousel-item -->
                                    <div class="carousel-item">
                                        <div class="text-center p-4">
                                            <i class="mdi mdi-ethereum widget-box-1-icon"></i>
                                            <div class="avatar-md m-auto">
                                                <span
                                                    class="avatar-title rounded-circle bg-light-subtle text-white font-size-24">
                                                    <i class="mdi mdi-ethereum"></i>
                                                </span>
                                            </div>
                                            <h4 class="mt-3 lh-base fw-normal text-white"><b>ETH</b> News</h4>
                                            <p class="text-white-50 font-size-13">Bitcoin prices fell sharply amid the
                                                global sell-off in equities. Negative news
                                                over the Bitcoin past week has dampened Bitcoin basics
                                                sentiment for bitcoin. </p>
                                            <button type="button" class="btn btn-light btn-sm">View details <i
                                                    class="mdi mdi-arrow-right ms-1"></i></button>
                                        </div>
                                    </div>
                                    <!-- end carousel-item -->
                                    <div class="carousel-item">
                                        <div class="text-center p-4">
                                            <i class="mdi mdi-litecoin widget-box-1-icon"></i>
                                            <div class="avatar-md m-auto">
                                                <span
                                                    class="avatar-title rounded-circle bg-light-subtle text-white font-size-24">
                                                    <i class="mdi mdi-litecoin"></i>
                                                </span>
                                            </div>
                                            <h4 class="mt-3 lh-base fw-normal text-white"><b>Litecoin</b> News</h4>
                                            <p class="text-white-50 font-size-13">Bitcoin prices fell sharply amid the
                                                global sell-off in equities. Negative news
                                                over the Bitcoin past week has dampened Bitcoin basics
                                                sentiment for bitcoin. </p>
                                            <button type="button" class="btn btn-light btn-sm">View details <i
                                                    class="mdi mdi-arrow-right ms-1"></i></button>
                                        </div>
                                    </div>
                                    <!-- end carousel-item -->
                                </div>
                                <!-- end carousel-inner -->

                                <div class="carousel-indicators carousel-indicators-rounded">
                                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                                        class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                        aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                        aria-label="Slide 3"></button>
                                </div>
                                <!-- end carousel-indicators -->
                            </div>
                            <!-- end carousel -->
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end col -->
    </div>
@endsection
@push('scripts')
    <!-- apexcharts -->
    <script src="{{ asset('assets/admin/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ asset('assets/admin/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}">
    </script>
    <script src="{{ asset('assets/admin/libs/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/admin/js/pages/chartjs.init.js') }}"></script>

    <script>
        // get colors array from the string
        function getChartColorsArray(chartId) {
            var colors = $(chartId).attr('data-colors');
            var colors = JSON.parse(colors);
            return colors.map(function(value) {
                var newValue = value.replace(' ', '');
                if (newValue.indexOf('--') != -1) {
                    var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                    if (color) return color;
                } else {
                    return newValue;
                }
            })
        }

        $(document).ready(function() {
            // mini-1
            var minichart1Colors = getChartColorsArray("#mini-chart1");
            var options = {
                series: [{
                    data: [2, 10, 18, 22, 36, 15, 47, 75, 65, 19, 14, 2, 47, 42, 15, ]
                }],
                chart: {
                    type: 'line',
                    height: 50,
                    sparkline: {
                        enabled: true
                    }
                },
                colors: minichart1Colors,
                stroke: {
                    curve: 'smooth',
                    width: 2,
                },
                tooltip: {
                    fixed: {
                        enabled: false
                    },
                    x: {
                        show: false
                    },
                    y: {
                        title: {
                            formatter: function(seriesName) {
                                return ''
                            }
                        }
                    },
                    marker: {
                        show: false
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#mini-chart1"), options);
            chart.render();

            // mini-2
            var minichart2Colors = getChartColorsArray("#mini-chart2");
            var options = {
                series: [{
                    data: [15, 42, 47, 2, 14, 19, 65, 75, 47, 15, 42, 47, 2, 14, 12, ]
                }],
                chart: {
                    type: 'line',
                    height: 50,
                    sparkline: {
                        enabled: true
                    }
                },
                colors: minichart2Colors,
                stroke: {
                    curve: 'smooth',
                    width: 2,
                },
                tooltip: {
                    fixed: {
                        enabled: false
                    },
                    x: {
                        show: false
                    },
                    y: {
                        title: {
                            formatter: function(seriesName) {
                                return ''
                            }
                        }
                    },
                    marker: {
                        show: false
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#mini-chart2"), options);
            chart.render();
        })
    </script>
@endpush
