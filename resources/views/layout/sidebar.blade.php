<div class="sidebar-wrapper">
    <div>
        <div class="logo-wrapper"><a href="{{ route('dashboard') }}"><img class="img-fluid for-light"
                    src="{{ asset('assets/images/logo/logo.png') }}" alt=""><img class="img-fluid for-dark"
                    src="{{ asset('assets/images/logo/logo-dark.png') }}" alt=""></a>
                    <div class="back-btn"><i class="fa fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-left"> </i>
            </div>
        </div>
        <div class="logo-icon-wrapper"><a href="{{ route('dashboard') }}"><img class="img-fluid for-light"
                    src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""><img class="img-fluid for-dark"
                    src="{{ asset('assets/images/logo/logo-icon-dark.png') }}" alt=""></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"><a href="{{ route('dashboard') }}"><img class="img-fluid for-light"
                                src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""><img
                                class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo-icon-dark.png') }}"
                                alt=""></a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h4 class="lan-1">General </h4>
                        </div>
                    </li>
                    <li class="sidebar-list"> <a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="home"></i><span class="lan-3">Dashboard</span><span
                                class="badge badge-primary">2</span></a>
                        <ul class="sidebar-submenu">
                            <li><a class="lan-4" href="{{ route('dashboard') }}">Default</a></li>
                            <li><a class="lan-5" href="{{ route('ecommerce_dashboard') }}">Ecommerce</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="airplay"></i><span class="lan-6">Widgets</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('general-widget') }}">General</a></li>
                            <li><a href="{{ route('chart-widget') }}">Chart</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="layout"></i><span class="lan-7">Page
                                layout</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('box-layout') }}">Boxed</a></li>
                            <li><a href="{{ route('layout-rtl') }}">RTL</a></li>
                            <li><a href="{{ route('layout-dark') }}">Dark Layout</a></li>
                            <li><a href="{{ route('hide-on-scroll') }}">Hide Nav Scroll</a></li>
                            <li><a href="{{ route('footer-light') }}">Footer Light</a></li>
                            <li><a href="{{ route('footer-dark') }}">Footer Dark</a></li>
                            <li><a href="{{ route('footer-fixed') }}">Footer Fixed</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h4 class="lan-8">Applications </h4>
                        </div>
                    </li>
                    <li class="sidebar-list"> <a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="box"></i><span>Project </span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('projects') }}">Project List</a></li>
                            <li><a href="{{ route('projectcreate') }}">Create new</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('file_manager') }}"><i data-feather="git-pull-request"> </i><span>File
                                manager</span></a></li>
                    <li class="sidebar-list"> <a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('kanban') }}"><i data-feather="monitor"> </i><span>kanban Board</span></a>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="shopping-bag"></i><span>Ecommerce</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('product') }}">Product</a></li>
                            <li><a href="{{ route('page-product') }}">Product page</a></li>
                            <li><a href="{{ route('add_product') }}">Add Product</a></li>
                            <li><a href="{{ route('list-products') }}">Product list</a></li>
                            <li><a href="{{ route('payment-details') }}">Payment Details</a></li>
                            <li><a href="{{ route('order-history') }}">Order History</a></li>
                            <li><a href="{{ route('invoice-template') }}">Invoice</a></li>
                            <li><a href="{{ route('cart') }}">Cart</a></li>
                            <li><a href="{{ route('list-wish') }}">Wishlist</a></li>
                            <li><a href="{{ route('checkout') }}">Checkout</a></li>
                            <li><a href="{{ route('pricing') }}">Pricing </a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="mail"></i><span>Email</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('email_inbox') }}">Mail Inbox</a></li>
                            <li><a href="{{ route('email_read') }}">Read mail</a></li>
                            <li><a href="{{ route('email_compose') }}">Compose</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="message-circle"></i><span>Chat</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('chat') }}">Chat App</a></li>
                            <li><a href="{{ route('video_chat') }}">Video chat</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="users"></i><span>Users</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('user-profile') }}">Users Profile</a></li>
                            <li><a href="{{ route('edit-profile') }}">Users Edit</a></li>
                            <li><a href="{{ route('user-cards') }}">Users Cards</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('bookmark') }}"><i data-feather="heart"> </i><span>Bookmarks</span></a>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('contacts') }}"><i data-feather="list"> </i><span>Contacts</span></a>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('task') }}"><i data-feather="check-square"> </i><span>Tasks</span></a>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('calendar_basic') }}"><i data-feather="calendar">
                            </i><span>Calendar</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('social_app') }}"><i data-feather="zap"> </i><span>Social App</span></a>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('to_do') }}"><i data-feather="clock"> </i><span>To-Do</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('search') }}"><i data-feather="search"> </i><span>Search
                                Result</span></a></li>
                    <li class="sidebar-main-title">
                        <div>
                            <h4>Forms & Table </h4>
                        </div>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="file-text"></i><span>Forms</span></a>
                        <ul class="sidebar-submenu">
                            <li><a class="submenu-title" href="javascript:void(0)">Form Controls<span
                                        class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="{{ route('form-validation') }}">Form Validation</a></li>
                                    <li><a href="{{ route('base-input') }}">Base Inputs</a></li>
                                    <li><a href="{{ route('radio-checkbox-control') }}">Checkbox & Radio</a></li>
                                    <li><a href="{{ route('input-group') }}">Input Groups</a></li>
                                    <li><a href="{{ route('megaoptions') }}">Mega Options</a></li>
                                </ul>
                            </li>
                            <li><a class="submenu-title" href="javascript:void(0)">Form Widgets<span
                                        class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="{{ route('datepicker') }}">Datepicker</a></li>
                                    <li><a href="{{ route('time-picker') }}">Timepicker</a></li>
                                    <li><a href="{{ route('datetimepicker') }}">Datetimepicker</a></li>
                                    <li><a href="{{ route('daterangepicker') }}">Daterangepicker</a></li>
                                    <li><a href="{{ route('touchspin') }}">Touchspin</a></li>
                                    <li><a href="{{ route('select2') }}">Select2</a></li>
                                    <li><a href="{{ route('switch') }}">Switch</a></li>
                                    <li><a href="{{ route('typeahead') }}">Typeahead</a></li>
                                    <li><a href="{{ route('clipboard') }}">Clipboard</a></li>
                                </ul>
                            </li>
                            <li><a class="submenu-title" href="javascript:void(0)">Form layout<span
                                        class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="{{ route('default-form') }}">Default Forms</a></li>
                                    <li><a href="{{ route('form-wizard') }}">Form Wizard 1</a></li>
                                    <li><a href="{{ route('form-wizard-two') }}">Form Wizard 2</a></li>
                                    <li><a href="{{ route('form-wizard-three') }}">Form Wizard 3</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i data-feather="server"></i><span>Tables</span></a>
                        <ul class="sidebar-submenu">
                            <li><a class="submenu-title" href="javascript:void(0)">Bootstrap Tables<span
                                        class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="{{ route('bootstrap-basic-table') }}">Basic Tables</a></li>
                                    <li><a href="{{ route('table-components') }}">Table components</a></li>
                                </ul>
                            </li>
                            <li><a class="submenu-title" href="javascript:void(0)">Data Tables<span
                                        class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="{{ route('datatable-basic-init') }}">Basic Tables</a></li>
                                    <li><a href="{{ route('datatable-api') }}">Data API</a></li>
                                    <li><a href="{{ route('datatable-data-source') }}">Data Sources</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('datatable-ext-autofill') }}">Ex. Data Tables</a>
                            </li>
                            <li><a href="{{ route('js_grid_table') }}">Js Grid Table </a></li>
                        </ul>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h4>Components</h4>
                        </div>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="box"></i><span>Ui Kits</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('state-color')}}">State color</a></li>
                            <li><a href="{{ route('typography') }}">Typography</a></li>
                            <li><a href="{{ route('avatars') }}">Avatars</a></li>
                            <li><a href="{{ route('helper-classes') }}">Helper classes</a></li>
                            <li><a href="{{ route('grid') }}">Grid</a></li>
                            <li><a href="{{ route('tag-pills') }}">Tag & pills</a></li>
                            <li><a href="{{ route('progress-bar') }}">Progress</a></li>
                            <li><a href="{{ route('modal') }}">Modal</a></li>
                            <li><a href="{{ route('alert') }}">Alert</a></li>
                            <li><a href="{{ route('popover') }}">Popover</a></li>
                            <li><a href="{{ route('tooltip') }}">Tooltip</a></li>
                            <li><a href="{{ route('loader') }}">Spinners</a></li>
                            <li><a href="{{ route('dropdown') }}">Dropdown</a></li>
                            <li><a href="{{ route('according') }}">Accordion</a></li>
                            <li><a class="submenu-title" href="javascript:void(0)">Tabs<span class="sub-arrow"><i
                                            class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="{{ route('tab_bootstrap') }}">Bootstrap Tabs</a></li>
                                    <li><a href="{{ route('tab_line') }}">Line Tabs</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('box-shadow') }}">Shadow</a></li>
                            <li><a href="{{ route('list') }}">Lists</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="folder-plus"></i><span>Bonus
                                Ui</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('scrollable') }}">Scrollable</a></li>
                            <li><a href="{{ route('tree') }}">Tree view</a></li>
                            <li><a href="{{ route('bootstrap-notify') }}">Bootstrap Notify</a></li>
                            <li><a href="{{ route('rating') }}">Rating</a></li>
                            <li><a href="{{ route('dropzone') }}">dropzone</a></li>
                            <li><a href="{{ route('tour') }}">Tour</a></li>
                            <li><a href="{{ route('sweet-alert2') }}">SweetAlert2</a></li>
                            <li><a href="{{ route('modal-animated') }}">Animated Modal</a></li>
                            <li><a href="{{ route('owl-carousel') }}">Owl Carousel</a></li>
                            <li><a href="{{ route('ribbons') }}">Ribbons</a></li>
                            <li><a href="{{ route('pagination') }}">Pagination</a></li>
                            <li><a href="{{ route('breadcrumb') }}">Breadcrumb</a></li>
                            <li><a href="{{ route('range-slider') }}">Range Slider</a></li>
                            <li><a href="{{ route('image-cropper') }}">Image cropper</a></li>
                            <li><a href="{{ route('sticky') }}">Sticky</a></li>
                            <li><a href="{{ route('basic-card') }}">Basic Card</a></li>
                            <li><a href="{{ route('creative-card') }}">Creative Card</a></li>
                            <li><a href="{{ route('tabbed-card') }}">Tabbed Card</a></li>
                            <li><a href="{{ route('dragable-card') }}">Draggable Card</a></li>
                            <li><a class="submenu-title" href="javascript:void(0)">Timeline<span class="sub-arrow"><i
                                            class="fa fa-angle-right"></i></span></a>
                                <ul class="nav-sub-childmenu submenu-content">
                                    <li><a href="{{ route('timeline_v_1') }}">Timeline 1</a></li>
                                    <li><a href="{{ route('timeline_v_2') }}">Timeline 2</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="edit-3"></i><span>Builders</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('form-builder-1') }}"> Form Builder 1</a></li>
                            <li><a href="{{ route('form-builder-2') }}"> Form Builder 2</a></li>
                            <li><a href="{{ route('pagebuild') }}">Page Builder</a></li>
                            <li><a href="{{ route('button-builder') }}">Button Builder</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="cloud-drizzle"></i><span>Animation</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('animate') }}">Animate</a></li>
                            <li><a href="{{ route('scroll-reval') }}">Scroll Reveal</a></li>
                            <li><a href="{{ route('aos') }}">AOS animation</a></li>
                            <li><a href="{{ route('tilt') }}">Tilt Animation</a></li>
                            <li><a href="{{ route('wow') }}">Wow Animation</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="command"></i><span>Icons</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('flag-icon') }}">Flag icon</a></li>
                            <li><a href="{{ route('font-awesome') }}">Fontawesome Icon</a></li>
                            <li><a href="{{ route('ico-icon') }}">Ico Icon</a></li>
                            <li><a href="{{ route('themify-icon') }}">Thimify Icon</a></li>
                            <li><a href="{{ route('feather-icon') }}">Feather icon</a></li>
                            <li><a href="{{ route('whether-icon') }}">Whether Icon</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="cloud"></i><span>Buttons</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('buttons') }}">Default Style</a></li>
                            <li><a href="{{ route('button-group') }}">Button Group</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="bar-chart"></i><span>Charts</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('chart-apex') }}">Apex Chart</a></li>
                            <li><a href="{{ route('chart-google') }}">Google Chart</a></li>
                            <li><a href="{{ route('chart-sparkline') }}">Sparkline chart</a></li>
                            <li><a href="{{ route('chart-flot') }}">Flot Chart</a></li>
                            <li><a href="{{ route('chart-knob') }}">Knob Chart</a></li>
                            <li><a href="{{ route('chart-morris') }}">Morris Chart</a></li>
                            <li><a href="{{ route('chartjs') }}">Chatjs Chart</a></li>
                            <li><a href="{{ route('chartist') }}">Chartist Chart</a></li>
                            <li><a href="{{ route('chart-peity') }}">Peity Chart</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h4>Pages </h4>
                        </div>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('sample_page') }}"><i data-feather="file-text"> </i><span>Sample
                                page</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('internationalization') }}"><i data-feather="globe">
                            </i><span>Internationalization</span></a></li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('dashboard') }}" target="_blank"><i
                                data-feather="anchor"></i><span>Starter kit</span></a></li>
                    <li class="mega-menu"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="layers"></i><span>Others</span></a>
                        <div class="mega-menu-container menu-content">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Error Page</h5>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu">
                                                <li><a href="{{ route('error-page1') }}">Error Page 1</a></li>
                                                <li><a href="{{ route('error-page2') }}">Error Page 2</a></li>
                                                <li><a href="{{ route('error-page3') }}">Error Page 3</a></li>
                                                <li><a href="{{ route('error-page4') }}">Error Page 4</a></li>
                                                <li><a href="{{ route('error-page5') }}">Error Page 5 </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5> Authentication</h5>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu">
                                                <li><a href="{{ route('login') }}" target="_blank">Login Simple</a>
                                                </li>
                                                <li><a href="{{ route('login_one') }}" target="_blank">Login with bg
                                                        image</a></li>
                                                <li><a href="{{ route('login_two') }}" target="_blank">Login with
                                                        image two </a></li>
                                                <li><a href="{{ route('login-bs-validation') }}"
                                                        target="_blank">Login With validation</a></li>
                                                <li><a href="{{ route('login-bs-tt-validation') }}"
                                                        target="_blank">Login with tooltip</a></li>
                                                <li><a href="{{ route('login-sa-validation') }}"
                                                        target="_blank">Login with sweetalert</a></li>
                                                <li><a href="{{ route('sign-up') }}" target="_blank">Register
                                                        Simple</a></li>
                                                <li><a href="{{ route('sign-up-one') }}" target="_blank">Register
                                                        with Bg Image </a></li>
                                                <li><a href="{{ route('sign-up-two') }}" target="_blank">Register
                                                        with Bg video</a></li>
                                                <li><a href="{{ route('unlock') }}">Unlock User</a></li>
                                                <li><a href="{{ route('forget-password') }}">Forget Password</a></li>
                                                <li><a href="{{ route('reset-password') }}">Reset Password</a></li>
                                                <li><a href="{{ route('maintenance') }}">Maintenance</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Coming Soon</h5>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu">
                                                <li><a href="{{ route('comingsoon') }}">Coming Simple</a></li>
                                                <li><a href="{{ route('comingsoon-bg-video') }}">Coming with Bg
                                                        video</a></li>
                                                <li><a href="{{ route('comingsoon-bg-img') }}">Coming with Bg
                                                        Image</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Email templates</h5>
                                            </div>
                                            <ul class="submenu-content opensubmegamenu">
                                                <li><a href="{{ route('basic-template') }}">Basic Email</a></li>
                                                <li><a href="{{ route('email-header') }}">Basic With Header</a></li>
                                                <li><a href="{{ route('template-email') }}">Ecomerce Template</a>
                                                </li>
                                                <li><a href="{{ route('template-email-2') }}">Email Template 2</a>
                                                </li>
                                                <li><a href="{{ route('ecommerce-templates') }}">Ecommerce Email</a>
                                                </li>
                                                <li><a href="{{ route('email-order-success') }}">Order Success</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h4>Miscellaneous </h4>
                        </div>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="image"></i><span>Gallery</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('gallery') }}">Gallery Grid</a></li>
                            <li><a href="{{ route('gallery-with-description') }}">Gallery Grid Desc</a></li>
                            <li><a href="{{ route('masonry-gallery') }}">Masonry Gallery</a></li>
                            <li><a href="{{ route('masonry-gallery-with-disc') }}">Masonry with Desc</a></li>
                            <li><a href="{{ route('gallery-hover') }}">Hover Effects</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="film"></i><span>Blog</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('blog') }}">Blog Details</a></li>
                            <li><a href="{{ route('single-blog') }}">Blog Single</a></li>
                            <li><a href="{{ route('add-post') }}">Add Post</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('faq_page') }}"><i data-feather="help-circle"> </i><span>FAQ</span></a>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="package"></i><span>Job
                                Search</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('job-cards-view') }}">Cards view</a></li>
                            <li><a href="{{ route('job-list-view') }}">List View</a></li>
                            <li><a href="{{ route('job-details') }}">Job Details</a></li>
                            <li><a href="{{ route('job-apply') }}">Apply</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="radio"></i><span>Learning</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('learning-list-view') }}">Learning List</a></li>
                            <li><a href="{{ route('learning-detailed') }}">Detailed Course</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="map"></i><span>Maps</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('map_js') }}">Maps JS</a></li>
                            <li><a href="{{ route('vector-map') }}">Vector Maps</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="edit"></i><span>Editors</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('summernote') }}">Summer Note</a></li>
                            <li><a href="{{ route('ckeditor') }}">CK editor</a></li>
                            <li><a href="{{ route('simple-mde') }}">MDE editor</a></li>
                            <li><a href="{{ route('ace_code_editor') }}">ACE code editor</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="javascript:void(0)"><i
                                data-feather="sunrise">
                            </i><span>Knowledgebase</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('knowledgebase') }}">Knowledgebase</a></li>
                            <li><a href="{{ route('knowledge-category') }}">Knowledge category</a></li>
                            <li><a href="{{ route('knowledge-detail') }}">Knowledge detail </a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav"
                            href="{{ route('support-ticket') }}"><i data-feather="users"> </i><span>Support Ticket
                            </span></a></li>
                </ul>
                <div class="sidebar-img-section">
                    <div class="sidebar-img-content"><img class="img-fluid"
                            src="{{ asset('assets/images/dashboard/upgrade/2.png') }}" alt="">
                        <h4>Experiance with more Features</h4><a class="btn btn-primary"
                            href="https://themeforest.net/user/pixelstrap/portfolio" target="_blank">Check
                            now</a>
                    </div>
                </div>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
