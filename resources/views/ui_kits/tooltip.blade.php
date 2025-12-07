@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Tooltip</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ui Kits</li>
                        <li class="breadcrumb-item active">Tooltip</li>
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
                        <h3>Basic Tooltip</h3>
                    </div>
                    <div class="card-body btn-showcase">
                        <button class="example-popover btn btn-primary" type="button" data-container="body"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Popover title">Hover Me</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Directions</h3>
                    </div>
                    <div class="card-body btn-showcase">
                        <button class="btn btn-primary" type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Tooltip on top">Tooltip on top</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="tooltip" data-bs-placement="right"
                            title="Tooltip on right">Tooltip on right</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                            title="Tooltip on bottom">Tooltip on bottom</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="tooltip" data-bs-placement="left"
                            title="Tooltip on left">Tooltip on left</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>HTML elements</h3>
                    </div>
                    <div class="card-body btn-showcase">
                        <button class="btn btn-primary" type="button" data-bs-toggle="m-tooltip" data-bs-trigger="click"
                            data-bs-placement="top"
                            title="&lt;em&gt;Tooltip&lt;/em&gt; &lt;u&gt;with&lt;/u&gt; &lt;b&gt;HTML&lt;/b&gt;"
                            data-bs-html="true"
                            data-content="And here's some amazing &lt;b&gt;HTML&lt;/b&gt; content. It's very &lt;code&gt;engaging&lt;/code&gt;. Right?"
                            data-original-title="Tooltip &lt;b&gt;with&lt;/b&gt; &lt;code&gt;HTML&lt;/code&gt;">Click
                            me</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="m-tooltip" data-bs-trigger="click"
                            data-bs-placement="right"
                            title="&lt;em&gt;Tooltip&lt;/em&gt; &lt;u&gt;with&lt;/u&gt; &lt;b&gt;HTML&lt;/b&gt;"
                            data-bs-html="true"
                            data-content="And here's some amazing &lt;b&gt;HTML&lt;/b&gt; content. It's very &lt;code&gt;engaging&lt;/code&gt;. Right?"
                            data-original-title="Tooltip &lt;b&gt;with&lt;/b&gt; &lt;code&gt;HTML&lt;/code&gt;">Click
                            me</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="m-tooltip" data-bs-trigger="click"
                            data-bs-placement="bottom"
                            title="&lt;em&gt;Tooltip&lt;/em&gt; &lt;u&gt;with&lt;/u&gt; &lt;b&gt;HTML&lt;/b&gt;"
                            data-bs-html="true"
                            data-content="And here's some amazing &lt;b&gt;HTML&lt;/b&gt; content. It's very &lt;code&gt;engaging&lt;/code&gt;. Right?"
                            data-original-title="Tooltip &lt;b&gt;with&lt;/b&gt; &lt;code&gt;HTML&lt;/code&gt;">Click
                            me</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="m-tooltip" data-bs-trigger="click"
                            data-bs-placement="left"
                            title="&lt;em&gt;Tooltip&lt;/em&gt; &lt;u&gt;with&lt;/u&gt; &lt;b&gt;HTML&lt;/b&gt;"
                            data-bs-html="true"
                            data-content="And here's some amazing &lt;b&gt;HTML&lt;/b&gt; content. It's very &lt;code&gt;engaging&lt;/code&gt;. Right?"
                            data-original-title="Tooltip &lt;b&gt;with&lt;/b&gt; &lt;code&gt;HTML&lt;/code&gt;">Click
                            me</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
