@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Email Application</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Email</li>
                        <li class="breadcrumb-item active"> Email Reading</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="email-wrap">
            <div class="row">
                <div class="col-xl-3 col-md-6 xl-30 box-col-30">
                    <div class="email-sidebar md-sidebar"><a class="btn btn-primary md-sidebar-toggle"
                            href="javascript:void(0)">email filter</a>
                        <div class="email-left-aside md-sidebar-aside">
                            <div class="card">
                                <div class="card-body">
                                    <div class="email-app-sidebar">
                                        <div class="d-flex">
                                            <div class="d-flex-size-email"><img class="me-3 rounded-circle"
                                                    src="{{ asset('assets/images/user/user.png') }}" alt=""></div>
                                            <div class="flex-grow-1"><a href="{{ route('user-profile')}}">
                                                    <h6 class="f-w-600">MARKJENCO</h6>
                                                </a>
                                                <p>Markjecno@gmail.com</p>
                                            </div>
                                        </div>
                                        <ul class="nav main-menu">
                                            <li class="nav-item"><a class="btn-primary btn-block btn-mail w-100"
                                                    id="pills-darkhome-tab" href="{{ route('email_compose')}}"><i
                                                        class="icofont icofont-envelope me-2"></i> NEW MAIL</a></li>
                                            <li class="nav-item"><a class="show" id="pills-darkprofile-tab"
                                                    href="{{ route('email_inbox')}}"><span class="title"><i class="icon-import"></i>
                                                        Inbox</span><span class="badge pull-right">(236)</span></a></li>
                                            <li><a href="{{ route('email_inbox')}}"><span class="title"><i class="icon-folder"></i>
                                                        All mail</span></a></li>
                                            <li><a href="{{ route('email_read')}}"><span class="title"><i
                                                            class="icon-new-window"></i> Sent</span><span
                                                        class="badge pull-right">(69)</span></a></li>
                                            <li><a href="javascript:void(0)"><span class="title"><i
                                                            class="icon-pencil-alt"></i> DRAFT</span><span
                                                        class="badge pull-right">(59)</span></a></li>
                                            <li><a href="javascript:void(0)"><span class="title"><i class="icon-trash"></i>
                                                        TRASH</span><span class="badge pull-right">(99)</span></a></li>
                                            <li><a href="javascript:void(0)"><span class="title"><i
                                                            class="icon-info-alt"></i> IMPORTANT</span></a></li>
                                            <li><a href="javascript:void(0)"><span class="title"><i class="icon-star"></i>
                                                        Starred</span></a></li>
                                            <li>
                                                <hr>
                                            </li>
                                            <li><a href="javascript:void(0)"><span class="title"><i class="icon-email"></i>
                                                        UNREAD</span></a></li>
                                            <li><a href="javascript:void(0)"><span class="title"><i
                                                            class="icon-export"></i> SPAM</span></a></li>
                                            <li><a href="javascript:void(0)"><span class="title"><i class="icon-share"></i>
                                                        OUTBOX</span></a></li>
                                            <li><a href="javascript:void(0)"><span class="title"><i class="icon-file"></i>
                                                        UPDATE</span></a></li>
                                            <li><a href="javascript:void(0)"><span class="title"><i class="icon-bell"></i>
                                                        ALERT</span></a></li>
                                            <li><a href="javascript:void(0)"><span class="title"><i
                                                            class="icon-notepad"></i> NOTES</span><span
                                                        class="badge pull-right">(20)</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-md-12 xl-70 box-col-70">
                    <div class="email-right-aside">
                        <div class="card email-body">
                            <div class="email-profile">
                                <div class="email-right-aside">
                                    <div class="email-body">
                                        <div class="email-content">
                                            <div class="email-top">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="d-flex"><img class="me-3 rounded-circle"
                                                                src="{{ asset('assets/images/user/user.png') }}" alt="">
                                                            <div class="flex-grow-1">
                                                                <h6 class="d-block">John Deo</h6>
                                                                <p>Inquiry about our theme pages design.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="email-wrapper">
                                                <div class="emailread-group">
                                                    <div class="read-group">
                                                        <p>Hello</p>
                                                        <p>Dear Sir Good Morning,</p>
                                                    </div>
                                                    <div class="read-group">
                                                        <h3>Inquiry about our theme pages design.</h3>
                                                        <p>Koho Admin is a full featured, multipurpose, premium bootstrap admin template built with Bootstrap 5 Framework, HTML5, CSS and JQuery.It has a huge collection of reusable UI components and integrated with latest jQuery plugins.</p>
                                                        <p class="m-t-10">It can be used for all type of Web applications like custom admin panel, app backend, CMS or CRM. UI components and integrated with latest jQuery plugins. Koho Admin is a full featured, multipurpose, premium bootstrap admin template built with Bootstrap 5 Framework.</p>
                                                    </div>
                                                </div>
                                                <div class="emailread-group">
                                                    <h6 class="text-muted mb-0"><i class="icofont icofont-clip"></i>
                                                        ATTACHMENTS</h6><a
                                                        class="text-muted text-end right-download font-primary f-w-600"
                                                        href="javascript:void(0)"><i
                                                            class="fa fa-long-arrow-down me-2"></i>Download All</a>
                                                    <div class="clearfix"></div>
                                                    <div class="attachment">
                                                        <ul>
                                                            <li><img class="img-fluid" src="{{ asset('assets/images/email/1.jpg') }}"
                                                                    alt=""></li>
                                                            <li><img class="img-fluid" src="{{ asset('assets/images/email/2.jpg') }}"
                                                                    alt=""></li>
                                                            <li><img class="img-fluid" src="{{ asset('assets/images/email/3.jpg') }}"
                                                                    alt=""></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="emailread-group">
                                                    <textarea class="form-control" rows="4" cols="50" placeholder="write about your nots"></textarea>
                                                    <div class="action-wrapper">
                                                        <ul class="actions">
                                                            <li><a class="btn btn-primary" href="javascript:void(0)"><i
                                                                        class="fa fa-reply me-2"></i>Reply</a></li>
                                                            <li><a class="btn btn-secondary" href="javascript:void(0)"><i
                                                                        class="fa fa-reply-all me-2"></i>Reply All</a></li>
                                                            <li><a class="btn btn-danger" href="javascript:void(0)"><i
                                                                        class="fa fa-share me-2"></i>Forward</a></li>
                                                        </ul>
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
    </div>
@endsection
