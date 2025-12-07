@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Avatars</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ui Kits</li>
                        <li class="breadcrumb-item active">Avatars</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="user-profile">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h3>Sizing</h3>
                        </div>
                        <div class="card-body avatar-showcase">
                            <div class="avatars">
                                <div class="avatar"><img class="img-100 rounded-circle" src="{{ asset('assets/images/user/1.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-90 rounded-circle" src="{{ asset('assets/images/user/1.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-80 rounded-circle" src="{{ asset('assets/images/user/1.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-70 rounded-circle" src="{{ asset('assets/images/user/1.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-60 rounded-circle" src="{{ asset('assets/images/user/1.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-50 rounded-circle" src="{{ asset('assets/images/user/1.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-40 rounded-circle" src="{{ asset('assets/images/user/1.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-30 rounded-circle" src="{{ asset('assets/images/user/1.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-20 rounded-circle" src="{{ asset('assets/images/user/1.jpg') }}"
                                        alt="#"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h3>Initials</h3>
                        </div>
                        <div class="card-body avatar-showcase">
                            <div class="avatars">
                                <div class="avatar"><img class="img-100 rounded-circle"
                                        src="{{ asset('assets/images/user/16.png') }}" alt="#"></div>
                                <div class="avatar"><img class="img-90 rounded-circle" src="{{ asset('assets/images/user/16.png') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-80 rounded-circle" src="{{ asset('assets/images/user/16.png') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-70 rounded-circle" src="{{ asset('assets/images/user/16.png') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-60 rounded-circle" src="{{ asset('assets/images/user/16.png') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-50 rounded-circle" src="{{ asset('assets/images/user/16.png') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-40 rounded-circle" src="{{ asset('assets/images/user/16.png') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-30 rounded-circle" src="{{ asset('assets/images/user/16.png') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-20 rounded-circle" src="{{ asset('assets/images/user/16.png') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-10 rounded-circle" src="{{ asset('assets/images/user/16.png') }}"
                                        alt="#"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h3>Status Indicator</h3>
                        </div>
                        <div class="card-body avatar-showcase">
                            <div class="avatars">
                                <div class="avatar"><img class="img-100 rounded-circle" src="{{ asset('assets/images/user/6.jpg') }}"
                                        alt="#">
                                    <div class="status"></div>
                                </div>
                                <div class="avatar"><img class="img-90 rounded-circle" src="{{ asset('assets/images/user/6.jpg') }}"
                                        alt="#">
                                    <div class="status"></div>
                                </div>
                                <div class="avatar"><img class="img-80 rounded-circle" src="{{ asset('assets/images/user/6.jpg') }}"
                                        alt="#">
                                    <div class="status"></div>
                                </div>
                                <div class="avatar"><img class="img-70 rounded-circle" src="{{ asset('assets/images/user/6.jpg') }}"
                                        alt="#">
                                    <div class="status"></div>
                                </div>
                                <div class="avatar"><img class="img-60 rounded-circle" src="{{ asset('assets/images/user/6.jpg') }}"
                                        alt="#">
                                    <div class="status"></div>
                                </div>
                                <div class="avatar"><img class="img-50 rounded-circle" src="{{ asset('assets/images/user/6.jpg') }}"
                                        alt="#">
                                    <div class="status"></div>
                                </div>
                                <div class="avatar"><img class="img-40 rounded-circle" src="{{ asset('assets/images/user/6.jpg') }}"
                                        alt="#">
                                    <div class="status"></div>
                                </div>
                                <div class="avatar"><img class="img-30 rounded-circle" src="{{ asset('assets/images/user/6.jpg') }}"
                                        alt="#">
                                    <div class="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h3>Groups</h3>
                        </div>
                        <div class="card-body avatar-showcase">
                            <div class="customers d-inline-block avatar-group">
                                <ul>
                                    <li class="d-inline-block"><img class="img-70 rounded-circle"
                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""></li>
                                    <li class="d-inline-block"><img class="img-70 rounded-circle"
                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""></li>
                                    <li class="d-inline-block"><img class="img-70 rounded-circle"
                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""></li>
                                </ul>
                            </div>
                            <div class="customers d-inline-block avatar-group">
                                <ul>
                                    <li class="d-inline-block"><img class="img-50 rounded-circle"
                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""></li>
                                    <li class="d-inline-block"><img class="img-50 rounded-circle"
                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""></li>
                                    <li class="d-inline-block"><img class="img-50 rounded-circle"
                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""></li>
                                </ul>
                            </div>
                            <div class="customers d-inline-block avatar-group">
                                <ul>
                                    <li class="d-inline-block"><img class="img-40 rounded-circle"
                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""></li>
                                    <li class="d-inline-block"><img class="img-40 rounded-circle"
                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""></li>
                                    <li class="d-inline-block"><img class="img-40 rounded-circle"
                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h3>Shape</h3>
                        </div>
                        <div class="card-body avatar-showcase">
                            <div class="avatars">
                                <div class="avatar"><img class="img-100 b-r-8" src="{{ asset('assets/images/user/7.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-90 b-r-30" src="{{ asset('assets/images/user/7.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-80 b-r-35" src="{{ asset('assets/images/user/7.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-70 rounded-circle" src="{{ asset('assets/images/user/7.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-60 b-r-25" src="{{ asset('assets/images/user/7.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar"><img class="img-50 b-r-15" src="{{ asset('assets/images/user/7.jpg') }}"
                                        alt="#"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h3>Ratio</h3>
                        </div>
                        <div class="card-body avatar-showcase">
                            <div class="avatars">
                                <div class="avatar ratio"><img class="b-r-8 img-100" src="{{ asset('assets/images/user/10.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar ratio"><img class="b-r-8 img-90" src="{{ asset('assets/images/user/10.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar ratio"><img class="b-r-8 img-80" src="{{ asset('assets/images/user/10.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar ratio"><img class="b-r-8 img-70" src="{{ asset('assets/images/user/10.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar ratio"><img class="b-r-8 img-60" src="{{ asset('assets/images/user/10.jpg') }}"
                                        alt="#"></div>
                                <div class="avatar ratio"><img class="b-r-8 img-50" src="{{ asset('assets/images/user/10.jpg') }}"
                                        alt="#"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
