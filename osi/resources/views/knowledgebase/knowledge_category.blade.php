@extends('layout.master')

@section('main-content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Knowledgebase</h3>
                </div>
                <div class="col-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> <i data-feather="home"></i></a></li>
                        <li class="breadcrumb-item">Knowledgebase</li>
                        <li class="breadcrumb-item active">Knowledgebase Category</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid faq-section">
        <div class="row">
            <div class="col-12">
                <div class="knowledgebase-bg"><img class="bg-img-cover bg-center"
                        src="{{ asset('assets/images/knowledgebase/bg_1.jpg') }}" alt="Background Image1"></div>
                <div class="knowledgebase-search">
                    <div>
                        <h3>How Can I help you?</h3>
                        <form class="form-inline" action="#" method="get">
                            <div class="form-group w-100 mb-0"><i data-feather="search"></i>
                                <input class="form-control-plaintext w-100" type="text" placeholder="Type question here"
                                    title="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 box-col-33">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="d-flex faq-widgets">
                            <div class="flex-grow-1">
                                <h2>Articles</h2>
                                <p>How little experience or technical knowledge you currently have. The web is a very big place, and if you are the typical internet user, you probably visit several websites every day.</p>
                            </div><i data-feather="book-open"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 box-col-33">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="d-flex faq-widgets">
                            <div class="flex-grow-1">
                                <h2>Knowledgebase</h2>
                                <p>A Website Designing course enables learners to use essential designing and programming tools required to do the job efficiently. The curriculum is a blend of various themes.</p>
                            </div><i data-feather="aperture"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12 box-col-33">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="d-flex faq-widgets">
                            <div class="flex-grow-1">
                                <h2>Support</h2>
                                <p>The customer support industry is renaissance. Customer support as a specialty is coming into its own, offering companies a competitive advantage that’s difficult to copy.</p>
                            </div><i data-feather="file-text"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="header-faq">
                    <h5 class="mb-0">knowledge articles by category</h5>
                </div>
                <div class="row browse">
                    <div class="col-xl-4 col-md-6 box-col-33">
                        <div class="browse-articles">
                            <h3><span><i data-feather="archive"></i></span>Any other legal pages I should know about?</h3>
                            <ul>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Policies have wide acceptance editors</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"> </i>Guidelines are sets of best practices supported.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Editors should attempt to follow guidelines.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>They are best treated common sense.</h5>
                                    </a></li>
                                <li><a class="f-w-600 font-primary" href="{{ route('knowledge-detail') }}"> <i class="me-1"
                                            data-feather="arrow-right"></i><span>See More (40)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 box-col-33">
                        <div class="browse-articles">
                            <h3><span><i data-feather="archive"></i></span>Does Moment have an API export?</h3>
                            <ul>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Resources API retrieves content produced by businesses.
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>API allows the search and download of AP Images.
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>API retrieve a list of breaking news categories
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>API retrieves rules for a fantasy league.
                                        </h5>
                                    </a></li>
                                <li><a class="f-w-600 font-primary" href="{{ route('knowledge-detail') }}"><i class="me-1"
                                            data-feather="arrow-right"></i><span> See More (90) </span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 box-col-33">
                        <div class="browse-articles">
                            <h3><span><i data-feather="archive"></i></span>How do WordPress Site Maintenance</h3>
                            <ul>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Make a backup of your WordPress website.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Run a Wordpress security Website check.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Monitor your search engine optimization.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Set a regular maintenance schedule in Website.</h5>
                                    </a></li>
                                <li><a class="f-w-600 font-primary" href="{{ route('knowledge-detail') }}"> <i class="me-1"
                                            data-feather="arrow-right"></i><span>See More (40)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 box-col-33">
                        <div class="browse-articles browse-bottom">
                            <h3><span><i data-feather="archive"></i></span>Can I change my plan later?</h3>
                            <ul>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>You can easily change plans at any time.
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i><span>you’ll be charged a prorated fee to cover that upgrade.</span></h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i><span>But also the leap into electronic typesetting,</span></h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i><span>When an unknown printer took a galley</span></h5>
                                    </a></li>
                                <li><a class="f-w-600 font-primary" href="{{ route('knowledge-detail') }}"> <i class="me-1"
                                            data-feather="arrow-right"></i><span>See More (90)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 box-col-33">
                        <div class="browse-articles browse-bottom">
                            <h3><span><i data-feather="archive"></i></span>How do I pay for the Premium Plan?</h3>
                            <ul>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Premium plans are billed as a single yearly payment.
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Monthly plans are billed every month date of your signup.
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>You can set Monthly plan.
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>When an unknown printer took a galley
                                        </h5>
                                    </a></li>
                                <li><a class="f-w-600 font-primary" href="{{ route('knowledge-detail') }}"> <i class="me-1"
                                            data-feather="arrow-right"></i><span>See More (50)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 box-col-33">
                        <div class="browse-articles browse-bottom">
                            <h3><span><i data-feather="archive"></i></span>What is your Shopping Policy</h3>
                            <ul>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Many new editors rely on nuanced policy interpretations.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Editor continually attempts to find a different policy.
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Violation of maintaining a neutral point of view.
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>You double check your Website sources.
                                        </h5>
                                    </a></li>
                                <li><a class="f-w-600 font-primary" href="{{ route('knowledge-detail') }}"> <i class="me-1"
                                            data-feather="arrow-right"></i><span>See More (26)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 box-col-33">
                        <div class="browse-articles browse-bottom">
                            <h3><span><i data-feather="archive"></i></span>How can I change the component?</h3>
                            <ul>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>You can modify some specific component settings.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Click Apply to validate the set preferences.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Following procedure applies to the external components
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>To modify those specific components settings.</h5>
                                    </a></li>
                                <li><a class="f-w-600 font-primary" href="{{ route('knowledge-detail') }}"> <i class="me-1"
                                            data-feather="arrow-right"></i><span>See More (10)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 box-col-33">
                        <div class="browse-articles browse-bottom">
                            <h3><span><i data-feather="archive"></i></span>How does it work?</h3>
                            <ul>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>To get started with your Theme Forest account.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Compile your Koho theme using gulp Compiler.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Users can browse by topic or use the visually.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>This works through various methods of site tracking</h5>
                                    </a></li>
                                <li><a class="f-w-600 font-primary" href="{{ route('knowledge-detail') }}"> <i class="me-1"
                                            data-feather="arrow-right"></i><span>See More (21)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 box-col-33">
                        <div class="browse-articles browse-bottom">
                            <h3><span><i data-feather="archive"></i></span>What is Lorem Ipsum?</h3>
                            <ul>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Common language regular than the existing languages.
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Lorem Ipsum has been the industry's standard dummy
                                        </h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>Language is not real even ‘Lorem’ does't exist.</h5>
                                    </a></li>
                                <li><a href="{{ route('knowledge-detail') }}">
                                        <h5><i data-feather="file-text"></i>dummy text that is not meant to mean anything.</h5>
                                    </a></li>
                                <li><a class="f-w-600 font-primary" href="{{ route('knowledge-detail') }}"> <i class="me-1"
                                            data-feather="arrow-right"></i><span>See More (34)</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
