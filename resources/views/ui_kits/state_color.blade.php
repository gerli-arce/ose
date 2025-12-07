@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>State Color</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ui Kits</li>
                        <li class="breadcrumb-item active">State Color</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts                    -->
    <div class="container-fluid state-color">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Default Color</h3>
                    </div>
                    <div class="card-body">
                        <div class="color-box">
                            <button class="btn btn-primary">#3e5fce</button>
                            <button class="btn btn-secondary">#f73164</button>
                            <button class="btn btn-success">#51bb25</button>
                            <button class="btn btn-info">#a927f9</button>
                            <button class="btn btn-warning">#f8d62b</button>
                            <button class="btn btn-danger">#dc3545</button>
                            <button class="btn btn-light">#f4f4f4</button>
                            <button class="btn btn-dark">#2c323f</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
