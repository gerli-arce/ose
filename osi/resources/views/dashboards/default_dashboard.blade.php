@extends('layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
@endsection


@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Default Dashboard</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active">Default </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid default-page">
        <div class="row">
            <div class="col-xl-5 col-lg-5">
                <div class="card profile-greeting">
                    <div class="card-body">
                        <div>
                            <h1>Welcome,William</h1>
                            <p> You have completed 40% of your this week! Start a new goal & improve your result</p><a
                                class="btn" href="{{ route('user-profile') }}">Continue<i data-feather="arrow-right"></i></a>
                        </div>
                        <div class="greeting-img"><img class="img-fluid"
                                src="{{ asset('assets/images/dashboard/profile-greeting/bg.png') }}" alt=""></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-lg-3">
                <div class="card yearly-view">
                    <div class="card-header pb-0">
                        <h3>Yearly Overview<span class="badge badge-primary">50/100</span></h3>
                        <h5 class="mb-0">Monday</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="yearly-view" id="yearly-view"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-lg-4">
                <div class="card activity-review">
                    <div class="card-header pb-0">
                        <h3> Activity </h3>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li>
                                    <div><i class="icon-settings"></i></div>
                                </li>
                                <li><i class="view-html fa fa-code"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li><i class="icofont icofont-refresh reload-card"></i></li>
                                <li><i class="icofont icofont-error close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordernone">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex"> <img class="img-fluid"
                                                    src="{{ asset('assets/images/dashboard/activity/1.jpg') }}" alt="">
                                                <div class="flex-grow-1"><a href="{{ route('to_do') }}">
                                                        <h5>Review request jim Smith</h5>
                                                    </a>
                                                    <p>jan 03 19 12:25 PM at Tame</p>
                                                </div>
                                                <div class="time-badge">
                                                    <p>14m Ago </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex"><img class="img-fluid"
                                                    src="{{ asset('assets/images/dashboard/activity/2.jpg') }}" alt="">
                                                <div class="flex-grow-1"> <a href="{{ route('to_do') }}">
                                                        <h5>New contact added</h5>
                                                    </a>
                                                    <p>jan 02 19 03:10 PM at Fresno</p>
                                                </div>
                                                <div class="time-badge">
                                                    <p>22m Ago </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex"> <img class="img-fluid"
                                                    src="{{ asset('assets/images/dashboard/activity/3.jpg') }}" alt="">
                                                <div class="flex-grow-1"> <a href="{{ route('to_do') }}">
                                                        <h5>Sent review (504)236-7302</h5>
                                                    </a>
                                                    <p>jan 02 19 07:35 PM at Iris</p>
                                                </div>
                                                <div class="time-badge">
                                                    <p>30m Ago </p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="code-box-copy">
                            <button class="code-box-copy__btn btn-clipboard" data-clipboard-target="#activity-view"
                                title="" data-bs-original-title="Copy" aria-label="Copy"><i
                                    class="icofont icofont-copy-alt"></i></button>
                            <pre><code class="language-html" id="activity-view">&lt;div class="card activity-review"&gt;
&lt;div class="card-header pb-0"&gt;
&lt;h3&gt; Activity &lt;/h3&gt;
&lt;div class="card-header-right"&gt;
 &lt;ul class="list-unstyled card-option"&gt;
     &lt;li&gt;
         &lt;div&gt;&lt;i class="icon-settings"&gt;&lt;/i&gt;&lt;/div&gt;
     &lt;/li&gt;
    &lt;li&gt;&lt;i class="view-html fa fa-code"&gt;&lt;/i&gt;&lt;/li&gt;
    &lt;li&gt;&lt;i class="icofont icofont-maximize full-card"&gt;&lt;/i&gt;&lt;/li&gt;
    &lt;li&gt;&lt;i class="icofont icofont-minus minimize-card"&gt;&lt;/i&gt;&lt;/li&gt;
    &lt;li&gt;&lt;i class="icofont icofont-refresh reload-card"&gt;&lt;/i&gt;&lt;/li&gt;
    &lt;li&gt;&lt;i class="icofont icofont-error close-card"&gt;&lt;/i&gt;&lt;/li&gt;
  &lt;/ul&gt;
&lt;/div&gt;
&lt;/div&gt;
&lt;div class="card-body"&gt;
 &lt;div class="table-responsive"&gt;
     &lt;table class="table table-bordernone"&gt;
         &lt;tbody&gt;
             &lt;tr&gt;
                 &lt;td&gt;
                     &lt;div class="d-flex"&gt;
                         &lt;img class="img-fluid" src="{{ asset('assets/images/dashboard/activity/1.jpg') }}" alt="" /&gt;
                     &lt;div class="flex-grow-1"&gt;
                       &lt;h5&gt;Review request j im Smith&lt;/h5&gt;
                       &lt;p&gt;jan 03 2019 12:25 PM at Tamecula&lt;/p&gt;
                     &lt;/div&gt;
                   &lt;/div&gt;
                 &lt;/td&gt;
               &lt;/tr&gt;
               &lt;tr&gt;
                   &lt;td&gt;
                       &lt;div class="d-flex"&gt;&lt;img class="img-fluid" src="{{ asset('assets/images/dashboard/activity/2.jpg') }}" alt="" /&gt;
                         &lt;div class="flex-grow-1"&gt;
                           &lt;h5&gt;New contact added messenger&lt;/h5&gt;
                           &lt;p&gt;jan 02 2019 03:10 PM at Fresno&lt;/p&gt;
                         &lt;/div&gt;
                       &lt;/div&gt;
                   &lt;/td&gt;
               &lt;/tr&gt;
               &lt;tr&gt;
                   &lt;td&gt;
                       &lt;div class="d-flex"&gt;
                           &lt;img class="img-fluid" src="{{ asset('assets/images/dashboard/activity/1.jpg') }}" alt="" /&gt;
                           &lt;div class="flex-grow-1"&gt;
                               &lt;h5&gt;Sent review (504)236-7302&lt;/h5&gt;
                               &lt;p&gt;jan 02 2019 03:10 PM at Fresno&lt;/p&gt;
                           &lt;/div&gt;
                         &lt;/div&gt;
                   &lt;/td&gt;
               &lt;/tr&gt;
             &lt;/tbody&gt;
           &lt;/table&gt;
         &lt;/div&gt;
       &lt;/div&gt;
     &lt;/div&gt;</code></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5">
                <div class="card transaction-history">
                    <div class="card-header pb-0">
                        <h3> Transaction</h3>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li>
                                    <div><i class="icon-settings"></i></div>
                                </li>
                                <li><i class="view-html fa fa-code"></i></li>
                                <li><i class="icofont icofont-maximize full-card"></i></li>
                                <li><i class="icofont icofont-minus minimize-card"></i></li>
                                <li><i class="icofont icofont-refresh reload-card"></i></li>
                                <li><i class="icofont icofont-error close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordernone table-responsive">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Date and Time</th>
                                    <th>Income</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex">
                                            <div class="icon"><img class="img-fluid"
                                                    src="{{ asset('assets/images/dashboard/transaction/1.png') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1"><a href="{{ route('page-product') }}">
                                                    <h5>Nike Sports NK</h5>
                                                </a>
                                                <p>Free delivery</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>12 May 2020</h5>
                                        <p>In 6 Days</p>
                                    </td>
                                    <td>
                                        <h5>+$456</h5>
                                    </td>
                                    <td>
                                        <div class="progress-showcase">
                                            <p>65%</p>
                                            <div class="progress progress-bg-success" style="height: 5px;">
                                                <div class="progress-bar" role="progressbar" style="width: 65%"
                                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>Paypal</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex">
                                            <div class="icon"><img class="img-fluid"
                                                    src="{{ asset('assets/images/dashboard/transaction/2.png') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1"><a href="{{ route('page-product') }}">
                                                    <h5>Women Bag</h5>
                                                </a>
                                                <p>₹83.65 delivery</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>06 May 2020</h5>
                                        <p>In 5 Days</p>
                                    </td>
                                    <td>
                                        <h5>-$80</h5>
                                    </td>
                                    <td>
                                        <div class="progress-showcase">
                                            <p>45%</p>
                                            <div class="progress progress-bg-warning" style="height: 5px;">
                                                <div class="progress-bar" role="progressbar" style="width: 45%"
                                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>Credit Card</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex">
                                            <div class="icon"><img class="img-fluid"
                                                    src="{{ asset('assets/images/dashboard/transaction/3.png') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1"><a href="{{ route('page-product') }}">
                                                    <h5>Sunglasses</h5>
                                                </a>
                                                <p>Free delivery</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>28 Sep 2020</h5>
                                        <p>in 4 Months</p>
                                    </td>
                                    <td>
                                        <h5>-$4,232</h5>
                                    </td>
                                    <td>
                                        <div class="progress-showcase">
                                            <p>85%</p>
                                            <div class="progress progress-bg-danger" style="height: 5px;">
                                                <div class="progress-bar" role="progressbar" style="width: 85%"
                                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>Paypal</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex">
                                            <div class="icon"><img class="img-fluid"
                                                    src="{{ asset('assets/images/dashboard/transaction/4.png') }}" alt="">
                                            </div>
                                            <div class="flex-grow-1"><a href="{{ route('page-product') }}">
                                                    <h5>Cotton T-shirt</h5>
                                                </a>
                                                <p>₹283.65 delivery</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>22 Mar 2020</h5>
                                        <p>In 8 Days</p>
                                    </td>
                                    <td>
                                        <h5>-$645</h5>
                                    </td>
                                    <td>
                                        <div class="progress-showcase">
                                            <p>75%</p>
                                            <div class="progress progress-bg-info" style="height: 5px;">
                                                <div class="progress-bar" role="progressbar" style="width: 75%"
                                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>Credit Card</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="code-box-copy">
                            <button class="code-box-copy__btn btn-clipboard" data-clipboard-target="#transaction-view"
                                title="" data-bs-original-title="Copy" aria-label="Copy"><i
                                    class="icofont icofont-copy-alt"></i></button>
                            <pre><code class="language-html" id="transaction-view">&lt;div class="card transaction-history"&gt;
&lt;div class="card-header pb-0"&gt;
&lt;h3&gt; Transaction&lt;/h3&gt;
&lt;div class="card-header-right"&gt;
&lt;ul class="list-unstyled card-option"&gt;
  &lt;li&gt;&lt;div&gt;&lt;i class="icon-settings"&gt;&lt;/i&gt;&lt;/div&gt;&lt;/li&gt;
  &lt;li&gt;&lt;i class="view-html fa fa-code"&gt;&lt;/i&gt;&lt;/li&gt;
  &lt;li&gt;&lt;i class="icofont icofont-maximize full-card"&gt;&lt;/i&gt;&lt;/li&gt;
  &lt;li&gt;&lt;i class="icofont icofont-minus minimize-card"&gt;&lt;/i&gt;&lt;/li&gt;
  &lt;li&gt;&lt;i class="icofont icofont-refresh reload-card"&gt;&lt;/i&gt;&lt;/li&gt;
  &lt;li&gt;&lt;i class="icofont icofont-error close-card"&gt;&lt;/i&gt;&lt;/li&gt;
&lt;/ul&gt;
&lt;/div&gt;
&lt;/div&gt;
&lt;div class="card-body"&gt;
&lt;div class="table-responsive"&gt;
&lt;table class="table table-bordernone"&gt;
 &lt;thead&gt;
   &lt;tr&gt;
     &lt;th&gt;Item Name&lt;/th&gt;
     &lt;th&gt;Date and Time&lt;/th&gt;
     &lt;th&gt;Income&lt;/th&gt;
     &lt;th&gt;Progress&lt;/th&gt;
     &lt;th&gt;Status&lt;/th&gt;
   &lt;/tr&gt;
 &lt;/thead&gt;
  &lt;tbody&gt;
    &lt;tr&gt;
      &lt;td&gt;&lt;div class="d-flex"&gt;
        &lt;div class="icon"&gt;&lt;img class="img-fluid" src="{{ asset('assets/images/dashboard/transaction/1.png') }}" alt="" /&gt;&lt;/div&gt;&lt;div class="flex-grow-1"&gt;&lt;h5&gt;Nike Sports NK&lt;/h5&gt;
        &lt;p&gt;Free delivery&lt;/p&gt;
        &lt;/div&gt;
        &lt;/div&gt;
      &lt;/td&gt;
      &lt;td&gt;
       &lt;h5&gt;12 May 2020&lt;/h5&gt;
       &lt;p&gt;In 6 Days&lt;/p&gt;
      &lt;/td&gt;
      &lt;td&gt;
        &lt;h5&gt;+$456&lt;/h5&gt;
      &lt;/td&gt;
      &lt;td&gt;
        &lt;div class="progress-showcase"&gt;
          &lt;p&gt;65%&lt;/p&gt;
        &lt;div class="progress" style="height: 5px;"&gt;
          &lt;div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"&gt;&lt;/div&gt;
        &lt;/div&gt;
        &lt;/div&gt;
      &lt;/td&gt;
      &lt;td&gt;
        &lt;h5&gt;Paypal&lt;/h5&gt;
      &lt;/td&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;td&gt;
        &lt;div class="d-flex"&gt;
          &lt;div class="icon"&gt;&lt;img class="img-fluid" src="{{ asset('assets/images/dashboard/transaction/2.png') }}" alt="" /&gt;&lt;/div&gt;&lt;div class="flex-grow-1"&gt;&lt;h5&gt;Women Bag&lt;/h5&gt;&lt;p&gt;₹83.65 delivery&lt;/p&gt;&lt;/div&gt;&lt;/div&gt;
      &lt;/td&gt;
      &lt;td&gt;
        &lt;h5&gt;06 May 2020&lt;/h5&gt;
        &lt;p&gt;In 5 Days&lt;/p&gt;
      &lt;/td&gt;
      &lt;td&gt;&lt;h5&gt;-$80&lt;/h5&gt;&lt;/td&gt;
      &lt;td&gt;&lt;div class="progress-showcase"&gt;&lt;p&gt;45%&lt;/p&gt;&lt;div class="progress" style="height: 5px;"&gt;&lt;div class="progress-bar bg-warning" role="progressbar" style="width: 65%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;
      &lt;/td&gt;
      &lt;td&gt;&lt;h5&gt;Credit Card&lt;/h5&gt;&lt;/td&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;td&gt;&lt;div class="d-flex"&gt;&lt;div class="icon"&gt;&lt;img class="img-fluid" src="{{ asset('assets/images/dashboard/transaction/3.png') }}" alt="" /&gt;&lt;/div&gt;&lt;div class="flex-grow-1"&gt;&lt;h5&gt;Sunglasses&lt;/h5&gt;&lt;p&gt;Free delivery&lt;/p&gt;&lt;/div&gt;&lt;/div&gt;&lt;/td&gt;
      &lt;td&gt;&lt;h5&gt;28 Sep 2020&lt;/h5&gt;&lt;p&gt;in 4 Months&lt;/p&gt;&lt;/td&gt;
      &lt;td&gt;&lt;h5&gt;-$4,232&lt;/h5&gt;&lt;/td&gt;&lt;td&gt;&lt;div class="progress-showcase"&gt;&lt;p&gt;85%&lt;/p&gt;&lt;div class="progress" style="height: 5px;"&gt;&lt;div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/td&gt;
      &lt;td&gt;&lt;h5&gt;Paypal&lt;/h5&gt;&lt;/td&gt;
    &lt;/tr&gt;
    &lt;tr&gt;
      &lt;td&gt;&lt;div class="d-flex"&gt;&lt;div class="icon"&gt;&lt;img class="img-fluid" src="{{ asset('assets/images/dashboard/transaction/4.png') }}" alt="" /&gt;&lt;/div&gt;&lt;div class="flex-grow-1"&gt;&lt;h5&gt;Cotton T-shirt&lt;/h5&gt;&lt;p&gt;₹283.65 delivery&lt;/p&gt;&lt;/div&gt;&lt;/div&gt;&lt;/td&gt;
      &lt;td&gt;&lt;h5&gt;22 Mar 2020&lt;/h5&gt;&lt;p&gt;In 8 Days&lt;/p&gt;&lt;/td&gt;
      &lt;td&gt;&lt;h5&gt;-$645&lt;/h5&gt;&lt;/td&gt;
      &lt;td&gt;&lt;div class="progress-showcase"&gt;&lt;p&gt;75%&lt;/p&gt;&lt;div class="progress" style="height: 5px;"&gt;&lt;div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/td&gt;
     &lt;td&gt;&lt;h5&gt;Credit Card&lt;/h5&gt;
     &lt;/td&gt;
   &lt;/tr&gt;
 &lt;/tbody&gt;
&lt;/table&gt;
&lt;/div&gt;
&lt;/div&gt;
&lt;/div&gt;</code></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3">
                <div class="row">
                    <div class="col-xl-12 col-sm-6 col-lg-12">
                        <div class="card value-chart bg-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="round-progress knob-block text-center">
                                            <div class="progress-circle">
                                                <input class="knob1" data-width="10" data-height="70"
                                                    data-thickness=".3" data-angleoffset="0" data-linecap="round"
                                                    data-fgcolor="#534686" data-bgcolor="#C4C4C4" value="62">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="valuechart-detail">
                                            <div>
                                                <p>Our Sale Value </p>
                                                <h2>$7454.25</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><span class="badge badge-primary">New</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-sm-6 col-lg-12">
                        <div class="card value-chart stock-value bg-secondary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="stock-value" id="stock-value"></div>
                                    </div>
                                    <div class="col-6">
                                        <div class="valuechart-detail">
                                            <div>
                                                <p>Today Value</p>
                                                <h2>$5263.04 </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><span class="badge badge-primary">Hot </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4">
                <div class="beyo-line row">
                    <div class="beyoline-header col-xl-12">
                        <div id="beyo-line"></div>
                    </div>
                    <div class="beyo-detail col-xl-12">
                        <h3>Beyond the Lines <span class="badge badge-primary">6 hours ago</span></h3>
                        <p>One Of the world,s brightest, young surf Srars, Kanoa lgarashi.</p>
                        <div class="date-history">
                            <ul>
                                <li><img class="img-fluid" src="{{ asset('assets/images/dashboard/beyo-line/1.png') }}"
                                        alt=""></li>
                                <li><img class="img-fluid" src="{{ asset('assets/images/dashboard/beyo-line/2.png') }}"
                                        alt=""></li>
                                <li><img class="img-fluid" src="{{ asset('assets/images/dashboard/beyo-line/3.png') }}"
                                        alt=""></li>
                                <li><img class="img-fluid" src="{{ asset('assets/images/dashboard/beyo-line/4.png') }}"
                                        alt=""></li>
                                <li>
                                    <h2>+ 350</h2>
                                </li>
                            </ul>
                            <div class="date-lable">
                                <h3>10</h3>
                                <p>june</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 des-xl-100 box-col-100 col-lg-12">
                <div class="card investment-chart">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="investment-group"><span class="invest-lable">+13.6%</span>
                                    <div id="invest-chart"></div>
                                    <div class="chart-detail">
                                        <h5>Total Investment</h5>
                                        <h2>$7454.25</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="investment-group"><span class="invest-lable">+15.4%</span>
                                    <div class="gain-chart" id="gain-chart"></div>
                                    <div class="chart-detail">
                                        <h5>Total Gain</h5>
                                        <h2>$7454.25</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="investment-group"><span class="invest-lable">+15.4%</span>
                                    <div class="profit-chart" id="profit-chart"></div>
                                    <div class="chart-detail">
                                        <h5>Profit in 6 months</h5>
                                        <h2>$7454.25</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 box-col-50 des-xl-50">
                <div class="card social-shared">
                    <div class="card-header pb-0">
                        <h3>Top Social Media Shared</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordernone">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex">
                                                <div class="icon"><a href="https://www.facebook.com/"
                                                        target="_blank"><img class="img-fluid"
                                                            src="{{ asset('assets/images/dashboard/social-media/fb.png') }}"
                                                            alt=""></a></div>
                                                <div class="flex-grow-1"><a href="https://www.facebook.com/login"
                                                        target="_blank">
                                                        <h5>Facebook</h5>
                                                        <p>Social Media </p>
                                                    </a></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex"><i class="fa fa-arrow-up font-success me-2"></i>
                                                <h5>3.7%</h5>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>$24,000 </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex">
                                                <div class="icon"><a href="https://www.instagram.com/accounts/login/"
                                                        target="_blank"><img class="img-fluid"
                                                            src="{{ asset('assets/images/dashboard/social-media/insta.png') }}"
                                                            alt=""></a></div>
                                                <div class="flex-grow-1"><a
                                                        href="https://www.instagram.com/accounts/login/" target="_blank">
                                                        <h5>Instagram</h5>
                                                        <p>Social Media</p>
                                                    </a></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex"> <i class="fa fa-arrow-up font-success me-2"></i>
                                                <h5>3.7%</h5>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>$33,000</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex">
                                                <div class="icon"><a href="https://www.twitter.com/login"
                                                        target="_blank"><img class="img-fluid"
                                                            src="{{ asset('assets/images/dashboard/social-media/twit.png') }}"
                                                            alt=""></a></div>
                                                <div class="flex-grow-1"><a href="https://www.twitter.com/login"
                                                        target="_blank">
                                                        <h5>Twitter</h5>
                                                        <p>Social Media </p>
                                                    </a></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex"><i class="fa fa-arrow-up font-success me-2"></i>
                                                <h5>7.6%</h5>
                                            </div>
                                        </td>
                                        <td>
                                            <h5>$72,000 </h5>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 box-col-50 des-xl-50">
                <div class="card upgrade-history">
                    <div class="card-body">
                        <div>
                            <h3>Buy more space now! </h3>
                            <p>Invite 2 Friends and get 5 GB extra space.</p><a class="btn btn-primary"
                                href="https://themeforest.net/user/pixelstrap/portfolio" target="_blank"> Upgrade now</a>
                        </div>
                    </div>
                    <div class="upgrade-img"><img class="img-fluid" src="{{ asset('assets/images/dashboard/upgrade/1.png') }}"
                            alt=""></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/chart/knob/knob.min.js') }}"></script>
<script src="{{ asset('assets/js/chart/knob/knob-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"> </script>
<script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
<script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
<script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
@endsection
