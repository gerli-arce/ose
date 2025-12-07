@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Owl Carousel</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Bonus Ui</li>
                        <li class="breadcrumb-item active">Owl Carousel</li>
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
                        <h3>Basic Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-1">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Responsive Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-2">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Center Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-3">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Merge Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-4">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Auto Width Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-5">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/11.jpg') }}" alt="">
                            </div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/12.jpg') }}" alt="">
                            </div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/13.jpg') }}" alt="">
                            </div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/14.jpg') }}" alt="">
                            </div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/15.jpg') }}" alt="">
                            </div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/11.jpg') }}" alt="">
                            </div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/12.jpg') }}" alt="">
                            </div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/13.jpg') }}" alt="">
                            </div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/14.jpg') }}" alt="">
                            </div>
                            <div class="item"><img src="{{ asset('assets/images/slider-auto-width/15.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>URL Hash Navigations</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-6">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Events</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-7">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Stage Padding Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-8">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Right to Left Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-9">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Lazy load Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-10">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Animate Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-12">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Auto Play Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-13">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Auto Height Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-14">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Mouse Wheel Example</h3>
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="owl-carousel-15">
                            <div class="item"><img src="{{ asset('assets/images/slider/1.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/2.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/3.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/4.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/5.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/6.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/7.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/8.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/9.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/10.jpg') }}" alt=""></div>
                            <div class="item"><img src="{{ asset('assets/images/slider/11.jpg') }}" alt=""></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/owlcarousel/owl-custom.js') }}"></script>
@endsection
