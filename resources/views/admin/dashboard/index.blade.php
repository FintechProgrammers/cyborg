@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if (auth()->user()->hasRole('super admin'))
        <div class="row">
            <div class="col-xl-6 col-md-12">
                <!-- card -->
                <div class="card card-h-100 bg-dark border-0">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <span class="text-white mb-3 lh-1 d-block text-truncate">Deposits</span>
                                <h4 class="mb-3 text-white">
                                    <span class=" text-white">${{ formatNumber(number_format($totalDeposit, 1)) }}</span>
                                </h4>
                            </div>

                            <div class="col-6">
                                <div id="mini-chart1" data-colors='["#fff"]' class="apex-charts mb-2"></div>
                            </div>
                        </div>
                        {{-- <div class="text-nowrap">
                    <span class="badge bg-success-subtle text-success">+20.9k</span>
                    <span class="ms-1  font-size-13 text-white">Since today</span>
                </div> --}}
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
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Withdrawals</span>
                                <h4 class="mb-3">
                                    <span class="">${{ formatNumber(number_format($totalWithdrawal, 1)) }}</span>
                                </h4>
                            </div>
                            <div class="col-6">
                                <div id="mini-chart2" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                            </div>
                        </div>
                        {{-- <div class="text-nowrap">
                    <span class="badge bg-danger-subtle text-danger">-29 Unpaid</span>
                </div> --}}
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-h-100 bg-success border-0">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <span class="text-white mb-3 lh-1 d-block text-truncate">Total Users</span>
                            <h4 class="mb-3">
                                <span class=" text-white">{{ formatNumber($totalUsers) }}</span>
                            </h4>
                        </div>
                    </div>
                    {{-- <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success">+20.9k</span>
                        <span class="ms-1 text-white font-size-13">Since today</span>
                    </div> --}}
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
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Active Users</span>
                            <h4 class="mb-3">
                                <span class="">{{ formatNumber($paidUsers) }}</span>
                            </h4>
                        </div>

                    </div>
                    {{-- <div class="text-nowrap">
                        <span class="badge bg-danger-subtle text-danger">-29 Trades</span>
                        <span class="ms-1 text-muted font-size-13">Since last week</span>
                    </div> --}}
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col-->

        @if (auth()->user()->hasRole('super admin'))
            <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-h-100 bg-danger border-0">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <span class="text-white mb-3 lh-1 d-block text-truncate">Users Balance</span>
                                <h4 class="mb-3 text-white">
                                    $<span class=" text-white">{{ formatNumber(number_format($usersBalance, 1)) }}</span>
                                </h4>
                            </div>
                        </div>
                        {{-- <div class="text-nowrap">
                        <span class="badge bg-danger-subtle text-danger">+ $2.8k</span>
                        <span class="ms-1 text-white font-size-13">Since last week</span>
                    </div> --}}
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
                                <span class="mb-3 lh-1 d-block text-truncate text-white">Total Fee</span>
                                <h4 class="mb-3">
                                    <span class=" text-white">${{ formatNumber(number_format($feeBalance, 1)) }}</span>
                                </h4>
                            </div>
                        </div>
                        {{-- <div class="text-nowrap">
                        <span class="badge bg-success-subtle text-success">+2.95%</span>
                        <span class="ms-1 text-white font-size-13">Since last week</span>
                    </div> --}}
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        @endif
    </div>
    <div class="row">
        <div class="col-xl-6 col-md-12">
            <!-- card -->
            <div class="card card-h-100 bg-primary border-0">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-white mb-3 lh-1 d-block text-truncate">Active Bots</span>
                            <h4 class="mb-3 text-white">
                                <span class=" text-white">{{ formatNumber(number_format($activeBots)) }}</span>
                            </h4>
                        </div>

                        <div class="col-6">
                            <div id="mini-chart1" data-colors='["#fff"]' class="apex-charts mb-2"></div>
                        </div>
                    </div>
                    {{-- <div class="text-nowrap">
                <span class="badge bg-success-subtle text-success">+20.9k</span>
                <span class="ms-1  font-size-13 text-white">Since today</span>
            </div> --}}
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-6 col-md-12">
            <!-- card -->
            <div class="card card-h-100 bg-primary border-0">
                <!-- card body -->
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-white mb-3 lh-1 d-block text-truncate">Total Bots</span>
                            <h4 class="mb-3 text-white">
                                <span class=" text-white">{{ formatNumber(number_format($totalBots)) }}</span>
                            </h4>
                        </div>

                        <div class="col-6">
                            <div id="mini-chart1" data-colors='["#fff"]' class="apex-charts mb-2"></div>
                        </div>
                    </div>
                    {{-- <div class="text-nowrap">
                <span class="badge bg-success-subtle text-success">+20.9k</span>
                <span class="ms-1  font-size-13 text-white">Since today</span>
            </div> --}}
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div>
    <div class="row">
        @foreach ($bindedExcahnges as $item)
            <div class="col-xl-2 col-md-5">
                <!-- card -->
                <div class="card card-h-100">
                    <!-- card body -->
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">{{ $item['name'] }} api bind</span>
                                <h4 class="mb-3">
                                    <span class="">{{ formatNumber($item['count']) }}</span>
                                </h4>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        @endforeach
    </div>

    <div class="row">

        @if (auth()->user()->hasRole('super admin'))
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Deposit/Withdraw</h4>
                    </div>
                    <div class="card-body">

                        <canvas id="lineChart" class="chartjs-chart"
                            data-colors='["rgba(81, 86, 190, 0.2)", "#5156be", "rgba(235, 239, 242, 0.2)", "#ebeff2"]'></canvas>

                    </div>
                </div>
            </div> <!-- end col -->
        @endif
        <!-- end col -->
        <div class="col-xl-4">
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
                                        <span class="avatar-title rounded-circle bg-light-subtle text-white font-size-24">
                                            <i class="mdi mdi-currency-btc"></i>
                                        </span>
                                    </div>
                                    <h4 class="mt-3 lh-base fw-normal text-white"><b>Bitcoin</b> News</h4>
                                    <p class="text-white-50 font-size-13">Bitcoin prices fell sharply amid the
                                        global sell-off in equities. Negative news
                                        over the Bitcoin past week has dampened Bitcoin basics
                                        sentiment for bitcoin. </p>

                                </div>
                            </div>
                            <!-- end carousel-item -->
                            <div class="carousel-item">
                                <div class="text-center p-4">
                                    <i class="mdi mdi-ethereum widget-box-1-icon"></i>
                                    <div class="avatar-md m-auto">
                                        <span class="avatar-title rounded-circle bg-light-subtle text-white font-size-24">
                                            <i class="mdi mdi-ethereum"></i>
                                        </span>
                                    </div>
                                    <h4 class="mt-3 lh-base fw-normal text-white"><b>ETH</b> News</h4>
                                    <p class="text-white-50 font-size-13">Bitcoin prices fell sharply amid the
                                        global sell-off in equities. Negative news
                                        over the Bitcoin past week has dampened Bitcoin basics
                                        sentiment for bitcoin. </p>

                                </div>
                            </div>
                            <!-- end carousel-item -->
                            <div class="carousel-item">
                                <div class="text-center p-4">
                                    <i class="mdi mdi-litecoin widget-box-1-icon"></i>
                                    <div class="avatar-md m-auto">
                                        <span class="avatar-title rounded-circle bg-light-subtle text-white font-size-24">
                                            <i class="mdi mdi-litecoin"></i>
                                        </span>
                                    </div>
                                    <h4 class="mt-3 lh-base fw-normal text-white"><b>Litecoin</b> News</h4>
                                    <p class="text-white-50 font-size-13">Bitcoin prices fell sharply amid the
                                        global sell-off in equities. Negative news
                                        over the Bitcoin past week has dampened Bitcoin basics
                                        sentiment for bitcoin. </p>

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
    <script
        src="{{ asset('assets/admin/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}">
    </script>
    <script src="{{ asset('assets/admin/libs/chart.js/chart.umd.js') }}"></script>
    {{-- <script src="{{ asset('assets/admin/js/pages/chartjs.init.js') }}"></script> --}}

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

        function getChartColorsArrayLin(chartId) {
            if (document.getElementById(chartId) !== null) {
                var colors = document.getElementById(chartId).getAttribute("data-colors");
                colors = JSON.parse(colors);
                return colors.map(function(value) {
                    var newValue = value.replace(" ", "");
                    if (newValue.indexOf(",") === -1) {
                        var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                        if (color) return color;
                        else return newValue;;
                    } else {
                        var val = value.split(',');
                        if (val.length == 2) {
                            var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
                            rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
                            return rgbaColor;
                        } else {
                            return newValue;
                        }
                    }
                });
            }
        }

        function setDepositChart(deposits) {
            var minichart1Colors = getChartColorsArray("#mini-chart1");

            var options = {
                series: [{
                    data: deposits
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
        }

        function setWithdrawalChart(withdrawal) {
            var minichart2Colors = getChartColorsArray("#mini-chart2");
            var options = {
                series: [{
                    data: withdrawal
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
        }

        function setLineChart(deposits, withdrawal) {

            Chart.defaults.borderColor = "rgba(133, 141, 152, 0.1)";
            Chart.defaults.color = "#858d98";
            // line chart
            var islinechart = document.getElementById('lineChart');
            lineChartColor = getChartColorsArrayLin('lineChart');
            if (lineChartColor) {
                islinechart.setAttribute("width", islinechart.parentElement.offsetWidth);

                var lineChart = new Chart(islinechart, {
                    type: 'line',
                    options: {
                        maintainAspectRatio: false,
                    },
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August",
                            "September", "October"
                        ],
                        datasets: [{
                                label: "Deposits",
                                fill: true,
                                lineTension: 0.5,
                                backgroundColor: lineChartColor[0],
                                borderColor: lineChartColor[1],
                                borderCapStyle: 'butt',
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                pointBorderColor: lineChartColor[1],
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 1,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: lineChartColor[1],
                                pointHoverBorderColor: "#fff",
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                data: [deposits.January, deposits.February, deposits.March, deposits.March,
                                    deposits.April, deposits.May, deposits.June, deposits.July, deposits
                                    .August, deposits.September, deposits.October, deposits.November,
                                    deposits.December
                                ]
                            },
                            {
                                label: "Withrawals",
                                fill: true,
                                lineTension: 0.5,
                                backgroundColor: lineChartColor[2],
                                borderColor: lineChartColor[3],
                                borderCapStyle: 'butt',
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                pointBorderColor: lineChartColor[3],
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 1,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: lineChartColor[3],
                                pointHoverBorderColor: "#eef0f2",
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                data: [
                                    withdrawal.January, withdrawal.February, withdrawal.March, withdrawal
                                    .March,
                                    withdrawal.April, withdrawal.May, withdrawal.June, withdrawal.July,
                                    withdrawal
                                    .August, withdrawal.September, withdrawal.October, withdrawal.November,
                                    withdrawal.December
                                ]
                            }
                        ]
                    },

                });
            }
        }

        $(document).ready(function() {

            $.ajax({
                url: "{{ route('admin.dashboard.getStatistics') }}",
                method: "GET",
                success: function(result) {
                    console.log(result.data)
                    setDepositChart(result.data.deposits);

                    setWithdrawalChart(result.data.withdrawals)

                    setLineChart(result.data.depositsInMonths, result.data.withdrawalInMonth)
                },
                error: function(jqXHR, testStatus, error) {

                    console.log(jqXHR.responseText, testStatus, error);
                    displayMessage(
                        "Error occurred",
                        "error"
                    );

                },
                timeout: 8000,
            });

        })
    </script>
@endpush
