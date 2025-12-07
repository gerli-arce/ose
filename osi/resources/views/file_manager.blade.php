@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>
                        File Manager</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Apps</li>
                        <li class="breadcrumb-item active"> File Manager</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 box-col-30 pe-0">
                <div class="md-sidebar job-sidebar"><a class="btn btn-primary md-sidebar-toggle"
                        href="javascript:void(0)">file filter</a>
                    <div class="md-sidebar-aside custom-scrollbar">
                        <div class="file-sidebar">
                            <div class="card">
                                <div class="card-body">
                                    <ul>
                                        <li>
                                            <div class="btn btn-primary"><i data-feather="home"></i>Home </div>
                                        </li>
                                        <li>
                                            <div class="btn btn-light"><i data-feather="folder"></i>All </div>
                                        </li>
                                        <li>
                                            <div class="btn btn-light"><i data-feather="clock"></i>Recent </div>
                                        </li>
                                        <li>
                                            <div class="btn btn-light"><i data-feather="star"></i>Starred </div>
                                        </li>
                                        <li>
                                            <div class="btn btn-light"><i data-feather="alert-circle"></i>Recovery </div>
                                        </li>
                                        <li>
                                            <div class="btn btn-light"><i data-feather="trash-2"></i>Deleted </div>
                                        </li>
                                    </ul>
                                    <hr>
                                    <ul>
                                        <li>
                                            <div class="btn btn-outline-primary"><i data-feather="database"> </i>Storage
                                            </div>
                                            <div class="m-t-15">
                                                <div class="progress sm-progress-bar mb-1">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <h6>25 GB of 100 GB used</h6>
                                            </div>
                                        </li>
                                    </ul>
                                    <hr>
                                    <ul>
                                        <li>
                                            <div class="btn btn-outline-primary"><i data-feather="grid"> </i>Pricing plan
                                            </div>
                                        </li>
                                        <li>
                                            <div class="pricing-plan">
                                                <h6>Trial Version </h6>
                                                <h5>FREE</h5>
                                                <p> 100 GB Space</p>
                                                <div class="btn btn-outline-primary btn-xs">Selected</div><img
                                                    class="bg-img" src="{{ asset('assets/images/dashboard/folder.png') }}"
                                                    alt="">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-md-12 box-col-70">
                <div class="file-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-md-flex d-sm-block">
                                <form class="form-inline" action="#" method="get">
                                    <div class="form-group d-flex mb-0"> <i class="fa fa-search"></i>
                                        <input class="form-control-plaintext" type="text" placeholder="Search...">
                                    </div>
                                </form>
                                <div class="flex-grow-1 text-end">
                                    <form class="d-inline-flex" action="#" method="POST" enctype="multipart/form-data"
                                        name="myForm">
                                        <div class="btn btn-primary" onclick="getFile()"> <i
                                                data-feather="plus-square"></i>Add New</div>
                                        <div style="height: 0px;width: 0px; overflow:hidden;">
                                            <input id="upfile" type="file" onchange="sub(this)">
                                        </div>
                                    </form>
                                    <div class="btn btn-outline-primary ms-3"><i data-feather="upload"> </i>Upload </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body file-manager">
                            <h5>Quick Access </h5>
                            <ul class="quick-file d-flex flex-row">
                                <li>
                                    <div class="quick-box"><i class="fa fa-youtube-play font-danger"></i></div>
                                    <h6>Videos</h6>
                                </li>
                                <li>
                                    <div class="quick-box"><i class="fa fa-th font-info"></i></div>
                                    <h6>Apps</h6>
                                </li>
                                <li>
                                    <div class="quick-box"><i class="fa fa-file-text-o font-secondary"></i></div>
                                    <h6>Document</h6>
                                </li>
                                <li>
                                    <div class="quick-box"><i class="fa fa-music font-warning"></i></div>
                                    <h6>Music</h6>
                                </li>
                                <li>
                                    <div class="quick-box"><i class="fa fa-download font-primary"></i></div>
                                    <h6>Download</h6>
                                </li>
                                <li>
                                    <div class="quick-box"><i class="fa fa-folder font-info"></i></div>
                                    <h6>Folder</h6>
                                </li>
                                <li>
                                    <div class="quick-box"><i class="fa fa-file-archive-o font-secondary"></i></div>
                                    <h6>Zip File</h6>
                                </li>
                                <li>
                                    <div class="quick-box"><i class="fa fa-trash font-danger"></i></div>
                                    <h6>Trash</h6>
                                </li>
                            </ul>
                            <h5 class="mt-4">Folders</h5>
                            <ul class="folder">
                                <li class="folder-box">
                                    <div class="d-block"><i class="f-44 fa fa-file-archive-o txt-warning"></i><i
                                            class="fa fa-ellipsis-v me-0 f-14 ellips"></i>
                                        <div class="mt-3">
                                            <h6>Endless admin</h6>
                                            <p>20 files<span class="pull-right"> <i class="fa fa-clock-o"> </i> 2 Hour
                                                    ago</span></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="folder-box">
                                    <div class="d-block"><i class="f-44 fa fa-folder txt-warning"></i><i
                                            class="fa fa-ellipsis-v me-0 f-14 ellips"></i>
                                        <div class="mt-3">
                                            <h6>Universal admin</h6>
                                            <p>14 files<span class="pull-right"> <i class="fa fa-clock-o"> </i> 3 Hour
                                                    ago</span></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="folder-box">
                                    <div class="d-block"><i class="f-44 fa fa-file-archive-o txt-warning"></i><i
                                            class="fa fa-ellipsis-v me-0 f-14 ellips"></i>
                                        <div class="mt-3">
                                            <h6>Unice admin</h6>
                                            <p>15 files<span class="pull-right"> <i class="fa fa-clock-o"> </i> 3 Day
                                                    ago</span></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="folder-box">
                                    <div class="d-block"><i class="f-44 fa fa-folder txt-warning"></i><i
                                            class="fa fa-ellipsis-v me-0 f-14 ellips"></i>
                                        <div class="mt-3">
                                            <h6>Koho admin</h6>
                                            <p>10 files<span class="pull-right"> <i class="fa fa-clock-o"> </i> 1 Day
                                                    ago</span></p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <h5 class="mt-4">Files </h5>
                            <ul class="d-flex flex-row files-content">
                                <li class="folder-box d-flex align-items-center">
                                    <div class="d-flex align-items-center files-list">
                                        <div class="flex-shrink-0 file-left"><i class="f-22 fa fa-folder font-info"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-xxl-3 ms-xl-2">
                                            <h6>Logo.psd</h6>
                                            <p>2 Hours ago, 2.0 MB</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="folder-box d-flex align-items-center">
                                    <div class="d-flex align-items-center files-list">
                                        <div class="flex-shrink-0 file-left"><i
                                                class="f-22 fa fa-file-excel-o font-success"></i></div>
                                        <div class="flex-grow-1 ms-xxl-3 ms-xl-2">
                                            <h6>Backend.xls</h6>
                                            <p>2 Days ago, 3.00 GB</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="folder-box d-flex align-items-center">
                                    <div class="d-flex align-items-center files-list">
                                        <div class="flex-shrink-0 file-left"><i
                                                class="f-22 fa fa-file-archive-o font-warning"></i></div>
                                        <div class="flex-grow-1 ms-xxl-3 ms-xl-2">
                                            <h6>Project.zip</h6>
                                            <p>3 Hours ago, 1.90 GB</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="folder-box d-flex align-items-center">
                                    <div class="d-flex align-items-center files-list">
                                        <div class="flex-shrink-0 file-left"><i class="f-22 fa fa-folder font-info"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-xxl-3 ms-xl-2">
                                            <h6>Requirements.txt</h6>
                                            <p>1 Days ago, 0.90 KB</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon-clipart.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
@endsection
