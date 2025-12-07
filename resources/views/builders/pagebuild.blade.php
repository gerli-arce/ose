@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/page-builder.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Page Builder</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Builders</li>
                        <li class="breadcrumb-item active">Page Builder</li>
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
                        <h3>Page Builder</h3>
                    </div>
                    <div class="card-body">
                        <!-- Page grid builder start-->
                        <div class="page-builder">
                            <div id="myGrid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Lorem ipsum dolor sit amet, consectetur</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit exercitationem
                                            eaque aperiam rem quia quibusdam dolor ducimus quo similique eos pariatur
                                            nostrum aliquam nam eius, doloremque quis voluptatum unde. Porro voluptates
                                            aspernatur voluptate ipsam, magni vero. Accusamus, iusto tempore id!</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quae laboriosam,
                                            excepturi quas.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <h3>Lorem ipsum dolor</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea facilis vel aliquam
                                            aspernatur dolor placeat totam saepe perferendis. Odio quia vel sed eveniet
                                            cupiditate, illum doloremque sint veniam eum? Corporis?</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h3>Pariatur reprehenderit</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo adipisci ipsa,
                                            consequuntur cum, sunt dolores veniam. Enim inventore in dolore deserunt vitae
                                            sequi nemo!</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h3>Pariatur reprehenderit</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea excepturi ducimus
                                            numquam aut error corporis aspernatur ipsum quos eius culpa!</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3>Lorem ipsum dolor.</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro distinctio atque
                                            molestiae optio, consequuntur? Iusto ratione cumque dolor aut dolore!</p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3>Lorem ipsum dolor.</h3>
                                                <hr>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis facilis
                                                    molestias voluptatum laudantium fuga ratione tempora rem dolor dicta
                                                    rerum vero ut, suscipit ex qui amet quam vel cupiditate quaerat minus
                                                    assumenda reiciendis, similique omnis delectus! Autem, repudiandae
                                                    cumque eaque?</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h3>Lorem ipsum dolor.</h3>
                                                <hr>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eveniet
                                                    molestiae quaerat illum, consequuntur iusto aspernatur quam provident?
                                                    Possimus!</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h3>Lusto ratione</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quis fugit quasi
                                            officiis id laudantium error aut ut aperiam dicta saepe non vel, cupiditate
                                            illum ipsam velit deleniti natus incidunt impedit molestias dolore quos dolores
                                            enim. Aliquid ipsam eaque consequuntur quaerat, suscipit a in. Praesentium
                                            repudiandae quibusdam recusandae sequi eligendi quos, dignissimos, officiis
                                            officia minima necessitatibus eaque consequatur in id adipisci qui minus
                                            voluptatum quae debitis, quas maxime iure. Tempore vero unde quia reiciendis ad
                                            beatae voluptate omnis, ipsa expedita ab, quasi, neque. Doloribus, pariatur. Aut
                                            hic voluptate.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Page grid builder start-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/editor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/editor/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/page-builder/jquery.grideditor.min.js') }}"></script>
    <script src="{{ asset('assets/js/page-builder/page-builder-custom.js') }}"></script>
@endsection
