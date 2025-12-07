@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Morris Chart</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Morris Chart</li>
                        <li class="breadcrumb-item active">Charts</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Line Chart</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="morris-line-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Updating Data</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="updating-data-morris-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Decimal Data</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder float-start" id="decimal-morris-chart"></div>
                            <p class="float-end" id="choices"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Displaying X Labels Diagonally</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="x-Labels-Diagonally-morris-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Bar Line Chart</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="bar-line-chart-morris"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Displaying X Labels Diagonally(Bar Chart)</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="x-lable-morris-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Stacked Bars Chart</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="stacked-bar-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Simple Bar Charts</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="morris-simple-bar-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Area charts behaving like line Charts</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="graph123"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Donut Color Chart</h3>
                    </div>
                    <div class="card-body chart-block">
                        <div class="flot-chart-container">
                            <div class="flot-chart-placeholder" id="donut-color-chart-morris"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/chart/morris-chart/raphael.js') }}"></script>
    <script src="{{ asset('assets/js/chart/morris-chart/morris.js') }}"></script>
    <script src="{{ asset('assets/js/chart/morris-chart/prettify.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart/morris-chart/morris-script.js') }}"></script>
@endsection
