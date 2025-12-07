@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Dashboard General</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="home-item" href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">General</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid ecommerce-page">
        <div class="row">
        
            {{-- Card 1: Sales Today --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="sale-detail">
                                    <div class="icon"><i data-feather="shopping-bag"></i></div>
                                    <div class="sale-content">
                                        <h3>Ventas Hoy</h3>
                                        <p>{{ number_format($salesToday, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="small-chart-view sales-chart" id="sales-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Card 2: Sales Month --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="sale-detail">
                                    <div class="icon"><i data-feather="dollar-sign"></i></div>
                                    <div class="sale-content">
                                        <h3>Ventas Mes</h3>
                                        <p>{{ number_format($salesMonth, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="small-chart-view income-chart" id="income-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Card 3: Docs Today --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="sale-detail">
                                    <div class="icon"><i data-feather="file-text"></i></div>
                                    <div class="sale-content">
                                        <h3>Docs. Hoy</h3>
                                        <p>{{ $docsTodayCount }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="small-chart-view order-chart" id="order-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Card 4: Overdue --}}
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card sale-chart">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="sale-detail">
                                    <div class="icon"><i data-feather="alert-circle"></i></div>
                                    <div class="sale-content">
                                        <h3>Vencidos</h3>
                                        <p>{{ $overdueCount }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="small-chart-view visitor-chart" id="visitor-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Main Chart --}}
            <div class="col-xxl-5 col-xl-4 col-sm-6 box-col-40">
                <div class="card recent-order">
                    <div class="card-header pb-0">
                        <h3>Tendencia de Ventas</h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="medium-chart">
                             <div class="recent-chart" id="recent-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Products --}}
            <div class="col-xl-4 col-sm-6 box-col-30">
                <div class="card top-products">
                    <div class="card-header pb-0">
                        <h3>Top Productos</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordernone">
                                <tbody>
                                    @forelse($topProducts as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="icon"><i data-feather="box"></i></div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5>{{ Str::limit($product->name, 15) }}</h5>
                                                    <p>{{ $product->total_qty }} Und.</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>Total</h5>
                                            <p>{{ number_format($product->total_amount, 2) }}</p>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2">Sin datos</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Invoices --}}
            <div class="col-xxl-3 col-xl-4 col-sm-6 box-col-30">
                <div class="card best-sellers">
                    <div class="card-header pb-0">
                        <h3>Cuentas por Cobrar</h3>
                    </div>
                    <div class="card-body">
                         <div class="table-responsive">
                            <table class="table table-bordernone">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingInvoices as $inv)
                                    <tr>
                                        <td>
                                             <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <h5>{{ $inv->issue_date->format('d/m') }}</h5>
                                                    <p>{{ $inv->issue_date->format('Y') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>{{ Str::limit($inv->customer->name ?? $inv->customer->business_name, 10) }}</h5>
                                        </td>
                                        <td>
                                            <h5>{{ number_format($inv->total, 2) }} </h5>
                                        </td>
                                        <td>
                                            <div class="status-showcase">
                                                <span class="badge badge-warning">{{ ucfirst($inv->payment_status) }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4">Sin datos</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Slider (Static/Preserved) --}}
             <div class="col-xxl-4 col-xl-5 col-md-7 box-col-40">
                <div class="items-slider">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <div class="card rated-product bg-secondary">
                            <div class="card-body">
                                <div class="img-wrapper"><img class="img-fluid"
                                        src="{{ asset('assets/images/dashboard-2/wellington-shoes.png') }}"
                                        alt="wellington-shoes"><span
                                        class="badge badge-primary rated-product-badge">Promocionado</span></div>
                                <div class="product-detail"> <a href="#">
                                        <h4>Sistema OSE</h4>
                                    </a>
                                    <h3>v1.0</h3>
                                    <ul class="rating-star">
                                        <li> <i class="fa fa-star"></i></li>
                                        <li> <i class="fa fa-star"></i></li>
                                        <li> <i class="fa fa-star"></i></li>
                                        <li> <i class="fa fa-star"></i></li>
                                        <li> <i class="fa fa-star"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick/slick.js') }}"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Re-init Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Common Sparkline Options
            var sparklineOptions = {
                chart: {
                    type: "bar",
                    height: 100,
                    stacked: true,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "40px",
                        borderRadius: 2
                    }
                },
                grid: { show: false },
                dataLabels: { enabled: false },
                legend: { show: false },
                xaxis: { show: false, labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { show: false, labels: { show: false } },
                tooltip: { enabled: false }
            };

            // Helper to get random data or real data
            var chartData = {!! json_encode($chartValues) !!};
            var last7Days = chartData.slice(-7);
             if(last7Days.length < 7) {
                 // Fill with 0 if not enough data
                 last7Days = Array(7).fill(0).map((v, i) => last7Days[i] || 0);
             }

            // 1. Sales Chart Sparkline
            var saleOptions = {
                ...sparklineOptions,
                series: [{ name: "Sales", data: last7Days }],
                colors: ["#7366ff", "#dad8e0"] // Primary color
            };
            new ApexCharts(document.querySelector("#sales-chart"), saleOptions).render();

            // 2. Income Chart Sparkline (Using same data for visual now)
            var incomeOptions = {
                ...sparklineOptions,
                series: [{ name: "Income", data: last7Days.map(v => v * 0.8) }],
                colors: ["#f73164", "#faded1"] // Secondary color
            };
            new ApexCharts(document.querySelector("#income-chart"), incomeOptions).render();

            // 3. Order Chart Sparkline
            var orderOptions = {
                ...sparklineOptions,
                series: [{ name: "Orders", data: last7Days.map(v => Math.max(v * 0.1, 1)) }],
                colors: ["#7366ff", "#dad8e0"]
            };
            new ApexCharts(document.querySelector("#order-chart"), orderOptions).render();

            // 4. Visitor/Overdue Chart Sparkline
            var visitorOptions = {
                ...sparklineOptions,
                series: [{ name: "Overdue", data: [5, 10, 2, 8, 1, 0, 5] }], // Dummy for overdue trend
                colors: ["#f73164", "#faded1"]
            };
            new ApexCharts(document.querySelector("#visitor-chart"), visitorOptions).render();


            // 5. Recent Chart (Main Area Chart)
            var recentOptions = {
                series: [{
                    name: "Ventas",
                    data: {!! json_encode($chartValues) !!}
                }],
                chart: {
                    height: 355,
                    type: "area",
                    toolbar: { show: false },
                    dropShadow: {
                        enabled: true,
                        top: 10,
                        left: 0,
                        blur: 3,
                        color: '#7366ff',
                        opacity: 0.15
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: "smooth", width: 2 },
                xaxis: {
                    type: "datetime",
                    categories: {!! json_encode($chartLabels) !!},
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                grid: { borderColor: "#f1f3f5" },
                colors: ["#7366ff"],
                fill: {
                  type: "gradient",
                  gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.05,
                    stops: [0, 100]
                  }
                }
            };
            new ApexCharts(document.querySelector("#recent-chart"), recentOptions).render();


            // Slick Slider (re-implementation if needed, or stick to template script)
            if($(".items-slider").length) {
                $(".items-slider").slick({
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    autoplay: true,
                    infinite: true,
                    responsive: [
                        { breakpoint: 421, settings: { slidesToShow: 1, slidesToScroll: 1 } }
                    ]
                });
            }
        });
    </script>
@endsection
