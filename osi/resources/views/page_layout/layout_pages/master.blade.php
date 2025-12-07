<!DOCTYPE html>
@yield('page-html')

<head>
    {{-- meta and title includes--}}
    @include('layout.head')

    {{-- page css start --}}
    @include('page_layout.layout_pages.css')
    {{-- page css end --}}

</head>

@yield('page-body')
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->

    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="loader"></div>
    </div>
    <!-- Loader ends-->

    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper box-layout" id="pageWrapper">

    <!-- Page Header Start-->
        @include('layout.header')
    <!-- Page Header Ends -->

    <!-- Page Body Start-->
        <div class="page-body-wrapper">

    <!-- Page Sidebar Start-->
                @include('layout.sidebar')
    <!-- Page Sidebar Ends-->

    <div class="page-body">

    {{--page main content  --}}
    @yield('page-content')

    </div>
    <!-- footer start-->
    @yield('page-footer')
    </div>

    </div>

    {{-- page scripts --}}
    @include('page_layout.layout_pages.scripts')

</body>

</html>
