@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Apex Chart</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Charts</li>
                        <li class="breadcrumb-item active">Apex Chart</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Basic Area Chart </h3>
                    </div>
                    <div class="card-body">
                        <div id="basic-apex"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Area Spaline Chart </h3>
                    </div>
                    <div class="card-body">
                        <div id="area-spaline"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Bar chart</h3>
                    </div>
                    <div class="card-body">
                        <div id="basic-bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Column Chart </h3>
                    </div>
                    <div class="card-body">
                        <div id="column-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>
                            3d Bubble Chart </h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-bubble"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Stepline Chart </h3>
                    </div>
                    <div class="card-body">
                        <div id="stepline"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Column Chart</h3>
                    </div>
                    <div class="card-body">
                        <div id="annotationchart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Pie Chart </h3>
                    </div>
                    <div class="card-body apex-chart">
                        <div id="piechart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Donut Chart</h3>
                    </div>
                    <div class="card-body apex-chart">
                        <div id="donutchart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Mixed Chart</h3>
                    </div>
                    <div class="card-body">
                        <div id="mixedchart"> </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Candlestick Chart </h3>
                    </div>
                    <div class="card-body">
                        <div id="candlestick"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Radar Chart </h3>
                    </div>
                    <div class="card-body">
                        <div id="radarchart"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6 box-col-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Radial Bar Chart</h3>
                    </div>
                    <div class="card-body">
                        <div id="circlechart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
@endsection
