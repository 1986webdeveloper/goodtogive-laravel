@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Project Slabs</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item activ">Project Slabs
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right">
                        <a class="btn btn-info" href="{{ route('project-slab-add')}}">Add Project Slab</a>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- `new` constructor table -->
                <section id="constructor">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Project Slabs</h4>
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
                                        <table data-ajax-url="{{ route('project-slab-list') }}" class="table table-striped responsive table-bordered dataex-res-constructor" id="editable-sample" data-order-type="DESC" data-order-cols="1">
                                            <thead>
                                                <tr>
                                                    <th data-table-search="false" data-table-sort="true" data-name="DT_RowIndex" data-width="5">ID</th>
                                                    <th data-table-search="true" data-table-sort="true" data-name="project_name">Project Name</th>
                                                    <th data-table-search="true" data-table-sort="true" data-name="amount">Amount</th>
                                                    <th data-table-search="false" data-table-sort="false" data-name="action">Action</th>
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
        </div> 
    @endsection
    
    