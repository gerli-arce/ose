@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/rating.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Product list</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ecommerce</li>
                        <li class="breadcrumb-item active">Product list</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid list-products">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3>Individual column searching (text inputs) </h3><span>The searching functionality provided by
                            DataTables is useful for quickly search through the information in the table - however the
                            search is global, and you may wish to present controls that search on specific columns.</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive product-table">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Details</th>
                                        <th>Amount</th>
                                        <th>Stock</th>
                                        <th>Start date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-1.png') }}" alt=""></a>
                                        </td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4> Green Jacket </h4>
                                            </a><span>Vida Loca - Green Denim Fit Men's Casual Shirt.</span>
                                        </td>
                                        <td>$10</td>
                                        <td class="font-success">In Stock</td>
                                        <td>20/08/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-2.png') }}" alt=""></a>
                                        </td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4> Sky Blue Shirt </h4>
                                            </a>
                                            <p>Wild West - Sky Blue Cotton Blend Regular Fit Men's Formal Shirt.</p>
                                        </td>
                                        <td>$12</td>
                                        <td class="font-primary">Low Stock</td>
                                        <td>12/04/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-3.png') }}" alt=""></a>
                                        </td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4> White Tshirt </h4>
                                            </a>
                                            <p>aask - White Tshirt Blend Women's Fit & Flare t-shirt.</p>
                                        </td>
                                        <td>$15</td>
                                        <td class="font-danger">out of stock</td>
                                        <td>04/08/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-4.png') }}" alt=""></a>
                                        </td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4> Black Top </h4>
                                            </a>
                                            <p>R L F - black Cotton Blend Women's A-Line Skirt.</p>
                                        </td>
                                        <td>$16</td>
                                        <td class="font-primary">Low Stock</td>
                                        <td>25/04/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-5.png') }}" alt=""></a>
                                        </td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4>Bluegreen Shirt</h4>
                                            </a>
                                            <p>The Dry State - Blue Denim Regular Fit Men's Denim Jacket.</p>
                                        </td>
                                        <td>$29</td>
                                        <td class="font-success">In Stock</td>
                                        <td>29/06/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-6.png') }}" alt=""></a>
                                        </td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4> Greyblack Shirt</h4>
                                            </a>
                                            <p>Vida Loca - Blue Denim Fit Men's Casual Shirt.</p>
                                        </td>
                                        <td>$54</td>
                                        <td class="font-primary">Low Stock</td>
                                        <td>05/28/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-1.png') }}"
                                                    alt=""></a></td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4> Blue Shirt </h4>
                                            </a>
                                            <p>Vida Loca - Blue Denim Fit Men's Casual Shirt.</p>
                                        </td>
                                        <td>$86</td>
                                        <td class="font-danger">out of stock</td>
                                        <td>06/21/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-2.png') }}"
                                                    alt=""></a></td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4>Light Shirt</h4>
                                            </a>
                                            <p>Vida Loca - light Denim Fit Men's Casual Shirt.</p>
                                        </td>
                                        <td>$10</td>
                                        <td class="font-danger">out of stock</td>
                                        <td>09/23/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-3.png') }}"
                                                    alt=""></a></td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4> Greyblack Shirt </h4>
                                            </a>
                                            <p>The Dry State - Greyblack Denim Regular Fit Men's Denim Jacket.</p>
                                        </td>
                                        <td>$18</td>
                                        <td class="font-success">In Stock</td>
                                        <td>10/25/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img
                                                    src="{{ asset('assets/images/ecommerce/product-table-4.png') }}"
                                                    alt=""></a></td>
                                        <td><a href="{{ route('page-product') }}">
                                                <h4> Black Top </h4>
                                            </a>
                                            <p>aask - Black Polyester Blend Women's Fit & Flare top.</p>
                                        </td>
                                        <td>$10</td>
                                        <td class="font-danger">out of stock</td>
                                        <td>04/25/2022</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Delete</button>
                                            <button class="btn btn-primary btn-xs" type="button"
                                                data-original-title="btn btn-danger btn-xs" title="">Edit</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Individual column searching (text inputs) Ends-->
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/rating/jquery.barrating.js') }}"></script>
    <script src="{{ asset('assets/js/rating/rating-script.js') }}"></script>
    <script src="{{ asset('assets/js/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/ecommerce.js') }}"></script>
    <script src="{{ asset('assets/js/product-list-custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
@endsection
