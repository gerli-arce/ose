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
                        <li class="breadcrumb-item active">Knowledgebase Details </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Starts-->
    <div class="container-fluid knowledge-details">
        <div class="row">
            <div class="col-xl-3 xl-40 box-col-40">
                <div class="md-sidebar job-sidebar"><a class="md-sidebar-toggle btn btn-primary" href="javascript:void(0)">Knowledge
                        filter</a>
                    <div class="md-sidebar-aside job-left-aside custom-scrollbar custom-scrollbar">
                        <div class="default-according style-1 faq-accordion job-accordion" id="accordionoc">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0 p-0">
                                                <button class="btn btn-link ps-0" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseicon1">Knowledge Categories</button>
                                            </h5>
                                        </div>
                                        <div class="collapse show" id="collapseicon1" aria-labelledby="collapseicon1"
                                            data-bs-parent="#accordion">
                                            <div class="categories">
                                                <div class="learning-header"><span class="f-w-600">Design</span></div>
                                                <ul>
                                                    <li><a href="javascript:void(0)">UI Design </a><span
                                                            class="badge badge-primary pull-right">28</span></li>
                                                    <li><a href="javascript:void(0)">UX Design </a><span
                                                            class="badge badge-primary pull-right">35</span></li>
                                                    <li><a href="javascript:void(0)">Interface Design </a><span
                                                            class="badge badge-primary pull-right">17</span></li>
                                                    <li><a href="javascript:void(0)">User Experience </a><span
                                                            class="badge badge-primary pull-right">26</span></li>
                                                </ul>
                                            </div>
                                            <div class="categories pt-0">
                                                <div class="learning-header"><span class="f-w-600">Development</span></div>
                                                <ul>
                                                    <li><a href="javascript:void(0)">Frontend Development</a><span
                                                            class="badge badge-primary pull-right">48</span></li>
                                                    <li><a href="javascript:void(0)">Backend Development</a><span
                                                            class="badge badge-primary pull-right">19</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0 p-0">
                                                <button class="btn btn-link ps-0" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseicon2" aria-expanded="true"
                                                    aria-controls="collapseicon2">Upcoming Courses</button>
                                            </h5>
                                        </div>
                                        <div class="collapse show" id="collapseicon2" aria-labelledby="collapseicon2"
                                            data-bs-parent="#accordion">
                                            <div class="upcoming-course card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1"><span class="f-w-600">UX Development</span>
                                                        <h6>Course By <a href="javascript:void(0)">Development Team</a></h6>
                                                        <ul class="rating">
                                                            <li><i class="fa fa-star font-warning"></i></li>
                                                            <li><i class="fa fa-star font-warning"></i></li>
                                                            <li><i class="fa fa-star font-warning"></i></li>
                                                            <li><i class="fa fa-star-o font-warning"></i></li>
                                                            <li><i class="fa fa-star-o font-warning"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0 p-0 font-primary">18</h5><span class="d-block">Dec</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-grow-1"><span class="f-w-600">Business Analyst</span>
                                                        <h6>Course By <a href="javascript:void(0)">Analyst Team.</a></h6>
                                                        <ul class="rating">
                                                            <li><i class="fa fa-star font-warning"></i></li>
                                                            <li><i class="fa fa-star font-warning"></i></li>
                                                            <li><i class="fa fa-star-o font-warning"></i></li>
                                                            <li><i class="fa fa-star-o font-warning"></i></li>
                                                            <li><i class="fa fa-star-o font-warning"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0 p-0 font-primary">28</h5><span class="d-block">Dec</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-grow-1"><span class="f-w-600">Web Development</span>
                                                        <h6>Course By <a href="javascript:void(0)">Designer</a></h6>
                                                        <ul class="rating">
                                                            <li><i class="fa fa-star font-warning"></i></li>
                                                            <li><i class="fa fa-star font-warning"></i></li>
                                                            <li><i class="fa fa-star font-warning"></i></li>
                                                            <li><i class="fa fa-star font-warning"></i></li>
                                                            <li><i class="fa fa-star-half-o font-warning"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0 p-0 font-primary">5</h5><span class="d-block">Jan</span>
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
            </div>
            <div class="col-xl-9 xl-60 box-col-60">
                <div class="blog-single">
                    <div class="blog-box">
                        <div class="card"><img class="img-fluid w-100" src="{{ asset('assets/images/knowledgebase/1.jpg') }}"
                                alt="blog-main"></div>
                        <div class="card">
                            <div class="card-body">
                                <div class="blog-details">
                                    <h4 class="mt-0">Why Choose Knowledge Course ?</h4>
                                    <div class="single-blog-content-top">
                                        <p>Knowledge can be defined as awareness of facts or as practical skills, and may also refer to familiarity with objects or situations. Knowledge of facts, also called propositional knowledge, is often defined as true belief that is distinct from opinion or guesswork by virtue of justification. While there is wide agreement among philosophers that it is a form of true belief, many controversies in philosophy focus on justification: whether it is needed at all, how to understand it, and whether something else besides it is needed.</p>
                                        <p>Knowledge can be produced in many different ways. The most important source is perception, which refers to the usage of the five senses. Many theorists also include introspection as a source of knowledge, not of external physical objects, but of one's own mental states. Other sources often discussed include memory, rational intuition, inference, and testimony. According to foundationalism, some of these sources are basic in the sense that they can justify beliefs without depending on other mental states. This claim is rejected by coherentists.</p>
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
