@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>sweet Alert</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Bonus Ui</li>
                        <li class="breadcrumb-item active">sweet Alert</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Basic Examples</h3>
                    </div>
                    <div class="card-body btn-showcase">
                        <button class="btn btn-primary sweet-1" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-1']);">Basic</button>
                        <button class="btn btn-primary sweet-2" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-2']);">With Title alert</button>
                        <button class="btn btn-success sweet-3" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-3']);">Success alert</button>
                        <button class="btn btn-info sweet-4" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-4']);">Info alert</button>
                        <button class="btn btn-warning sweet-5" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-5']);">Warning alert</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Advanced State</h3>
                    </div>
                    <div class="card-body btn-showcase">
                        <button class="btn btn-success sweet-12" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-12']);">Success</button>
                        <button class="btn btn-danger sweet-11" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-11']);">Danger</button>
                        <button class="btn btn-info sweet-13" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-13']);">Information</button>
                        <button class="btn btn-warning sweet-10" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-10']);">Warning</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Alert State</h3>
                    </div>
                    <div class="card-body btn-showcase">
                        <button class="btn btn-success sweet-8" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-8']);">Success</button>
                        <button class="btn btn-danger sweet-7" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-7']);">Danger</button>
                        <button class="btn btn-info sweet-9" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-9']);">Information</button>
                        <button class="btn btn-warning sweet-6" type="button"
                            onclick="_gaq.push(['_trackEvent', 'example', 'try', 'sweet-6']);">Warning</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/app.js') }}"></script>
@endsection
