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
                        <li class="breadcrumb-item active"> Email Inbox</li>
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
                    <div class="email-sidebar md-sidebar"><a class="btn btn-primary md-sidebar-toggle" href="javascript:void(0)">email
                            filter</a>
                        <div class="md-sidebar-aside email-left-aside">
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
                                        <ul class="nav main-menu" role="tablist">
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
                                <div>
                                    <div class="pe-0 b-r-light"></div>
                                    <div class="email-top">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="d-flex">
                                                    <label class="email-chek d-block">
                                                        <input class="checkbox_animated" type="checkbox" checked="">
                                                    </label>
                                                    <div class="flex-grow-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle"
                                                                id="dropdownMenuButton" type="button"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">Action</button>
                                                            <div class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuButton"><a
                                                                    class="dropdown-item"
                                                                    href="javascript:void(0)">Refresh</a><a
                                                                    class="dropdown-item" href="javascript:void(0)">Mark
                                                                    as important</a><a class="dropdown-item"
                                                                    href="javascript:void(0)">Move to span</a><a
                                                                    class="dropdown-item" href="javascript:void(0)">Move
                                                                    to trush </a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="inbox">
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox" checked="">
                                                </label><i class="like" data-feather="star"></i><img
                                                    class="me-3 rounded-circle" src="{{ asset('assets/images/ecommerce/01.jpg') }}"
                                                    alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Pork Lorn </h6>
                                                <p>Account recovered successfully. Welcome back to your accountIf you suspect you were locked out of your account because of changes made.</p>
                                                <span>11:59 PM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox" checked="">
                                                </label><i data-feather="star"></i><img class="me-3 rounded-circle"
                                                    src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Dee Deo</h6>
                                                <p>Art by Jack Daly Apply now: Graphic Designer @Shell The world is changing fast, and Shell is changing too–not only to keep up with the external pace.</p><span>09:59 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox" checked="">
                                                </label><i class="like" data-feather="star"></i><img
                                                    class="me-3 rounded-circle" src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                                    alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Peg Legge</h6>
                                                <p>Your password was changed. The password for your Google account was changed. If you didnt change it, you should recover your account.</p><span>07:59 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox">
                                                </label><i data-feather="star"></i><img class="me-3 rounded-circle"
                                                    src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Olive Yew</h6>
                                                <p>Looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage.
                                                    Ipsum passage.</p><span>05:59 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox" checked="">
                                                </label><i class="like" data-feather="star"></i><img
                                                    class="me-3 rounded-circle" src="{{ asset('assets/images/ecommerce/05.jpg') }}"
                                                    alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Abby Normal</h6>
                                                <p>Account recovered successfully. Welcome back to your accountIf you suspect you were locked out of your account because of changes made.</p>
                                                <span>09:29 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox">
                                                </label><i class="like" data-feather="star"></i><img
                                                    class="me-3 rounded-circle" src="{{ asset('assets/images/ecommerce/06.jpg') }}"
                                                    alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Clyde Stale</h6>
                                                <p>Art by Jack Daly Apply now: Graphic Designer @Shell The world is changing fast, and Shell is changing too–not only to keep up with the external pace.</p><span>06:03 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox">
                                                </label><i data-feather="star"></i><img class="me-3 rounded-circle"
                                                    src="{{ asset('assets/images/ecommerce/08.jpg') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Art Decco</h6>
                                                <p>Your password was changed. The password for your Google account was changed. If you didnt change it, you should recover your account.</p><span>01:11 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox" checked="">
                                                </label><i data-feather="star"></i><img class="me-3 rounded-circle"
                                                    src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Hugo First</h6>
                                                <p>Account recovered successfully. Welcome back to your accountIf you suspect you were locked out of your account because of changes made.</p><span>09:09 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox" checked="">
                                                </label><i data-feather="star"></i><img class="me-3 rounded-circle"
                                                    src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Col Fays</h6>
                                                <p>Art by Jack Daly Apply now: Graphic Designer @Shell The world is changing fast, and Shell is changing too–not only to keep up with the external pace.</p><span>06:10 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox">
                                                </label><i class="like" data-feather="star"></i><img
                                                    class="me-3 rounded-circle" src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                                    alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Toi Story</h6>
                                                <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment.</p>
                                                <span>09:59 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox">
                                                </label><i class="like" data-feather="star"></i><img
                                                    class="me-3 rounded-circle" src="{{ asset('assets/images/ecommerce/04.jpg') }}"
                                                    alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Jen Tile</h6>
                                                <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system.</p><span>00:59 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox">
                                                </label><i class="like" data-feather="star"></i><img
                                                    class="me-3 rounded-circle" src="{{ asset('assets/images/ecommerce/05.jpg') }}"
                                                    alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>C. Yasoon</h6>
                                                <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system.</p><span>05:59 AM</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex-size-email">
                                                <label class="d-block mb-0">
                                                    <input class="checkbox_animated" type="checkbox" checked="">
                                                </label><i data-feather="star"></i><img class="me-3 rounded-circle"
                                                    src="{{ asset('assets/images/ecommerce/06.jpg') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6>Anne Teak</h6>
                                                <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
                                                <span>09:59 AM</span>
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
