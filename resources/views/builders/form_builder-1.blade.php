@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Form Builder 1</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Builders</li>
                        <li class="breadcrumb-item active"> Form Builder 1</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="form-builder">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h3>Drag & Drop components</h3><span>Copy the html code from View HTML tab </span>
                        </div>
                        <div class="card-body">
                            <div class="row clearfix form-builder">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-builder-header-1">
                                        <ul class="nav nav-primary" id="formtabs"></ul>
                                    </div>
                                    <form class="form-horizontal theme-form" id="components">
                                        <fieldset>
                                            <div class="tab-content"></div>
                                        </fieldset>
                                    </form>
                                </div>
                                <div class="col-lg-12 col-xl-6 lg-mt-col">
                                    <div class="form-builder-header-1">
                                        <h4>Drag Elements Here</h4>
                                    </div>
                                    <div id="build">
                                        <form class="form-horizontal drag-box" id="target"></form>
                                    </div>
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
    <script src="{{ asset('assets/js/form-builder/form-builder-1/require.js') }}"></script>
    <script src="{{ asset('assets/js/form-builder/form-builder-1/main-built.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}') }}"></script>
@endsection
