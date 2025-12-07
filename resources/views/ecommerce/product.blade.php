@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/range-slider.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Product</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ecommerce</li>
                        <li class="breadcrumb-item active">Product</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid product-wrapper">
        <div class="product-grid">
            <div class="feature-products">
                <div class="row m-b-10">
                    <div class="col-md-3 col-sm-2 products-total">
                        <div class="square-product-setting d-inline-block"><a class="icon-grid grid-layout-view"
                                href="javascript:void(0)" data-original-title="" title=""><i
                                    data-feather="grid"></i></a></div>
                        <div class="square-product-setting d-inline-block"><a class="icon-grid m-0 list-layout-view"
                                href="javascript:void(0)" data-original-title="" title=""><i
                                    data-feather="list"></i></a></div>
                        <div class="d-none-productlist filter-toggle">
                            <h4 class="mb-0">Filters<span class="ms-2"><i class="toggle-data"
                                        data-feather="chevron-down"></i></span></h4>
                        </div>
                        <div class="grid-options d-inline-block">
                            <ul>
                                <li><a class="product-2-layout-view" href="javascript:void(0)" data-original-title=""
                                        title=""><span class="line-grid line-grid-1 bg-primary"></span><span
                                            class="line-grid line-grid-2 bg-primary"></span></a></li>
                                <li><a class="product-3-layout-view" href="javascript:void(0)" data-original-title=""
                                        title=""><span class="line-grid line-grid-3 bg-primary"></span><span
                                            class="line-grid line-grid-4 bg-primary"></span><span
                                            class="line-grid line-grid-5 bg-primary"></span></a></li>
                                <li><a class="product-4-layout-view" href="javascript:void(0)" data-original-title=""
                                        title=""><span class="line-grid line-grid-6 bg-primary"></span><span
                                            class="line-grid line-grid-7 bg-primary"></span><span
                                            class="line-grid line-grid-8 bg-primary"></span><span
                                            class="line-grid line-grid-9 bg-primary"></span></a></li>
                                <li><a class="product-6-layout-view" href="javascript:void(0)" data-original-title=""
                                        title=""><span class="line-grid line-grid-10 bg-primary"></span><span
                                            class="line-grid line-grid-11 bg-primary"></span><span
                                            class="line-grid line-grid-12 bg-primary"></span><span
                                            class="line-grid line-grid-13 bg-primary"></span><span
                                            class="line-grid line-grid-14 bg-primary"></span><span
                                            class="line-grid line-grid-15 bg-primary"></span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-10 text-end"><span class="f-w-600 m-r-5">Showing Products 1 - 24 Of 200
                            Results</span>
                        <div class="select2-drpdwn-product select-options d-inline-block">
                            <select class="form-control btn-square" name="select">
                                <option value="opt1">Lowest Prices</option>
                                <option value="opt2">Highest Prices</option>
                                <option value="opt3">Featured</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pro-filter-sec">
                            <div class="product-sidebar">
                                <div class="filter-section">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="mb-0 f-w-600">Filters<span class="pull-right"><i
                                                        class="fa fa-chevron-down toggle-data"></i></span></h4>
                                        </div>
                                        <div class="left-filter">
                                            <div class="card-body filter-cards-view animate-chk">
                                                <div class="product-filter">
                                                    <h6 class="f-w-600">Category</h6>
                                                    <div class="checkbox-animated mt-0">
                                                        <label class="d-block" for="edo-ani5">
                                                            <input class="checkbox_animated" id="edo-ani5"
                                                                type="checkbox" data-original-title="" title="">Man
                                                            Shirt
                                                        </label>
                                                        <label class="d-block" for="edo-ani6">
                                                            <input class="checkbox_animated" id="edo-ani6"
                                                                type="checkbox" data-original-title="" title="">Man
                                                            Jeans
                                                        </label>
                                                        <label class="d-block" for="edo-ani7">
                                                            <input class="checkbox_animated" id="edo-ani7"
                                                                type="checkbox" data-original-title=""
                                                                title="">Woman Top
                                                        </label>
                                                        <label class="d-block" for="edo-ani8">
                                                            <input class="checkbox_animated" id="edo-ani8"
                                                                type="checkbox" data-original-title=""
                                                                title="">Woman Jeans
                                                        </label>
                                                        <label class="d-block" for="edo-ani9">
                                                            <input class="checkbox_animated" id="edo-ani9"
                                                                type="checkbox" data-original-title="" title="">Man
                                                            T-shirt
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="product-filter">
                                                    <h6 class="f-w-600">Brand</h6>
                                                    <div class="checkbox-animated mt-0">
                                                        <label class="d-block" for="chk-ani">
                                                            <input class="checkbox_animated" id="chk-ani"
                                                                type="checkbox" data-original-title="" title="">
                                                            Levi's
                                                        </label>
                                                        <label class="d-block" for="chk-ani1">
                                                            <input class="checkbox_animated" id="chk-ani1"
                                                                type="checkbox" data-original-title=""
                                                                title="">Diesel
                                                        </label>
                                                        <label class="d-block" for="chk-ani2">
                                                            <input class="checkbox_animated" id="chk-ani2"
                                                                type="checkbox" data-original-title="" title="">Lee
                                                        </label>
                                                        <label class="d-block" for="chk-ani3">
                                                            <input class="checkbox_animated" id="chk-ani3"
                                                                type="checkbox" data-original-title=""
                                                                title="">Hudson
                                                        </label>
                                                        <label class="d-block" for="chk-ani4">
                                                            <input class="checkbox_animated" id="chk-ani4"
                                                                type="checkbox" data-original-title=""
                                                                title="">Denizen
                                                        </label>
                                                        <label class="d-block" for="chk-ani5">
                                                            <input class="checkbox_animated" id="chk-ani5"
                                                                type="checkbox" data-original-title=""
                                                                title="">Spykar
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="product-filter slider-product">
                                                    <h6 class="f-w-600">Colors</h6>
                                                    <div class="color-selector">
                                                        <ul>
                                                            <li class="active white"></li>
                                                            <li class="bg-primary"> </li>
                                                            <li class="bg-secondary"></li>
                                                            <li class="bg-success"></li>
                                                            <li class="bg-warning"></li>
                                                            <li class="bg-danger"></li>
                                                            <li class="blue"></li>
                                                            <li class="red"></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="product-filter pb-0">
                                                    <h6 class="f-w-600">Price</h6>
                                                    <input id="u-range-03" type="text">
                                                    <h6 class="f-w-600">New Products</h6>
                                                </div>
                                                <div class="product-filter pb-0 new-products">
                                                    <div class="owl-carousel owl-theme" id="testimonial">
                                                        <div class="item">
                                                            <div class="product-box">
                                                                <div class="d-flex">
                                                                    <div class="product-img me-3"><img class="img-fluid"
                                                                            src="{{ asset('assets/images/ecommerce/01.jpg') }}"
                                                                            alt="" data-original-title=""
                                                                            title=""></div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="product-details">
                                                                            <div>
                                                                                <ul>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                </ul>
                                                                                <p class="mb-0 f-w-700">Fancy Shirt</p>
                                                                                <div class="f-w-500">$100.00</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="product-box">
                                                                <div class="d-flex">
                                                                    <div class="product-img me-3"><img class="img-fluid"
                                                                            src="{{ asset('assets/images/ecommerce/02.jpg') }}"
                                                                            alt="" data-original-title=""
                                                                            title=""></div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="product-details">
                                                                            <div>
                                                                                <ul>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                </ul>
                                                                                <p class="mb-0 f-w-700">Fancy Shirt</p>
                                                                                <div class="f-w-500">$100.00</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="product-box">
                                                                <div class="d-flex">
                                                                    <div class="product-img me-3"><img class="img-fluid"
                                                                            src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                                                            alt="" data-original-title=""
                                                                            title=""></div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="product-details">
                                                                            <div>
                                                                                <ul>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                </ul>
                                                                                <p class="mb-0 f-w-700">Fancy Shirt</p>
                                                                                <div class="f-w-500">$100.00</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="product-box">
                                                                <div class="d-flex">
                                                                    <div class="product-img me-3"><img class="img-fluid"
                                                                            src="{{ asset('assets/images/ecommerce/01.jpg') }}"
                                                                            alt="" data-original-title=""
                                                                            title=""></div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="product-details">
                                                                            <div>
                                                                                <ul>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                </ul>
                                                                                <p class="mb-0 f-w-700">Fancy Shirt</p>
                                                                                <div class="f-w-500">$100.00</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="product-box">
                                                                <div class="d-flex">
                                                                    <div class="product-img me-3"><img class="img-fluid"
                                                                            src="{{ asset('assets/images/ecommerce/02.jpg') }}"
                                                                            alt="" data-original-title=""
                                                                            title=""></div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="product-details">
                                                                            <div>
                                                                                <ul>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                </ul>
                                                                                <p class="mb-0 f-w-700">Fancy Shirt</p>
                                                                                <div class="f-w-500">$100.00</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="product-box">
                                                                <div class="d-flex">
                                                                    <div class="product-img me-3"><img class="img-fluid"
                                                                            src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                                                            alt="" data-original-title=""
                                                                            title=""></div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="product-details">
                                                                            <div>
                                                                                <ul>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                    <li><i
                                                                                            class="fa fa-star font-warning"></i>
                                                                                    </li>
                                                                                </ul>
                                                                                <p class="mb-0 f-w-700">Fancy Shirt</p>
                                                                                <div class="f-w-500">$100.00</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-filter text-center"><img
                                                        class="img-fluid banner-product"
                                                        src="{{ asset('assets/images/ecommerce/banner.jpg') }}" alt=""
                                                        data-original-title="" title=""></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-search">
                                <form>
                                    <div class="form-group m-0">
                                        <input class="form-control" type="search" placeholder="Search.."
                                            data-original-title="" title=""><i class="fa fa-search"></i>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-wrapper-grid">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid" src="{{ asset('assets/images/ecommerce/05.jpg') }}"
                                        alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter16"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter16">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4> Women's Jacket</h4>
                                                        </a>
                                                        <div class="product-price">$120.00
                                                            <del>$150.00</del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart </a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Women's T-Shirt</h4>
                                    </a>
                                    <p>Solid Women's T-shirt</p>
                                    <div class="product-price">$120.00
                                        <del>$150.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img">
                                    <div class="ribbon ribbon-danger">Sale</div><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/06.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Denim Jacket</h4>
                                                        </a>
                                                        <div class="product-price">$260.00
                                                            <del>$320.00 </del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Men's Shirt</h4>
                                    </a>
                                    <p>Denim Regular Men's Shirt</p>
                                    <div class="product-price">$260.00
                                        <del>$320.00 </del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid" src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                        alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter2"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter2">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Wonder Skirt</h4>
                                                        </a>
                                                        <div class="product-price">$426.00
                                                            <del>$480.00</del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Women's Coat</h4>
                                    </a>
                                    <p>Woolen Women's Coat</p>
                                    <div class="product-price">$426.00
                                        <del>$480.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img">
                                    <div class="ribbon ribbon-success ribbon-right">50%</div><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/07.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter3"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter3">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Roadster</h4>
                                                        </a>
                                                        <div class="product-price">$526.00
                                                            <del>$610.00 </del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Men's Shirt</h4>
                                    </a>
                                    <p>Cotton Regular Fit Men's Shirt</p>
                                    <div class="product-price">$526.00
                                        <del>$610.00 </del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img">
                                    <div class="ribbon ribbon-success ribbon-right">50%</div><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/08.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter4"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter4">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Roadster</h4>
                                                        </a>
                                                        <div class="product-price">$626.00
                                                            <del>$650.00 </del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Track Suit</h4>
                                    </a>
                                    <p>Cotton Regular Fit Women's Track Suit</p>
                                    <div class="product-price">$626.00
                                        <del>$650.00 </del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid" src="{{ asset('assets/images/ecommerce/01.jpg') }}"
                                        alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter5"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter5">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Women's Jacket </h4>
                                                        </a>
                                                        <div class="product-price">$150.00
                                                            <del>$170.00</del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Women's Jacket</h4>
                                    </a>
                                    <p>Solid Denim Jacket</p>
                                    <div class="product-price">$150.00
                                        <del>$170.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img">
                                    <div class="ribbon ribbon-danger">Sale</div><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter6"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter6" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenter1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Denim Jacket</h4>
                                                        </a>
                                                        <div class="product-price">$350.00
                                                            <del>$370.00 </del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Denim Jacket</h4>
                                    </a>
                                    <p>Denim Regular Men's Jacket</p>
                                    <div class="product-price">$350.00
                                        <del>$370.00 </del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid" src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                        alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter7"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter7" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenter2" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Wonder Skirt</h4>
                                                        </a>
                                                        <div class="product-price">$430.00
                                                            <del>$450.00</del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Women's Coat</h4>
                                    </a>
                                    <p>Woolen Women's Coat</p>
                                    <div class="product-price">$430.00
                                        <del>$450.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid" src="{{ asset('assets/images/ecommerce/03.jpg') }}"
                                        alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter8"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter8" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenter2" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Wonder Skirt</h4>
                                                        </a>
                                                        <div class="product-price">$500.00
                                                            <del>$520.00</del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Women's Coat</h4>
                                    </a>
                                    <p>Woolen Women's Coat</p>
                                    <div class="product-price">$500.00
                                        <del>$520.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img">
                                    <div class="ribbon ribbon-success ribbon-right">50%</div><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter9"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter9" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenter3" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Roadster</h4>
                                                        </a>
                                                        <div class="product-price">$540.00
                                                            <del>$580.00 </del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Roadster</h4>
                                    </a>
                                    <p>Cotton Regular Fit Men's Shirt</p>
                                    <div class="product-price">$540.00
                                        <del>$580.00 </del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid" src="{{ asset('assets/images/ecommerce/01.jpg') }}"
                                        alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter10"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter10">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Women's Jacket </h4>
                                                        </a>
                                                        <div class="product-price">$200.00
                                                            <del>$250.00</del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Women's Jacket </h4>
                                    </a>
                                    <p>Solid Denim Jacket</p>
                                    <div class="product-price">$200.00
                                        <del>$250.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img">
                                    <div class="ribbon ribbon-danger">Sale</div><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter11"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter11" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenter1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt=""></div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Denim Jacket</h4>
                                                        </a>
                                                        <div class="product-price">$420.00
                                                            <del>$450.00 </del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary" href="{{ route('page-product') }}">View
                                                                    Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Denim Jacket</h4>
                                    </a>
                                    <p>Denim Regular Men's Jacket</p>
                                    <div class="product-price">$420.00
                                        <del>$450.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img">
                                    <div class="ribbon ribbon-danger">Sale</div><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter12"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter12" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenter1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="">
                                                    </div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Denim Jacket</h4>
                                                        </a>
                                                        <div class="product-price">$460.00
                                                            <del>$480.00 </del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary"
                                                                    href="{{ route('page-product') }}">View Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Denim Jacket</h4>
                                    </a>
                                    <p>Denim Regular Men's Jacket</p>
                                    <div class="product-price">$460.00
                                        <del>$480.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter13"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter13" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenter2" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt="">
                                                    </div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Wonder Skirt</h4>
                                                        </a>
                                                        <div class="product-price">$590.00
                                                            <del>$620.00</del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary"
                                                                    href="{{ route('page-product') }}">View Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Women's Coat</h4>
                                    </a>
                                    <p>Woolen Women's Coat</p>
                                    <div class="product-price">$590.00
                                        <del>$620.00</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img">
                                    <div class="ribbon ribbon-success ribbon-right">50%</div><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/07.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter14"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter14" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenter3" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt="">
                                                    </div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Roadster</h4>
                                                        </a>
                                                        <div class="product-price">$38.00
                                                            <del>$45.00 </del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary"
                                                                    href="{{ route('page-product') }}">View Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Men's Shirt</h4>
                                    </a>
                                    <p>Cotton Regular Fit Men's Shirt</p>
                                    <div class="product-price">$526.00
                                        <del>$610.00 </del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid"
                                        src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a href="{{ route('cart') }}"><i class="icon-shopping-cart"></i></a></li>
                                            <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter15"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalCenter15">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="">
                                                    </div>
                                                    <div class="product-details col-lg-6 text-start"><a
                                                            href="{{ route('page-product') }}">
                                                            <h4>Women's Jacket </h4>
                                                        </a>
                                                        <div class="product-price">$270.00
                                                            <del>$300.00</del>
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">Rock Paper Scissors Womens Tank Top High Neck Cotton Top Stylish Women Top..</p>
                                                        </div>
                                                        <div class="product-size">
                                                            <ul>
                                                                <li>
                                                                    <button class="btn" type="button">M</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">L</button>
                                                                </li>
                                                                <li>
                                                                    <button class="btn" type="button">Xl</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Quantity</h6>
                                                            <fieldset>
                                                                <div class="input-group">
                                                                    <input class="touchspin text-center" type="text"
                                                                        value="5">
                                                                </div>
                                                            </fieldset>
                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="{{ route('cart') }}">Add to Cart</a><a
                                                                    class="btn btn-primary"
                                                                    href="{{ route('page-product') }}">View Details</a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details"><a href="{{ route('page-product') }}">
                                        <h4>Women's Jacket </h4>
                                    </a>
                                    <p>Solid Denim Jacket</p>
                                    <div class="product-price">$270.00
                                        <del>$300.00</del>
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

@section('scripts')
    <script src="{{ asset('assets/js/range-slider/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('assets/js/range-slider/rangeslider-script.js') }}"></script>
    <script src="{{ asset('assets/js/touchspin/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/touchspin/touchspin.js') }}"></script>
    <script src="{{ asset('assets/js/touchspin/input-groups.min.js') }}"></script>
    <script src="{{ asset('assets/js/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('assets/js/product-tab.js') }}"></script>
@endsection
