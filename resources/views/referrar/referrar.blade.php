@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Referrar</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Referrar
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                 <div class="content-header-right col-md-6 col-12">
                    <div class="btn-group float-md-right">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#inlineForm">
                                                        Manage Referrar
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <label class="modal-title text-text-bold-600" id="myModalLabel33">Manage Church Referrar</label>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('action-referrar-add') }}" method = "post" class="manage-referrer-submit wizard-notification" id="target">
                                                                <form action="#">
                                                                    <div class="modal-body">
                                                                        <label>Church List: </label>
                                                                        <div class="form-group">
                                                                            <select class="c-select form-control is_church_referrer" id="church_referrer" name="church_id">
                                                                            <option value="">Select Church </option>

                                                                            @foreach($userList as $churchinfo)
                                                                            <option value="{{ $churchinfo->id }}">{{ $churchinfo->firstname }} {{ $churchinfo->lastname }}</option>
                                                                            @endforeach

                                                                            </select>
                                                                        </div>

                                                                        <label>Refrrer ID: </label>
                                                                        <div class="form-group">
                                                                            <input type="text" id="referrer_id" name="referrer_Id" placeholder="Referrer ID" class="form-control">
                                                                        </div>
                                                                         <label>Vendor Id: </label>
                                                                        <div class="form-group">
                                                                            <input type="text" id="vendor_id" name="vendor_id" placeholder="Vendor Id" class="form-control">
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                  
                                                                        <a href="javascript:void(0)" class="btn btn-warning mr-1" data-dismiss="modal">
                                                                        <i class="ft-x"></i> Cancel
                                                                        </a>
                                                                        <button type="submit" class="btn btn-primary">
                                                                        <i class="la la-check-square-o"></i> Save
                                                                        </button>
                                                                   
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                    <h4 class="card-title">Referrar</h4>
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
                                        <table data-ajax-url="{{ route('referrar-list') }}" class="table table-striped table-hover responsive table-bordered" id="editable-sample" data-order-type="DESC" data-order-cols="1">
                                            <thead>
                                                <tr>                                                
                                                <th data-table-search="false" data-table-sort="true" data-name="DT_RowIndex" data-width="5">ID</th>
                                                <th data-table-search="true" data-table-sort="true" data-name="church_id" >Church Name</th>
                                                <th data-table-search="true" data-table-sort="true" data-name="referrer_Id" id="referrer_Id">Referrar ID</th>
                                                <th data-table-search="true" data-table-sort="true" data-name="vendor_id" id="vendor_id">Vendor ID</th>
                                              
                                              <!--   <th data-table-search="false" data-table-sort="false" data-name="action" >Action</th> -->
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
    
    