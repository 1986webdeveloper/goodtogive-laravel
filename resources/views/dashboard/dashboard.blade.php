@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <div class="content-wrapper">
            
            <div class="content-body">
                <!-- Revenue, Hit Rate & Deals -->
                <div class="row">
                    <div class="col-xl-6 col-12">
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="card pull-up">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="media-body text-left">
                                                    <h6 class="text-muted">Total Users </h6>
                                                    <h3>{{ $userCount }}</h3>
                                                </div>
                                                <div class="align-self-center">
                                                    <i class="icon-user success font-large-2 float-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="card pull-up">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="media-body text-left">
                                                    <h6 class="text-muted">Total Projects</h6>
                                                    <h3>{{ $projectCount }}</h3>
                                                </div>
                                                <div class="align-self-center">
                                                    <i class="icon-user success font-large-2 float-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-12">
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="card pull-up">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="media-body text-left">
                                                    <h6 class="text-muted">Total Fund </h6>
                                                    <h3>{{ $totalFund }}</h3>
                                                </div>
                                                <div class="align-self-center">
                                                    <i class="icon-user success font-large-2 float-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="card pull-up">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="media d-flex">
                                                <div class="media-body text-left">
                                                    <h6 class="text-muted">Others</h6>
                                                    <h3>3,568</h3>
                                                </div>
                                                <div class="align-self-center">
                                                    <i class="icon-call-in danger font-large-2 float-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Revenue, Hit Rate & Deals -->

                <!-- `new` constructor table -->
                <section id="constructor">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Upcoming Event</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">
                                        @if(Session::has('message'))
                                            <div class="alert alert-success mb-2" role="alert">
                                                {{ Session::get('message')}}    
                                            </div>
                                        @endif
                                        <table data-ajax-url="{{ route('dashboard-list') }}" class="table table-striped table-bordered dataex-res-constructor" id="editable-sample" data-order-type="DESC" data-order-cols="1">
                                            <thead>
                                                <tr>
                                                    <th data-table-search="false" data-table-sort="true" data-name="church_reference_id" data-width="5">Reference ID</th>
                                                    <th data-table-search="true" data-table-sort="true" data-name="church_name">Church Name</th>
                                                    <th data-table-search="true" data-table-sort="true" data-name="title">Event Title</th>
                                                    <th data-table-search="true" data-table-sort="true" data-name="description" >Description</th>
                                                    <th data-table-search="true" data-table-sort="true" data-name="start_date">Start Date</th>
                                                    <th data-table-search="true" data-table-sort="true" data-name="end_date">End Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- `new` constructor table -->
            </div>
        </div>
    @endsection
    