@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Timeline 2</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Timeline</li>
                        <li class="breadcrumb-item active">Timeline 2</li>
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
                        <h3>Example</h3>
                    </div>
                    <div class="card-body">
                        <div id="timeline-2">
                            <div data-year="2010">Start</div>
                            <div class="active" data-year="2011">Lorem is simply dummy text of the printing and typesetting
                                industry. the printing and typesetting industry.</div>
                            <div data-year="2013">Lorem is simply dummy text of the printing and typesetting industry.
                            </div>
                            <div data-year="2014">Lorem is simply dummy text of the printing and typesetting industry.</div>
                            <div data-year="2015">Lorem is simply dummy text of the printing and typesetting industry.</div>
                            <div data-year="2017">Lorem is simply dummy text of the printing and typesetting industry.</div>
                            <div data-year="2018">End.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/timeline/timeline-v-2/jquery.timeliny.min.js') }}"></script>
    <script src="{{ asset('assets/js/timeline/timeline-v-2/timeline-v-2-custom.js') }}"></script>
@endsection
