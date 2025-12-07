@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3> Project List</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Project</li>
                        <li class="breadcrumb-item active">Project List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row project-cards">
            <div class="col-md-12 project-list">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6 p-0 d-flex">
                            <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab"
                                        href="#top-home" role="tab" aria-controls="top-home" aria-selected="true"><i
                                            data-feather="target"></i>All</a></li>
                                <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-bs-toggle="tab"
                                        href="#top-profile" role="tab" aria-controls="top-profile"
                                        aria-selected="false"><i data-feather="info"></i>Doing</a></li>
                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                        href="#top-contact" role="tab" aria-controls="top-contact"
                                        aria-selected="false"><i data-feather="check-circle"></i>Done</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6 p-0">
                            <div class="form-group mb-0 me-0"></div><a class="btn btn-primary" href="{{ route('projectcreate')}}"> <i
                                    data-feather="plus-square"> </i>Create New Project</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content" id="top-tabContent">
                            <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                                aria-labelledby="top-home-tab">
                                <div class="row">
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-primary">Doing</span>
                                            <h4>Endless admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt="" data-original-title=""
                                                    title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Endless Admin featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-secondary">Done</span>
                                            <h4>Universal admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Universal Admin featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-secondary">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-secondary">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-secondary">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-secondary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-success">Done</span>
                                            <h4>Poco admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Poco Admin featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-success">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-success">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-success">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-info">Done</span>
                                            <h4>Xolo admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/4.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Xolo Admin featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-info">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-info">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-info">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-info" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-warning">Doing</span>
                                            <h4>Zeta admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Zeta Admin featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-warning">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-warning">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-warning">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-warning progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-danger">Done</span>
                                            <h4>Tivo admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Tivo Admin featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-danger">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-danger">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-danger">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-danger" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="top-profile" role="tabpanel"
                                aria-labelledby="profile-top-tab">
                                <div class="row">
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-primary">Doing</span>
                                            <h4>Endless admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Endless Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-primary">Doing</span>
                                            <h4>Universal admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Universal Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-primary">Doing</span>
                                            <h4>Poco admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Poco Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-primary">Doing</span>
                                            <h4>Tivo admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Tivo Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-primary">Doing</span>
                                            <h4>Koho admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Koho Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-primary">Doing</span>
                                            <h4>Xolo admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Xolo Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="top-contact" role="tabpanel"
                                aria-labelledby="contact-top-tab">
                                <div class="row">
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-secondary">Done</span>
                                            <h4>Endless admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Endless Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-secondary">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-secondary">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-secondary">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-secondary progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-secondary">Done</span>
                                            <h4>Universal admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Universal Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-secondary">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-secondary">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-secondary">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-secondary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-secondary">Done</span>
                                            <h4>Poco admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Poco Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-secondary">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-secondary">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-secondary">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-secondary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-secondary">Done</span>
                                            <h4>Xolo admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Xolo Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-secondary">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-secondary">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-secondary">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-secondary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-primary">Done</span>
                                            <h4>Zeta admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Zeta Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-secondary">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-secondary">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-secondary">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-secondary progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-xl-4 col-md-6 box-col-33">
                                        <div class="project-box"><span class="badge badge-secondary">Done</span>
                                            <h4>Tivo admin Design</h4>
                                            <div class="d-flex"><img class="img-20 me-2 rounded-circle"
                                                    src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Tivo Admin is a full featured, multipurpose, premium bootstrap admin template.
                                            </p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-secondary">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-secondary">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-secondary">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-secondary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
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
    <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
@endsection
