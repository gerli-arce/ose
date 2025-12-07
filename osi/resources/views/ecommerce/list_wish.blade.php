@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Wishlist</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ecommerce</li>
                        <li class="breadcrumb-item active">Wishlist</li>
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
                        <h3>Wishlist</h3>
                    </div>
                    <div class="card-body whishlist-main">
                        <div class="row">
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Women's Jacket</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 220$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-success">In stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Denim Jacket</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-half-o"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 250$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-danger">Out of stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Wonder Skirt</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 310$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-success">In stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Roadster</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-half-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 140$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-danger">Out of stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img
                                                class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/05.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Women T-shirt</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 205$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-danger">Out of stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img
                                                class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/06.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Men's Shirt</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 120$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-success">In stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img
                                                class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/05.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Women T-shirt</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-half-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 280$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-danger">Out of stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img
                                                class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/07.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Men's Jacket</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 350$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-success">In stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img
                                                class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/08.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Women Trecksuit</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 210$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-danger">Out of stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img
                                                class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Women's Jacket</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 140$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-success">In stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img
                                                class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Denim Jacket</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 290$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-success">In stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 box-col-20 xl-20">
                                <div class="product-details-box">
                                    <div class="d-flex"><a href="{{ route('page-product') }}"><img
                                                class="align-self-center img-fluid"
                                                src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt="#"></a>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><a href="{{ route('page-product') }}">
                                                    <h6>Wonder Skirt</h6>
                                                </a></div>
                                            <ul class="rating">
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-o"></i></li>
                                            </ul>
                                            <div class="price">Price<span>: 210$</span></div>
                                            <div class="avaiabilty">
                                                <div class="text-danger">Out of stock</div>
                                            </div><a class="btn btn-primary btn-xs" href="{{ route('cart')}}">Move to Cart</a>
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

@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection
