@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Dropdown</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ui Kits</li>
                        <li class="breadcrumb-item active">Dropdown</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid dropdown-page">
        <div class="row">
            <div class="col-sm-12 col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Basic Dropdown</h3>
                    </div>
                    <div class="card-body dropdown-basic">
                        <div class="dropdown">
                            <button class="dropbtn btn-primary" type="button">Dropdown Button <span><i
                                        class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                    href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something Else
                                    Here</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Basic Color Dropdown</h3>
                    </div>
                    <div class="card-body dropdown-basic">
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-primary" type="button">Action <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-secondary" type="button">Action <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-success" type="button">Action <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-info" type="button">Action <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-warning txt-dark" type="button">Action <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-danger" type="button">Action <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Dropdown Split Button</h3>
                    </div>
                    <div class="card-body dropdown-basic">
                        <div class="btn-group">
                            <button class="btn btn-primary" type="button">Primary Button</button>
                            <div class="dropdown separated-btn">
                                <button class="btn btn-primary" type="button"><i
                                        class="icofont icofont-arrow-down"></i></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Link 1</a><a
                                        href="javascript:void(0)">Link 2</a><a href="javascript:void(0)">Link 3</a></div>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-secondary" type="button">Secondary Button</button>
                            <div class="dropdown separated-btn">
                                <button class="btn btn-secondary" type="button"><i
                                        class="icofont icofont-arrow-down"></i></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Link 1</a><a
                                        href="javascript:void(0)">Link 2</a><a href="javascript:void(0)">Link 3</a></div>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-success" type="button">Success Button</button>
                            <div class="dropdown separated-btn">
                                <button class="btn btn-success" type="button"><i
                                        class="icofont icofont-arrow-down"></i></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Link 1</a><a
                                        href="javascript:void(0)">Link 2</a><a href="javascript:void(0)">Link 3</a></div>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-info" type="button">Info Button</button>
                            <div class="dropdown separated-btn">
                                <button class="btn btn-info" type="button"><i
                                        class="icofont icofont-arrow-down"></i></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Link 1</a><a
                                        href="javascript:void(0)">Link 2</a><a href="javascript:void(0)">Link 3</a></div>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-warning" type="button">Warning Button</button>
                            <div class="dropdown separated-btn">
                                <button class="btn btn-warning" type="button"><i
                                        class="icofont icofont-arrow-down"></i></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Link 1</a><a
                                        href="javascript:void(0)">Link 2</a><a href="javascript:void(0)">Link 3</a></div>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-danger" type="button">Danger Button</button>
                            <div class="dropdown separated-btn">
                                <button class="btn btn-danger" type="button"><i
                                        class="icofont icofont-arrow-down"></i></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Link 1</a><a
                                        href="javascript:void(0)">Link 2</a><a href="javascript:void(0)">Link 3</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Rounded Dropdown</h3>
                    </div>
                    <div class="card-body dropdown-basic">
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-primary btn-round" type="button">Primary Button <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-secondary btn-round" type="button">Secondary Button <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-success btn-round" type="button">Success Button <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-info btn-round" type="button">Info Button <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-warning txt-dark btn-round" type="button">Warning Button
                                    <span><i class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <div class="btn-group mb-0">
                                <button class="dropbtn btn-danger btn-round" type="button">Danger Button <span><i
                                            class="icofont icofont-arrow-down"></i></span></button>
                                <div class="dropdown-content"><a href="javascript:void(0)">Action</a><a
                                        href="javascript:void(0)">Another Action</a><a href="javascript:void(0)">Something
                                        Else Here</a><a href="javascript:void(0)">Separated Link </a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Dropdown With Divider</h3><span>Use a class </span>
                    </div>
                    <div class="card-body dropdown-basic">
                        <div class="dropdown">
                            <button class="dropbtn btn-primary" type="button">Dropdown Button <span><i
                                        class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content"><a href="javascript:void(0)">Link 1</a><a
                                    href="javascript:void(0)">Link 2</a><a href="javascript:void(0)">Link 3</a><a
                                    href="javascript:void(0)">Another Link</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Dropdown With Header</h3><span>Use a class <code>.dropdown-header</code></span>
                    </div>
                    <div class="card-body dropdown-basic">
                        <div class="dropdown">
                            <button class="dropbtn btn-primary" type="button">Dropdown Button <span><i
                                        class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content">
                                <h5 class="dropdown-header">Dropdown header</h5><a href="javascript:void(0)">Link 1</a><a
                                    href="javascript:void(0)">Link 2</a>
                                <h5 class="dropdown-header">Dropdown header</h5><a href="javascript:void(0)">Another
                                    Link</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Dropdown With Disable</h3><span>Use a class <code>.dropdown-disabled</code></span>
                    </div>
                    <div class="card-body dropup-basic dropdown-basic">
                        <div class="dropup dropdown">
                            <button class="dropbtn btn-primary" type="button">Dropdown Button <span><i
                                        class="icofont icofont-arrow-up"></i></span></button>
                            <div class="dropup-content dropdown-content"><a href="javascript:void(0)">Normal</a><a
                                    class="active" href="javascript:void(0)">Active</a><a class="disabled"
                                    href="javascript:void(0)">Disabled</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Dropdown With DropUp</h3><span>Use a class <code>.drop-up</code></span>
                    </div>
                    <div class="card-body dropup-basic dropdown-basic">
                        <div class="dropup dropdown">
                            <button class="dropbtn btn-primary" type="button">Dropdown Button <span><i
                                        class="icofont icofont-arrow-up"></i></span></button>
                            <div class="dropup-content dropdown-content"><a href="javascript:void(0)">Link 1</a><a
                                    href="javascript:void(0)">Link 2</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
