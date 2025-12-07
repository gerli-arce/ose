@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Tag & Pills</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item"> Ui Kits</li>
                        <li class="breadcrumb-item active">Tag & Pills</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid tagpills-badge">
        <div class="row">
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Contextual variations</h3>
                    </div>
                    <div class="card-body"><span class="badge badge-primary">Primary</span><span
                            class="badge badge-secondary">Secondary</span><span
                            class="badge badge-success">Success</span><span class="badge badge-info">Info</span><span
                            class="badge badge-warning text-dark">Warning</span><span
                            class="badge badge-danger">Danger</span><span
                            class="badge badge-light text-dark">Light</span><span
                            class="badge badge-dark tag-pills-sm-mb">Dark</span></div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Contextual variations</h3>
                    </div>
                    <div class="card-body"><span class="badge rounded-pill badge-primary">Primary</span><span
                            class="badge rounded-pill badge-secondary">Secondary</span><span
                            class="badge rounded-pill badge-success">Success</span><span
                            class="badge rounded-pill badge-info">Info</span><span
                            class="badge rounded-pill badge-warning text-dark">Warning</span><span
                            class="badge rounded-pill badge-danger">Danger</span><span
                            class="badge rounded-pill badge-light text-dark">Light</span><span
                            class="badge rounded-pill badge-dark tag-pills-sm-mb">Dark</span></div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Tags with number</h3>
                    </div>
                    <div class="card-body"><a class="badge badge-primary" href="javascript:void(0)">1</a><a
                            class="badge badge-secondary" href="javascript:void(0)">2</a><a class="badge badge-success"
                            href="javascript:void(0)">3</a><a class="badge badge-info" href="javascript:void(0)">4</a><a
                            class="badge badge-warning text-dark" href="javascript:void(0)">5</a><a
                            class="badge badge-danger" href="javascript:void(0)">6</a><a class="badge badge-light text-dark"
                            href="javascript:void(0)">7</a><a class="badge badge-dark" href="javascript:void(0)">8</a></div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Pills with number</h3>
                    </div>
                    <div class="card-body"><span class="badge rounded-pill badge-primary">1</span><span
                            class="badge rounded-pill badge-secondary">2</span><span
                            class="badge rounded-pill badge-success">3</span><span
                            class="badge rounded-pill badge-info">4</span><span
                            class="badge rounded-pill badge-warning text-dark">5</span><span
                            class="badge rounded-pill badge-danger">6</span><span
                            class="badge rounded-pill badge-light text-dark">7</span><span
                            class="badge rounded-pill badge-dark">8</span></div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Tags With icon</h3>
                    </div>
                    <div class="card-body"><a class="badge badge-primary" href="javascript:void(0)"><i
                                data-feather="dollar-sign"></i></a><a class="badge badge-secondary"
                            href="javascript:void(0)"><i data-feather="headphones"></i></a><a class="badge badge-success"
                            href="javascript:void(0)"><i data-feather="link"></i></a><a class="badge badge-info"
                            href="javascript:void(0)"><i data-feather="github"></i></a><a
                            class="badge badge-warning text-dark" href="javascript:void(0)"><i
                                data-feather="award"></i></a><a class="badge badge-danger" href="javascript:void(0)"><i
                                data-feather="activity"></i></a><a class="badge badge-light text-dark"
                            href="javascript:void(0)"><i data-feather="heart"></i></a><a class="badge badge-dark"
                            href="javascript:void(0)"><i data-feather="mail"></i></a></div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Pills with Icon</h3>
                    </div>
                    <div class="card-body"><a class="badge rounded-pill badge-primary" href="javascript:void(0)"><i
                                data-feather="dollar-sign"></i></a><a class="badge rounded-pill badge-secondary"
                            href="javascript:void(0)"><i data-feather="headphones"></i></a><a
                            class="badge rounded-pill badge-success" href="javascript:void(0)"><i
                                data-feather="link"></i></a><a class="badge rounded-pill badge-info"
                            href="javascript:void(0)"><i data-feather="github"></i></a><a
                            class="badge rounded-pill badge-warning text-dark" href="javascript:void(0)"><i
                                data-feather="award"></i></a><a class="badge rounded-pill badge-danger"
                            href="javascript:void(0)"><i data-feather="activity"></i></a><a
                            class="badge rounded-pill badge-light text-dark" href="javascript:void(0)"><i
                                data-feather="heart"></i></a><a class="badge rounded-pill badge-dark"
                            href="javascript:void(0)"><i data-feather="mail"></i></a></div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card height-equal">
                    <div class="card-header pb-0">
                        <h3>Badges Example</h3>
                    </div>
                    <div class="card-body">
                        <h1>heading <span class="badge badge-primary">New</span></h1>
                        <h2>heading <span class="badge badge-primary">New</span></h2>
                        <h3>heading <span class="badge badge-primary">New</span></h3>
                        <h4>heading <span class="badge badge-primary">New</span></h4>
                        <h5>heading <span class="badge badge-primary">New</span></h5>
                        <h6>heading <span class="badge badge-primary">New</span></h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card height-equal">
                    <div class="card-header pb-0">
                        <h3>Badges as part buttons</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <button class="btn btn-primary" type="button">Messages <span
                                    class="badge rounded-pill badge-light text-dark"><i
                                        data-feather="mail"></i></span></button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-secondary" type="button">notifications <span
                                    class="badge rounded-pill badge-light text-dark"><i
                                        data-feather="bell"></i></span></button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-success" type="button">Update available <span
                                    class="badge rounded-pill badge-light text-dark"><i
                                        data-feather="settings"></i></span></button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-info" type="button">Playing Now <span
                                    class="badge rounded-pill badge-light text-dark"><i
                                        data-feather="music"></i></span></button>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-warning text-dark" type="button">1.2 GB Used <span
                                    class="badge rounded-pill badge-light text-dark"><i
                                        data-feather="alert-triangle"></i></span></button>
                        </div>
                        <div>
                            <button class="btn btn-danger" type="button">Alert <span
                                    class="badge rounded-pill badge-light text-dark"><i
                                        data-feather="alert-circle"></i></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
