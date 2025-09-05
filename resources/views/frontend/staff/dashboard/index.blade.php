@extends('frontend.staff.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item active"><span>{{ __('message.dashboard') }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
    <div class="container-lg px-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="">{{ __('message.orders') }}</h5>
                        <div>
                            <select class="form-select form-select-sm chart-current-duration">
                                <option value="today">{{ translate('Today', 'ယနေ့') }}</option>
                                <option value="weekly">{{ translate('Weekly', 'အပတ်စဥ်') }}</option>
                                <option value="monthly">{{ translate('Monthly', 'လစဥ်') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-body">
                        <canvas id="orderLineChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card mb-4">
                    <h5 class="card-header">{{ __('message.order_statuses') }}</h5>
                    <div class="card-body">
                        <canvas id="orderPieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card mb-4">
                    <h5 class="card-header">{{ __('message.stock_quantity') }}</h5>
                    <div class="card-body">
                        <canvas id="productStockQuantityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('chartjs/chart.js') }}"></script>

    <script>
        $(document).ready(function() {
            // product stock quantity chart --- start
            new Chart(document.getElementById('productStockQuantityChart').getContext('2d'), {
                type: 'polarArea',
                data: {
                    labels: @json($products_stock_quantity_ary['name']),
                    datasets: [{
                        label: "{{ __('message.stock_quantity') }}",
                        data: @json($products_stock_quantity_ary['stock_quantity']),
                        hoverOffset: 4
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            // product stock quantity chart --- end

            // order doughnut chart --- start
            new Chart(document.getElementById('orderPieChart'), {
                type: 'doughnut',
                data: {
                    labels: [
                        'Pending',
                        'Confirm',
                        'Cancel'
                    ],
                    datasets: [{
                        label: 'Order',
                        data: @json($order_status_count_ary),
                        backgroundColor: [
                            'rgb(249, 177, 21)',
                            'rgb(27, 158, 62)',
                            'rgb(229, 83, 83)'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            // order doughnut chart --- end

            // order line chart --- start
            var orderLabel = "{{ __('message.order') }}";
            var revenueLabel = "{{ __('message.revenue') }}";
            var orderLineChart = new Chart(document.getElementById('orderLineChart'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: orderLabel,
                            data: [],
                            backgroundColor: 'rgba(51, 153, 255, 0.2)',
                            borderColor: 'rgb(51, 153, 255)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.1
                        },
                        {
                            label: revenueLabel,
                            data: [],
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.1
                        }
                    ]
                },
            });

            getOrderLineChart('today');

            $(document).on('change', '.chart-current-duration', function () {
                let chart_current_duration = $(this).val();
                console.log(chart_current_duration);

                getOrderLineChart(chart_current_duration);
            });

            function getOrderLineChart(chart_current_duration) {
                $.get('/get-order-chart-data', {
                        chart_current_duration
                    })
                    .then(function(res) {
                        if (res.success == 1) {
                            console.log(res.data.total_counts);
                            console.log(res.data.total_revenues);

                            orderLineChart.data.labels = res.data.dates;
                            orderLineChart.data.datasets[0].data = res.data.counts;
                            orderLineChart.data.datasets[0].label = `${orderLabel} (${res.data.total_counts})`;
                            orderLineChart.data.datasets[1].data = res.data.revenues;
                            orderLineChart.data.datasets[1].label = `${revenueLabel} (${res.data.total_revenues})`;

                            orderLineChart.update();

                            console.log(res.message);
                        } else {
                            toastr.warning(res.message);
                        }
                    }).fail(function(error) {
                        toastr.error(error.message);
                    });
            }
            // order line chart --- end
        });
    </script>
@endsection
