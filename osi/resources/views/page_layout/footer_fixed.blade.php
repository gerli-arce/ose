@extends('page_layout.layout_pages.master')

@section('page-html')
    <html lang="en">
@endsection

@section('page-body')

    <body>
    @endsection

    @section('page-content')
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>Footer Fixed</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                            <li class="breadcrumb-item">Page Layout</li>
                            <li class="breadcrumb-item active">Footer Fixed</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h3> Sample Card</h3><span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span>
                        </div>
                        <div class="card-body">
                            <p>
                                "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                                sunt in culpa qui officia deserunt mollit anim id est laborum."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('page-footer')
        <footer class="footer footer-fix">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-10 p-0 footer-left">
                        <p class="mb-0">Copyright 2022 © Koho theme by pixelstrap</p>
                    </div>
                    <div class="col-2 p-0 footer-right"> <i class="fa fa-heart font-danger"> </i></div>
                </div>
            </div>
        </footer>
    @endsection

    @section('page-scripts')
    @endsection
