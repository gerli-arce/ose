@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Cart</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ecommerce</li>
                        <li class="breadcrumb-item active">Cart</li>
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
                        <h3>Cart</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="order-history table-responsive wishlist">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><img class="img-fluid img-40"
                                                    src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="#"></td>
                                            <td>
                                                <div class="product-name"><a href="javascript:void(0)">
                                                        <h6>Women's Jacket</h6>
                                                    </a></div>
                                            </td>
                                            <td>$21</td>
                                            <td>
                                                <fieldset class="qty-box">
                                                    <div class="input-group">
                                                        <input class="touchspin text-center" type="text" value="5">
                                                    </div>
                                                </fieldset>
                                            </td>
                                            <td><i data-feather="x-circle"></i></td>
                                            <td>$12456</td>
                                        </tr>
                                        <tr>
                                            <td><img class="img-fluid img-40"
                                                    src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="#"></td>
                                            <td>
                                                <div class="product-name"><a href="javascript:void(0)">
                                                        <h6>Denim Jacket</h6>
                                                    </a></div>
                                            </td>
                                            <td>$50</td>
                                            <td>
                                                <fieldset class="qty-box">
                                                    <div class="input-group">
                                                        <input class="touchspin text-center" type="text" value="5">
                                                    </div>
                                                </fieldset>
                                            </td>
                                            <td><i data-feather="x-circle"></i></td>
                                            <td>$12456</td>
                                        </tr>
                                        <tr>
                                            <td><img class="img-fluid img-40"
                                                    src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt="#"></td>
                                            <td>
                                                <div class="product-name"><a href="javascript:void(0)">
                                                        <h6>Wonder Skirt</h6>
                                                    </a></div>
                                            </td>
                                            <td>$11</td>
                                            <td>
                                                <fieldset class="qty-box">
                                                    <div class="input-group">
                                                        <input class="touchspin text-center" type="text" value="5">
                                                    </div>
                                                </fieldset>
                                            </td>
                                            <td><i data-feather="x-circle"></i></td>
                                            <td>$12456</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <div class="input-group">
                                                    <input class="form-control" type="text"
                                                        placeholder="Enter coupon code"><a class="btn btn-primary"
                                                        href="javascript:void(0)">Apply</a>
                                                </div>
                                            </td>
                                            <td class="total-amount">
                                                <h4 class="m-0 text-end"><span class="f-w-600">Total Price :</span></h4>
                                            </td>
                                            <td><span>$6935.00 </span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-end" colspan="5"><a
                                                    class="btn btn-secondary cart-btn-transform"
                                                    href="{{ route('product') }}">Continue Shopping</a></td>
                                            <td><a class="btn btn-success cart-btn-transform"
                                                    href="{{ route('checkout') }}">Check
                                                    Out</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="{{ asset('assets/js/touchspin/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/touchspin/touchspin.js') }}"></script>
    <script src="{{ asset('assets/js/touchspin/input-groups.min.js') }}"></script>
@endsection


