@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Recent Orders</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Ecommerce</li>
                        <li class="breadcrumb-item active"> Recent Orders</li>
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
                        <h3>Order history</h3>
                    </div>
                    <div class="card-body">
                        <div class="order-history table-responsive">
                            <table class="table table-bordernone display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Product name</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Article number</th>
                                        <th scope="col">Units</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Payment Mode</th>
                                        <th scope="col"><i class="fa fa-angle-down"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Women's Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle"></span>Processing</div>
                                            </div>
                                        </td>
                                        <td>M</td>
                                        <td><span class="badge badge-light-warning">White</span></td>
                                        <td>4215738</td>
                                        <td>1</td>
                                        <td>$21</td>
                                        <td>Credit Card</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Denim Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle"></span>Processing</div>
                                            </div>
                                        </td>
                                        <td>L</td>
                                        <td><span class="badge badge-light-primary">Blue</span></td>
                                        <td>5476182</td>
                                        <td>2</td>
                                        <td>$15</td>
                                        <td>Bank Transfer</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                    <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Wonder Skirt</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle"></span>Processing</div>
                                            </div>
                                        </td>
                                        <td>XL</td>
                                        <td><span class="badge badge-light-danger">Red</span></td>
                                        <td>1756457</td>
                                        <td>1</td>
                                        <td>$25</td>
                                        <td>COD</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Roadster</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>M</td>
                                        <td><span class="badge badge-light-info">Brown</span></td>
                                        <td>7451725</td>
                                        <td>2</td>
                                        <td>$56</td>
                                        <td>Google Pay</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/05.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Women T-shirt</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>XL</td>
                                        <td><span class="badge badge-light-info">Brown</span></td>
                                        <td>4127421</td>
                                        <td>1</td>
                                        <td>$60</td>
                                        <td>Credit Card</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/06.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Fancy Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>XL</td>
                                        <td><span class="badge badge-light-warning">Light gray</span></td>
                                        <td>3581714</td>
                                        <td>1</td>
                                        <td>$28</td>
                                        <td>COD</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/07.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Men's Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>XXL</td>
                                        <td><span class="badge badge-light-primary">Blue</span></td>
                                        <td>6748142</td>
                                        <td>1</td>
                                        <td>$25</td>
                                        <td>Bank Transfer</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/08.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Women Trecksuit</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle cancel-order"></span>Cancelled</div>
                                            </div>
                                        </td>
                                        <td>L</td>
                                        <td><span class="badge badge-light-success">Pink</span></td>
                                        <td>5748214</td>
                                        <td>1</td>
                                        <td>$58</td>
                                        <td>Credit Card</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/01.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Women's Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle"></span>Processing</div>
                                            </div>
                                        </td>
                                        <td>M</td>
                                        <td><span class="badge badge-light-warning">White</span></td>
                                        <td>4215738</td>
                                        <td>1</td>
                                        <td>$21</td>
                                        <td>Credit Card</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/02.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Denim Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle"></span>Processing</div>
                                            </div>
                                        </td>
                                        <td>L</td>
                                        <td><span class="badge badge-light-primary">Blue</span></td>
                                        <td>5476182</td>
                                        <td>2</td>
                                        <td>$15</td>
                                        <td>Bank Transfer</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                    <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/03.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Wonder Skirt</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle"></span>Processing</div>
                                            </div>
                                        </td>
                                        <td>XL</td>
                                        <td><span class="badge badge-light-danger">Red</span></td>
                                        <td>1756457</td>
                                        <td>1</td>
                                        <td>$25</td>
                                        <td>COD</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/04.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Roadster</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>M</td>
                                        <td><span class="badge badge-light-info">Brown</span></td>
                                        <td>7451725</td>
                                        <td>2</td>
                                        <td>$56</td>
                                        <td>Google Pay</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/05.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Women T-shirt</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>XL</td>
                                        <td><span class="badge badge-light-info">Brown</span></td>
                                        <td>4127421</td>
                                        <td>1</td>
                                        <td>$60</td>
                                        <td>Credit Card</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/06.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Fancy Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>XL</td>
                                        <td><span class="badge badge-light-warning">Light gray</span></td>
                                        <td>3581714</td>
                                        <td>1</td>
                                        <td>$28</td>
                                        <td>COD</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/07.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Men's Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>XXL</td>
                                        <td><span class="badge badge-light-primary">Blue</span></td>
                                        <td>6748142</td>
                                        <td>1</td>
                                        <td>$25</td>
                                        <td>Bank Transfer</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/08.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Women Trecksuit</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle cancel-order"></span>Cancelled</div>
                                            </div>
                                        </td>
                                        <td>L</td>
                                        <td><span class="badge badge-light-success">Pink</span></td>
                                        <td>5748214</td>
                                        <td>1</td>
                                        <td>$58</td>
                                        <td>Credit Card</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/05.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Women T-shirt</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>XL</td>
                                        <td><span class="badge badge-light-info">Brown</span></td>
                                        <td>4127421</td>
                                        <td>1</td>
                                        <td>$60</td>
                                        <td>Credit Card</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/06.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Fancy Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>XL</td>
                                        <td><span class="badge badge-light-warning">Light gray</span></td>
                                        <td>3581714</td>
                                        <td>1</td>
                                        <td>$28</td>
                                        <td>COD</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/07.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Men's Jacket</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle shipped-order"></span>Shipped</div>
                                            </div>
                                        </td>
                                        <td>XXL</td>
                                        <td><span class="badge badge-light-primary">Blue</span></td>
                                        <td>6748142</td>
                                        <td>1</td>
                                        <td>$25</td>
                                        <td>Bank Transfer</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('page-product') }}"><img class="img-fluid img-30"
                                                    src="{{ asset('assets/images/ecommerce/08.jpg') }}" alt="#"></a></td>
                                        <td>
                                            <div class="product-name"><a href="javascript:void(0)">Women Trecksuit</a>
                                                <div class="order-process"><span
                                                        class="order-process-circle cancel-order"></span>Cancelled</div>
                                            </div>
                                        </td>
                                        <td>L</td>
                                        <td><span class="badge badge-light-success">Pink</span></td>
                                        <td>5748214</td>
                                        <td>1</td>
                                        <td>$58</td>
                                        <td>Credit Card</td>
                                        <td><i data-feather="more-vertical"></i></td>
                                    </tr>
                                </tbody>
                            </table>
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
