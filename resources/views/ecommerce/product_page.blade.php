@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/rating.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Product Page</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ecommerce</li>
                        <li class="breadcrumb-item active">Product Page</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div>
            <div class="row product-page-main p-0">
                <div class="col-xl-5 col-md-6 box-col-7 xl-50">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-8 product-main">
                                    <div class="pro-slide-single">
                                        <div><img class="img-fluid" src="{{ asset('assets/images/ecommerce/01.jpg') }}"
                                                alt=""></div>
                                        <div><img class="img-fluid" src="{{ asset('assets/images/ecommerce/02.jpg') }}"
                                                alt=""></div>
                                        <div><img class="img-fluid" src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                                alt=""></div>
                                        <div><img class="img-fluid" src="{{ asset('assets/images/ecommerce/04.jpg') }}"
                                                alt=""></div>
                                        <div><img class="img-fluid" src="{{ asset('assets/images/ecommerce/05.jpg') }}"
                                                alt=""></div>
                                        <div><img class="img-fluid" src="{{ asset('assets/images/ecommerce/06.jpg') }}"
                                                alt=""></div>
                                        <div><img class="img-fluid" src="{{ asset('assets/images/ecommerce/07.jpg') }}"
                                                alt=""></div>
                                        <div><img class="img-fluid" src="{{ asset('assets/images/ecommerce/08.jpg') }}"
                                                alt=""></div>
                                    </div>
                                </div>
                                <div class="col-xl-4 product-thumbnail">
                                    <div class="pro-slide-right">
                                        <div>
                                            <div class="slide-box"><img src="{{ asset('assets/images/ecommerce/01.jpg') }}"
                                                    alt=""></div>
                                        </div>
                                        <div>
                                            <div class="slide-box"><img src="{{ asset('assets/images/ecommerce/02.jpg') }}"
                                                    alt=""></div>
                                        </div>
                                        <div>
                                            <div class="slide-box"><img src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                                    alt=""></div>
                                        </div>
                                        <div>
                                            <div class="slide-box"><img src="{{ asset('assets/images/ecommerce/04.jpg') }}"
                                                    alt=""></div>
                                        </div>
                                        <div>
                                            <div class="slide-box"><img src="{{ asset('assets/images/ecommerce/05.jpg') }}"
                                                    alt=""></div>
                                        </div>
                                        <div>
                                            <div class="slide-box"><img src="{{ asset('assets/images/ecommerce/06.jpg') }}"
                                                    alt=""></div>
                                        </div>
                                        <div>
                                            <div class="slide-box"><img src="{{ asset('assets/images/ecommerce/07.jpg') }}"
                                                    alt=""></div>
                                        </div>
                                        <div>
                                            <div class="slide-box"><img src="{{ asset('assets/images/ecommerce/08.jpg') }}"
                                                    alt=""></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 box-col-12 proorder-xl-3 xl-100">
                    <div class="card">
                        <div class="card-body">
                            <div class="pro-group pt-0 border-0">
                                <div class="product-page-details mt-0">
                                    <h3>Women Pink shirt.</h3>
                                    <div class="pro-review">
                                        <div class="d-flex">
                                            <select id="u-rating-fontawesome" name="rating" autocomplete="off">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select><span>(250 review)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-price">$26.00
                                    <del>$350.00 </del>
                                </div>
                                <div class="color-selector">
                                    <ul>
                                        <li class="active white"></li>
                                        <li class="bg-primary"> </li>
                                        <li class="bg-secondary"></li>
                                        <li class="bg-success"></li>
                                        <li class="bg-warning"></li>
                                        <li class="bg-danger"></li>
                                    </ul>
                                </div><a class="btn btn-secondary mt-0" href="{{ route('cart')}}"><i
                                        class="fa fa-shopping-cart me-2"></i>Buy Now</a>
                            </div>
                            <div class="pro-group">
                                <p>Rock Paper Scissors Various Dots Half Sleeves Girl’s Regular Fit T-Shirt I 100% Cotton T Shirt with Half Sleeve Round Neck I Regular Wear Solid Kids Tees and Black Sleeve.</p>
                            </div>
                            <div class="pro-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td> <b>Availability &nbsp;: &nbsp;</b></td>
                                                    <td class="txt-success">In stock</td>
                                                </tr>
                                                <tr>
                                                    <td> <b>Brand
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b>
                                                    </td>
                                                    <td>Pixelstrap</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td> <b>Seller &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:
                                                            &nbsp;&nbsp;&nbsp;</b></td>
                                                    <td>ABC</td>
                                                </tr>
                                                <tr>
                                                    <td> <b>Fabric &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;:
                                                            &nbsp;&nbsp;&nbsp;</b></td>
                                                    <td>Cotton </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="pro-group">
                                <div class="row">
                                    <div class="col-md-4 xl-50 box-col-30">
                                        <h4 class="product-title">share it</h4>
                                    </div>
                                    <div class="col-md-7 xl-50 box-col-70">
                                        <div class="product-icon">
                                            <ul class="product-social">
                                                <li class="d-inline-block"><a href="https://www.facebook.com"
                                                        target="_blank"><i class="fa fa-facebook"></i></a></li>
                                                <li class="d-inline-block"><a href="https://www.google.com/"
                                                        target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                                <li class="d-inline-block"><a href="https://twitter.com/"
                                                        target="_blank"><i class="fa fa-twitter"></i></a></li>
                                                <li class="d-inline-block"><a href="https://www.instagram.com/"
                                                        target="_blank"><i class="fa fa-instagram"> </i></a></li>
                                            </ul>
                                            <form class="d-inline-block f-right"> </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pro-group pb-0">
                                <div class="pro-shop"><a class="btn btn-primary m-r-10" href="{{ route('cart')}}"> <i
                                            class="fa fa-shopping-basket me-2"></i>Add To Cart</a><a
                                        class="btn btn-danger" href="{{ route('list-wish')}}"><i class="fa fa-heart me-2"></i>Add
                                        To WishList </a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 box-col-5 xl-50">
                    <div class="card">
                        <div class="card-body">
                            <!-- side-bar colleps block stat-->
                            <div class="filter-block">
                                <h4>Brand </h4>
                                <ul>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" id="Raymond" type="checkbox"
                                                value="">
                                            <label class="form-check-label" for="Raymond">Raymond</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" id="Pepe-Jeans" type="checkbox"
                                                value="">
                                            <label class="form-check-label" for="Pepe-Jeans">Pepe-Jeans</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" id="Celio" type="checkbox"
                                                value="">
                                            <label class="form-check-label" for="Celio">Celio</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" id="aime" type="checkbox"
                                                value="">
                                            <label class="form-check-label" for="aime">aime</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" id="aliff" type="checkbox"
                                                value="">
                                            <label class="form-check-label" for="aliff">aliff</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="collection-filter-block">
                                <ul class="pro-services">
                                    <li>
                                        <div class="d-flex"><i data-feather="truck"></i>
                                            <div class="flex-grow-1">
                                                <h5>Free Shipping </h5>
                                                <p>Free Shipping World Wide</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex"><i data-feather="clock"></i>
                                            <div class="flex-grow-1">
                                                <h5>24 X 7 Service </h5>
                                                <p>Online Service For New Customer </p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- silde-bar colleps block end here-->
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="row product-page-main">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs border-tab mb-0" id="top-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab"
                                href="#top-home" role="tab" aria-controls="top-home"
                                aria-selected="false">Fabric</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-bs-toggle="tab"
                                href="#top-profile" role="tab" aria-controls="top-profile"
                                aria-selected="false">Video</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                href="#top-contact" role="tab" aria-controls="top-contact"
                                aria-selected="true">Details</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="brand-top-tab" data-bs-toggle="tab"
                                href="#top-brand" role="tab" aria-controls="top-brand"
                                aria-selected="true">Brand</a>
                            <div class="material-border"></div>
                        </li>
                    </ul>
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade active show" id="top-home" role="tabpanel"
                            aria-labelledby="top-home-tab">
                            <p class="mb-0 m-t-20">Refresh your wardrobe with this chic top. With an eye-catching square neck, this top also features pretty puff sleeves. Stunning pink colour Classic solid pattern Square neck Elasticated puff sleeves Belt included, Polyester fabric, machine wash.."</p>
                            <p class="mb-0 m-t-20">Tee Stores is an Indian contemporary clothing brand. The product pages display a fine quality fabric with colorful description. We offer many vivid designs, art, styles that "combine heritage with modernity, simplicity, playfulness and street style"."</p>
                        </div>
                        <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                            <p class="mb-0 m-t-20">Lorate Solid Men's Fashion Full Sleeves Latest Jacket for Men With</p>
                            <p class="mb-0">Button Closure Long Sleeve Casual Torn Lycra Denim Jacket.</p>
                        </div>
                        <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">
                            <p class="mb-0 m-t-20">Rock Paper Scissors Various Dots Half Sleeves Girl’s Regular Fit T-Shirt I 100% Cotton</p>
                            <p class="mb-0">T Shirt with Half Sleeve Round Neck I Regular Wear Solid Kids Tees and Black Sleeve.</p>
                        </div>
                        <div class="tab-pane fade" id="top-brand" role="tabpanel" aria-labelledby="brand-top-tab">
                            <p class="mb-0 m-t-20">Product Dimensions : 18 x 18 x 4 cm</p>
                            <p class="mb-0">Date First Available : 31 March 2022</p>
                            <p class="mb-0">Manufacturer : Tee Stores</p>
                            <p class="mb-0">Item part number : TS-WT721-XS-WHITE</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/rating/jquery.barrating.js') }}"></script>
    <script src="{{ asset('assets/js/rating/rating-script.js') }}"></script>
    <script src="{{ asset('assets/js/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/slick-slider/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick-slider/slick-theme.js') }}"></script>
    <script src="{{ asset('assets/js/ecommerce.js') }}"></script>
@endsection
